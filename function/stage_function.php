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
//插入日志
function insert_log($stageid,$myid){

  global $tankdb;
  $log_action = "创建了阶段";
  $insert_log=sprintf("INSERT INTO tk_log(tk_log_user,tk_log_action,tk_log_type,tk_log_class)
          VALUES($myid,%s,$stageid,2)",
             GetSQLValueString($log_action,"text"));
  $Recordset_pfilename = mysql_query($insert_log, $tankdb) or die(mysql_error());
  $log_id = mysql_insert_id();

  return $log_id;
}
?>