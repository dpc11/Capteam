<?php require_once('config/tank_config.php'); ?>  
<html>     
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="skin/themes/base/lhgdialog.css" rel="stylesheet" type="text/css" />
<title>log</title>
	<script type="text/javascript">
		var P = window.parent, D = P.loadinndlg(); 	
		function closreload(url)
		{
			if(!url)
				P.reload();    
		}
		
		function over()
		{
			P.cancel();
		}
		function jumpto(){
			document.form1.cont.value="跳转中……";
			document.form1.cont.disabled=true;
		}
		function login(){
			window.location.href='index.php';
		}
	</script>
	
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
	
<script charset="utf-8" src="editor/kindeditor.js"></script>
<script charset="utf-8" src="editor/lang/zh_CN.js"></script>

<script type="text/javascript">


</script>
</head> 
<?php 

header("Content-type: text/html;charset=utf-8");
//检测用户名是否存在
$username = stripslashes(trim($_GET['textfield1']));
/*
$query = mysql_query("select uid from tk_user where tk_user_login='$username'");
$num = mysql_num_rows($query);
//echo "$num";
if($num==1){
  echo '<script>alert("用户名已存在，请换个其他的用户名");window.history.go(-1);</script>';
  exit;
}
*/
$regtime = time();
$email = trim($_GET['textfield5']);


if (isset($_GET['textfield3'])) {
  $password = $_GET['textfield3'];
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
$newID = mysql_insert_id();

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
    $emailbody = "亲爱的".$username."：<br/>感谢您在Capteam团队协作平台注册了新帐号。<br/>请点击链接激活您的帐号。<br/><a href='http://localhost/Capteam/Capteam/active.php?verify=".$token."' target='_blank'>http://localhost/Capteam/Capteam/active.php?verify=".$token."</a><br/>如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接24小时内有效。<br/>如果此次激活请求非你本人所发，请忽略本邮件。<br/><p style='text-align:right'>-------- Capteam团队协作平台敬上</p>";
   // $emailbody="哇哈哈";
    $rs = $smtp->sendmail($smtpemailto, $smtpemailfrom, $emailsubject, $emailbody, $emailtype);
   // echo "$rs";
  if($rs==1){
    $mailstyle = explode('@',$email); 
    $url='http://mail.'.$mailstyle[1];
  }
?>   
<body > 
	<form action="" method="post" name="form1" id="form1" >
	 <div class="modal-body">

	 	<input type="hidden" name="textfield1" id="textfield1" value="<?php echo $username; ?>" />
		<input type="hidden" name="textfield3" id="textfield3" value="<?php echo $password; ?>" />
		<input type="hidden" name="textfield5" id="textfield5" value="<?php echo $email; ?>" />
			  <div class="form-group col-xs-12">
      
				 <div name="INFO" id="INFO" >
					<h2 id="titlemsg" name="titlemsg" value="恭喜 "<?php echo $username; ?>" 注册成功!Capteam已将激活邮件发送至您的注册邮箱，请前往邮箱点击激活账号，"></h2>
					<p>"感谢注册！Capteam已将验证邮件发送到注册邮箱： "<?php echo $email; ?>" ，请进入邮箱并激活账号。"</p>
					<button type="button" class="btn btn-primary btn-sm" data-loading-text="跳转中……" name="cont" onClick="jumpto()">登陆邮箱</button>				 
					<button type="button" class="btn btn-primary btn-sm"  onClick="login()">激活成功，点击登陆</button>
					<h1>&nbsp;</h1>
					<p>没有收到邮件？</p>
					<p>1.检查你的邮件垃圾箱</p>
					<p>2.检查邮件是否被拦截</p>
					<p>3.若仍未收到邮件，请尝试<a href="">重新发送</a></p>
				 </div>
              </div>
			<div class="clearboth"></div>
			</div>
			<div class="form-group col-xs-12">
					<p>没有收到邮件？</p>
					<p>1.检查你的邮件垃圾箱</p>
					<p>2.检查邮件是否被拦截</p>
					<p>3.若仍未收到邮件，请尝试<a href="">重新发送</a></p>
	
			</div>
</form>
<script type="text/javascript">
$('button[data-loading-text]').click(function () {
    var btn = $(this).button('loading');
    setTimeout(function () {
        btn.button('reset');
		window.open('<?php  echo $url; ?>');		
    }, 3000);
});
</script>
		
</body>
</html> 
