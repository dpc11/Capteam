<?php
	$version = "1.3.3b";
	$maxRows = 30;
	$tasklevel = 0;
	mysql_select_db($database_tankdb,$tankdb);

	function delete_all()
    {
        global $tankdb;
        global $database_tankdb;
        $deleteSQL = "DELETE FROM tk_message WHERE tk_mess_status=-1";
        mysql_select_db($database_tankdb, $tankdb);
        $Result1 = mysql_query($deleteSQL, $tankdb) or die(mysql_error());
    }
	
    function delete_message($mid)
    {
        global $tankdb;
        global $database_tankdb;
        $deleteSQL = "DELETE FROM tk_message WHERE meid in ".$mid;
        mysql_select_db($database_tankdb, $tankdb);
        $Result1 = mysql_query($deleteSQL, $tankdb) or die(mysql_error());
    }
	
	function delete_message_to_garbage($mid)
    {
        global $tankdb;
        global $database_tankdb;
        $deleteSQL = "UPDATE tk_message SET tk_mess_status=-1 WHERE meid in ".$mid;
		mysql_select_db($database_tankdb, $tankdb);
        $Result1 = mysql_query($deleteSQL, $tankdb) or die(mysql_error());
    }
	
    function update_message($mid,$status1,$status2)
    {
        global $tankdb;
        global $database_tankdb;
        $deleteSQL = sprintf("UPDATE tk_message set tk_mess_status = %s WHERE meid=%s and tk_mess_status = %s",GetSQLValueString($status2, "int"),GetSQLValueString($mid, "int"),GetSQLValueString($status1, "int"));
        mysql_select_db($database_tankdb, $tankdb);
        $Result1 = mysql_query($deleteSQL, $tankdb) or die(mysql_error());
    }	

function send_message( $to, $from, $type, $id=0, $title=0, $cc=0 ) {
    if($to <> $from & $to <> null){
global $tankdb;
global $database_tankdb;
global $multilingual_message_newtask;
global $multilingual_message_newtaskcomment;
global $multilingual_message_exam;
global $multilingual_message_edituser;
global $multilingual_message_edittask;
global $multilingual_message_newtaskcommit;

global $multilingual_message_newtask_cc;
global $multilingual_message_newtaskcomment_cc;
global $multilingual_message_exam_cc;
global $multilingual_message_edituser_cc;
global $multilingual_message_edittask_cc;
global $multilingual_message_newtaskcommit_cc;


if($cc==0){//是新建任务
$msg_newtask = $multilingual_message_newtask;
$msg_taskcomm = $multilingual_message_newtaskcomment;
$msg_taskcommit = $multilingual_message_newtaskcommit;
$msg_exam = $multilingual_message_exam;
$msg_edituser = $multilingual_message_edituser;
$msg_edittask = $multilingual_message_edittask;
} else {//是抄送
$msg_taskcommit = $multilingual_message_newtaskcommit_cc;
$msg_newtask = $multilingual_message_newtask_cc;
$msg_taskcomm = $multilingual_message_newtaskcomment_cc;
$msg_exam = $multilingual_message_exam_cc;
$msg_edituser = $multilingual_message_edituser_cc;
$msg_edittask = $multilingual_message_edittask_cc;
}

$mail_create = get_item( 'mail_create' );  
$mail_update = get_item( 'mail_update' );  
$mail_comment = get_item( 'mail_comment' );  
//新建任务
if($type=="newtask"){
$text = $msg_newtask." <a href='default_task_edit.php?editID=".$id."&pagetabs=mtask'>".$title."</a>";
if($mail_create=="on" && $cc==0){
send_mail($to,$from,$type,$id,$title);
}
}
//评论任务
else if($type=="taskcomm"){
$text = $msg_taskcomm." <a href='default_task_edit.php?editID=".$id."&pagetabs=mtask#comment'>".$title."</a>";
if($mail_comment=="on" && $cc==0){
send_mail($to,$from,$type,$id,$title);
}
}
//提交任务
else if($type=="taskcommit"){
$text = $msg_taskcommit." <a href='default_task_edit.php?editID=".$id."&pagetabs=mtask#comment'>".$title."</a>";
if($mail_comment=="on" && $cc==0){
send_mail($to,$from,$type,$id,$title);
}
}

else if($type=="logcomm"){
$text = $msg_taskcomm." <a href='default_task_edit.php?editID=".$id."&pagetabs=mtask#log'>".$title."</a>";
if($mail_comment=="on" && $cc==0){
send_mail($to,$from,$type,$id,$title);
}
}

else if($type=="examtask"){
$text = $msg_exam." <a href='default_task_edit.php?editID=".$id."&pagetabs=mtask'>".$title."</a>";
if($mail_create=="on" && $cc==0){
send_mail($to,$from,$type,$id,$title);
}
}

else if($type=="edituser"){
$text = $msg_edituser." <a href='default_task_edit.php?editID=".$id."&pagetabs=mtask'>".$title."</a>";
if($mail_create=="on" && $cc==0){
send_mail($to,$from,$type,$id,$title);
}
}
//编辑任务
else if($type=="edittask"){
$text = $msg_edittask." <a href='default_task_edit.php?editID=".$id."&pagetabs=ftask#log'>".$title."</a>";
if($mail_update=="on" && $cc==0){
send_mail($to,$from,$type,$id,$title);
}
}

$insert_msg_SQL = sprintf("INSERT INTO tk_message (tk_mess_touser, tk_mess_fromuser, tk_mess_title,tk_task_id) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($to, "int"),
                       GetSQLValueString($from, "int"),
                       GetSQLValueString($text, "text"),
                       GetSQLValueString($id, "int"));  
$insert_msg_RS = mysql_query($insert_msg_SQL, $tankdb) or die(mysql_error());

    } //to no from

}
?>