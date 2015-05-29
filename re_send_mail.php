<?php require_once('config/tank_config.php');?>
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
			top.location.href='index.php';
		}
		function resend(){
			var username=document.getElementById('textfield1').value;
			var password=document.getElementById('textfield3').value;
			var email=document.getElementById('textfield5').value;
			window.open('re_send_mail.php?email='+email);
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
<?php $email = trim($_GET['email']); 
//echo $email;

$regtime = time();

mysql_select_db($database_tankdb, $tankdb); 

$findsql="SELECT* FROM tk_user WHERE tk_user_email='$email'";
$result=mysql_query($findsql);
$username  = mysql_result($result,0,'tk_display_name');
//echo $username;

$token = md5($username.$regtime);
//echo $token;
$token_exptime = time()+60*60*24;//过期时间为24小时后

$updatesql="UPDATE tk_user SET token='$token', token_exptime='$token_exptime' WHERE tk_user_email='$email' ";//修改token和token_exptime
mysql_query($updatesql);

include_once("plug-in/mail/smtp.class.php");
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
    $emailbody = "亲爱的".$displayname."：<br/>感谢您在Capteam团队协作平台注册了新帐号。<br/>请点击链接激活您的帐号。<br/><a href='http://localhost/Capteam/active.php?verify=".$token."' target='_blank'>http://localhost/Capteam/active.php?email=".$email."&verify=".$token."</a><br/>如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接24小时内有效。<br/>如果此次激活请求非你本人所发，请忽略本邮件。<br/><p style='text-align:right'>-------- Capteam团队协作平台敬上</p>";
   // $emailbody="哇哈哈";
    $rs = $smtp->sendmail($smtpemailto, $smtpemailfrom, $emailsubject, $emailbody, $emailtype);
    //echo "$rs";
  if($rs==1){
    $mailstyle = explode('@',$email); 
    $url='http://mail.'.$mailstyle[1];   ?>   
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
				 <div class="form-group col-xs-2">
				
				 </div>
				 <div class="form-group col-xs-9">
					<p id="titlemsg" name="titlemsg" style="font-size:24px;font-weight:bold;margin:15px">重新发送邮件成功!<br></p>
					</div>
				</div>
			
				<div class="form-group col-xs-12" style="font-size:20px;line-height:1.8; word-break: break-all;">
					<div style="font-size:20px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Capteam已将验证邮件重新发送至您的注册邮箱： <?php echo $email; ?>  ，请进入邮箱并激活账号。本网站温馨提示，由于您选择的是重新发送邮箱，所以除该邮件之外的所有来自本网站的激活链接均已失效。</div> 	
				</div>
			
				<div class="form-group col-xs-12">
					<div class="form-group col-xs-6"></div>
				<div class="form-group col-xs-2"><button type="button" class="btn btn-default" style="font-size:16px;" name="cont" data-loading-text="跳转中……" onclick="jumpto();">登陆邮箱</button></div>	
				<div class="form-group col-xs-1">
				<div class="form-group col-xs-3"><button type="button" class="btn btn-default" style="font-size:16px;"  onclick="login();">已激活，点击登录</button></div>		 
					
				</div>  
				<p>&nbsp;</p>
			<div class="form-group col-xs-12">	
				<div class="form-group col-xs-1">	</div>
				<div class="form-group col-xs-7">				
					<p class="text-muted" style="font-size:15px;">没有收到邮件？</p> 
					<p class="text-muted" style="font-size:15px;">1.检查你的邮件垃圾箱</p> 
					<p class="text-muted" style="font-size:15px;">2.检查邮件是否被拦截</p> 
					<p class="text-muted" style="font-size:15px;">3.若仍未收到邮件，请尝试<a href="#" onclick="resend();">重新发送</a></p> 	
				</div>				
				<div class="form-group col-xs-4">	</div>
			</div>
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
<?php  }


?>

