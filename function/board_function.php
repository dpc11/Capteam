<?php
$version = "1.3.3b";
$maxRows = 30;
$tasklevel = 0;
mysql_select_db($database_tankdb,$tankdb);

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
//查找项目看板
function get_board_info($pid){

  global $tankdb;
  $selBoardInfo = "SELECT * FROM tk_board,tk_user WHERE board_from=uid AND board_pid = $pid AND board_del_status=1 AND board_type=1 ORDER BY board_seq";
  $BoardInfoRS = mysql_query($selBoardInfo, $tankdb) or die(mysql_error());

  return $BoardInfoRS;
}

//查找个人看板
function get_personal_board_info($uid){

  global $tankdb;
  $selBoardInfo = "SELECT * FROM tk_board,tk_user WHERE board_from=uid AND board_from = $uid AND board_del_status=1 AND board_type=2 ORDER BY board_seq";
  $BoardInfoRS = mysql_query($selBoardInfo, $tankdb) or die(mysql_error());

  return $BoardInfoRS;
}

?>