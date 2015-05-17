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

//get item
function get_item( $item ) {
	
	global $tankdb;
	$sql_item = "SELECT tk_item_value FROM tk_item WHERE tk_item_key = '$item'";

	$Recordset_item = mysql_query($sql_item, $tankdb) or die(mysql_error());
	$row_Recordset_item = mysql_fetch_assoc($Recordset_item);
	
	return $row_Recordset_item['tk_item_value'];
}


//check message
function check_message( $userid ) {
	global $tankdb;
	global $database_tankdb;

	$user_message_id = $_SESSION['MM_msg'];
	$count_message_SQL = sprintf("SELECT 
								COUNT(meid) as count_msg   
								FROM tk_message  							
					WHERE tk_mess_status = 1 AND tk_mess_touser = '$userid'"
									);//选择未读的消息
	//WHERE tk_mess_status = 1 AND tk_mess_touser = '$userid'"
	//WHERE meid > '$user_message_id' AND tk_mess_touser = '$userid'"
	$count_message_RS = mysql_query($count_message_SQL, $tankdb) or die(mysql_error());
	$row_count_message = mysql_fetch_assoc($count_message_RS);
	//$_SESSION['MM_msg_con'] = $row_count_message['count_msg'];
	return $row_count_message['count_msg'];
}

?>