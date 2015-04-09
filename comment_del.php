<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php
$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "2") {   
  header("Location: ". $restrictGoTo); 
  exit;
}
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

$taskid = "-1";
if (isset($_GET['taskID'])) {
  $taskid = $_GET['taskID'];
}
$projectid = "-1";
if (isset($_GET['projectID'])) {
  $projectid = $_GET['projectID'];
}

$date = "-1";
if (isset($_GET['date'])) {
  $date = $_GET['date'];
}

$tid = "-1";
if (isset($_GET['tid'])) {
  $tid = $_GET['tid'];
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_comment = sprintf("SELECT * FROM tk_comment WHERE coid = %s", GetSQLValueString($_GET['delID'], "int"));
$Recordset_comment = mysql_query($query_Recordset_comment, $tankdb) or die(mysql_error());
$row_Recordset_comment = mysql_fetch_assoc($Recordset_comment);
$totalRows_Recordset_comment = mysql_num_rows($Recordset_comment);

$comuser = $row_Recordset_comment['tk_comm_user'];

$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "5" && $comuser <> $_SESSION['MM_uid']) {   
  header("Location: ". $restrictGoTo); 
  exit;
}


if ((isset($_GET['delID'])) && ($_GET['delID'] != "") && ($_SESSION['MM_Username'] <> $multilingual_dd_user_readonly)) {
  $deleteSQL = sprintf("DELETE FROM tk_comment WHERE coid=%s",
                       GetSQLValueString($_GET['delID'], "int"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($deleteSQL, $tankdb) or die(mysql_error());

if ($row_Recordset_comment['tk_comm_type'] == 3) {
  $updateSQL = sprintf("UPDATE tk_task_byday SET csa_tb_comment=csa_tb_comment-1 WHERE tbid=%s", GetSQLValueString($row_Recordset_comment['tk_comm_pid'], "int"));
  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($updateSQL, $tankdb) or die(mysql_error());
}

if( $projectid== "-1"){
  $deleteGoTo = "default_task_edit.php?editID=$taskid";
  } else if($date <> "-1"){
 $deleteGoTo = "log_view.php?date=".$date."&taskid=".$tid;
} else {
  $deleteGoTo = "project_view.php?recordID=$projectid";
  }
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>

<body>
</body>
</html>