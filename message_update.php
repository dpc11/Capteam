<!-- 此文件用于ajax异步更新message数据库 -->
<?php require_once('function/dao.php'); ?>
<?php 
$meid = $_POST['meid'];
// echo $meid;
//创建Message数据库操作实体类
$message_dao_obj = new message_dao();
//将半已读的消息（状态为2）置为已读（状态为0）
$message_dao_obj->update_message($row_Recordset1['meid'],2,0);

?>