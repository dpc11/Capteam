<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_Recordset1 = 20;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

$colname_Recordset1 = $multilingual_dd_project_inside;
if (isset($_GET[$multilingual_dd_project_inside])) {
  $colname_Recordset1 = $_GET[$multilingual_dd_project_inside];
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT * FROM tk_project 
							
							inner join tk_user on tk_project.project_to_user=tk_user.tk_user_login
							WHERE project_type = %s ORDER BY project_lastupdate DESC", GetSQLValueString($colname_Recordset1, "text"));
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS - <?php echo $multilingual_head_project; ?></title>
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script type="text/javascript" src="srcipt/js.js"></script>
<link href="skin/themes/base/custom.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php require('head.php'); ?>
<br />
<?php if ($totalRows_Recordset1 > 0) { // Show if recordset not empty ?>
<table class="rowcon" border="0" align="center">
<tr>
<td>
<?php if ($_SESSION['MM_UserGroup'] == $multilingual_dd_role_admin) {  ?>
      <input name="button2" type="button" id="button2" onClick="javascript:self.location='project_add.php';" value="<?php echo $multilingual_projectlist_new; ?>" class="button">
      <?php }  ?> 
</td>
<td align="right"><?php echo ($startRow_Recordset1 + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset1 + $maxRows_Recordset1, $totalRows_Recordset1) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset1 ?>)</td>
</tr>
</table>

<div class="taskdiv">
<table border="0" cellspacing="0" cellpadding="0" align="center" class="maintable">
<thead class="toptable">
  <tr>
    <th><?php echo $multilingual_project_id; ?></th>
    <th><?php echo $multilingual_project_title; ?></th>
    <th><?php echo $multilingual_project_code; ?></th>
    <th><?php echo $multilingual_project_start; ?></th>
    <th><?php echo $multilingual_project_end; ?></th>
    <th><?php echo $multilingual_project_touser; ?></th>
    <th><?php echo $multilingual_project_status; ?></th>
    <th><?php echo $multilingual_global_lastupdate; ?></th>
    </tr>
</thead>
  <?php do { ?>
    <tr>
      <td><?php echo $row_Recordset1['id']; ?></td>
      <td class="task_title"><a href="project_view.php?recordID=<?php echo $row_Recordset1['id']; ?>" ><?php echo $row_Recordset1['project_name']; ?></a>&nbsp; </td>
      <td><?php echo $row_Recordset1['project_code']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['project_start']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['project_end']; ?>&nbsp; </td>
      <td>
	  <a href="user_view.php?recordID=<?php echo $row_Recordset1['project_to_user']; ?>">
	  <?php echo $row_Recordset1['tk_display_name']; ?></a>
	  &nbsp; </td>
      <td><?php echo $row_Recordset1['project_status']; ?></td>
      <td><?php echo $row_Recordset1['project_lastupdate']; ?>&nbsp; </td>
      </tr>
    <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
</table>
</div>
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
  <div class="ui-widget"  style="margin-left:5px;">
    <div class="ui-state-highlight fontsize-s" style=" padding: 5px; width:260px;"> 
      <span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
    <table>
	<tr>
	<td>
	<?php echo $multilingual_project_none; ?>
	</td>
	<td>
	<?php if ($_SESSION['MM_UserGroup'] == $multilingual_dd_role_admin) {  ?>
      <input name="" type="button" onClick="javascript:self.location='project_add.php';" value="<?php echo $multilingual_projectlist_new; ?>"  class="button">
      <?php }  ?> 
	</td>
	</tr>
	</table>
	</div>
  </div>
  </div>
<?php } // Show if recordset empty ?>  
<p>&nbsp;</p>
<?php require('foot.php'); ?>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
