<?php require_once('config/tank_config.php'); ?>

<?php 
header("Content-type: text/html;charset=utf-8");
//检测用户名是否存在
$username = stripslashes(trim($_POST['username']));
$query = mysql_query("select uid from tk_user where tk_user_login='$username'");
$num = mysql_num_rows($query);
//echo "$num";
if($num==1){
  echo '<script>alert("用户名已存在，请换个其他的用户名");window.history.go(-1);</script>';
  exit;
}
//$password = md5(crypt($password,substr($password,0,2)));
$email = trim($_POST['email']);
//echo "$password";
$regtime = time();

$token = md5($username.$password.$regtime); //创建用于激活识别码
$token_exptime = time()+60*60*24;//过期时间为24小时后
//echo "$email";
echo "$token";
echo "/tr";
echo "$token_exptime";
/**/


if (isset($_POST['password'])) {
  $password = $_POST['password'];
}

$tk_password = md5(crypt($password,substr($password,0,2)));


if ( empty( $_POST['tk_user_email'] ) ){
$tk_user_email = "'',";
}else{
$tk_user_email = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_user_email']), "text"));
}

$sql = "insert into tk_user (tk_user_login,tk_user_pass,tk_user_email,token_exptime,token)values('$username','$tk_password','$email','$token_exptime','$token')";
mysql_query($sql);
/*$insertSQL = sprintf("INSERT INTO tk_user (tk_user_login, tk_user_pass,tk_user_email,token_exptime,token)) VALUES (
  %s, %s,'$email' ,'$token_exptime','$token')",
                       GetSQLValueString($username, "text"),
                       GetSQLValueString($tk_password, "text"));
  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());*/


if(mysql_insert_id()){//写入成功，发邮件
  include_once("smtp.class.php");
  $smtpserver = "smtp.qq.com"; //SMTP服务器
    $smtpserverport = 25; //SMTP服务器端口
    $smtpusermail = "1152352921@qq.com"; //SMTP服务器的用户邮箱
    $smtpuser = "1152352921@qq.com"; //SMTP服务器的用户帐号
    $smtppass = "whxNo.1"; //SMTP服务器的用户密码
    $smtp = new Smtp($smtpserver, $smtpserverport, true, $smtpuser, $smtppass); //这里面的一个true是表示使用身份验证,否则不使用身份验证.
    $emailtype = "HTML"; //信件类型，文本:text；网页：HTML
    $smtpemailto = "1152352921@qq.com";
    $smtpemailfrom = $smtpusermail;
    $emailsubject = "用户帐号激活";
    $emailbody = "亲爱的".$username."：<br/>感谢您在我站注册了新帐号。<br/>请点击链接激活您的帐号。<br/><a href='http://localhost//Capteam/active.php?verify=".$token."' target='_blank'>http://localhost//Capteam/active.php?verify=".$token."</a><br/>如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接24小时内有效。<br/>如果此次激活请求非你本人所发，请忽略本邮件。<br/><p style='text-align:right'>-------- Hellwoeba.com 敬上</p>";
   // $emailbody="哇哈哈";
    $rs = $smtp->sendmail($smtpemailto, $smtpemailfrom, $emailsubject, $emailbody, $emailtype);
    echo "$rs";
  if($rs==1){
    //$msg = '去邮箱查看邮件吧'; 
    echo '<script>alert("去邮箱查看邮件吧");window.history.go(-1);</script>';
  }else{
    $msg = $rs; 
    echo $msg;
  }
}
echo $msg;
?>

