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
function get_parent_folder_id($id){

	global $tankdb;
	$query_Recordset_pfilename = sprintf("SELECT * FROM tk_document WHERE docid = %s", GetSQLValueString($fileid, "int"));
	$Recordset_pfilename = mysql_query($query_Recordset_pfilename, $tankdb) or die(mysql_error());
	$row_Recordset_pfilename = mysql_fetch_assoc($Recordset_pfilename);
	$totalRows_Recordset_pfilename = mysql_num_rows($Recordset_pfilename);

	return $row_Recordset_pfilename['tk_doc_parentdocid'];
}

function get_document_name($id){

	global $tankdb;
	$query_Recordset_pfilename = sprintf("SELECT * FROM tk_document WHERE docid = %s", GetSQLValueString($id, "int"));
	$Recordset_pfilename = mysql_query($query_Recordset_pfilename, $tankdb) or die(mysql_error());
	$row_Recordset_pfilename = mysql_fetch_assoc($Recordset_pfilename);
	
	return $row_Recordset_pfilename['tk_doc_title'];

}
function get_projectID($id){

	global $tankdb;
	$query_Recordset_pfilename = sprintf("SELECT * FROM tk_document WHERE docid = %s", GetSQLValueString($fileid, "int"));
	$Recordset_pfilename = mysql_query($query_Recordset_pfilename, $tankdb) or die(mysql_error());
	$row_Recordset_pfilename = mysql_fetch_assoc($Recordset_pfilename);
	$totalRows_Recordset_pfilename = mysql_num_rows($Recordset_pfilename);
	
	return $row_Recordset_pfilename['tk_doc_pid'];

}

function get_doc_description($id){

	global $tankdb;
	$query_Recordset_pfilename = sprintf("SELECT * FROM tk_document WHERE docid = %s", GetSQLValueString($fileid, "int"));
	$Recordset_pfilename = mysql_query($query_Recordset_pfilename, $tankdb) or die(mysql_error());
	$row_Recordset_pfilename = mysql_fetch_assoc($Recordset_pfilename);
	$totalRows_Recordset_pfilename = mysql_num_rows($Recordset_pfilename);
	
	return $row_Recordset_pfilename['tk_doc_description'];

}
?>