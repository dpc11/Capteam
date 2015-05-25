<?php require_once('config/tank_config.php'); ?>
<?php require_once('function/message_function.php'); ?>
<?php 
$mid = $_POST['mid'];
$option = $_POST['option'];

if($option==1){
	delete_message_to_garbage($mid);
}else if($option==2){
	delete_message($mid);
}else if($option==3){
	delete_all();
}

?>