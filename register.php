<?php require_once('config/tank_config.php'); ?>

<?php 
header("Content-type: text/html;charset=utf-8");
//检测用户名是否存在
$username = stripslashes(trim($_POST['textfield1']));
$query = mysql_query("select uid from tk_user where tk_user_login='$username'");
$num = mysql_num_rows($query);
//echo "$num";
if($num==1){
  echo '<script>alert("用户名已存在，请换个其他的用户名");window.history.go(-1);</script>';
  exit;
}
$displayname = trim($_POST['textfield2']);
$regtime = time();
$email = trim($_POST['textfield5']);


if (isset($_POST['textfield3'])) {
  $password = $_POST['textfield3'];
}

$tk_password = md5(crypt($password,substr($password,0,2)));


if ( empty( $_POST['textfield5'] ) ){
$tk_user_email = "'',";
}else{
$tk_user_email = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['textfield5']), "text"));
}

$token = md5($username.$password.$regtime); //创建用于激活识别码
$token_exptime = time()+60*60*24;//过期时间为24小时后


$sql = "INSERT into tk_user (tk_user_login,tk_user_pass,tk_display_name,tk_user_email,token_exptime,token)values('$username','$tk_password','$displayname','$email','$token_exptime','$token')";
mysql_query($sql);

if(mysql_insert_id()){//写入成功，发邮件
  include_once("smtp.class.php");
    $smtpserver = "smtp.qq.com"; //SMTP服务器
    $smtpserverport = 25; //SMTP服务器端口
    $smtpusermail = "1152352921@qq.com"; //SMTP服务器的用户邮箱
    $smtpuser = "1152352921@qq.com"; //SMTP服务器的用户帐号
    $smtppass = "whxNo.1"; //SMTP服务器的用户密码
    $smtp = new Smtp($smtpserver, $smtpserverport, true, $smtpuser, $smtppass); //这里面的一个true是表示使用身份验证,否则不使用身份验证.
    $emailtype = "HTML"; //信件类型，文本:text；网页：HTML
    $smtpemailto = "$email";
    $smtpemailfrom = $smtpusermail;
    $emailsubject = "用户帐号激活";
    $emailbody = "亲爱的".$username."：<br/>感谢您在我站注册了新帐号。<br/>请点击链接激活您的帐号。<br/><a href='http://localhost//Capteam/active.php?verify=".$token."' target='_blank'>http://localhost//Capteam/active.php?verify=".$token."</a><br/>如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接24小时内有效。<br/>如果此次激活请求非你本人所发，请忽略本邮件。<br/><p style='text-align:right'>-------- Hellwoeba.com 敬上</p>";
   // $emailbody="哇哈哈";
    $rs = $smtp->sendmail($smtpemailto, $smtpemailfrom, $emailsubject, $emailbody, $emailtype);
   // echo "$rs";
  if($rs==1){
    $mailstyle = explode('@',$email); 
    $url='http://mail.'.$mailstyle[1];
   // echo $mailstyle[1];echo "</br>"; 
   // echo $url;
   //header("location:registerToMail.php?url="+$url);

   $msg ='为您转到邮箱，点击激活您的账号……';
  //$url = "http://localhost//Capteam/user_login.php";   
?> 
<html>     
<head>     
<meta http-equiv="refresh" content="2;url=<?php echo $url; ?>">     
</head>     

</html> 

  <?php }else{
    $msg = $rs; 
    echo $msg;
  }
}
echo $msg;
?>