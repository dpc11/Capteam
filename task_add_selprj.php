<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$url_project = $_SERVER["QUERY_STRING"] ;
$current_url = current(explode("&sort",$url_project));


$currentPage = $_SERVER["PHP_SELF"];

$section = 1;
if (isset($_GET['section'])) {
  $section = $_GET['section'];
}

$copy = -1;
if (isset($_GET['copy'])) {
  $copy = $_GET['copy'];
}

$touser = "-1";
if (isset($_GET['touser'])) {
  $touser= $_GET['touser'];
}

$user_id = "-1";
if (isset($_GET['UID'])) {
  $user_id= $_GET['UID'];
}

if($section == 1){

//project
$maxRows_Recordset1 = 100;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;


$colinputtitle_Recordset1 = "";
if (isset($_GET['inputtitle'])) {
  $colinputtitle_Recordset1 = $_GET['inputtitle'];
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT id, project_name, task_status FROM tk_project 
inner join tk_status_project on tk_project.project_status=tk_status_project.psid 
							WHERE task_status NOT LIKE %s AND project_name LIKE %s ORDER BY project_lastupdate DESC",  
GetSQLValueString("%" . $multilingual_dd_status_prjfinish . "%", "text"), 
GetSQLValueString("%" . $colinputtitle_Recordset1 . "%", "text"));
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

}else {
//task
$maxRows_Recordset_subtask = 100;
$pageNum_Recordset_subtask = 0;
if (isset($_GET['pageNum_Recordset_subtask'])) {
  $pageNum_Recordset_subtask = $_GET['pageNum_Recordset_subtask'];
}
$startRow_Recordset_subtask = $pageNum_Recordset_subtask * $maxRows_Recordset_subtask;

$where = "";
			$where=' WHERE';

		if($section ==2){
			$project_id = 0;
			if (isset($_GET['projectid'])) {
			  $project_id = $_GET['projectid'];
			}
			$projectid = GetSQLValueString($project_id, "int");
				$where.= " tk_task.csa_project = $projectid AND tk_task.csa_remark4 = '-1'";
		} else {
			
			$task_id = 0;
			if (isset($_GET['taskid'])) {
			  $task_id = $_GET['taskid'];
			}
			$ptaskid = GetSQLValueString($task_id, "int");
				$where.= " tk_task.csa_remark4 = $ptaskid";
		} 

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_subtask = sprintf("SELECT * 
							FROM tk_task 
							inner join tk_task_tpye on tk_task.csa_type=tk_task_tpye.id								
							$where ORDER BY csa_last_update DESC");
$query_limit_Recordset_subtask = sprintf("%s LIMIT %d, %d", $query_Recordset_subtask, $startRow_Recordset_subtask, $maxRows_Recordset_subtask);
$Recordset_subtask = mysql_query($query_limit_Recordset_subtask, $tankdb) or die(mysql_error());
$row_Recordset_subtask = mysql_fetch_assoc($Recordset_subtask);

if (isset($_GET['totalRows_Recordset_subtask'])) {
  $totalRows_Recordset_subtask = $_GET['totalRows_Recordset_subtask'];
} else {
  $all_Recordset_subtask = mysql_query($query_Recordset_subtask);
  $totalRows_Recordset_subtask = mysql_num_rows($all_Recordset_subtask);
}
$totalPages_Recordset_subtask = ceil($totalRows_Recordset_subtask/$maxRows_Recordset_subtask)-1;

$queryString_Recordset_subtask = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_subtask") == false && 
        stristr($param, "totalRows_Recordset_subtask") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_subtask = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_subtask = sprintf("&totalRows_Recordset_subtask=%d%s", $totalRows_Recordset_subtask, $queryString_Recordset_subtask);

if ($row_Recordset_subtask['csa_remark6'] == "-1" ){
$wbs_id = "1";
} else {
$wbs_id = $row_Recordset_subtask['csa_remark6'];
}


$wbsID = $wbs_id + 1;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS - <?php echo $multilingual_project_file_management; ?></title>
<link href="skin/themes/base/lhgdialog.css" rel="stylesheet" type="text/css" />
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
	<script type="text/javascript">
var P = window.parent, D = P.loadinndlg();   
function closreload(url)
{
    if(!url)
	    P.reload();    
}
function over()
{
    P.cancel();
}
	</script>
</head>

<body>

<?php if($section ==1){?>
<table align="center" class="table table-hover glink" width="100%" style="border-top:2px #fff solid;">

	<?php if($totalRows_Recordset1 > "0"){?>
	<?php do { ?>
		<tr >
      <td>
	  <div class="float_left">
	  <a href="task_add_selprj.php?projectid=<?php echo $row_Recordset1['id']; ?>&section=2&UID=<?php echo $user_id; ?>&touser=<?php echo $touser; ?>&copy=<?php echo $copy; ?>
	  " class="icon_folder"><?php echo $row_Recordset1['project_name']; ?></a>
	  </div>
	  <div class="float_right">
	  <a onclick="javascript:parent.parent.location='default_task_add.php?projectID=<?php echo $row_Recordset1['id']; ?>&UID=<?php echo $user_id; ?>&touser=<?php echo $touser; ?>&copy=<?php echo $copy; ?>';" href="#">
			 <i class="icon-random"></i> <?php echo $multilingual_project_newtask; ?>			 </a>

	  </div>
</td>
    </tr>
    
	<?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>

<?php if($_SESSION['MM_rank'] > "3") {  ?>
<tr>
      <td >
	 <a href="project_add.php" target="_blank"><?php echo $multilingual_projectlist_new; ?></a>
</td>
</tr>
<?php }  ?> 

<?php } else {?>
<tr>
<td>
<div class="update_bg">
    <?php echo $multilingual_project_none2; ?> <?php if($_SESSION['MM_rank'] > "3") {  ?><a href="project_add.php" target="_blank"><b><?php echo $multilingual_projectlist_new; ?></b></a><?php } ?></div></td>
</tr>
<?php } ?>
</table>
<table class="rowcon" border="0">
<tr>
<td>   <table border="0">
        <tr>
          <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, 0, $queryString_Recordset1); ?>#task"><?php echo $multilingual_global_first; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, max(0, $pageNum_Recordset1 - 1), $queryString_Recordset1); ?>#task"><?php echo $multilingual_global_previous; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, min($totalPages_Recordset1, $pageNum_Recordset1 + 1), $queryString_Recordset1); ?>#task"><?php echo $multilingual_global_next; ?></a>
              <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, $totalPages_Recordset1, $queryString_Recordset1); ?>#task"><?php echo $multilingual_global_last; ?></a>
              <?php } // Show if not last page ?></td>
        </tr>
      </table></td>
<td align="right"><div style="display:none;"><?php echo ($startRow_Recordset1 + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset1 + $maxRows_Recordset1, $totalRows_Recordset1) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset1 ?>)&nbsp;&nbsp;&nbsp;&nbsp;</div></td>
</tr>
</table>
<?php } else { ?>
<table align="center" class="table table-hover glink" width="100%"  style="border-top:2px #fff solid;">
<tr><td ><a href="javascript:history.go(-1)"><b><?php echo $multilingual_taskadd_back; ?></b></a></td></tr>
	<?php if($totalRows_Recordset_subtask > "0"){?>
	<?php do { ?>
		<tr >
      <td >
	  <div class="float_left">
	  <a href="task_add_selprj.php?taskid=<?php echo $row_Recordset_subtask['TID']; ?> &section=3&UID=<?php echo $user_id; ?>&touser=<?php echo $touser; ?>&copy=<?php echo $copy; ?>
	  " class="icon_file"><?php echo $row_Recordset_subtask['csa_text']; ?></a>
	  </div>
	  <div class="float_right">
	  <a onclick="javascript:parent.parent.location='default_task_add.php?taskID=<?php echo $row_Recordset_subtask['TID']; ?>&projectID=<?php echo $row_Recordset_subtask['csa_project']; ?>&wbsID=<?php echo $wbsID; ?>&UID=<?php echo $user_id; ?>&touser=<?php echo $touser; ?>&copy=<?php echo $copy; ?>';" href="#">
			 <i class="icon-random"></i> <?php echo $multilingual_project_newtask; ?>			 </a>
	  

	  </div>
</td>
    </tr>
    
	<?php
} while ($row_Recordset_subtask = mysql_fetch_assoc($Recordset_subtask));
  $rows = mysql_num_rows($Recordset_subtask);
  if($rows > 0) {
      mysql_data_seek($Recordset_subtask, 0);
	  $row_Recordset_subtask = mysql_fetch_assoc($Recordset_subtask);
  }
?>
<?php }else {  ?>
<tr>
<td>
<?php echo $multilingual_taskadd_nosub; ?>
</td>
</tr>
<?php } ?>
</table>
<table class="rowcon" border="0" align="center">
<tr>
<td>   <table border="0">
        <tr>
          <td><?php if ($pageNum_Recordset_subtask > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_subtask=%d%s", $currentPage, 0, $queryString_Recordset_subtask); ?>#task"><?php echo $multilingual_global_first; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_subtask > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_subtask=%d%s", $currentPage, max(0, $pageNum_Recordset_subtask - 1), $queryString_Recordset_subtask); ?>#task"><?php echo $multilingual_global_previous; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_subtask < $totalPages_Recordset_subtask) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_subtask=%d%s", $currentPage, min($totalPages_Recordset_subtask, $pageNum_Recordset_subtask + 1), $queryString_Recordset_subtask); ?>#task"><?php echo $multilingual_global_next; ?></a>
              <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_Recordset_subtask < $totalPages_Recordset_subtask) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_subtask=%d%s", $currentPage, $totalPages_Recordset_subtask, $queryString_Recordset_subtask); ?>#task"><?php echo $multilingual_global_last; ?></a>
              <?php } // Show if not last page ?></td>
        </tr>
      </table></td>
<td align="right"><div style="display:none;"><?php echo ($startRow_Recordset_subtask + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_subtask + $maxRows_Recordset_subtask, $totalRows_Recordset_subtask) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_subtask ?>)&nbsp;&nbsp;&nbsp;&nbsp;</div></td>
</tr>
</table>
<?php } ?>
</body>
</html>