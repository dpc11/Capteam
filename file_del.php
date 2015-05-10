<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php require_once('function/file_function.php'); ?>
<?php require_once('function/file_log_function.php'); ?>
<?php
$restrictGoTo = "user_error3.php";

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
$p_id = "-1";
if (isset($_GET['pid'])) {
  $p_id = $_GET['pid'];
}
$projectid = "-1";
if (isset($_GET['projectID'])) {
  $projectid = $_GET['projectID'];
}

$pageurl = "-1";
if (isset($_GET['url'])) {
  $pageurl = $_GET['url'];
}
$newName = $_SESSION['MM_uid'];
$pageurl =strtr($pageurl,"!","&");

if ((isset($_GET['delID'])) && ($_GET['delID'] != "")) {
	/*
  $deleteSQL = sprintf("UPDATE tk_document SET tk_doc_del_status=-1 WHERE docid=%s",
                       GetSQLValueString($_GET['delID'], "int"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($deleteSQL, $tankdb) or die(mysql_error());
*/

	delete_doc(GetSQLValueString($_GET['delID'], "int"));

  $log_id = delete_file_log(GetSQLValueString($_GET['delID'], "int"),$newName);

	$deleteGoTo = $pageurl;  

	header(sprintf("Location: %s", $deleteGoTo));
}
?>
<!DOCTYPE html PUBLIC>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>

<body>
<?php echo $pageurl;?>
</body>
</html>