<?php require_once('config/tank_config.php'); ?>

<!DOCTYPE html PUBLIC >
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="css/lhgcore/lhgdialog.css" rel="stylesheet" type="text/css" />
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
	</script>
	
	<link href="css/bootstrap/bootstrap.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="js/jquery/jquery.js"></script>
<script src="js/bootstrap/bootstrap.js"></script>
	
<script charset="utf-8" src="plug-in/editor/kindeditor.js"></script>
<script charset="utf-8" src="plug-in/editor/lang/zh_CN.js"></script>

	<script type="text/javascript">
		
		function jumpto(){
			//document.form1.cont.value="跳转中……";
			document.form1.cont.disabled=true;
		}
		function login(){
			top.location.href='user_login.php';
		}
		function resend(){
			var username=document.getElementById('textfield1').value;
			var password=document.getElementById('textfield3').value;
			var email=document.getElementById('textfield5').value;
			window.open('register_real.php?textfield1='+username+'&&textfield3='+password+'&&textfield5='+email+'&&TYPE=2','_self');
		}
		function register(){
			window.parent.parent.document.getElementById("lhg_registerDILOG").height="350px";
			setTimeout(function () {				
				top.location.href='user_register.php';		
			},5000);
		}
		function registerLINK(){				
				top.location.href='user_register.php';
		}
		function setHeight(x){
			window.parent.parent.document.getElementById("lhg_registerDILOG").height=x;
	   }
	</script>
</head> 
<?php 
header("Content-type: text/html;charset=utf-8");

$email = stripslashes(trim($_GET['email']));
  ?>   
<body onload='setHeight("500px");' > 
	<form action="" method="post" name="myform" id="form1"  >
	 <div class="modal-body" onload='setHeight("500px");' >
	 	<input type="hidden" name="textfield1" id="textfield1" value="<?php echo $username; ?>" />
		<input type="hidden" name="textfield3" id="textfield3" value="<?php echo $password; ?>" />
		<input type="hidden" name="textfield5" id="textfield5" value="<?php echo $email; ?>" />
			<div class="form-group col-xs-12">
				<h5>&nbsp;</h5>
				 <div class="form-group col-xs-12">
				 <div class="form-group col-xs-1"></div>
				</div>
			
				<div class="form-group col-xs-12" style="font-size:20px;line-height:1.8; word-break: break-all;">
					<div style="font-size:20px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;尊敬的： <?php echo $email; ?> ，您好，此条链接为无效链接，可能的原因有：1.
						您已经点击过该链接并成功找回密码。您可以点击下方“已重置，点击登录”按钮进入登录页面，使用您重置的密码登录系统。

						2.此条链接纯属系统误发，请自行忽略，给您带来的不便敬请谅解。--Capteam敬上</div> 	
				</div>
			
				<div class="form-group col-xs-12">
					<div class="form-group col-xs-6"></div>
				<!--<div class="form-group col-xs-2"><button type="button" class="btn btn-default" style="font-size:16px;" name="cont" data-loading-text="跳转中……" onclick="jumpto();">登录邮箱</button></div>	
				--><div class="form-group col-xs-1">
				<div class="form-group col-xs-3"><button type="button" class="btn btn-default" style="font-size:16px;"  onclick="login();">已重置，点击登录</button></div>		 
					
				</div>  
				<p>&nbsp;</p>
		
			</div>
		</div>	
<script type="text/javascript">
$('button[data-loading-text]').click(function () {
    var btn = $(this).button('loading');
    setTimeout(function () {
        btn.button('reset');
		window.open('<?php  echo $url; ?>','_blank');		
    }, 3000);
});
</script>
		
		</form>
</body>
<?php  //注册成功，发送失败 


/*function sendmail($time,$email,$url){
	include_once("smtp.class.php");
	$smtpserver = ""; //SMTP服务器
    $smtpserverport = 25; //SMTP服务器端口
       $smtpusermail = "1152352921@qq.com"; //SMTP服务器的用户邮箱
    $smtpuser = "1152352921@qq.com"; //SMTP服务器的用户帐号
    $smtppass = "whxNo.1"; //SMTP服务器的用户密码
    $smtp = new Smtp($smtpserver, $smtpserverport, true, $smtpuser, $smtppass); //这里面的一个true是表示使用身份验证,否则不使用身份验证.
    $emailtype = "HTML"; //信件类型，文本:text；网页：HTML
    $smtpemailto = $email;
    $smtpemailfrom = $smtpusermail;
    $emailsubject = "Helloweba.com - 找回密码";
    $emailbody = "亲爱的".$email."：<br/>您在".$time."提交了找回密码请求。请点击下面的链接重置密码（按钮24小时内有效）。<br/><a href='".$url."' target='_blank'>".$url."</a><br/>如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问。<br/>如果您没有提交找回密码请求，请忽略此邮件。";
    $rs = $smtp->sendmail($smtpemailto, $smtpemailfrom, $emailsubject, $emailbody, $emailtype);
	return $rs;
}*/


/*if(mysql_insert_id()){//写入成功，发邮件
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
}*/




function injectChk($sql_str) { //防止注入
		$check = eregi('select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile', $sql_str);
		if ($check) {
			echo('非法字符串');
			exit ();
		} else {
			return $sql_str;
		}
}

?>