<?php require_once('config/tank_config.php'); 
//require_once('session.php');

$email_to = "-1";
if (isset($_GET['to'])) {
  $email_to = $_GET['to'];
}

$email_from = "-1";
if (isset($_GET['from'])) {
  $email_from = $_GET['from'];
}

$email_type = "-1";
if (isset($_GET['type'])) {
  $email_type = $_GET['type'];
}

$email_id = "-1";
if (isset($_GET['id'])) {
  $email_id = $_GET['id'];
}

$email_title = "-1";
if (isset($_GET['title'])) {
  $email_title = $_GET['title'];
}


$email_port = "-1";
if (isset($_GET['port'])) {
  $email_port = $_GET['port'];
}



if($email_to <> "-1"){


$userfile = get_user($email_to);

$fromuser = get_user($email_from);

$nowuser = $fromuser->name;


$self_url =  "http://".$_SERVER['SERVER_NAME'].":".$email_port.$_SERVER['PHP_SELF'];
$self =  substr($self_url , strrpos($self_url , '/') + 1);
$host_url=str_replace($self,'',$self_url);

if($email_type=="newtask"){ //newtask
$mail_subject = $nowuser." ".$multilingual_message_newtask." ".$email_title;
$mail_body = $nowuser." ".$multilingual_message_newtask." <a href='".$host_url."default_task_edit.php?editID=".$email_id."&pagetab=mtask'>".$email_title."</a>";
}


if($email_type=="taskcomm"){ //taskcomm
$mail_subject = $nowuser." ".$multilingual_message_newtaskcomment." ".$email_title;
$mail_body = $nowuser." ".$multilingual_message_newtaskcomment." <a href='".$host_url."default_task_edit.php?editID=".$email_id."&pagetab=mtask#comment'>".$email_title."</a>";
}


if($email_type=="logcomm"){ //logcomm
$mail_subject = $nowuser." ".$multilingual_message_newtaskcomment." ".$email_title;
$mail_body = $nowuser." ".$multilingual_message_newtaskcomment." <a href='".$host_url."default_task_edit.php?editID=".$email_id."&pagetab=mtask#log'>".$email_title."</a>";
}


if($email_type=="examtask"){ //examtask
$mail_subject = $nowuser." ".$multilingual_message_exam." ".$email_title;
$mail_body = $nowuser." ".$multilingual_message_exam." <a href='".$host_url."default_task_edit.php?editID=".$email_id."&pagetab=mtask'>".$email_title."</a>";
}

else if($email_type=="edituser"){
$mail_subject = $nowuser." ".$multilingual_message_edituser." ".$email_title;
$mail_body = $nowuser." ".$multilingual_message_edituser." <a href='".$host_url."default_task_edit.php?editID=".$email_id."&pagetab=mtask'>".$email_title."</a>";
}

else if($email_type=="edittask"){
$mail_subject = $nowuser." ".$multilingual_message_edittask." ".$email_title;
$mail_body = $nowuser." ".$multilingual_message_edittask." <a href='".$host_url."default_task_edit.php?editID=".$email_id."&pagetab=ftask#log'>".$email_title."</a>";
}


$send_email = wss_post_office($userfile->email,$mail_subject,$mail_body);

}



?>