<?php require_once('config/tank_config.php'); ?>
<?php 
if (!isset($_SESSION)) {
  session_start();
}

if ($_SESSION['MM_UserGroup'] == $multilingual_dd_role_admin) {
  $userrank = "5";
  $loginStrlogin = $_SESSION['MM_Username'];
  $updateSQL = sprintf("UPDATE tk_user SET tk_user_rank='$userrank' WHERE tk_user_login='$loginStrlogin'");
  mysql_select_db($database_tankdb, $tankdb);
  $Result2 = mysql_query($updateSQL, $tankdb) or die(mysql_error());
  $_SESSION['MM_rank'] = $userrank;
}
?>
<?php require_once('session_unset.php'); ?>
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

$update = 2;
if (isset($_POST['agreecheck'])) {
$update = $_POST['agreecheck'];
}

if($update == "start"){ //如果用户点击升级

if($version < "1.2.9"){ //如果版本小于129
$insertSQL = sprintf("INSERT ignore INTO tk_status (task_status, task_status_display, task_status_backup1, task_status_backup2) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString("完成验收", "text"),
                       GetSQLValueString("<div style='background-color: #336699; width:100%; text-align:center;'>完成验收</div>", "text"),
					   GetSQLValueString("22", "text"),
                       GetSQLValueString("1", "text"));

$insertSQL2 = sprintf("INSERT ignore INTO tk_status (task_status, task_status_display, task_status_backup1, task_status_backup2) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString("驳回", "text"),
                       GetSQLValueString("<div style='background-color: red; width:100%; text-align:center;'>驳回</div>", "text"),
					   GetSQLValueString("23", "text"),
                       GetSQLValueString("1", "text"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());
  $Result2 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());

} //if >=129

if($version < "1.3.0"){ //如果版本小于130
set_time_limit(3600);

mysql_select_db($database_tankdb, $tankdb);

$query_Recordset1 = "SELECT * FROM  `tk_user` ";
$Recordset1 = mysql_query($query_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

do{ //update user id

$user_id = $row_Recordset1['ID'];
$user_name = $row_Recordset1['tk_user_login'];

mysql_query("UPDATE `tk_project` SET  `project_to_user` =  '$user_id' WHERE `project_to_user` =  '$user_name';", $tankdb) or die(mysql_error());

mysql_query("UPDATE  `tk_document` SET  `tk_doc_create` =  '$user_id' WHERE  `tk_doc_create` =  '$user_name';",    $tankdb) or die(mysql_error());

mysql_query("UPDATE  `tk_document` SET  `tk_doc_edit` =  '$user_id' WHERE  `tk_doc_edit` =  '$user_name';",    $tankdb) or die(mysql_error());

mysql_query("UPDATE  `tk_announcement` SET  `tk_anc_create` =  '$user_id' WHERE  `tk_anc_create` =  '$user_name' ;", $tankdb) or die(mysql_error());

mysql_query("UPDATE  `tk_task` SET  `csa_from_user` =  '$user_id' WHERE  `csa_from_user` =  '$user_name';", $tankdb) or die(mysql_error());

mysql_query("UPDATE  `tk_task` SET  `csa_to_user` =  '$user_id' WHERE  `csa_to_user` =  '$user_name';", $tankdb) or die(mysql_error());

mysql_query("UPDATE  `tk_task` SET  `csa_create_user` =  '$user_id' WHERE  `csa_create_user` =  '$user_name';", $tankdb) or die(mysql_error());

mysql_query("UPDATE  `tk_task` SET  `csa_last_user` =  '$user_id' WHERE  `csa_last_user` =  '$user_name';", $tankdb) or die(mysql_error());

mysql_query("UPDATE  `tk_comment` SET  `tk_comm_user` =  '$user_id' WHERE  `tk_comm_user` =  '$user_name';", $tankdb) or die(mysql_error());

mysql_query("UPDATE  `tk_log` SET  `tk_log_user` =  '$user_id' WHERE  `tk_log_user` =  '$user_name';", $tankdb) or die(mysql_error());

mysql_query("UPDATE  `tk_task_byday` SET  `csa_tb_backup2` =  '$user_id' WHERE  `csa_tb_backup2` =  '$user_name';", $tankdb) or die(mysql_error());

}while($row_Recordset1 = mysql_fetch_assoc($Recordset1));

//update project status
$query_Recordset2 = "SELECT * FROM  `tk_status_project` ";
$Recordset2 = mysql_query($query_Recordset2, $tankdb) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);

do{
$p_status_id = $row_Recordset2['id'];
$p_status_style = $row_Recordset2['task_status_display'];
mysql_query("UPDATE  `tk_project` SET  `project_status` =  '$p_status_id' WHERE  `project_status` =  \"$p_status_style\";", $tankdb) or die(mysql_error());
}while($row_Recordset2 = mysql_fetch_assoc($Recordset2));


//update log
mysql_query("UPDATE  `tk_log` SET  `tk_log_class` =  '1' WHERE  `tk_log_type` NOT LIKE  'doc%';", $tankdb) or die(mysql_error());
mysql_query("UPDATE  `tk_log` SET  `tk_log_class` =  '2' WHERE  `tk_log_type` LIKE  'doc%';", $tankdb) or die(mysql_error());

$query_Recordset3 = "SELECT * FROM  `tk_log` WHERE `tk_log_class` =  '2'";
$Recordset3 = mysql_query($query_Recordset3, $tankdb) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);

do{
$log_type = $row_Recordset3['tk_log_type'];
$log_type_id = substr($log_type, 3);

mysql_query("UPDATE  `tk_log` SET  `tk_log_type` =  '$log_type_id' WHERE  `tk_log_type` =  '$log_type';", $tankdb) or die(mysql_error());
}while($row_Recordset3 = mysql_fetch_assoc($Recordset3));


//update comment
mysql_query("UPDATE  `tk_comment` SET  `tk_comm_type` =  '1' WHERE  `tk_comm_pid` NOT LIKE  'log%' AND `tk_comm_pid` NOT LIKE  '000%';", $tankdb) or die(mysql_error());
mysql_query("UPDATE  `tk_comment` SET  `tk_comm_type` =  '2' WHERE  `tk_comm_pid` LIKE  '000%';", $tankdb) or die(mysql_error());
mysql_query("UPDATE  `tk_comment` SET  `tk_comm_type` =  '3' WHERE  `tk_comm_pid` LIKE  'log%';", $tankdb) or die(mysql_error());

$query_Recordset5 = "SELECT * FROM  `tk_comment` WHERE `tk_comm_type` =  '3'";
$Recordset5 = mysql_query($query_Recordset5, $tankdb) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);

do{
$com_type = $row_Recordset5['tk_comm_pid'];
$com_type_id = substr($com_type, 3);

mysql_query("UPDATE  `tk_comment` SET  `tk_comm_pid` =  '$com_type_id' WHERE  `tk_comm_pid` =  '$com_type';", $tankdb) or die(mysql_error());
}while($row_Recordset5 = mysql_fetch_assoc($Recordset5));


//exit;

//tk_task
mysql_query("ALTER TABLE  `tk_task` CHANGE  `TID`  `TID` BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_from_dept`  `csa_from_dept` MEDIUMINT( 6 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_from_user`  `csa_from_user` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_to_dept`  `csa_to_dept` MEDIUMINT( 6 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_to_user`  `csa_to_user` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_year`  `csa_year` SMALLINT( 5 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_month`  `csa_month` TINYINT( 3 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_project`  `csa_project` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_project_sub`  `csa_project_sub` MEDIUMINT( 7 ) NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_type`  `csa_type` SMALLINT( 4 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_text`  `csa_text` VARCHAR( 80 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_priority`  `csa_priority` TINYINT( 3 ) NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_temp`  `csa_temp` TINYINT( 3 ) NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_plan_st`  `csa_plan_st` DATE NOT NULL DEFAULT  '0000-00-00'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_plan_et`  `csa_plan_et` DATE NOT NULL DEFAULT  '0000-00-00'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_plan_hour`  `csa_plan_hour` FLOAT( 20, 1 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_remark1`  `csa_remark1` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_remark2`  `csa_remark2` SMALLINT( 4 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_remark3`  `csa_remark3` BIGINT( 20 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_remark3`  `csa_remark3` DATETIME NOT NULL DEFAULT  '0000-00-00 00:00:00'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_remark4`  `csa_remark4` BIGINT( 20 ) NOT NULL DEFAULT  '-1'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_remark5`  `csa_remark5` VARCHAR( 300 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '>>-1'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_remark6`  `csa_remark6` BIGINT( 20 ) NOT NULL DEFAULT  '-1'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_remark7`  `csa_remark7` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_remark8`  `csa_remark8` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `test01`  `test01` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `test02`  `test02` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `test03`  `test03` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `test04`  `test04` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_create_user`  `csa_create_user` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_last_user`  `csa_last_user` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_last_update`  `csa_last_update` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP", $tankdb) or die(mysql_error());


//tk_announcement
mysql_query("ALTER TABLE  `tk_announcement` CHANGE  `tk_anc_title`  `tk_anc_title` VARCHAR( 80 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_announcement` CHANGE  `tk_anc_text`  `tk_anc_text` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_announcement` CHANGE  `tk_anc_type`  `tk_anc_type` SMALLINT( 4 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_announcement` CHANGE  `tk_anc_create`  `tk_anc_create` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_announcement` DROP  `tk_anc_backup1`", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_announcement` DROP  `tk_anc_backup2`", $tankdb) or die(mysql_error());


//tk_comment
mysql_query("ALTER TABLE  `tk_comment` CHANGE  `tk_comm_title`  `tk_comm_title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_comment` CHANGE  `tk_comm_text`  `tk_comm_text` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_comment` CHANGE  `tk_comm_type`  `tk_comm_type` TINYINT( 2 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_comment` CHANGE  `tk_comm_user`  `tk_comm_user` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_comment` CHANGE  `tk_comm_pid`  `tk_comm_pid` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_comment` DROP  `tk_comm_backup1`", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_comment` DROP  `tk_comm_backup2`", $tankdb) or die(mysql_error());


//tk_document
mysql_query("ALTER TABLE  `tk_document` CHANGE  `tk_doc_title`  `tk_doc_title` VARCHAR( 80 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_document` CHANGE  `tk_doc_description`  `tk_doc_description` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_document` CHANGE  `tk_doc_attachment`  `tk_doc_attachment` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  ''", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_document` CHANGE  `tk_doc_class1`  `tk_doc_class1` BIGINT( 20 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_document` CHANGE  `tk_doc_class2`  `tk_doc_class2` BIGINT( 20 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_document` CHANGE  `tk_doc_type`  `tk_doc_type` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_document` CHANGE  `tk_doc_create`  `tk_doc_create` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_document` CHANGE  `tk_doc_createtime`  `tk_doc_createtime` DATETIME NOT NULL DEFAULT  '0000-00-00 00:00:00'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_document` CHANGE  `tk_doc_edit`  `tk_doc_edit` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_document` CHANGE  `tk_doc_edittime`  `tk_doc_edittime` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_document` CHANGE  `tk_doc_backup1`  `tk_doc_backup1` TINYINT( 2 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_document` CHANGE  `tk_doc_backup2`  `tk_doc_backup2` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());


//tk_item
mysql_query("ALTER TABLE  `tk_item` CHANGE  `item_id`  `item_id` SMALLINT( 4 ) UNSIGNED NOT NULL AUTO_INCREMENT", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_item` CHANGE  `tk_item_key`  `tk_item_key` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_item` CHANGE  `tk_item_value`  `tk_item_value` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_item` CHANGE  `tk_item_title`  `tk_item_title` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_item` CHANGE  `tk_item_description`  `tk_item_description` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_item` CHANGE  `tk_item_type`  `tk_item_type` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_item` DROP  `tk_item_backup1`", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_item` DROP  `tk_item_backup2`", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_item` DROP  `tk_item_backup3`", $tankdb) or die(mysql_error());


//tk_log
mysql_query("ALTER TABLE  `tk_log` CHANGE  `tk_log_user`  `tk_log_user` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_log` CHANGE  `tk_log_action`  `tk_log_action` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_log` CHANGE  `tk_log_time`  `tk_log_time` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_log` CHANGE  `tk_log_type`  `tk_log_type` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_log` CHANGE  `tk_log_class`  `tk_log_class` TINYINT( 2 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_log` CHANGE  `tk_log_description`  `tk_log_description` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_log` DROP  `tk_log_backup1`", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_log` DROP  `tk_log_backup2`", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_log` DROP  `tk_log_backup3`", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_log` DROP  `tk_log_backup4`", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_log` DROP  `tk_log_backup5`", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_log` DROP  `tk_log_backup6`", $tankdb) or die(mysql_error());



//tk_project
mysql_query("ALTER TABLE  `tk_project` CHANGE  `id`  `id` BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_project` CHANGE  `project_name`  `project_name` VARCHAR( 80 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_project` CHANGE  `project_code`  `project_code` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_project` CHANGE  `project_text`  `project_text` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_project` CHANGE  `project_type`  `project_type` TINYINT( 2 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_project` CHANGE  `project_from`  `project_from` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_project` CHANGE  `project_from_user`  `project_from_user` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_project` CHANGE  `project_from_contact`  `project_from_contact` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_project` CHANGE  `project_start`  `project_start` DATE NOT NULL DEFAULT  '0000-00-00'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_project` CHANGE  `project_end`  `project_end` DATE NOT NULL DEFAULT  '0000-00-00'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_project` CHANGE  `project_to_dept`  `project_to_dept` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_project` CHANGE  `project_to_user`  `project_to_user` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_project` CHANGE  `project_status`  `project_status` SMALLINT( 4 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_project` CHANGE  `project_remark`  `project_remark` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_project` DROP  `project_backup1`", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_project` DROP  `project_backup2`", $tankdb) or die(mysql_error());



//tk_status
mysql_query("ALTER TABLE  `tk_status` CHANGE  `id`  `id` SMALLINT( 4 ) UNSIGNED NOT NULL AUTO_INCREMENT", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_status` CHANGE  `task_status`  `task_status` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_status` CHANGE  `task_status_display`  `task_status_display` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_status` CHANGE  `task_status_backup1`  `task_status_backup1` BIGINT( 20 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_status` CHANGE  `task_status_backup2`  `task_status_backup2` TINYINT( 2 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());



//tk_status_project
mysql_query("ALTER TABLE  `tk_status_project` CHANGE  `id`  `psid` SMALLINT( 4 ) UNSIGNED NOT NULL AUTO_INCREMENT", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_status_project` CHANGE  `task_status`  `task_status` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_status_project` CHANGE  `task_status_display`  `task_status_display` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_status_project` CHANGE  `task_status_pbackup1`  `task_status_pbackup1` BIGINT( 20 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_status_project` DROP  `task_status_pbackup2`", $tankdb) or die(mysql_error());




//tk_task_byday
mysql_query("ALTER TABLE  `tk_task_byday` CHANGE  `csa_tb_year`  `csa_tb_year` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task_byday` DROP  `csa_tb_month`", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task_byday` DROP  `csa_tb_day`", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task_byday` CHANGE  `csa_tb_status`  `csa_tb_status` SMALLINT( 4 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task_byday` CHANGE  `csa_tb_manhour`  `csa_tb_manhour` FLOAT( 20, 1 ) NOT NULL DEFAULT  '0.0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task_byday` CHANGE  `csa_tb_text`  `csa_tb_text` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task_byday` CHANGE  `csa_tb_comment`  `csa_tb_comment` SMALLINT( 5 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task_byday` CHANGE  `csa_tb_backup1`  `csa_tb_backup1` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task_byday` CHANGE  `csa_tb_backup2`  `csa_tb_backup2` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task_byday` CHANGE  `csa_tb_backup3`  `csa_tb_backup3` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task_byday` CHANGE  `csa_tb_backup4`  `csa_tb_backup4` SMALLINT( 4 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());




//tk_task_tpye
mysql_query("ALTER TABLE  `tk_task_tpye` CHANGE  `id`  `id` SMALLINT( 4 ) UNSIGNED NOT NULL AUTO_INCREMENT", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task_tpye` CHANGE  `task_tpye`  `task_tpye` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task_tpye` CHANGE  `tk_task_typerank`  `tk_task_typerank` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task_tpye` CHANGE  `task_tpye_backup1`  `task_tpye_backup1` BIGINT( 20 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task_tpye` DROP  `task_tpye_backup2`", $tankdb) or die(mysql_error());


//tk_user
mysql_query("ALTER TABLE  `tk_user` CHANGE  `ID`  `uid` BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_user` CHANGE  `tk_user_login`  `tk_user_login` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  ''", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_user` CHANGE  `tk_user_pass`  `tk_user_pass` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  ''", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_user` CHANGE  `tk_display_name`  `tk_display_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  ''", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_user` CHANGE  `pid`  `pid` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_user` CHANGE  `tk_user_status`  `tk_user_status` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  ''", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_user` CHANGE  `tk_user_remark`  `tk_user_remark` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_user` CHANGE  `tk_user_rank`  `tk_user_rank` TINYINT( 2 ) NOT NULL DEFAULT  '0'", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_user` CHANGE  `tk_user_contact`  `tk_user_contact` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  ''", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_user` CHANGE  `tk_user_email`  `tk_user_email` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  ''", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_user` CHANGE  `tk_user_backup1`  `tk_user_backup1` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_user` DROP  `tk_user_backup2`", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_user` DROP  `tk_user_backup3`", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_user` DROP  `tk_user_backup4`", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_user` DROP  `tk_user_backup5`", $tankdb) or die(mysql_error());

//index
mysql_query("ALTER TABLE tk_task ADD INDEX touser_st_et( csa_to_user, csa_plan_st, csa_plan_et )", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE tk_task ADD INDEX fruser( csa_from_user )", $tankdb) or die(mysql_error());


//updatedata

mysql_query("DELETE FROM `tk_item` WHERE `tk_item`.`item_id`=2", $tankdb) or die(mysql_error());
mysql_query("DELETE FROM `tk_item` WHERE `tk_item`.`item_id`=6", $tankdb) or die(mysql_error());

$subprj = "子项目";

$ca = "控制账户";

mysql_query("INSERT INTO `tk_task_tpye` (`task_tpye` ,`task_tpye_backup1`)VALUES ( '".$ca."',  '0')", $tankdb) or die(mysql_error());

mysql_query("INSERT INTO `tk_task_tpye` (`task_tpye` ,`task_tpye_backup1`)VALUES ( '".$subprj."',  '0')", $tankdb) or die(mysql_error());
} //if >=130


if($version < "1.3.1"){ //如果版本小于131

} //if >=131

if($version < "1.3.2"){ //如果版本小于132
mysql_query("ALTER TABLE  `tk_user` ADD  `tk_user_token` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '0' AFTER  `tk_user_pass`", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_user` ADD  `tk_user_message` BIGINT( 20 ) NOT NULL DEFAULT  '0' AFTER  `tk_user_email`", $tankdb) or die(mysql_error());

mysql_query("
CREATE TABLE `tk_message` (
`meid` BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`tk_mess_touser` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT  '0',
`tk_mess_fromuser` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT  '0',
`tk_mess_title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`tk_mess_text` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`tk_mess_status` TINYINT( 2 ) NOT NULL DEFAULT  '1',
`tk_mess_type` TINYINT( 2 ) NOT NULL DEFAULT  '0',
`tk_mess_time` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = INNODB;
", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_message` ADD INDEX (  `tk_mess_touser` )", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_message` ADD INDEX (  `tk_mess_fromuser` )", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_task` CHANGE  `csa_remark8`  `csa_remark8` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL", $tankdb) or die(mysql_error());
} //if >=132

if($version < "1.3.3"){ //如果版本小于133
mysql_query("ALTER TABLE  `tk_task` CHANGE  `test01`  `test01` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL", $tankdb) or die(mysql_error());

mysql_query("ALTER TABLE  `tk_user` ADD  `tk_user_lastuse` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER  `tk_user_message`", $tankdb) or die(mysql_error());
} //if >=133


$update_rs =1; //如果用户点击升级,且成功

} else {
$update_rs =0; //如果用户点击升级,且失败

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS升级说明</title>

<style type="text/css">
<!--
img {border: none;}
li {list-style: none;}
body,html{  font-family:Arial; width:100%;   margin: 0; padding: 0; min-width:960px; background:#E0E0E0; }
body {
	font-size: 12px; line-height:150%;
}
table {
	font-size: 12px;
}
p{ margin:5px;}
.main_body{ width:947px; margin:10px auto auto auto;}
.content_bg{ width:945px; border:1px solid #CCCCCC;  background:#FFF; }
.rowcon{ width:95%;  margin:auto; }
.rowcon td a{ color:#395a90;text-decoration:underline;  padding-right:10px; }
.rowcon td a:visited{ color:#395a90;text-decoration:underline; }
.rowcon td a:hover{ color:#395a90;text-decoration:none; }
.rowcon a{ color:#395a90;text-decoration:underline;  padding-right:10px; }
.rowcon a:visited{ color:#395a90;text-decoration:underline; }
.rowcon a:hover{ color:#395a90;text-decoration:none; }
.big_text{ font-size:36px; font-weight:bold; line-height:150%;}
.font_big18{ font-size:18px; font-weight:bold;}
.ping_logo{ background:url(wss/skin/themes/base/images/wsslogo.png) no-repeat; width:158px; height:158px; margin:20px auto;}
}
-->
</style>
<script>
var checkobj
function agreesubmit(el){
checkobj=el
if (document.all||document.getElementById){
for (i=0;i<checkobj.form.length;i++){  //hunt down submit button
var tempobj=checkobj.form.elements[i]
if(tempobj.type.toLowerCase()=="submit")
tempobj.disabled=!checkobj.checked
}
}
}

function defaultagree(el){
if (!document.all && !document.getElementById){
if (window.checkobj && checkobj.checked)
return true
else{
alert("Please read/accept terms to submit form")
return false
}
}
}
</script>
</head>

<body>
<div class="main_body">
<div class="content_bg">
<div class="rowcon">


  <?php  if($update_rs == 0){ ?>  
  <p>&nbsp;</p>
  <p><span class="font_big18">WSS升级说明</span><br/><br/>
  <span class="font_big18">注意，本升级脚本在Wamp环境的默认配置下，经过数十次测试，均可正常运行。由于不同的amp环境对数据库的操作权限不同，我们不保证在其他amp环境中可正常升级，如果您的环境中无法正常运行本升级脚本，我们建议您先将您正在使用的WSS中的数据库导出，然后使用一台windows环境的pc，参考 <a href="http://www.wssys.net/zh-CN/file.php?recordID=16&projectID=-1" target="_blank">5分钟安装说明</a> 部署一套Wamp环境当前版本的WSS并导入您刚导出的数据，在Wamp环境中运行升级脚本，对数据库进行升级后，再导入至您其他amp环境中的WSS。</span><br/><br/>
  </p>
  <p><span class="font_big18">使用这种方式升级所需时间不超过10分钟，WSS官方不再受理任何升级脚本相关的问题。</span><br/><br/></p>
  <p><span class="font_big18">另外，二次开发导致的无法升级也不在官方支持的范围内。</span><br/><br/></p>
  <p>&nbsp;</p>
<p>请仔细阅读以下步骤再进行升级：</p>
  <p>&nbsp;</p>
  <p>1) <b>备份数据库</b>：本升级脚本会对您的数据库进行升级，理论上不会造成您的数据丢失的情况，但为了安全，我们仍然建议您先备份数据库再进行升级，备份方式：使用phpmyadmin完整的导出tankdb表；<br />
	  2) 确认本文件已经正确拷贝至您要升级的WSS目录下；<br />
	  3) 确认已经使用管理员权限登录WSS（此时升级操作还未进行，登录的还是您老版本的WSS）；<br />
	  4) 确认以上准备工作后，请点击“开始升级”按钮，进行升级，如果您系统中的数据很多，升级操作将需要几分钟甚至更多时间，请耐心等待。升级过程中，请不要关闭本页面，或关闭服务器电源，否则将导致升级失败；<br />
	  5) 如升级失败，请使用phpmyadmin恢复您所备份的数据库，并重新执行本升级脚本。<br />

	<p>&nbsp;</p>
	<p>    
	<p>
    <form name="agreeform" onSubmit="return defaultagree(this)" method="POST" action="upgrade.php">
	
	
<label><input name="agreecheck" type="checkbox" onClick="agreesubmit(this)" value="start"><b>我已备份数据库，并且了解升级操作不可逆</b></label><br>
<input type="Submit" value="开始升级" disabled>
</form>

<script>
document.forms.agreeform.agreecheck.checked=false
</script>
<p></p>
	<p>&nbsp;</p>
	<p><br/>
	  <span class="font_big18">免责声明</span><br/>
	  </p>
	<p>WSS为使用者根据需要自愿下载使用，White Shark System以及WSS的作者，对WSS使用过程中造成的任何数据丢失及其他风险不承担任何责任。</p>
  <br/>

  <?php }else if ($update_rs ==1){ ?>  
<p>&nbsp;</p>
<p><span class="font_big18">数据库升级成功</span><br/>
<p>&nbsp;</p>
<p>数据库升级已经完成，请继续执行以下操作完成升级：</p>
	<p>&nbsp;</p>
	<p>1) 返回 <a href="index.php" target="_blank">首页，</a>并退出登录（重要）；</p>
	<p>2) 使用 WSS 压缩包中的 WSS目录覆盖你服务器上的WSS目录（如修改过数据库连接文件config/tank_config.php，则升级后需要重新配置）；</p>
	<p>3) 删除服务器上本升级脚本文件（upgrade.php）；</p>
	<p>4) 重新登录后，可开始使用 WSS，如升级后样式错乱，使用ctrl+F5 强制刷新。</p>
	<br /><br />

   <?php } ?>  
</div>
</div>
</div> <br/>
</body>
</html>