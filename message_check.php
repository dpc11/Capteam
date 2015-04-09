<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php 

$message_count = check_message( $_SESSION['MM_uid'] );

echo json_encode($message_count);

?>
