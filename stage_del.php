<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_admin.php'); ?>
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

if ((isset($_GET['delID'])) && ($_GET['delID'] != "")) {

  $del_id = $_GET['delID'];
  $selFolderID = "SELECT tk_stage_folder_id,tk_stage_pid FROM tk_stage WHERE stageid=$del_id";
    mysql_select_db($database_tankdb,$tankdb);
  $Result1 = mysql_query($selFolderID,$tankdb) or die(mysql_error());
  $row = mysql_fetch_array($Result1);
  $project_id=$row['tk_stage_pid'];
  $folder_id=$row['tk_stage_folder_id'];

  $deleteStageSQL = "UPDATE tk_stage SET tk_stage_delestatus=-1 WHERE stageid=$del_id";
  mysql_select_db($database_tankdb, $tankdb);
  $Result2 = mysql_query($deleteStageSQL, $tankdb) or die(mysql_error());

  $deleteTaskSQL = "UPDATE tk_task SET csa_del_status=-1 WHERE csa_project_stage=$del_id";
  mysql_select_db($database_tankdb, $tankdb);
  $Result3 = mysql_query($deleteTaskSQL, $tankdb) or die(mysql_error());

  $deleteDocSQL = "UPDATE tk_document SET tk_doc_del_status=-1 WHERE docid=$folder_id OR tk_doc_parentdocid=$folder_id";
  mysql_select_db($database_tankdb, $tankdb);
  $Result4 = mysql_query($deleteDocSQL, $tankdb) or die(mysql_error());

  $deleteGoTo = "project_view.php?recordID=$project_id";
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