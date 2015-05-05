<?php require_once('config/tank_config.php'); ?>  
<html>     
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="skin/themes/base/lhgdialog.css" rel="stylesheet" type="text/css" />
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>	
<script charset="utf-8" src="editor/kindeditor.js"></script>
<script charset="utf-8" src="editor/lang/zh_CN.js"></script>
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
			top.location.href='index.php';
		}
		function resend(){
			var username=document.getElementById('textfield1').value;
			var password=document.getElementById('textfield3').value;
			var email=document.getElementById('textfield5').value;
			window.open('register_real.php?textfield1='+username+'&&textfield3='+password+'&&textfield5='+email+'&&type=2');
		}
		function register(){
			setTimeout(function () {				
				top.window.open('user_register.php');		
			}, 3000);
		}
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

$type=1;
if (isset($_GET['type'])) {
  $type = $_GET['type'];
}

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

if($type==1){//注册

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
    $emailbody = "亲爱的".$username."：<br/>感谢您在Capteam团队协作平台注册了新帐号。<br/>请点击链接激活您的帐号。<br/><a href='http://localhost/Capteam/Capteam/active.php?verify=".$token."' target='_blank'>http://localhost/Capteam/Capteam/active.php?verify=".$token."</a><br/>如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接24小时内有效。<br/>如果此次激活请求非你本人所发，请忽略本邮件。<br/><p style='text-align:right'>-------- Capteam团队协作平台敬上</p>";
   // $emailbody="哇哈哈";
    $rs = $smtp->sendmail($smtpemailto, $smtpemailfrom, $emailsubject, $emailbody, $emailtype);
   // echo "$rs";
  if($rs==1){
    $mailstyle = explode('@',$email); 
    $url='http://mail.'.$mailstyle[1]; echo "1"; ?>   
<body > 
	 <div class="modal-body">
	<form action="" method="post" name="myform" id="form1"  >
	 	<input type="hidden" name="textfield1" id="textfield1" value="<?php echo $username; ?>" />
		<input type="hidden" name="textfield3" id="textfield3" value="<?php echo $password; ?>" />
		<input type="hidden" name="textfield5" id="textfield5" value="<?php echo $email; ?>" />
			<div class="form-group col-xs-12">
				<h5>&nbsp;</h5>
				 <div class="form-group col-xs-12">
				 <div class="form-group col-xs-1"></div>
				 <div class="form-group col-xs-2">
					<img src="/Capteam/Capteam/img/OK.png" >
				 </div>
				 <div class="form-group col-xs-9">
					<p id="titlemsg" name="titlemsg" style="font-size:24px;font-weight:bold;margin:20 0 0px">注册成功!<br></p>
					</div>
				</div>
			
				<div class="form-group col-xs-12" style="font-size:20px;line-height:1.8; word-break: break-all;">
					<div style="font-size:20px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Capteam已将验证邮件发送至您的注册邮箱： <?php echo $email; ?>  ，请进入邮箱并激活账号。</div> 	
				</div>
			
				<div class="form-group col-xs-12">
					<div class="form-group col-xs-6"></div>
				<div class="form-group col-xs-2"><button type="button" class="btn btn-default" style="font-size:16px;" data-loading-text="跳转中……" onclick="jumpto()">登陆邮箱</button></div>	
				<div class="form-group col-xs-1">
				<div class="form-group col-xs-3"><button type="button" class="btn btn-default" style="font-size:16px;"  onclick="login()">已激活，点击登陆</button></div>		 
					
				</div>  
				<p>&nbsp;</p>
			<div class="form-group col-xs-12">	
				<div class="form-group col-xs-1">	</div>
				<div class="form-group col-xs-7">				
					<p class="text-muted" style="font-size:15px;">没有收到邮件？</p> 
					<p class="text-muted" style="font-size:15px;">1.检查你的邮件垃圾箱</p> 
					<p class="text-muted" style="font-size:15px;">2.检查邮件是否被拦截</p> 
					<p class="text-muted" style="font-size:15px;">3.若仍未收到邮件，请尝试<a href="#" onclick="return resend();">重新发送</a></p> 	
				</div>				
				<div class="form-group col-xs-4">	</div>
			</div>
			</div>
		</form>
		</div>	
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
<?php  }} } ?>
</html> 
