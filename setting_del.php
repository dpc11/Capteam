<?php require_once('config/tank_config.php'); ?>
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

if ((isset($_GET['delID'])) && ($_GET['delID'] != "") && ($_SESSION['MM_Username'] <> $multilingual_dd_user_readonly)) {
  $deleteSQL = sprintf("DELETE FROM tk_item WHERE item_id=%s",
                       GetSQLValueString($_GET['delID'], "int"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($deleteSQL, $tankdb) or die(mysql_error());

$itemtype = "-1";
if (isset($_GET['type'])) {
  $itemtype = $_GET['type'];
}

  $deleteGoTo = "setting.php?type=$itemtype";

  header(sprintf("Location: %s", $deleteGoTo));
}
?>
<!DOCTYPE html PUBLIC >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
</body>
</html>