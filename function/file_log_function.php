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
//新建插入日志
function insert_log_file($proid,$myid,$parentid,$docid){

  global $tankdb;

  $isProject = isProject($parentid);

  if($isProject == 1)//是项目文件
  {
    $log_action = "创建了项目文件";
  }
  else
  {
    $log_action = "创建了阶段文件";
  }

  $insert_log=sprintf("INSERT INTO tk_log(tk_log_user,tk_log_action,tk_log_type,tk_log_class)
          VALUES($myid,%s,$docid,4)",
             GetSQLValueString($log_action,"text"));
  $Recordset_pfilename = mysql_query($insert_log, $tankdb) or die(mysql_error());
  $log_id = mysql_insert_id();

  return $log_id;
}

//编辑插入日志
function update_log_file($proid,$myid,$parentid,$docid){

  global $tankdb;

  $isProject = isProject($parentid);

  if($isProject == 1)//是项目文件
  {
    $log_action = "编辑了项目文件";
  }
  else
  {
    $log_action = "编辑了阶段文件";
  }

  $insert_log=sprintf("INSERT INTO tk_log(tk_log_user,tk_log_action,tk_log_type,tk_log_class)
          VALUES($myid,%s,$docid,4)",
             GetSQLValueString($log_action,"text"));
  $Recordset_pfilename = mysql_query($insert_log, $tankdb) or die(mysql_error());
  $log_id = mysql_insert_id();

  return $log_id;
}


function isProject($parentid){
  global $tankdb;

  $selDocDetail = "SELECT * FROM tk_document WHERE docid=$parentid";
  $DocRS = mysql_query($selDocDetail, $tankdb) or die(mysql_error());
  $row = mysql_fetch_assoc($DocRS);

  if($row['tk_doc_parentdocid'] == -1)
    return 1;//是项目文件夹
  else
    return 0;//不是项目文件夹
}

//获取阶段文件的log列表
function get_stage_file_log($folder_id){

  global $tankdb;

  $selStageFileLog="SELECT * FROM tk_log,tk_document,tk_user WHERE tk_log_class=4 and tk_log_type= docid and tk_doc_parentdocid=$folder_id and tk_log_user=uid";
  $StageFileLogRS = mysql_query($selStageFileLog, $tankdb) or die(mysql_error());

  return $StageFileLogRS;
}

?>