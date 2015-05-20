<?php 

$url_project = $_SERVER["QUERY_STRING"] ;
$current_url = current(explode("&sort",$url_project));

$maxRows_Recordset1 = get_item( 'maxrows_project' );
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
	$pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;
$R_list_num=$startRow_Recordset1+1;

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

//现在的时间
$today_date = date('Y-m-d');
$prjtouser = $_SESSION['MM_uid'];

$Recordset1=get_project_list($pagetabs,$prjtouser,$today_date,$colinputtitle_Recordset1,$orderlist,$sortlist,$startRow_Recordset1,$maxRows_Recordset1);
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $totalRows_Recordset1 = get_project_list_num($pagetabs,$prjtouser,$today_date,$colinputtitle_Recordset1,$orderlist,$sortlist,$startRow_Recordset1,$maxRows_Recordset1);
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

<div class="tasktab" id="tasktab">
	<div class="condition">
		<span>
			<div class="clearboth" > </div>
			<form id="form1" name="form1" method="get" action="<?php echo $pagename; ?>" class="taskform form-inline">
				<!-- 搜索框 -->
				<input  type="text"  style="width:220px;float:left;" name="inputtitle" id="inputtitle" class="form-control input-sm" placeholder="<?php echo $multilingual_projectlist_search; ?>" value="<?php echo $colinputtitle_Recordset1;?>" />
				<!-- 用于记录 pagetab 变量 -->
				<input class="form-control " name="pagetab" id="pagetab" value="<?php echo $pagetabs;?>" style="display:none" />
				<!-- 搜索按钮 -->
				<button type="submit" name="button11" id="button11" class="btn btn-default btn-sm" /><span class="glyphicon glyphicon-search" style="display:inline;"></span> <?php echo $multilingual_global_searchbtn; ?></button>
			</form>
		</span>
	</div>

	<!-- 项目列表 -->
	<?php if ($totalRows_Recordset1 > 0) { // Show if recordset not empty ?>
		<table border="0" cellspacing="0" cellpadding="0" align="center"  class="toptable project_table maintable tasktab_bl">
			<thead class="toptable tasktab_bl">
				<tr>  
					<!-- 编号 -->
					<th class="topic">No.</th>

					<!-- 项目名称 -->
					<th class="topic">
						<a href="<?php echo $pagename; ?>?<?php echo $current_url; ?>&sort=project_name&order=<?php 
							if ( $sortlist <> "project_name"){
								echo "DESC";
							}else if( $sortlist == "project_name" && $orderlist == "DESC"){
								echo "ASC";
							} else {
								echo "DESC";
							}  ?>" 
							<?php 
								if($sortlist=="project_name" && $orderlist=="ASC"){
									echo "class='sort_asc'";
								} else if ($sortlist=="project_name" && $orderlist=="DESC"){
									echo "class='sort_desc'";
								}
							?>><?php echo $multilingual_project_title; ?></a>
					</th>
		
					<!-- 项目组长 -->
					<th class="topic">
						<a href="<?php echo $pagename; ?>?<?php echo $current_url; ?>&sort=project_name&order=<?php 
							if ( $sortlist <> "project_to_user"){
								echo "DESC";
							}else if( $sortlist == "project_to_user" && $orderlist == "DESC"){
								echo "ASC";
							} else {
								echo "DESC";
							} ?>" 
							<?php 
								if($sortlist=="project_to_user" && $orderlist=="ASC"){
									echo "class='sort_asc'";
								} else if ($sortlist=="project_to_user" && $orderlist=="DESC"){
									echo "class='sort_desc'";
								}
							?>><?php echo $multilingual_project_captain; ?></a>
					</th>
	   
					<!-- 项目起始 -->
					<th class="topic">
						<a href="<?php echo $pagename; ?>?<?php echo $current_url; ?>&sort=project_start&order=<?php 
							if ( $sortlist <> "project_start"){
								echo "DESC";
							}else if( $sortlist == "project_start" && $orderlist == "DESC"){
								echo "ASC";
							} else {
								echo "DESC";
							}  ?>" 
							<?php 
								if($sortlist=="project_start" && $orderlist=="ASC"){
									echo "class='sort_asc'";
								} else if ($sortlist=="project_start" && $orderlist=="DESC"){
									echo "class='sort_desc'";
								} ?>><?php echo $multilingual_project_start; 
							?></a>
					</th>
				   
					<!-- 项目结束 -->
					<th class="topic">
						<a href="<?php echo $pagename; ?>?<?php echo $current_url; ?>&sort=project_end&order=<?php 
							if ( $sortlist <> "project_end"){
								echo "DESC";
							}else if( $sortlist == "project_end" && $orderlist == "DESC"){
								echo "ASC";
							} else {
								echo "DESC";
							} ?>" 
							<?php 
								if($sortlist=="project_end" && $orderlist=="ASC"){
									echo "class='sort_asc'";
								} else if ($sortlist=="project_end" && $orderlist=="DESC"){
									echo "class='sort_desc'";
								}  ?>><?php echo $multilingual_project_end; 
							?></a>
					</th>
					
					<!-- 项目状态 -->
					<th class="topic">
						<a href="<?php echo $pagename; ?>?<?php echo $current_url; ?>&sort=project_lastupdate&order=<?php 
							if ( $sortlist <> "project_status"){
								echo "DESC";
							}else if( $sortlist == "project_status" && $orderlist == "DESC"){
								echo "ASC";
							} else {
								echo "DESC";
							} ?>" 
							<?php 
								if($sortlist=="project_status" && $orderlist=="ASC"){
									echo "class='sort_asc'";
								} else if ($sortlist=="project_status" && $orderlist=="DESC"){
									echo "class='sort_desc'";
								}
							?>><?php echo $multilingual_project_status; 
						?></a>
					</th>
	   
					<!-- 项目最后更新 -->
					<th class="topic">
						<a href="<?php echo $pagename; ?>?<?php echo $current_url; ?>&sort=project_lastupdate&order=<?php 
							if ( $sortlist <> "project_lastupdate"){
								echo "DESC";
							}else if( $sortlist == "project_lastupdate" && $orderlist == "DESC"){
								echo "ASC";
							} else {
								echo "DESC";
							}  ?>" 
							<?php 
								if($sortlist=="project_lastupdate" && $orderlist=="ASC"){
									echo "class='sort_asc'";
								} else if ($sortlist=="project_lastupdate" && $orderlist=="DESC"){
									echo "class='sort_desc'";
								} ?>>
							<?php echo $multilingual_global_lastupdate;	
						?></a>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php do { ?>
					<tr >			
					<!-- 项目列表内容，与上面的表头对应 -->
					<td class="week_style_padtd" ><?php echo $R_list_num; ?></td>
					<td  class="week_style_padtd" >&nbsp;&nbsp;<a href="project_view.php?recordID=<?php echo $row_Recordset1['id']; ?>&pagetab=<?php echo $pagetabs; ?>" ><?php echo $row_Recordset1['project_name']; ?></a>&nbsp; </td>
					<!-- 显示负责人 -->
					<td class="week_style_padtd" > <a href="user_view.php?recordID=<?php echo $row_Recordset1['project_to_user']; ?>"><?php echo $row_Recordset1['tk_display_name']; ?></a></td>
					<td class="week_style_padtd" ><?php echo $row_Recordset1['project_start']; ?>&nbsp; </td>
					<td class="week_style_padtd" ><?php echo $row_Recordset1['project_end']; ?>&nbsp; </td>
					<td class="week_style_padtd" ><?php //显示项目的状态
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
					<td class="week_style_padtd" ><?php echo $row_Recordset1['project_lastupdate']; ?>&nbsp; </td>
				</tr>
			<?php 
			$R_list_num++;
			} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
		</tbody>
	</table>

	<table class="rowcon" border="0" align="center">
		<tr>
			<td  align="left"><?php echo ($startRow_Recordset1 + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset1 + $maxRows_Recordset1, $totalRows_Recordset1) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset1 ?>)
			</td>
			<td>
				<table border="0">
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
				</table>
			</td>
		</tr>
	</table>
	<?php } else { // Show if recordset empty ?>  
		<div class="alert alert-warning search_warning" style="margin:6px;">
			<?php echo $multilingual_project_none; ?>
		</div>
	<?php } // Show if recordset empty ?>
</div>