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
//得到文件夹的父文件夹id
function get_parent_folder_id($id){

	global $tankdb;
	$query_Recordset_pfilename = sprintf("SELECT * FROM tk_document WHERE docid = %s", GetSQLValueString($id, "int"));
	$Recordset_pfilename = mysql_query($query_Recordset_pfilename, $tankdb) or die(mysql_error());
	$row_Recordset_pfilename = mysql_fetch_assoc($Recordset_pfilename);

	return $row_Recordset_pfilename['tk_doc_parentdocid'];
}

//得到文件夹的名字
function get_document_name($id){

	global $tankdb;
	$query_Recordset_pfilename = sprintf("SELECT * FROM tk_document WHERE docid = %s", GetSQLValueString($id, "int"));
	$Recordset_pfilename = mysql_query($query_Recordset_pfilename, $tankdb) or die(mysql_error());
	$row_Recordset_pfilename = mysql_fetch_assoc($Recordset_pfilename);
	
	return $row_Recordset_pfilename['tk_doc_title'];

}

//得到文档所在的项目的id
function get_projectID($id){

	global $tankdb;
	$query_Recordset_pfilename = sprintf("SELECT * FROM tk_document WHERE docid = %s", GetSQLValueString($id, "int"));
	$Recordset_pfilename = mysql_query($query_Recordset_pfilename, $tankdb) or die(mysql_error());
	$row_Recordset_pfilename = mysql_fetch_assoc($Recordset_pfilename);
	
	return $row_Recordset_pfilename['tk_doc_pid'];

}

//得到项目对应的根目录文件夹的id
function get_project_document_ID($id){

	global $tankdb;
	$query_Recordset_pfilename = sprintf("SELECT * FROM tk_document WHERE tk_doc_pid = %s and tk_doc_parentdocid=-1", GetSQLValueString($id, "int"));
	$Recordset_pfilename = mysql_query($query_Recordset_pfilename, $tankdb) or die(mysql_error());
	$row_Recordset_pfilename = mysql_fetch_assoc($Recordset_pfilename);
	$totalRows_Recordset_pfilename = mysql_num_rows($Recordset_pfilename);
	
	return $row_Recordset_pfilename['docid'];

}

//得到文件夹的描述
function get_doc_description($id){

	global $tankdb;
	$query_Recordset_pfilename = sprintf("SELECT * FROM tk_document WHERE docid = %s", GetSQLValueString($id, "int"));
	$Recordset_pfilename = mysql_query($query_Recordset_pfilename, $tankdb) or die(mysql_error());
	$row_Recordset_pfilename = mysql_fetch_assoc($Recordset_pfilename);
	$totalRows_Recordset_pfilename = mysql_num_rows($Recordset_pfilename);
	
	return $row_Recordset_pfilename['tk_doc_description'];

}

function delete_doc($id){
	
	$folder_number = check_folder_num($id);
	if($folder_number>0)
	{
		$DetailRS1=get_parent_folders($id);
		$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);
		do{
			
			delete_doc($row_DetailRS1['docid']);
			
		}while ($row_DetailRS1 = mysql_fetch_assoc($DetailRS1));
			$rows = mysql_num_rows($DetailRS1);
			if($rows > 0) {
				mysql_data_seek($Recordset_file, 0);
				$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);
			} 
	}
	global $tankdb;
	$query_Recordset_pfilename = sprintf("UPDATE tk_document SET tk_doc_del_status=-1 WHERE tk_doc_parentdocid=%s", GetSQLValueString($id, "int"));
	$Recordset_pfilename = mysql_query($query_Recordset_pfilename, $tankdb) or die(mysql_error());
	$row_Recordset_pfilename = mysql_fetch_assoc($Recordset_pfilename);
	
	//删除他自己
	global $tankdb;
	$query_Recordset_pfilename = sprintf("UPDATE tk_document SET tk_doc_del_status=-1 WHERE docid=%s", GetSQLValueString($id, "int"));
	$Recordset_pfilename = mysql_query($query_Recordset_pfilename, $tankdb) or die(mysql_error());
	$row_Recordset_pfilename = mysql_fetch_assoc($Recordset_pfilename);

}

function get_parent_folders($id){

	global $tankdb;
	$query_Recordset_pfilename = sprintf("SELECT distinct docid FROM tk_document WHERE tk_doc_parentdocid = %s and tk_doc_backup1=1 and tk_doc_del_status=1", GetSQLValueString($id, "int"));
	$Recordset_pfilename = mysql_query($query_Recordset_pfilename, $tankdb) or die(mysql_error());
	
	return $Recordset_pfilename;
	
}

function check_folder_num($id){

	global $tankdb;
	$query_Recordset_pfilename = sprintf("SELECT * FROM tk_document WHERE tk_doc_parentdocid = %s and tk_doc_backup1=1 and tk_doc_del_status=1", GetSQLValueString($id, "int"));
	$Recordset_pfilename = mysql_query($query_Recordset_pfilename, $tankdb) or die(mysql_error());
	$row_Recordset_pfilename = mysql_fetch_assoc($Recordset_pfilename);
	$totalRows_Recordset_pfilename = mysql_num_rows($Recordset_pfilename);
	
	return $totalRows_Recordset_pfilename;
	
}
?>