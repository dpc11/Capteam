<?php require_once('config/tank_config.php'); ?>
<?php require_once('function/message_function.php'); ?>
<?php 
$meid = $_POST['meid'];

//将半已读的消息（状态为2）置为已读（状态为0）
update_message($meid,2,0);

?>