<?php
//一页显示的最多记录数
$maxRows_Recordset_log = 15;
//当前显示的页数
$pageNum_Recordset_log = 0;
if (isset($_GET['pageNum_Recordset_log'])) {
  $pageNum_Recordset_log = $_GET['pageNum_Recordset_log'];
}
$startRow_Recordset_log = $pageNum_Recordset_log * $maxRows_Recordset_log;

//当前用户id
$userid = $_SESSION['MM_uid']; 
//获取log数组
$log_arr = get_logs($userid);
//总记录数
if (isset($_GET['totalRows_Recordset_log'])) {
  $totalRows_Recordset_log = $_GET['totalRows_Recordset_log'];
} else {
  $totalRows_Recordset_log = count($log_arr);
}
//总页数
$totalPages_Recordset_log = ceil($totalRows_Recordset_log/$maxRows_Recordset_log)-1;
$queryString_Recordset_log = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_log") == false && 
        stristr($param, "totalRows_Recordset_log") == false && 
        stristr($param, "tab") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_log = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_log = sprintf("&totalRows_Recordset_log=%d%s", $totalRows_Recordset_log, $queryString_Recordset_log);
?>


<script type="text/JavaScript">
function GP_popupConfirmMsg(msg) { //v1.0
  document.MM_returnValue = confirm(msg);
}

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}

function   searchtask() 
      {document.form1.action= "<?php echo $pagename; ?>"; 
        document.form1.submit(); 
        return   true; 
      
  } 

function   exportexcel() 
      {document.form1.action= "excel_log.php "; 
        document.form1.submit(); 
        return   false; 
      
      } 
</script>


<table width="100%" cellpadding="5">
  <tr>
    <td>
	<?php if ($totalRows_Recordset_log > 0) { ?>
    <div >


    <table class="table table-striped table-hover" width="98%" >
<thead>
<tr>
<th>
<br />
<span class="font_big18 fontbold breakwordsfloat_left">
<?php echo $multilingual_head_feed; ?></span></th>
<!-- <th>
<?php echo $multilingual_user_view_cost; ?></th>
<th>
<?php echo $multilingual_user_view_status; ?></th> -->
<th width="180px">
<?php echo $multilingual_user_view_project2; ?></th>
<th width="180px">
<?php echo $multilingual_project_file_update; ?></th>
<th></th>
</tr>
</thead>
<tbody>
  <?php 
    $log_count = 0;
    foreach($log_arr as $key => $val){    	
    	if($log_count >= $pageNum_Recordset_log * $maxRows_Recordset_log && $log_count < ($pageNum_Recordset_log+1) * $maxRows_Recordset_log){
  ?>
    <tr>
        <td class="glink">
	        <span class="glyphicon glyphicon-user"></span> 
	        <a href="user_view.php?recordID=<?php echo $val['user']; ?>">
	        <?php echo get_user_disName($val['user']); //显示负责人?>
	        </a>
	        <?php echo $multilingual_user_view_by; ?> 
		    <?php echo $val['time']; //显示时间?>	    
		    <?php echo $multilingual_user_view_do; ?>  
		    <?php 
		     $action = $val['action'];
		     $type_id = $val['type'];
		     $pid = $val['pid'];
		    if($val['class'] == 1){
	            echo "项目-"; 
	            echo "<a href='project_view.php?pagetab=allprj&recordID=$type_id'>$action</a>";
		    }else if($val['class'] == 2){
	            echo "阶段-"; 
	            echo "<a href='stage_view.php?sid=$type_id&pid=$pid'>$action</a>";
		    }else if($val['class'] == 3){
	            echo "任务-"; 
	            echo "<a href='default_task_edit.php?editID=$type_id&pagetab=alltask'>$action</a>";
		    }else if($val['class'] == 4){
	            echo "文件-"; 
	            echo "<a href='file.php?pagetab=allfile&projectID=$pid&recordID=$type_id'>$action</a>";
		    }?> 
        </td>
        <!-- 显示所属项目 -->
		<td class="glink" width="160px" >
		  <?php 
		  $project = get_project_by_id($pid);
		  $project_name = $project['project_name'];
		  echo "<a href='project_view.php?pagetab=allprj&recordID=$pid'>$project_name</a>"
		  ?>
		</td>

		<td class="glink" width="240px" >
		<?php echo $val['time']; ?>  
		</td>
    </tr>
<?php
    }
    $log_count++;
}
?>

</tbody>
</table>
</div>
<table class="rowcon" border="0" align="center">
<tr>
<td>   <table border="0">
        <tr>
          <td><?php if ($pageNum_Recordset_log > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_log=%d%s", $currentPage, 0, $queryString_Recordset_log); ?>&pagetab=<?php echo $pagetabs;?>#task"><?php echo $multilingual_global_first; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_log > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_log=%d%s", $currentPage, max(0, $pageNum_Recordset_log - 1), $queryString_Recordset_log); ?>&pagetab=<?php echo $pagetabs;?>#task"><?php echo $multilingual_global_previous; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_log < $totalPages_Recordset_log) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_log=%d%s", $currentPage, min($totalPages_Recordset_log, $pageNum_Recordset_log + 1), $queryString_Recordset_log); ?>&pagetab=<?php echo $pagetabs;?>#task"><?php echo $multilingual_global_next; ?></a>
              <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_Recordset_log < $totalPages_Recordset_log) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_log=%d%s", $currentPage, $totalPages_Recordset_log, $queryString_Recordset_log); ?>&pagetab=<?php echo $pagetabs;?>#task"><?php echo $multilingual_global_last; ?></a>
              <?php } // Show if not last page ?></td>
        </tr>
      </table></td>
<td align="right">   <?php echo ($startRow_Recordset_log + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_log + $maxRows_Recordset_log, $totalRows_Recordset_log) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_log ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
</table> 

<?php } else { ?>
<div class="alert alert-warning" style="margin:6px;">
  <?php echo $multilingual_user_view_nolog; ?></div>
<?php }  ?> </td>
</tr>
</table>  
