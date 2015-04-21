<?php
require_once('function.class.php');
$url_project = $_SERVER["QUERY_STRING"] ;
$current_url = current(explode("&sort",$url_project));

$maxRows_Recordset1 = get_item( 'maxrows_project' );
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;


$sortlist = "project_lastupdate";
if (isset($_GET['sort'])) {
  $sortlist = $_GET['sort'];
}

$orderlist = "DESC";
if (isset($_GET['order'])) {
  $orderlist= $_GET['order'];
}

$colinputtitle_Recordset1 = "";
if (isset($_GET['inputtitle'])) {
  $colinputtitle_Recordset1 = $_GET['inputtitle'];
}

$pagetabs = "allprj";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}

// if ($pagetabs == "mprj" || $pagetabs == "jprj"){
// $prjtouser = $_SESSION['MM_uid'];
// if (isset($_GET['ptouser'])) {
//   $prjtouser = $_GET['ptouser'];
// }
// }else {
// $prjtouser = 0;
// }
//现在的时间
$today_date = date('Y-m-d');
$prjtouser = $_SESSION['MM_uid'];
if($pagetabs == "jprj"){
//我参与的
//$where = "tk_task.csa_to_user = $prjtouser AND tk_status_project.task_status NOT LIKE '%%$multilingual_dd_status_prjfinish%%' AND";
$where = "t.tk_team_uid = $prjtouser AND p.project_del_status != -1 AND t.tk_team_ulimit != 3";
}else if($pagetabs == "closeprj"){
//归档项目
//$where = "tk_status_project.task_status LIKE '%%$multilingual_dd_status_prjfinish%%' AND";
$where = "p.project_del_status != -1 AND p.project_end < '$today_date'";
}else if($pagetabs == "mprj"){
//我负责的项目
//$where = "tk_status_project.task_status LIKE '%%$multilingual_dd_status_prjfinish%%' AND";
$where = "p.project_to_user = $prjtouser AND p.project_del_status != -1";
}else if($pagetabs == "allprj"){
//所有项目
//$where = "tk_status_project.task_status LIKE '%%$multilingual_dd_status_prjfinish%%' AND";
$where = "t.tk_team_uid = $prjtouser";
}
else if($prjtouser <> 0 ) {
//我负责的项目
//$where = "project_to_user = $prjtouser AND tk_status_project.task_status NOT LIKE '%%$multilingual_dd_status_prjfinish%%' AND";
$where = "p.project_to_user = $prjtouser AND p.project_del_status != -1";
}else{
//所有项目
//$where = "tk_status_project.task_status NOT LIKE '%%$multilingual_dd_status_prjfinish%%' AND";	
$where = "t.tk_team_uid = $prjtouser";
} 

if($pagetabs == "jprj" ){
//$where1 = "inner join tk_task on tk_project.id=tk_task.csa_project";
$where2 = "GROUP BY p.id";
}else{
//$where1 = "";
$where2 = "";
}

mysql_select_db($database_tankdb, $tankdb);
// $query_Recordset1 = sprintf("SELECT * FROM tk_project 
							
// 							inner join tk_user on tk_project.project_to_user=tk_user.uid 
// 							inner join tk_status_project on tk_project.project_status=tk_status_project.psid 
// 							$where1 
// 							WHERE $where project_name LIKE %s $where2 ORDER BY tk_project.%s %s", 
// 							GetSQLValueString("%" . $colinputtitle_Recordset1 . "%", "text"),
// 							GetSQLValueString($sortlist, "defined", $sortlist, "NULL"),
// 							GetSQLValueString($orderlist, "defined", $orderlist, "NULL"));


//修改后的sql语句					
$query_Recordset1 = sprintf("SELECT p.id,p.project_name,p.project_text,p.project_start,p.project_end,p.project_to_user,p.project_lastupdate,p.project_del_status,p.project_create_time 
	                        FROM tk_project p, tk_team t WHERE p.id=t.tk_team_pid AND
								$where AND p.project_name LIKE %s $where2 ORDER BY p.%s %s", 
							GetSQLValueString("%" . $colinputtitle_Recordset1 . "%", "text"),
							GetSQLValueString($sortlist, "defined", $sortlist, "NULL"),
							GetSQLValueString($orderlist, "defined", $orderlist, "NULL"));

$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysql_query($query_Recordset1);
  $totalRows_Recordset1 = mysql_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;

$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset1") == false && 
        stristr($param, "totalRows_Recordset1") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);
?>


<div class="search_div pagemarginfix">

<form id="form1" name="form1" method="get" action="<?php echo $pagename; ?>" class="saerch_form form-inline">

<!-- 搜索框 -->
<input  type="text" name="inputtitle" id="inputtitle" class="form-control input-sm" placeholder="<?php echo $multilingual_projectlist_search; ?>">

<!-- 用于记录 pagetab 变量 -->
<input name="pagetab" id="pagetab" value="<?php echo $pagetabs;?>" style="display:none" />

<!-- 搜索按钮 -->
<button type="submit" name="button11" id="button11" class="btn btn-default btn-sm" /><span class="glyphicon glyphicon-search" style="display:inline;"></span> <?php echo $multilingual_global_searchbtn; ?></button>
</form>
</div>

<!-- 项目列表 -->
<?php if ($totalRows_Recordset1 > 0) { // Show if recordset not empty ?>
<table  class="table table-striped table-hover glink" width="98%" >
<thead>
  <tr>
  
<!-- 项目ID（已删除） -->
<!--
    <th>
	<a href="<?php echo $pagename; ?>?<?php echo $current_url; ?>&sort=id&order=<?php 
	  if ( $sortlist <> "id"){
	  echo "DESC";
	  }else if( $sortlist == "id" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="id" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="id" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	<?php echo $multilingual_project_id; ?></a></th>
-->

<!-- 项目名称 -->
    <th>
	<a href="<?php echo $pagename; ?>?<?php echo $current_url; ?>&sort=project_name&order=<?php 
	  if ( $sortlist <> "project_name"){
	  echo "DESC";
	  }else if( $sortlist == "project_name" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="project_name" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="project_name" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	<?php echo $multilingual_project_title; ?></a></th>
	
<!-- 项目组长 -->
	<th>
	<a href="<?php echo $pagename; ?>?<?php echo $current_url; ?>&sort=project_name&order=<?php 
	  if ( $sortlist <> "project_to_user"){
	  echo "DESC";
	  }else if( $sortlist == "project_to_user" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="project_to_user" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="project_to_user" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	<?php echo $multilingual_project_captain; ?></a></th>
	
<!-- 项目代码（已删除） -->
<!--
    <th class="hide">
	<a href="<?php echo $pagename; ?>?<?php echo $current_url; ?>&sort=project_code&order=<?php 
	  if ( $sortlist <> "project_code"){
	  echo "DESC";
	  }else if( $sortlist == "project_code" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="project_code" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="project_code" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	<?php echo $multilingual_project_code; ?></a></th>
-->
   
<!-- 项目起始 -->
    <th>
	<a href="<?php echo $pagename; ?>?<?php echo $current_url; ?>&sort=project_start&order=<?php 
	  if ( $sortlist <> "project_start"){
	  echo "DESC";
	  }else if( $sortlist == "project_start" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="project_start" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="project_start" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	<?php echo $multilingual_project_start; ?></a></th>
   
<!-- 项目结束 -->
    <th>
	<a href="<?php echo $pagename; ?>?<?php echo $current_url; ?>&sort=project_end&order=<?php 
	  if ( $sortlist <> "project_end"){
	  echo "DESC";
	  }else if( $sortlist == "project_end" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="project_end" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="project_end" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	<?php echo $multilingual_project_end; ?></a></th>
	
<!-- 项目状态 -->
    <th>
	<a href="<?php echo $pagename; ?>?<?php echo $current_url; ?>&sort=project_lastupdate&order=<?php 
	  // if ( $sortlist <> "project_status"){
	  // echo "DESC";
	  // }else if( $sortlist == "project_status" && $orderlist == "DESC"){
	  // echo "ASC";
	  // } else {
	  // echo "DESC";
	  echo "ASC";
	  // }
	  ?>" 
	  <?php 
	  if($sortlist=="project_status" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="project_status" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	<?php echo $multilingual_project_status; ?></a></th>
   
<!-- 项目最后更新 -->
    <th>
	<a href="<?php echo $pagename; ?>?<?php echo $current_url; ?>&sort=project_lastupdate&order=<?php 
	  if ( $sortlist <> "project_lastupdate"){
	  echo "DESC";
	  }else if( $sortlist == "project_lastupdate" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="project_lastupdate" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="project_lastupdate" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	<?php echo $multilingual_global_lastupdate; ?></a></th>
    </tr>
</thead>

<tbody>
  <?php do { ?>
    <tr>
    
<!-- 项目列表内容，与上面的表头对应 -->
<!--      <td><?php echo $row_Recordset1['id']; ?></td>-->
      <td class="task_title">&nbsp;&nbsp;<a href="project_view.php?recordID=<?php echo $row_Recordset1['id']; ?>&pagetab=<?php echo $pagetabs; ?>" ><?php echo $row_Recordset1['project_name']; ?></a>&nbsp; </td>
      <td> <!-- 显示负责人 -->
	  <a href="user_view.php?recordID=<?php echo $row_Recordset1['project_to_user']; ?>">
	  <?php 
      $new_user = get_user($row_Recordset1['project_to_user']);
	  echo $new_user->name; ?></a>
	  &nbsp; </td>
<!--      <td class="hide"><?php echo $row_Recordset1['project_code']; ?>&nbsp; </td>-->
      <td><?php echo $row_Recordset1['project_start']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['project_end']; ?>&nbsp; </td>
      <td><?php //显示项目的状态
      $today_date = date('Y-m-d');//今天的日期，用于计算项目状态
      if($today_date < $row_Recordset1['project_start']){
      	//表示项目还没有开始
      	echo "<div style='background-color: #FF6666; width:100%; text-align:center;'>项目未开始</div>";
      }elseif ($today_date > $row_Recordset1['project_end']) {
      	//表示项目已结结束
        echo "<div style='background-color: #B3B3B3; width:100%; text-align:center;'>项目已结束</div>";
      }else{
      	//表示项目正在进行中
      	echo "<div style='background-color: #6ABD78; width:100%; text-align:center;'>开发进行中</div>";
      }?></td>
      <td><?php echo $row_Recordset1['project_lastupdate']; ?>&nbsp; </td>
      </tr>
    <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
</tbody>
</table>

<!-- 这里是控制分页的部分？没太看懂 -->
<table class="rowcon" border="0" align="center">
<tr>
<td><table border="0">
  <tr>
    <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, 0, $queryString_Recordset1); ?>"><?php echo $multilingual_global_first; ?></a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, max(0, $pageNum_Recordset1 - 1), $queryString_Recordset1); ?>"><?php echo $multilingual_global_previous; ?></a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, min($totalPages_Recordset1, $pageNum_Recordset1 + 1), $queryString_Recordset1); ?>"><?php echo $multilingual_global_next; ?></a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, $totalPages_Recordset1, $queryString_Recordset1); ?>"><?php echo $multilingual_global_last; ?></a>
        <?php } // Show if not last page ?></td>
  </tr>
</table></td>
<td align="right"><?php echo ($startRow_Recordset1 + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset1 + $maxRows_Recordset1, $totalRows_Recordset1) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset1 ?>)</td>
</tr>
</table>
<?php } else { // Show if recordset empty ?>  
  <div class="alert alert-warning" style="margin:6px;">

	<?php echo $multilingual_project_none; ?>

  </div>
<?php } // Show if recordset empty ?>