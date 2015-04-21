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

//strsToArray
function strsToArray($strs) { 
$result = array(); 
$array = array(); 
$strs = str_replace('，', ',', $strs); 
$strs = str_replace("n", ',', $strs); 
$strs = str_replace("rn", ',', $strs); 
$strs = str_replace(' ', ',', $strs); 
$array = explode(',', $strs); 
foreach ($array as $key => $value) { 
if ('' != ($value = trim($value))) { 
$result[] = $value; 
} 
} 
return $result; 
} 

//搜索二维数组
function in_2array($strs, $arr){
   $exist = 0;
   foreach($arr as $value){
     if(in_array($strs, $value)){
        $exist = 1;
        break;    //循环判断字符串是否存在于一位数组，存在则跳出  返回结果
     }
   }
   return $exist;
}
//二维数组去重
function array_unique_fb($array2D) { 
foreach ($array2D as $k=>$v) 
{ 
$v = join(",",$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串 
$temp[$k] = $v; 
} 
$temp = array_unique($temp); //去掉重复的字符串,也就是重复的一维数组 
foreach ($temp as $k => $v) 
{ 
$array=explode(",",$v); //再将拆开的数组重新组装 
$temp2[$k]["uid"] =$array[0]; 
$temp2[$k]["uname"] =$array[1]; 
} 
return $temp2; 
} 

//get pinyin
function getFirstLetter($str){     
	$fchar = ord($str{0});  
	if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($str{0});  
	@$s1 = iconv("UTF-8","gb2312", $str);  
	$s2 = iconv("gb2312","UTF-8", $s1);  
	if($s2 == $str){$s = $s1;}
	else{$s = $str;}  
	$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;  
	if($asc>=-20319 and $asc<=-20284)return "A";
if($asc>=-20283 and $asc<=-19776)return "B";
if($asc>=-19775 and $asc<=-19219)return "C";
if($asc>=-19218 and $asc<=-18711)return "D";
if($asc>=-18710 and $asc<=-18527)return "E";
if($asc>=-18526 and $asc<=-18240)return "F";
if($asc>=-18239 and $asc<=-17923)return "G";
if($asc>=-17922 and $asc<=-17418)return "H";
if($asc>=-17417 and $asc<=-16475)return "J";
if($asc>=-16474 and $asc<=-16213)return "K";
if($asc>=-16212 and $asc<=-15641)return "L";
if($asc>=-15640 and $asc<=-15166)return "M";
if($asc>=-15165 and $asc<=-14923)return "N";
if($asc>=-14922 and $asc<=-14915)return "O";
if($asc>=-14914 and $asc<=-14631)return "P";
if($asc>=-14630 and $asc<=-14150)return "Q";
if($asc>=-14149 and $asc<=-14091)return "R";
if($asc>=-14090 and $asc<=-13319)return "S";
if($asc>=-13318 and $asc<=-12839)return "T";
if($asc>=-12838 and $asc<=-12557)return "W";
if($asc>=-12556 and $asc<=-11848)return "X";
if($asc>=-11847 and $asc<=-11056)return "Y";
if($asc>=-11055 and $asc<=-10247)return "Z";
	return null;  
}  
 
function pinyin($zh){  
     $ret = "";  
     $s1 = iconv("UTF-8","gb2312", $zh);  
     $s2 = iconv("gb2312","UTF-8", $s1);  
     if($s2 == $zh){$zh = $s1;}  
     for($i = 0; $i < strlen($zh); $i++){  
         $s1 = substr($zh,$i,1);  
         $p = ord($s1);  
         if($p > 160){  
             $s2 = substr($zh,$i++,2);  
             $ret .= getFirstLetter($s2);  
         }else{  
             $ret .= $s1;  
         }  
     }  
     return $ret;  
}    

//add lastuser
function pushlastuse($uid, $name, $myid){  
global $tankdb;
global $database_tankdb;
$last_use_json = '[{"uid":"'.$uid.'", "uname":'.json_encode($name).' }]';
if($_SESSION['MM_last'] == null){
$last_use_arr = $last_use_json;

}else{
$last_use_new = json_decode($last_use_json, true);

$last_use_old = json_decode($_SESSION['MM_last'], true);

$last_use_merge = array_merge($last_use_new, $last_use_old); 

$last_use_unique = array_unique_fb($last_use_merge);

if(count($last_use_unique) > 5){
array_pop($last_use_unique);
}

$last_use_arr = json_encode($last_use_unique);
}

mysql_select_db($database_tankdb, $tankdb);
$update_lastuse = sprintf("UPDATE tk_user SET tk_user_lastuse=%s WHERE uid = $myid",
                       GetSQLValueString($last_use_arr, "text"));  
$rs_update_lastuse = mysql_query($update_lastuse, $tankdb) or die(mysql_error());
$_SESSION['MM_last'] = $last_use_arr;

return $last_use_arr;
}

//add task
function add_task( $ccuser = 0, $fuser, $tuser, $projectid, $type, $text, $priority, $temp, $start, $end, $hour, $status, $cuser, $luser, $taskid, $wbs, $wbsid, $nowuser, $tag, $remark ) {
global $tankdb;
global $database_tankdb;
global $multilingual_log_addtask;
$insertSQL = sprintf("INSERT INTO tk_task (test01, test02, csa_remark1, csa_from_user, csa_to_user, csa_project, csa_type, csa_text, csa_priority, csa_temp, csa_plan_st, csa_plan_et, csa_plan_hour, csa_remark2, csa_create_user, csa_last_user, csa_remark4, csa_remark5, csa_remark6, csa_project_sub, csa_remark7, test03, test04) VALUES (%s, $tag $remark %s, %s, %s, %s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, '0', '', '', '')",
                       GetSQLValueString($ccuser, "text"),
					   GetSQLValueString($fuser, "text"),
                       GetSQLValueString($tuser, "text"),
                       GetSQLValueString($projectid, "text"),
                       GetSQLValueString($type, "text"),
                       GetSQLValueString($text, "text"),
                       GetSQLValueString($priority, "text"),
                       GetSQLValueString($temp, "text"),
					   GetSQLValueString($start, "text"),
					   GetSQLValueString($end, "text"),
					   GetSQLValueString($hour, "text"),
					   GetSQLValueString($status, "text"),
					   GetSQLValueString($cuser, "text"),
					   GetSQLValueString($luser, "text"),
					   GetSQLValueString($taskid, "text"),
					   GetSQLValueString($wbs, "text"),
					   GetSQLValueString($wbsid, "text"));



  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());
  
  $newID = mysql_insert_id();
    $newName = $nowuser;

$insertSQL2 = sprintf("INSERT INTO tk_log (tk_log_user, tk_log_action, tk_log_type, tk_log_class, tk_log_description) VALUES (%s, %s, %s , 1, '' )",
                       GetSQLValueString($newName, "text"),
                       GetSQLValueString($multilingual_log_addtask, "text"),
                       GetSQLValueString($newID, "text"));  
  $Result2 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());

  return $newID;
}

//get tree
function get_tree( $projectid ) {
global $tankdb;
global $database_tankdb;


mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = "SELECT * FROM tk_task 
inner join tk_user on tk_task.csa_to_user=tk_user.uid 
inner join tk_status on tk_task.csa_status=tk_status.id 
WHERE csa_project = '$projectid' ORDER BY TID";
$Recordset1 = mysql_query($query_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

$FoundTask = mysql_num_rows($Recordset1);
    
if (!$FoundTask) {
 return 0;   
 
}

$i=0;
do{
	if($row_Recordset1['csa_remark4']=="-1"){
$pid= 0;
	} else {
$pid= $row_Recordset1['csa_remark4'];
	}
	

$str = $row_Recordset1['task_status_display'];
$str =  explode('background-color:', $str);
$str =  explode('width:', $str[1]);



$nodename = "<span style ='color:".$str[0]."'>■</span>"." [".$row_Recordset1['task_tpye']."]".$row_Recordset1['csa_text'];
$nodetitle = $row_Recordset1['task_status']." - ".$row_Recordset1['tk_display_name']." - ".$row_Recordset1['csa_text'];


$result[] = array('id'=>$row_Recordset1['TID'],'pid'=>$pid,'name'=>$nodename,'title'=>$nodetitle,);
$i++;
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); 

$str=json_encode($result);

return $str;
}

//get userinfo
function get_user($userid, $channel ="default"){
global $tankdb;
global $database_tankdb;

$query_touser =  sprintf("SELECT * FROM tk_user WHERE uid = %s",
                       GetSQLValueString($userid, "int"));  
$touser = mysql_query($query_touser, $tankdb) or die(mysql_error());
$row_touser = mysql_fetch_assoc($touser);

$userinfo->name = $row_touser["tk_display_name"];
$userinfo->email = $row_touser["tk_user_email"];
    if($channel == "infopage"){
      $userinfo->remark = $row_touser["tk_user_remark"];
        //$userinfo->rank = $row_touser["tk_user_rank"];  
      $userinfo->phone = $row_touser["tk_user_contact"];  
    }

return $userinfo;
}

//post office
function wss_post_office($to,$subject = "",$body = ""){

    //require_once('mail/class.phpmailer.php');
    //include("mail/class.smtp.php"); 
	require_once 'mail/PHPMailerAutoload.php';
    $mail             = new PHPMailer(); 
    $body             = preg_replace("/\[\]/",'',$body); 
	$mailto =  strstr( $to, '@', TRUE );
    $mail->CharSet = get_item( 'mail_charset' );                   //邮件编码格式设置
    $mail->IsSMTP(); 

    $mail->SMTPAuth   = get_item( 'mail_auth' );                  // 启用 SMTP 验证功能
   // $mail->SMTPSecure = "ssl";                 // SSL安全协议
    $mail->Host       = get_item( 'mail_host' );       // SMTP 邮件服务器地址,如:smtp.sina.com
    $mail->Port       = get_item( 'mail_port' );                    // SMTP 邮件服务器的端口号,默认为25
    $mail->Username   = get_item( 'mail_username' );   // 用户名:邮件帐号的用户名,如使用新浪邮箱，请填写完整的邮件地址,如: yourname@sina.com
    $mail->Password   = get_item( 'mail_password' );        // 密码:邮件帐号的密码
    $mail->From = get_item( 'mail_from' );         // 发送邮件的邮箱,如: yourname@sina.com
	$mail->FromName   = get_item( 'mail_fromname' );                 // 邮件发送人的显示名称
    $mail->Subject    = $subject;
    $mail->MsgHTML($body);
    $address = $to;
    $mail->AddAddress($address, $mailto);

    if(!$mail->Send()) {
        return "Mailer Error: " . $mail->ErrorInfo;
    } else {
		return "0";

	}
}

//send mail
function send_mail($to,$from,$type,$id,$title){
$email_to_opt = $to;
$email_from_opt = $from;
$email_type_opt = $type;
$email_id_opt = $id;
$email_title_opt = preg_replace("/ /","",$title);


$uri=$_SERVER["REQUEST_URI"]; 
$uri = dirname($uri)."/";

$fp = fsockopen($_SERVER['SERVER_NAME'], $_SERVER["SERVER_PORT"], $errno, $errstr, 30);
if (!$fp) {
    echo "$errstr ($errno)<br />\n";
} else {
    $out = "GET /".$uri."send_mail.php?port=".$_SERVER["SERVER_PORT"]."&to=".$email_to_opt."&from=".$email_from_opt."&type=".$email_type_opt."&id=".$email_id_opt."&title=".$email_title_opt."  / HTTP/1.1\r\n";
    $out .= "Host: ".$_SERVER["SERVER_NAME"]."\r\n";
    $out .= "Connection: Close\r\n\r\n";
  
    fwrite($fp, $out);
  /*忽略执行结果
    while (!feof($fp)) {
        echo fgets($fp, 128);
    }
	*/
    fclose($fp);
}


}

//check message
function check_message( $userid ) {
global $tankdb;
global $database_tankdb;

$user_message_id = $_SESSION['MM_msg'];
$count_message_SQL = sprintf("SELECT 
							COUNT(meid) as count_msg   
							FROM tk_message  							
							WHERE meid > '$user_message_id' AND tk_mess_touser = '$userid'"
								);
$count_message_RS = mysql_query($count_message_SQL, $tankdb) or die(mysql_error());
$row_count_message = mysql_fetch_assoc($count_message_RS);
//$_SESSION['MM_msg_con'] = $row_count_message['count_msg'];
return $row_count_message['count_msg'];
}


//send message
function send_message( $to, $from, $type, $id=0, $title=0, $cc=0 ) {
	if($to <> $from & $to <> null){
global $tankdb;
global $database_tankdb;
global $multilingual_message_newtask;
global $multilingual_message_newtaskcomment;
global $multilingual_message_exam;
global $multilingual_message_edituser;
global $multilingual_message_edittask;

global $multilingual_message_newtask_cc;
global $multilingual_message_newtaskcomment_cc;
global $multilingual_message_exam_cc;
global $multilingual_message_edituser_cc;
global $multilingual_message_edittask_cc;


if($cc==0){
$msg_newtask = $multilingual_message_newtask;
$msg_taskcomm = $multilingual_message_newtaskcomment;
$msg_exam = $multilingual_message_exam;
$msg_edituser = $multilingual_message_edituser;
$msg_edittask = $multilingual_message_edittask;
} else {
$msg_newtask = $multilingual_message_newtask_cc;
$msg_taskcomm = $multilingual_message_newtaskcomment_cc;
$msg_exam = $multilingual_message_exam_cc;
$msg_edituser = $multilingual_message_edituser_cc;
$msg_edittask = $multilingual_message_edittask_cc;
}

$mail_create = get_item( 'mail_create' );  
$mail_update = get_item( 'mail_update' );  
$mail_comment = get_item( 'mail_comment' );  

if($type=="newtask"){
$text = $msg_newtask." <a href='default_task_edit.php?editID=".$id."&pagetabs=mtask'>".$title."</a>";
if($mail_create=="on" && $cc==0){
send_mail($to,$from,$type,$id,$title);
}
}

else if($type=="taskcomm"){
$text = $msg_taskcomm." <a href='default_task_edit.php?editID=".$id."&pagetabs=mtask#comment'>".$title."</a>";
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

else if($type=="edittask"){
$text = $msg_edittask." <a href='default_task_edit.php?editID=".$id."&pagetabs=ftask#log'>".$title."</a>";
if($mail_update=="on" && $cc==0){
send_mail($to,$from,$type,$id,$title);
}
}

$insert_msg_SQL = sprintf("INSERT INTO tk_message (tk_mess_touser, tk_mess_fromuser, tk_mess_title) VALUES (%s, %s, %s )",
                       GetSQLValueString($to, "int"),
                       GetSQLValueString($from, "int"),
                       GetSQLValueString($text, "text"));  
$insert_msg_RS = mysql_query($insert_msg_SQL, $tankdb) or die(mysql_error());

	} //to no from

}

//check user and create token
function check_user( $useracc, $userpss ) {
global $tankdb;
global $database_tankdb;

  $tk_password = md5(crypt($userpss,substr($userpss,0,2)));
 $LoginRS__query=sprintf("SELECT uid, tk_user_login, tk_display_name, tk_user_rank FROM tk_user WHERE binary tk_user_login=%s AND (tk_user_pass=%s OR tk_user_pass=%s)",
  GetSQLValueString($useracc, "text"), GetSQLValueString($tk_password, "text"), GetSQLValueString($userpss, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $tankdb) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  
 
  if ($loginFoundUser) {

    $loginStruid  = mysql_result($LoginRS,0,'uid');
	$loginStrDisplayname  = mysql_result($LoginRS,0,'tk_display_name');
	$loginStrrank  = mysql_result($LoginRS,0,'tk_user_rank');
      if($loginStrrank == 0){
         return 2;  
      }


  $timeline = time();
  $basekey = str_shuffle("bjklmnopqrstu0123456789ABIJKLMNOPWXYZ").$timeline.$useracc;
  $token = md5($basekey);
	 $updateSQL = sprintf("UPDATE tk_user SET tk_user_token=%s WHERE tk_user_login=%s", 
                       GetSQLValueString($token, "text"),                      
                       GetSQLValueString($useracc, "text"));
  $Result2 = mysql_query($updateSQL, $tankdb) or die(mysql_error());

  $userarr = array(
	 'token'=>$token, 
     'uid'=>$loginStruid, 
	 'name'=>$loginStrDisplayname, 
	 'rank'=>$loginStrrank 
  );

  return  $userarr;

  }else {
	   return 3;
  }
}

//delete token
function del_token( $token ) {
global $tankdb;
global $database_tankdb;
$updateSQL = sprintf("UPDATE tk_user SET tk_user_token=0 WHERE tk_user_token=%s", 
                       GetSQLValueString($token, "text"));
  $Result2 = mysql_query($updateSQL, $tankdb) or die(mysql_error());

 // return  $updateSQL;
}

//check token
function check_token( $token ) {
global $tankdb;
global $database_tankdb;
 $LoginRS__query=sprintf("SELECT uid FROM tk_user WHERE tk_user_token=%s",
  GetSQLValueString($token, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $tankdb) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  
 
  if ($loginFoundUser) {

$loginStrpid  = mysql_result($LoginRS,0,'uid');

 return  $loginStrpid;
  } else {
 return  3;
  }
}

//task list
function task_list( $to = "0", $from = "0", $create = "0", $prt = "", $temp = "", $status = "", $unstatus = "+", $type = "", $project = "", $taskid = "", $tasktitle = "", $tag = "", $exam = "", $years = "--", $months = "--", $sort= "csa_last_update", $order= "DESC", $page="0", $pagetabs = "mtask" ) {

global $tankdb;
global $database_tankdb;
global $maxRows;

$maxRows_Recordset1 =$maxRows;
$pageNum_Recordset1 = 0;
if (isset($page)) {
  $pageNum_Recordset1 = $page;
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

if ($years == "--"){
$startday = "1975-09-23";
$endday = "3000-13-31";
} else if ($months == "--"){
$startday = $years."-01-01";
$endday = $years."-12-31";
} else {
$startday = $years."-".$months."-01";
$endday = $years."-".$months."-31";
}

if($years == "--"){
$YEAR = "0000";
} else {
$YEAR = $years;
}
if($months == "--"){
$MONTH = "00";
} else {
$MONTH = $months;
}

$coltouser = GetSQLValueString($to, "int");
$colfromuser = GetSQLValueString($from, "int");
$colcreateuser = GetSQLValueString($create, "int");

$colprt = GetSQLValueString($prt, "int");
$coltemp = GetSQLValueString($temp, "int");
$colstatus = GetSQLValueString("%%" . str_replace("%","%%",$status) . "%%", "text");
$colstatusf = GetSQLValueString("%%" . str_replace("%","%%",$unstatus) . "%%", "text");
$coltype = GetSQLValueString($type, "int");
$colproject = GetSQLValueString($project, "int");
$colinputid = GetSQLValueString("%%" . str_replace("%","%%",$taskid) . "%%", "text");
$colinputtitle = GetSQLValueString("%%" . str_replace("%","%%",$tasktitle) . "%%", "text");
$colinputtag = GetSQLValueString("%%" . str_replace("%","%%",$tag) . "%%", "text");
$colexams = GetSQLValueString("%%" . str_replace("%","%%",$exam) . "%%", "text");

		$where = "";
			$where=' WHERE';

			if($to <> 0 )
			{
				$where.= " tk_task.csa_to_user = $coltouser AND";
			}
			
			if($from <> 0)
			{
				$where.= " tk_task.csa_from_user = $colfromuser AND";
			}
			
			if(!empty($prt))
			{
				$where.= " tk_task.csa_priority = $colprt AND";
			}
			
			if(!empty($temp))
			{
				$where.= " tk_task.csa_temp = $coltemp AND";
			}
			
			if(!empty($status) && $pagetabs <> "etask")
			{
				$where.= " tk_status.task_status LIKE $colstatus AND";
			}
			
			if($pagetabs == "etask")
			{
				$where.= " tk_status.task_status LIKE $colexams AND";
			}
			
			if($unstatus  <> '+' && $pagetabs <> "etask")
			{
				$where.= " tk_status.task_status NOT LIKE $colstatusf AND";
			}
			
			if(!empty($type))
			{
				$where.= " tk_task.csa_type = $coltype AND";
			}
			
			if(!empty($project))
			{
				$where.= " tk_task.csa_project = $colproject AND";
			}
			
			if(!empty($taskid))
			{
				$where.= " tk_task.TID LIKE $colinputid AND";
			}
			
			if(!empty($tasktitle))
			{
				$where.= " tk_task.csa_text LIKE $colinputtitle AND";
			}
			
			if(!empty($tag))
			{
				$where.= " tk_task.test02 LIKE $colinputtag AND";
			}
			
			if($create <> 0)
			{
				$where.= " tk_task.csa_create_user = $colcreateuser AND";
			}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT *, 
							
							tk_project.project_name as project_name_prt,
							tk_user1.tk_display_name as tk_display_name1, 
							tk_user2.tk_display_name as tk_display_name2
							
							FROM tk_task  
							inner join tk_task_tpye on tk_task.csa_type=tk_task_tpye.id
							inner join tk_project on tk_task.csa_project=tk_project.id
							
							inner join tk_user as tk_user1 on tk_task.csa_to_user=tk_user1.uid 
							inner join tk_user as tk_user2 on tk_task.csa_from_user=tk_user2.uid 
							
							inner join tk_status on tk_task.csa_remark2=tk_status.id
							
							$where 
							(tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st >=%s
 							AND tk_task.csa_plan_et <=%s)
														
							ORDER BY %s %s", 
							
							GetSQLValueString($startday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($sort,   "defined", $sort, "NULL"),
							GetSQLValueString($order,  "defined", $order, "NULL")
							);

//return $query_Recordset1;

$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$task_arr = array ();
do { 

$task_arr["task"][$row_Recordset1['TID']]['TID'] =  $row_Recordset1['TID'];
$task_arr["task"][$row_Recordset1['TID']]['type'] =  $row_Recordset1['task_tpye'];
$task_arr["task"][$row_Recordset1['TID']]['title'] =  $row_Recordset1['csa_text'];
$task_arr["task"][$row_Recordset1['TID']]['to'] =  $row_Recordset1['tk_display_name1'];
    //$task_arr["task"][$row_Recordset1['TID']]['to_id'] =  $row_Recordset1['csa_to_user'];
    //$task_arr["task"][$row_Recordset1['TID']]['task_status_display'] =  $row_Recordset1['task_status_display'];
$task_arr["task"][$row_Recordset1['TID']]['task_status'] =  $row_Recordset1['task_status'];
    //$task_arr["task"][$row_Recordset1['TID']]['start'] =  $row_Recordset1['csa_plan_st'];
    //$task_arr["task"][$row_Recordset1['TID']]['end'] =  $row_Recordset1['csa_plan_et'];
    //$task_arr["task"][$row_Recordset1['TID']]['project_id'] =  $row_Recordset1['csa_project'];
    //$task_arr["task"][$row_Recordset1['TID']]['project'] =  $row_Recordset1['project_name_prt'];
    //$task_arr["task"][$row_Recordset1['TID']]['from_id'] =  $row_Recordset1['csa_from_user'];
    $task_arr["task"][$row_Recordset1['TID']]['from'] =  $row_Recordset1['tk_display_name2'];
    //$task_arr["task"][$row_Recordset1['TID']]['priority'] =  $row_Recordset1['csa_priority'];
    //$task_arr["task"][$row_Recordset1['TID']]['level'] =  $row_Recordset1['csa_temp'];
    //$task_arr["task"][$row_Recordset1['TID']]['lastupdate'] =  $row_Recordset1['csa_last_update'];

} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
$task_arr["total"]= $totalRows_Recordset1;

return $task_arr;
}


//sum exam
function sum_exam( $uid ) {
global $tankdb;
global $database_tankdb;
global $multilingual_dd_status_exam;
$query_Recordset_sumtotal = sprintf("SELECT 
							COUNT(*) as count_task   
							FROM tk_task 	
							inner join tk_status on tk_task.csa_remark2=tk_status.id 							
							WHERE csa_from_user = %s AND task_status LIKE %s", 
								GetSQLValueString($uid, "int"),
								GetSQLValueString("%" . $multilingual_dd_status_exam . "%", "text")
								);
$Recordset_sumtotal = mysql_query($query_Recordset_sumtotal, $tankdb) or die(mysql_error());
$row_Recordset_sumtotal = mysql_fetch_assoc($Recordset_sumtotal);
return $row_Recordset_sumtotal['count_task'];
}


//task view
function task_view( $taskid ) {
global $tankdb;
global $database_tankdb;
global $maxRows;

global $multilingual_dd_priority_p5;
global $multilingual_dd_priority_p4;
global $multilingual_dd_priority_p3;
global $multilingual_dd_priority_p2;
global $multilingual_dd_priority_p1;

global $multilingual_dd_level_l5;
global $multilingual_dd_level_l4;
global $multilingual_dd_level_l3;
global $multilingual_dd_level_l2;
global $multilingual_dd_level_l1;

$query_Recordset_task = sprintf("SELECT *, 
tk_user1.tk_display_name as tk_display_name1, 
tk_user2.tk_display_name as tk_display_name2, 
tk_user3.tk_display_name as tk_display_name3, 
tk_user4.tk_display_name as tk_display_name4,
tk_project.id as proid    
FROM tk_task 
inner join tk_task_tpye on tk_task.csa_type=tk_task_tpye.id 
inner join tk_status on tk_task.csa_remark2=tk_status.id 
inner join tk_user as tk_user1 on tk_task.csa_to_user=tk_user1.uid 
inner join tk_user as tk_user2 on tk_task.csa_from_user=tk_user2.uid 
inner join tk_user as tk_user3 on tk_task.csa_create_user=tk_user3.uid 
inner join tk_user as tk_user4 on tk_task.csa_last_user=tk_user4.uid 
inner join tk_project on tk_task.csa_project=tk_project.id 
WHERE TID = %s", GetSQLValueString($taskid, "int"));
$Recordset_task = mysql_query($query_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);


$query_Recordset_sumlog = sprintf("SELECT sum(csa_tb_manhour) as sum_hour FROM tk_task_byday WHERE csa_tb_backup1= %s", GetSQLValueString($taskid, "int"));
$Recordset_sumlog = mysql_query($query_Recordset_sumlog, $tankdb) or die(mysql_error());
$row_Recordset_sumlog = mysql_fetch_assoc($Recordset_sumlog);
    
    
$pattaskid = $row_Recordset_task['csa_remark4'];

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_pattask = "SELECT * FROM tk_task inner join tk_task_tpye on tk_task.csa_type=tk_task_tpye.id WHERE TID = '$pattaskid'";
$Recordset_pattask = mysql_query($query_Recordset_pattask, $tankdb) or die(mysql_error());
$row_Recordset_pattask = mysql_fetch_assoc($Recordset_pattask);
    

$maxRows_Recordset_subtask = $maxRows;
$pageNum_Recordset_subtask = 0;
if (isset($_GET['pageNum_Recordset_subtask'])) {
  $pageNum_Recordset_subtask = $_GET['pageNum_Recordset_subtask'];
}
$startRow_Recordset_subtask = $pageNum_Recordset_subtask * $maxRows_Recordset_subtask;

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_subtask = sprintf("SELECT * 
							FROM tk_task 
							inner join tk_task_tpye on tk_task.csa_type=tk_task_tpye.id								
							inner join tk_user on tk_task.csa_to_user=tk_user.uid 
							inner join tk_status on tk_task.csa_remark2=tk_status.id 
							WHERE tk_task.csa_remark4 = %s ORDER BY csa_last_update DESC", 
								GetSQLValueString($taskid, "int")
								);
$query_limit_Recordset_subtask = sprintf("%s LIMIT %d, %d", $query_Recordset_subtask, $startRow_Recordset_subtask, $maxRows_Recordset_subtask);
$Recordset_subtask = mysql_query($query_limit_Recordset_subtask, $tankdb) or die(mysql_error());
$row_Recordset_subtask = mysql_fetch_assoc($Recordset_subtask);

if (isset($_GET['totalRows_Recordset_subtask'])) {
  $totalRows_Recordset_subtask = $_GET['totalRows_Recordset_subtask'];
} else {
  $all_Recordset_subtask = mysql_query($query_Recordset_subtask);
  $totalRows_Recordset_subtask = mysql_num_rows($all_Recordset_subtask);
}
$totalPages_Recordset_subtask = ceil($totalRows_Recordset_subtask/$maxRows_Recordset_subtask)-1;

$queryString_Recordset_subtask = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_subtask") == false && 
        stristr($param, "totalRows_Recordset_subtask") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_subtask = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_subtask = sprintf("&totalRows_Recordset_subtask=%d%s", $totalRows_Recordset_subtask, $queryString_Recordset_subtask);
    
    
$query_task_day = sprintf("SELECT * FROM tk_task_byday 
inner join tk_status on tk_task_byday.csa_tb_status=tk_status.id 
WHERE csa_tb_backup1= %s ORDER BY csa_tb_year DESC LIMIT 0 , $maxRows", GetSQLValueString($taskid, "int"));
$Recordset_task_day = mysql_query($query_task_day, $tankdb) or die(mysql_error());
$row_Recordset_task_day = mysql_fetch_assoc($Recordset_task_day);
$totalRows_Recordset_task_day = mysql_num_rows($Recordset_task_day);

    
    
$maxRows_Recordset_comment = $maxRows;
$pageNum_Recordset_comment = 0;
if (isset($_GET['pageNum_Recordset_comment'])) {
  $pageNum_Recordset_comment = $_GET['pageNum_Recordset_comment'];
}
$startRow_Recordset_comment = $pageNum_Recordset_comment * $maxRows_Recordset_comment;

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_comment = sprintf("SELECT * FROM tk_comment 
inner join tk_user on tk_comment.tk_comm_user =tk_user.uid 
								 WHERE tk_comm_pid = %s AND tk_comm_type = 1 
								
								ORDER BY tk_comm_lastupdate DESC", 
								GetSQLValueString($taskid, "int")
								);
$query_limit_Recordset_comment = sprintf("%s LIMIT %d, %d", $query_Recordset_comment, $startRow_Recordset_comment, $maxRows_Recordset_comment);
$Recordset_comment = mysql_query($query_limit_Recordset_comment, $tankdb) or die(mysql_error());
$row_Recordset_comment = mysql_fetch_assoc($Recordset_comment);

if (isset($_GET['totalRows_Recordset_comment'])) {
  $totalRows_Recordset_comment = $_GET['totalRows_Recordset_comment'];
} else {
  $all_Recordset_comment = mysql_query($query_Recordset_comment);
  $totalRows_Recordset_comment = mysql_num_rows($all_Recordset_comment);
}
$totalPages_Recordset_comment = ceil($totalRows_Recordset_comment/$maxRows_Recordset_comment)-1;

$queryString_Recordset_comment = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_comment") == false && 
        stristr($param, "totalRows_Recordset_comment") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_comment = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_comment = sprintf("&totalRows_Recordset_comment=%d%s", $totalRows_Recordset_comment, $queryString_Recordset_comment);

switch ($row_Recordset_task['csa_priority'])
{
case 5:
  $priority = $multilingual_dd_priority_p5;
  break;
case 4:
  $priority =  $multilingual_dd_priority_p4;
  break;
case 3:
  $priority =  $multilingual_dd_priority_p3;
  break;
case 2:
  $priority =  $multilingual_dd_priority_p2;
  break;
case 1:
  $priority =  $multilingual_dd_priority_p1;
  break;
}

switch ($row_Recordset_task['csa_temp'])
{
case 5:
  $level = $multilingual_dd_level_l5;
  break;
case 4:
  $level =  $multilingual_dd_level_l4;
  break;
case 3:
  $level =  $multilingual_dd_level_l3;
  break;
case 2:
  $level =  $multilingual_dd_level_l2;
  break;
case 1:
  $level =  $multilingual_dd_level_l1;
  break;
}
    
    
$comment_arr = array ();
do { 

$comment_arr[$row_Recordset_comment['coid']]['coid'] =  $row_Recordset_comment['coid'];
$comment_arr[$row_Recordset_comment['coid']]['text'] =  $row_Recordset_comment['tk_comm_title'];
$comment_arr[$row_Recordset_comment['coid']]['userid'] =  $row_Recordset_comment['tk_comm_user'];
$comment_arr[$row_Recordset_comment['coid']]['user'] =  $row_Recordset_comment['tk_display_name'];
$comment_arr[$row_Recordset_comment['coid']]['date'] =  $row_Recordset_comment['tk_comm_lastupdate'];


} while ($row_Recordset_comment = mysql_fetch_assoc($Recordset_comment));     
    
    
$task_day_arr = array ();
do { 

$task_day_arr[$row_Recordset_task_day['tbid']]['tbid'] =  $row_Recordset_task_day['tbid'];
$task_day_arr[$row_Recordset_task_day['tbid']]['date'] =  $row_Recordset_task_day['csa_tb_year'];
$task_day_arr[$row_Recordset_task_day['tbid']]['status'] =  $row_Recordset_task_day['task_status'];
$task_day_arr[$row_Recordset_task_day['tbid']]['hour'] =  $row_Recordset_task_day['csa_tb_manhour'];
$task_day_arr[$row_Recordset_task_day['tbid']]['text'] =  $row_Recordset_task_day['csa_tb_text'];
$task_day_arr[$row_Recordset_task_day['tbid']]['comment'] =  $row_Recordset_task_day['csa_tb_comment'];


} while ($row_Recordset_task_day = mysql_fetch_assoc($Recordset_task_day));     
    
    
$sub_task_arr = array ();
do { 

$sub_task_arr[$row_Recordset_subtask['TID']]['TID'] =  $row_Recordset_subtask['TID'];
$sub_task_arr[$row_Recordset_subtask['TID']]['type'] =  $row_Recordset_subtask['task_tpye'];
$sub_task_arr[$row_Recordset_subtask['TID']]['title'] =  $row_Recordset_subtask['csa_text'];
$sub_task_arr[$row_Recordset_subtask['TID']]['to'] =  $row_Recordset_subtask['tk_display_name'];
$sub_task_arr[$row_Recordset_subtask['TID']]['to_id'] =  $row_Recordset_subtask['csa_to_user'];
$sub_task_arr[$row_Recordset_subtask['TID']]['task_status_display'] =  $row_Recordset_subtask['task_status_display'];
$sub_task_arr[$row_Recordset_subtask['TID']]['task_status'] =  $row_Recordset_subtask['task_status'];
$sub_task_arr[$row_Recordset_subtask['TID']]['start'] =  $row_Recordset_subtask['csa_plan_st'];
$sub_task_arr[$row_Recordset_subtask['TID']]['end'] =  $row_Recordset_subtask['csa_plan_et'];
$sub_task_arr[$row_Recordset_subtask['TID']]['project_id'] =  $row_Recordset_subtask['csa_project'];
$sub_task_arr[$row_Recordset_subtask['TID']]['priority'] =  $row_Recordset_subtask['csa_priority'];
$sub_task_arr[$row_Recordset_subtask['TID']]['level'] =  $row_Recordset_subtask['csa_temp'];
$sub_task_arr[$row_Recordset_subtask['TID']]['lastupdate'] =  $row_Recordset_subtask['csa_last_update'];

} while ($row_Recordset_subtask = mysql_fetch_assoc($Recordset_subtask)); 

    
if ($row_Recordset_task['csa_remark6'] == "-1" ){
$wbs_id = "1";
} else {
$wbs_id = $row_Recordset_task['csa_remark6'];
}

$wbsID = $wbs_id + 1;


if ($row_Recordset_task['csa_remark6'] == "-1"){
$wbssum = $row_Recordset_task['TID'].">".$wbsID;
}else {
$wbssum = $row_Recordset_task['csa_remark5'].">".$row_Recordset_task['TID'].">".$wbsID;
}
    
$query_Recordset_sumsublog = "SELECT round(sum(csa_tb_manhour),1) as sum_sublog FROM tk_task  
inner join tk_task_byday on tk_task.TID=tk_task_byday.csa_tb_backup1 
WHERE csa_remark5 LIKE '$wbssum%'";
$Recordset_sumsublog = mysql_query($query_Recordset_sumsublog, $tankdb) or die(mysql_error());
$row_Recordset_sumsublog = mysql_fetch_assoc($Recordset_sumsublog);
    
$task_view_arr = array ();
    
$task_view_arr["p_TID"]= $pattaskid;
$task_view_arr["p_title"]= $row_Recordset_pattask['csa_text'];
    
$task_view_arr["TID"]= $row_Recordset_task['TID'];
$task_view_arr["wbs"]= $wbsID;
$task_view_arr["project_name"]= $row_Recordset_task['project_name'];
$task_view_arr["project_id"]= $row_Recordset_task['csa_project'];
$task_view_arr["type"]= $row_Recordset_task['task_tpye'];
$task_view_arr["title"]= $row_Recordset_task['csa_text'];
$task_view_arr["text"]= $row_Recordset_task['csa_remark1'];
$task_view_arr["tag"]= $row_Recordset_task['test02'];
$task_view_arr["task_status_display"]= $row_Recordset_task['task_status_display'];
$task_view_arr["exam"]= $row_Recordset_task['csa_remark8'];
$task_view_arr["priority"]= $priority;
$task_view_arr["level"]= $level;

$task_view_arr["to_user"]= $row_Recordset_task['tk_display_name1'];
$task_view_arr["to_user_id"]= $row_Recordset_task['csa_to_user'];
$task_view_arr["from_user"]= $row_Recordset_task['tk_display_name2'];
$task_view_arr["from_user_id"]= $row_Recordset_task['csa_from_user'];
$task_view_arr["create_user"]= $row_Recordset_task['tk_display_name3'];
$task_view_arr["create_user_id"]= $row_Recordset_task['csa_create_user'];

$task_view_arr["start"]= $row_Recordset_task['csa_plan_st'];
$task_view_arr["end"]= $row_Recordset_task['csa_plan_et'];

$task_view_arr["sum_hour"]= $row_Recordset_sumlog["sum_hour"];
$task_view_arr["plan_hour"]= $row_Recordset_task['csa_plan_hour'];

    if($totalRows_Recordset_subtask > 0){
$task_view_arr["sub_task"]= $sub_task_arr;
$task_view_arr["sub_task_sumhour"]= $row_Recordset_sumsublog['sum_sublog'];
    }
$task_view_arr["sum_sub_task"]= $totalRows_Recordset_subtask;
    
if($totalRows_Recordset_task_day > 0){
$task_view_arr["task_day"]=$task_day_arr;
}
$task_view_arr["sum_task_day"]=$totalRows_Recordset_task_day;
    
if($totalRows_Recordset_comment > 0){
$task_view_arr["comment"]=$comment_arr;
}
$task_view_arr["sum_comment"]=$totalRows_Recordset_comment;
    
    
return $task_view_arr;
}


//log view
function log_view( $taskid, $date ) {
global $tankdb;
global $database_tankdb;
global $maxRows;

$query_log =  sprintf("SELECT * FROM tk_task_byday 
inner join tk_status on tk_task_byday.csa_tb_status=tk_status.id 
WHERE csa_tb_year= %s AND csa_tb_backup1= %s",    
                       GetSQLValueString($date, "text"),  
                       GetSQLValueString($taskid, "int"));
$log = mysql_query($query_log, $tankdb) or die(mysql_error());
$row_log = mysql_fetch_assoc($log);
$totalRows_log = mysql_num_rows($log);

    

$maxRows_Recordset_comment = $maxRows;
$pageNum_Recordset_comment = 0;
if (isset($_GET['pageNum_Recordset_comment'])) {
  $pageNum_Recordset_comment = $_GET['pageNum_Recordset_comment'];
}
$startRow_Recordset_comment = $pageNum_Recordset_comment * $maxRows_Recordset_comment;

$logid = $row_log['tbid'];

$query_Recordset_comment = sprintf("SELECT * FROM tk_comment 
inner join tk_user on tk_comment.tk_comm_user =tk_user.uid 
								 WHERE tk_comm_pid = %s AND tk_comm_type = 3 
								
								ORDER BY tk_comm_lastupdate DESC", 
								GetSQLValueString($logid, "text")
								);
$query_limit_Recordset_comment = sprintf("%s LIMIT %d, %d", $query_Recordset_comment, $startRow_Recordset_comment, $maxRows_Recordset_comment);
$Recordset_comment = mysql_query($query_limit_Recordset_comment, $tankdb) or die(mysql_error());
$row_Recordset_comment = mysql_fetch_assoc($Recordset_comment);
   
    
if (isset($_GET['totalRows_Recordset_comment'])) {
  $totalRows_Recordset_comment = $_GET['totalRows_Recordset_comment'];
} else {
  $all_Recordset_comment = mysql_query($query_Recordset_comment);
  $totalRows_Recordset_comment = mysql_num_rows($all_Recordset_comment);
}
$totalPages_Recordset_comment = ceil($totalRows_Recordset_comment/$maxRows_Recordset_comment)-1;

$queryString_Recordset_comment = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_comment") == false && 
        stristr($param, "totalRows_Recordset_comment") == false && 
        stristr($param, "tab") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_comment = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_comment = sprintf("&totalRows_Recordset_comment=%d%s", $totalRows_Recordset_comment, $queryString_Recordset_comment);


$comment_arr = array ();
do { 

$comment_arr[$row_Recordset_comment['coid']]['coid'] =  $row_Recordset_comment['coid'];
$comment_arr[$row_Recordset_comment['coid']]['text'] =  $row_Recordset_comment['tk_comm_title'];
$comment_arr[$row_Recordset_comment['coid']]['userid'] =  $row_Recordset_comment['tk_comm_user'];
$comment_arr[$row_Recordset_comment['coid']]['user'] =  $row_Recordset_comment['tk_display_name'];
$comment_arr[$row_Recordset_comment['coid']]['date'] =  $row_Recordset_comment['tk_comm_lastupdate'];


} while ($row_Recordset_comment = mysql_fetch_assoc($Recordset_comment));     
    
$log_view_arr = array ();
    
    $log_view_arr["tbid"]= $row_log['tbid']; 
    $log_view_arr["hour"]= $row_log['csa_tb_manhour'];    
    $log_view_arr["status"]= $row_log['task_status_display'];    
    $log_view_arr["text"]= $row_log['csa_tb_text'];    
    if($totalRows_Recordset_comment > 0){
    $log_view_arr["comment"]= $comment_arr;  
    }
    $log_view_arr["sum_comment"]= $totalRows_Recordset_comment;

    return $log_view_arr;
}

//get task status
function get_task_status( $exam = 0 ) {
global $tankdb;
global $database_tankdb;

$query_tkstatus1 = sprintf("SELECT * FROM tk_status WHERE task_status_backup2 = %s ORDER BY task_status_backup1 ASC", GetSQLValueString($exam, "int"));
$tkstatus1 = mysql_query($query_tkstatus1, $tankdb) or die(mysql_error());
$row_tkstatus1 = mysql_fetch_assoc($tkstatus1);
 
    //  return $row_tkstatus1['id'];
    
$tkstatus_arr = array ();
do { 

$tkstatus_arr[$row_tkstatus1['id']]['id'] =  $row_tkstatus1['id'];
$tkstatus_arr[$row_tkstatus1['id']]['task_status'] =  $row_tkstatus1['task_status'];
} while ($row_tkstatus1 = mysql_fetch_assoc($tkstatus1));     
    
return $tkstatus_arr;
}

//get task type
function get_task_type() {
global $tankdb;
global $database_tankdb;

$query_tktype ="SELECT * FROM tk_task_tpye ORDER BY task_tpye_backup1 ASC";
$tktypeRS = mysql_query($query_tktype, $tankdb) or die(mysql_error());
$row_tktype = mysql_fetch_assoc($tktypeRS);
 
$tktype_arr = array ();
do { 

$tktype_arr[$row_tktype['id']]['typeid'] =  $row_tktype['id'];
$tktype_arr[$row_tktype['id']]['task_tpye'] =  $row_tktype['task_tpye'];
} while ($row_tktype = mysql_fetch_assoc($tktypeRS));     
    
return $tktype_arr;
}


//get user
function get_user_select() {
global $tankdb;
global $database_tankdb;
  
//$query_user ="SELECT * FROM tk_user WHERE tk_user_rank <> '0' ORDER BY CONVERT(tk_display_name USING gbk )";
$query_user ="SELECT * FROM tk_user ORDER BY CONVERT(tk_display_name USING gbk )";
$userRS = mysql_query($query_user, $tankdb) or die(mysql_error());
$row_user = mysql_fetch_assoc($userRS);
 
$user_arr = array ();
do { 

$user_arr[$row_user['uid']]['uid'] =  $row_user['uid'];
$user_arr[$row_user['uid']]['name'] =  $row_user['tk_display_name'];
} while ($row_user = mysql_fetch_assoc($userRS));     
    
return $user_arr;
}

//submit exam
function submit_exam( $uid, $taskid, $status, $comment ) {
global $tankdb;
global $database_tankdb;
global $multilingual_log_exam1;
global $multilingual_log_exam;
global $multilingual_log_exam2;

$updatetask = sprintf("UPDATE tk_task SET csa_remark2=%s, csa_remark8=%s, csa_last_user=%s WHERE TID=%s", 
                       GetSQLValueString($status, "text"), 
                       GetSQLValueString($comment, "text"),
                       GetSQLValueString($uid, "text"),                      
                       GetSQLValueString($taskid, "int"));
  $Result2 = mysql_query($updatetask, $tankdb) or die(mysql_error());
 
if ($comment <> null){
$examtitle = $multilingual_log_exam1.$comment;
}

$query_tkstatus1 = sprintf("SELECT * FROM tk_status WHERE id = %s ", GetSQLValueString($status, "text"));
$tkstatus1 = mysql_query($query_tkstatus1, $tankdb) or die(mysql_error());
$row_tkstatus1 = mysql_fetch_assoc($tkstatus1);
 
 
  $newID = $taskid;
  $logstatus = $row_tkstatus1['task_status'];
  $newName = $uid;
  $action = $multilingual_log_exam.$multilingual_log_exam2."&nbsp;".$logstatus.$examtitle;

$insertSQL2 = sprintf("INSERT INTO tk_log (tk_log_user, tk_log_action, tk_log_type, tk_log_class, tk_log_description) VALUES (%s, %s, %s, 1, '')",
                       GetSQLValueString($newName, "text"),
                       GetSQLValueString($action, "text"),
                       GetSQLValueString($newID, "text"));  
$Result3 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());


$query_Recordset_task = sprintf("SELECT csa_to_user, csa_text, csa_remark2, csa_from_user  
FROM tk_task 
WHERE TID = %s", GetSQLValueString($taskid, "int"));
$Recordset_task = mysql_query($query_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);

$mailto = $row_Recordset_task['csa_to_user']; 
$title = $row_Recordset_task['csa_text'];



$msg_to = $mailto; 
$msg_from = $uid;
$msg_type = "examtask";
$msg_id = $taskid;
$msg_title = $title;
$mail = send_message( $msg_to, $msg_from, $msg_type, $msg_id, $msg_title ); 
    
    
return 0;
}

//submit comment
function submit_comment( $text, $poster, $pid, $type, $date=-1, $taskid=-1 ) {
global $tankdb;
global $database_tankdb;
global $multilingual_log_marklog1;
global $multilingual_log_marklog2;

$insertSQL = sprintf("INSERT INTO tk_comment (tk_comm_title, tk_comm_user, tk_comm_pid, tk_comm_type, tk_comm_text) VALUES (%s, %s, %s, %s, '')",
                     GetSQLValueString($text, "text"),  
                     GetSQLValueString($poster, "text"),
                       GetSQLValueString($pid, "text"),
                       GetSQLValueString($type, "text"));
$Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());

if ($type == 3) { //如果是log备注
  $updateSQL = sprintf("UPDATE tk_task_byday SET csa_tb_comment=csa_tb_comment+1 WHERE tbid=%s", GetSQLValueString($pid, "int"));
  $Result1 = mysql_query($updateSQL, $tankdb) or die(mysql_error());
    
  $lyear = $date;
  $lgyear = str_split($lyear,4);
  $lgmonth = str_split($lgyear[1],2);
  $ldate = $lgyear[0]."-".$lgmonth[0]."-".$lgmonth[1];

$marklogtext = $text;

$action = $multilingual_log_marklog1.$ldate.$multilingual_log_marklog2.$marklogtext;

$insertSQL2 = sprintf("INSERT INTO tk_log (tk_log_user, tk_log_action, tk_log_type, tk_log_class, tk_log_description) VALUES (%s, %s, %s, 1, ''  )",
                       GetSQLValueString($poster, "text"),
                       GetSQLValueString($action, "text"),
                       GetSQLValueString($taskid, "text"));  
$Result3 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());
}

if ($type == "3"){
      $pid = $taskid;
  } 
    
$query_log = sprintf("SELECT csa_to_user, csa_text  
FROM tk_task 
WHERE TID= %s ",GetSQLValueString($pid, "text"));
$log = mysql_query($query_log, $tankdb) or die(mysql_error());
$row_log = mysql_fetch_assoc($log);

$title = $row_log['csa_text'];  
    
if ($type == "1"){
	$comm_type = "taskcomm";
	$comm_title = $title;
  } else if ($type == "3"){
	  $comm_type = "logcomm";
	  $comm_title = $title."(".$ldate.$multilingual_log_marklog2.")";
  }    
 
if($type <> "2"){
$msg_to = $row_log['csa_to_user']; 
$msg_from = $poster;
$msg_type = $comm_type;
$msg_id = $pid;
$msg_title = $comm_title;
$mail = send_message( $msg_to, $msg_from, $msg_type, $msg_id, $msg_title );
}
    
    
return 0;
}


//submit log
function submit_log( $text, $status, $hour, $user, $date, $taskid ) {
global $tankdb;
global $database_tankdb;
global $multilingual_log_addlog1;
global $multilingual_log_addlog2;
global $multilingual_log_costlog;
global $multilingual_global_hour;


$day = preg_replace('/-/i','',$date); 

$checklog__query=sprintf("SELECT tbid FROM tk_task_byday WHERE csa_tb_year=%s AND csa_tb_backup1=%s",
  GetSQLValueString($day, "text"),
  GetSQLValueString($taskid, "text")); 
   
  $checklogRS = mysql_query($checklog__query, $tankdb) or die(mysql_error());
  $checklogFound = mysql_num_rows($checklogRS);
  
 
  if ($checklogFound) {

 return  5; //重复返回5
  }

    
$query_log = sprintf("SELECT *, 
tk_user1.uid as uid1, 
tk_user2.tk_display_name as tk_display_name2 
FROM tk_task 
inner join tk_user as tk_user2 on tk_task.csa_to_user=tk_user2.uid 
inner join tk_user as tk_user1 on tk_task.csa_from_user=tk_user1.uid 
WHERE TID = %s", GetSQLValueString($taskid, "text"));

$log = mysql_query($query_log, $tankdb) or die(mysql_error());

$row_log = mysql_fetch_assoc($log);
 

$query_tkstatus1 = sprintf("SELECT * FROM tk_status WHERE id = %s", GetSQLValueString($status, "text"));
$tkstatus1 = mysql_query($query_tkstatus1, $tankdb) or die(mysql_error());
$row_tkstatus1 = mysql_fetch_assoc($tkstatus1);
    
$touser = $row_log['csa_to_user']; 
$project = $row_log['csa_project']; 
$type = $row_log['csa_type']; 

if (  $text==null){
$insertSQL = sprintf("INSERT INTO tk_task_byday ( csa_tb_year, csa_tb_status, csa_tb_manhour, csa_tb_backup1, csa_tb_backup2, csa_tb_backup3, csa_tb_backup4) VALUES ( %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($day, "text"),
                       GetSQLValueString($status, "text"),
                       GetSQLValueString($hour, "text"),
                       GetSQLValueString($taskid, "text"),
                       GetSQLValueString($touser, "text"),
                       GetSQLValueString($project, "text"),
                       GetSQLValueString($type, "text"));
}else{
$insertSQL = sprintf("INSERT INTO tk_task_byday (csa_tb_text, csa_tb_year, csa_tb_status, csa_tb_manhour, csa_tb_backup1, csa_tb_backup2, csa_tb_backup3, csa_tb_backup4) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($text, "text"),
                       GetSQLValueString($day, "text"),
                       GetSQLValueString($status, "text"),
                       GetSQLValueString($hour, "text"),
                       GetSQLValueString($taskid, "text"),
                       GetSQLValueString($touser, "text"),
                       GetSQLValueString($project, "text"),
                       GetSQLValueString($type, "text"));
}

$Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());

    

$logstatus = $row_tkstatus1['task_status'];
$action = $multilingual_log_addlog1.$date.$multilingual_log_addlog2.$logstatus.$multilingual_log_costlog.$hour.$multilingual_global_hour."&nbsp;&nbsp;".$text;    
$insertSQL2 = sprintf("INSERT INTO tk_log (tk_log_user, tk_log_action, tk_log_type, tk_log_class, tk_log_description) VALUES (%s, %s, %s, 1, ''  )",
                       GetSQLValueString($user, "text"),
                       GetSQLValueString($action, "text"),
                       GetSQLValueString($taskid, "text"));  
$Result3 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());


$log_time = date("Y-m-d H:i:s");    
  $updateSQL = sprintf("UPDATE tk_task SET csa_remark2=%s, csa_remark3=%s, csa_last_user=%s WHERE TID=%s", 
                       GetSQLValueString($status, "text"),
                       GetSQLValueString($log_time, "text"),
                       GetSQLValueString($user, "text"),                      
                       GetSQLValueString($taskid, "int"));
  mysql_select_db($database_tankdb, $tankdb);
  $Result2 = mysql_query($updateSQL, $tankdb) or die(mysql_error());
    
    
$mailto = $row_log['uid1']; 
$title = $row_log['csa_text'];  
    
$msg_to = $mailto; 
$msg_from = $user;
$msg_type = "edittask";
$msg_id = $taskid;
$msg_title = $title;
$mail = send_message( $msg_to, $msg_from, $msg_type, $msg_id, $msg_title );
    
return 0;
}

//submit task
function submit_task( $title, $text, $tag, $type, $to, $from, $create, $start, $end, $pv, $prt, $level, $status, $ptaskid, $wbsid, $projectid ) {
global $tankdb;
global $database_tankdb;
global $multilingual_log_addtask;


$query_Recordset_task = sprintf("SELECT *, 
tk_project.id as proid  
FROM tk_task 
inner join tk_project on tk_task.csa_project=tk_project.id 
WHERE TID = %s", GetSQLValueString($ptaskid, "int"));
$Recordset_task = mysql_query($query_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);
    
if ($wbsid == "2"){
$wbs = $ptaskid.">".$wbsid;
} else {
$wbs = $row_Recordset_task['csa_remark5'].">".$row_Recordset_task['TID'].">".$wbsid; 
}    

if ( !empty( $tag ) ){
$fid_tag = "test02,";
$val_tag = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$tag), "text"));
}else{
$fid_tag = "";
$val_tag = "";
}
    
if ( !empty( $text ) ){
$fid_text = "csa_remark1,";
$val_text = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$text), "text"));
}else{
$fid_text = "";
$val_text = "";
}
    
    if ( empty( $pv ) ){
        $pv = '0.0';
    }
    
$insertSQL = sprintf("INSERT INTO tk_task ($fid_tag $fid_text csa_from_user, csa_to_user, csa_project, csa_type, csa_text, csa_priority, csa_temp, csa_plan_st, csa_plan_et, csa_plan_hour, csa_remark2, csa_create_user, csa_last_user, csa_remark4, csa_remark5, csa_remark6) VALUES ($val_tag $val_text %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($from, "text"),
                       GetSQLValueString($to, "text"),
                       GetSQLValueString($projectid, "text"),
                       GetSQLValueString($type, "text"),
                       GetSQLValueString($title, "text"),
                       GetSQLValueString($prt, "text"),
                       GetSQLValueString($level, "text"),
					   GetSQLValueString($start, "text"),
					   GetSQLValueString($end, "text"),
					   GetSQLValueString($pv, "text"),
					   GetSQLValueString($status, "text"),
					   GetSQLValueString($create, "text"),
					   GetSQLValueString($create, "text"),
					   GetSQLValueString($ptaskid, "text"),
					   GetSQLValueString($wbs, "text"),
					   GetSQLValueString($wbsid, "text"));

    //return $insertSQL;

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());
  
  $newID = mysql_insert_id();
    $newName = $create;

$insertSQL2 = sprintf("INSERT INTO tk_log (tk_log_user, tk_log_action, tk_log_type, tk_log_class, tk_log_description) VALUES (%s, %s, %s , 1, '' )",
                       GetSQLValueString($newName, "text"),
                       GetSQLValueString($multilingual_log_addtask, "text"),
                       GetSQLValueString($newID, "text"));  
$Result2 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());    

$msg_to = $to;
$msg_from = $create;
$msg_type = "newtask";
$msg_id = $newID;
$msg_title = $title;
$mail = send_message( $msg_to, $msg_from, $msg_type, $msg_id, $msg_title );    

    $redataarr = array();
    $redataarr["code"] = 0;
    $redataarr["newid"] = $newID;
    
return $redataarr;
}

//project list
function project_list( $uid, $sort= "project_lastupdate", $order= "DESC", $page="0", $pagetabs = "jprj", $channel="default" ) {

global $tankdb;
global $database_tankdb;
global $maxRows;

$maxRows_Recordset1 = $maxRows;
$pageNum_Recordset1 = 0;
if (isset($page)) {
  $pageNum_Recordset1 = $page;
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;



if ($pagetabs == "mprj" || $pagetabs == "jprj"){
$prjtouser = $uid;
if (isset($_GET['ptouser'])) {
  $prjtouser = $_GET['ptouser'];
}
}else {
$prjtouser = 0;
}


$prjtouser = GetSQLValueString($prjtouser, "int");

if($pagetabs == "jprj"){
$where = "WHERE tk_task.csa_to_user = $prjtouser";
}else if($prjtouser <> 0 ) {
$where = "WHERE project_to_user = $prjtouser";
}else{
$where = "";
} 


if($pagetabs == "jprj" ){
$where1 = "inner join tk_task on tk_project.id=tk_task.csa_project";
$where2 = "GROUP BY tk_project.id";
}else{
$where1 = "";
$where2 = "";
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT * FROM tk_project 
							
							inner join tk_user on tk_project.project_to_user=tk_user.uid 
							inner join tk_status_project on tk_project.project_status=tk_status_project.psid 
							$where1 
							$where $where2 ORDER BY tk_project.%s %s", 
							GetSQLValueString($sort, "defined", $sort, "NULL"),
							GetSQLValueString($order, "defined", $order, "NULL"));
    //return $query_Recordset1;							
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysql_query($query_Recordset1);
  $totalRows_Recordset1 = mysql_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;

$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset1") == false && 
        stristr($param, "totalRows_Recordset1") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);

$project_arr = array ();
do { 

$project_arr["project"][$row_Recordset1['id']]['id'] =  $row_Recordset1['id'];
$project_arr["project"][$row_Recordset1['id']]['title'] =  $row_Recordset1['project_name'];
    if($channel <> "file"){ //如果是项目文档页面，不显示状态等数据
$project_arr["project"][$row_Recordset1['id']]['status'] =  $row_Recordset1['task_status'];
$project_arr["project"][$row_Recordset1['id']]['user'] =  $row_Recordset1['tk_display_name'];
    }
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
    $project_arr["total"]= $totalRows_Recordset1;

return $project_arr;
}


//project view
function project_view( $prjid ) {
global $tankdb;
global $database_tankdb;
global $maxRows;
  
$query_DetailRS1 = sprintf("SELECT * FROM tk_project 
inner join tk_user on tk_project.project_to_user=tk_user.uid 
inner join tk_status_project on tk_project.project_status=tk_status_project.psid 
WHERE tk_project.id = %s", GetSQLValueString($prjid, "int"));
$DetailRS1 = mysql_query($query_DetailRS1, $tankdb) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);

$query_Recordset_sumlog =  sprintf("SELECT sum(csa_tb_manhour) as sum_hour FROM tk_task_byday WHERE csa_tb_backup3= %s ", GetSQLValueString($prjid, "int"));
$Recordset_sumlog = mysql_query($query_Recordset_sumlog, $tankdb) or die(mysql_error());
$row_Recordset_sumlog = mysql_fetch_assoc($Recordset_sumlog);
    

$maxRows_Recordset_task = $maxRows;
$pageNum_Recordset_task = 0;
if (isset($_GET['pageNum_Recordset_task'])) {
  $pageNum_Recordset_task = $_GET['pageNum_Recordset_task'];
}
$startRow_Recordset_task = $pageNum_Recordset_task * $maxRows_Recordset_task;

$query_Recordset_task = sprintf("SELECT *
							FROM tk_task 								
							inner join tk_task_tpye on tk_task.csa_type=tk_task_tpye.id								
							inner join tk_user on tk_task.csa_to_user=tk_user.uid 
							inner join tk_status on tk_task.csa_remark2=tk_status.id 
								WHERE csa_project = %s AND csa_remark4 = '-1' ORDER BY csa_last_update DESC", GetSQLValueString($prjid, "int"));
$query_limit_Recordset_task = sprintf("%s LIMIT %d, %d", $query_Recordset_task, $startRow_Recordset_task, $maxRows_Recordset_task);
$Recordset_task = mysql_query($query_limit_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);

    //return $query_limit_Recordset_task;
    
if (isset($_GET['totalRows_Recordset_task'])) {
  $totalRows_Recordset_task = $_GET['totalRows_Recordset_task'];
} else {
  $all_Recordset_task = mysql_query($query_Recordset_task);
  $totalRows_Recordset_task = mysql_num_rows($all_Recordset_task);
}
$totalPages_Recordset_task = ceil($totalRows_Recordset_task/$maxRows_Recordset_task)-1;

$queryString_Recordset_task = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_task") == false && 
        stristr($param, "totalRows_Recordset_task") == false && 
        stristr($param, "tab") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_task = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_task = sprintf("&totalRows_Recordset_task=%d%s", $totalRows_Recordset_task, $queryString_Recordset_task);    


$maxRows_Recordset_comment = $maxRows;
$pageNum_Recordset_comment = 0;
if (isset($_GET['pageNum_Recordset_comment'])) {
  $pageNum_Recordset_comment = $_GET['pageNum_Recordset_comment'];
}
$startRow_Recordset_comment = $pageNum_Recordset_comment * $maxRows_Recordset_comment;

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_comment = sprintf("SELECT * FROM tk_comment 
inner join tk_user on tk_comment.tk_comm_user =tk_user.uid 
								 WHERE tk_comm_pid = %s AND tk_comm_type = 2 
								
								ORDER BY tk_comm_lastupdate DESC", 
								GetSQLValueString($prjid, "int")
								);
$query_limit_Recordset_comment = sprintf("%s LIMIT %d, %d", $query_Recordset_comment, $startRow_Recordset_comment, $maxRows_Recordset_comment);
$Recordset_comment = mysql_query($query_limit_Recordset_comment, $tankdb) or die(mysql_error());
$row_Recordset_comment = mysql_fetch_assoc($Recordset_comment);

if (isset($_GET['totalRows_Recordset_comment'])) {
  $totalRows_Recordset_comment = $_GET['totalRows_Recordset_comment'];
} else {
  $all_Recordset_comment = mysql_query($query_Recordset_comment);
  $totalRows_Recordset_comment = mysql_num_rows($all_Recordset_comment);
}
$totalPages_Recordset_comment = ceil($totalRows_Recordset_comment/$maxRows_Recordset_comment)-1;

$queryString_Recordset_comment = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_comment") == false && 
        stristr($param, "totalRows_Recordset_comment") == false && 
        stristr($param, "tab") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_comment = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_comment = sprintf("&totalRows_Recordset_comment=%d%s", $totalRows_Recordset_comment, $queryString_Recordset_comment);
    
$sub_task_arr = array ();
do { 

$sub_task_arr[$row_Recordset_task['TID']]['TID'] =  $row_Recordset_task['TID'];
$sub_task_arr[$row_Recordset_task['TID']]['type'] =  $row_Recordset_task['task_tpye'];
$sub_task_arr[$row_Recordset_task['TID']]['title'] =  $row_Recordset_task['csa_text'];
$sub_task_arr[$row_Recordset_task['TID']]['to'] =  $row_Recordset_task['tk_display_name'];
$sub_task_arr[$row_Recordset_task['TID']]['to_id'] =  $row_Recordset_task['csa_to_user'];
$sub_task_arr[$row_Recordset_task['TID']]['task_status_display'] =  $row_Recordset_task['task_status_display'];
$sub_task_arr[$row_Recordset_task['TID']]['task_status'] =  $row_Recordset_task['task_status'];
$sub_task_arr[$row_Recordset_task['TID']]['start'] =  $row_Recordset_task['csa_plan_st'];
$sub_task_arr[$row_Recordset_task['TID']]['end'] =  $row_Recordset_task['csa_plan_et'];
$sub_task_arr[$row_Recordset_task['TID']]['project_id'] =  $row_Recordset_task['csa_project'];
$sub_task_arr[$row_Recordset_task['TID']]['priority'] =  $row_Recordset_task['csa_priority'];
$sub_task_arr[$row_Recordset_task['TID']]['level'] =  $row_Recordset_task['csa_temp'];
$sub_task_arr[$row_Recordset_task['TID']]['lastupdate'] =  $row_Recordset_task['csa_last_update'];

} while ($row_Recordset_task = mysql_fetch_assoc($Recordset_task)); 


$comment_arr = array ();
do { 

$comment_arr[$row_Recordset_comment['coid']]['coid'] =  $row_Recordset_comment['coid'];
$comment_arr[$row_Recordset_comment['coid']]['text'] =  $row_Recordset_comment['tk_comm_title'];
$comment_arr[$row_Recordset_comment['coid']]['userid'] =  $row_Recordset_comment['tk_comm_user'];
$comment_arr[$row_Recordset_comment['coid']]['user'] =  $row_Recordset_comment['tk_display_name'];
$comment_arr[$row_Recordset_comment['coid']]['date'] =  $row_Recordset_comment['tk_comm_lastupdate'];


} while ($row_Recordset_comment = mysql_fetch_assoc($Recordset_comment));   


    
$prj_view_arr = array ();
    
$prj_view_arr["prjid"]= $row_DetailRS1['id'];
$prj_view_arr["title"]= $row_DetailRS1['project_name'];
$prj_view_arr["text"]= $row_DetailRS1['project_text'];
$prj_view_arr["status"]= $row_DetailRS1['task_status_display'];
$prj_view_arr["hour"]= $row_Recordset_sumlog["sum_hour"];
$prj_view_arr["userid"]= $row_DetailRS1['project_to_user'];
$prj_view_arr["username"]= $row_DetailRS1['tk_display_name'];
$prj_view_arr["code"]= $row_DetailRS1['project_code'];
$prj_view_arr["start"]= $row_DetailRS1['project_start'];
$prj_view_arr["end"]= $row_DetailRS1['project_end'];
    
if($totalRows_Recordset_task > 0){
$prj_view_arr["task"]= $sub_task_arr;
}
$prj_view_arr["sum_task"]= $totalRows_Recordset_task;

if($totalRows_Recordset_comment > 0){
$prj_view_arr["comment"]=$comment_arr;
}
$prj_view_arr["sum_comment"]=$totalRows_Recordset_comment;    
    
return $prj_view_arr;
}

//file list
function file_list( $uid, $page="0", $project_id="-1", $pid="-1", $pagetabs = "allfile" ) {

global $tankdb;
global $database_tankdb;
global $maxRows;

if($pid<>"-1"){
    
if ($project_id <> "-1") {
  $inproject = " inner join tk_project on tk_document.tk_doc_class1=tk_project.id ";
} else { $inproject = " ";}
    
$query_DetailRS1 = sprintf("SELECT *, 
tk_user1.tk_display_name as tk_display_name1, 
tk_user2.tk_display_name as tk_display_name2 FROM tk_document 
inner join tk_user as tk_user1 on tk_document.tk_doc_create=tk_user1.uid  
inner join tk_user as tk_user2 on tk_document.tk_doc_edit=tk_user2.uid 
$inproject 
WHERE tk_document.docid = %s", GetSQLValueString($pid, "int"));
$DetailRS1 = mysql_query($query_DetailRS1, $tankdb) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);
} //if pid<>-1
    
$maxRows_Recordset_file = $maxRows;
$pageNum_Recordset_file = 0;
if (isset($page)) {
  $pageNum_Recordset_file = $page;
}
$startRow_Recordset_file = $pageNum_Recordset_file * $maxRows_Recordset_file;


if (isset($searchf)){
$inprolist = "tk_doc_title LIKE %s AND tk_doc_backup1 <> 1";
$inprolists = "%" . $filenames . "%";

}else if ($pid=="-1" && $project_id <> "-1" && $pagetabs == "allfile") { //如果不输入任何一个文件夹，且是项目文档
    $inprolist = " tk_doc_class1 = %s  AND  tk_doc_class2 = 0 ";  //0=项目无父级，显示某项目一级目录及文档
    $inprolists = $project_id; //-1代表只读取非项目文档
  
} else if ($pagetabs == "mcfile"){
$inprolist = " tk_doc_create = %s AND tk_doc_backup1 = 0 ";
$inprolists = $uid;
} 
 else if ($pagetabs == "mefile"){
$inprolist = " tk_log.tk_log_user = %s AND tk_log.tk_log_class = 2 AND tk_doc_backup1 = 0 ";
$inprolists = $uid;
} else { 
  $inprolist = " tk_doc_class2 = %s  ";
  $inprolists = $pid;
} 
if($pagetabs == "mefile" ){
$where1 = "inner join tk_log on tk_document.docid=tk_log.tk_log_type";
$where2 = "GROUP BY tk_document.docid";
}else{
$where1 = "";
$where2 = "";
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_file = sprintf("SELECT * FROM tk_document 
inner join tk_user on tk_document.tk_doc_edit =tk_user.uid 
$where1 
WHERE $inprolist
								
								$where2 ORDER BY tk_doc_backup1 DESC, tk_doc_edittime DESC", 
								GetSQLValueString($inprolists, "text")
								);
$query_limit_Recordset_file = sprintf("%s LIMIT %d, %d", $query_Recordset_file, $startRow_Recordset_file, $maxRows_Recordset_file);
$Recordset_file = mysql_query($query_limit_Recordset_file, $tankdb) or die(mysql_error());
$row_Recordset_file = mysql_fetch_assoc($Recordset_file);

if (isset($_GET['totalRows_Recordset_file'])) {
  $totalRows_Recordset_file = $_GET['totalRows_Recordset_file'];
} else {
  $all_Recordset_file = mysql_query($query_Recordset_file);
  $totalRows_Recordset_file = mysql_num_rows($all_Recordset_file);
}
$totalPages_Recordset_file = ceil($totalRows_Recordset_file/$maxRows_Recordset_file)-1;

$queryString_Recordset_file = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_file") == false && 
        stristr($param, "totalRows_Recordset_file") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_file = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_file = sprintf("&totalRows_Recordset_file=%d%s", $totalRows_Recordset_file, $queryString_Recordset_file);

$file_arr = array ();
do { 

$file_arr["filelist"][$row_Recordset_file['docid']]['docid'] =  $row_Recordset_file['docid'];
$file_arr["filelist"][$row_Recordset_file['docid']]['title'] =  $row_Recordset_file['tk_doc_title'];
$file_arr["filelist"][$row_Recordset_file['docid']]['username'] =  $row_Recordset_file['tk_display_name'];
$file_arr["filelist"][$row_Recordset_file['docid']]['last'] =  $row_Recordset_file['tk_doc_edittime'];
$file_arr["filelist"][$row_Recordset_file['docid']]['folder'] =  $row_Recordset_file['tk_doc_backup1'];

    if($row_Recordset_file['tk_doc_attachment'] <> null){
$file_arr["filelist"][$row_Recordset_file['docid']]['att'] =  1;        
    }
    
} while ($row_Recordset_file = mysql_fetch_assoc($Recordset_file));
    $file_arr["total"]= $totalRows_Recordset_file;
    $file_arr["pid"]= $row_DetailRS1["tk_doc_class2"];
    $file_arr["title"]= $row_DetailRS1["tk_doc_title"];
    $file_arr["text"]= $row_DetailRS1["tk_doc_description"];

return $file_arr;
}

//file view
function file_view( $fileid="-1", $project_id ="-1") {

global $tankdb;
global $database_tankdb;


    
if ($project_id <> "-1") {
  $inproject = " inner join tk_project on tk_document.tk_doc_class1=tk_project.id ";
} else { $inproject = " ";}
    
$query_DetailRS1 = sprintf("SELECT *, 

tk_user2.tk_display_name as tk_display_name2 FROM tk_document 

inner join tk_user as tk_user2 on tk_document.tk_doc_edit=tk_user2.uid 
$inproject 
WHERE tk_document.docid = %s", GetSQLValueString($fileid, "int"));
$DetailRS1 = mysql_query($query_DetailRS1, $tankdb) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);

    //return $query_DetailRS1;
$file_view_arr = array ();    
    $file_view_arr["pid"]= $row_DetailRS1["tk_doc_class2"];
    $file_view_arr["title"]= $row_DetailRS1["tk_doc_title"];
    $file_view_arr["text"]= $row_DetailRS1["tk_doc_description"];
    $file_view_arr["lastuser"]= $row_DetailRS1["tk_display_name2"];
    $file_view_arr["last"]= $row_DetailRS1["tk_doc_edittime"];
    $file_view_arr["att"]= $row_DetailRS1["tk_doc_attachment"];

return $file_view_arr;
}
?>