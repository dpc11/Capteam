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
<?php  
 
$displayname = stripslashes(trim($_GET['textfield1']));
/*
$query = mysql_query("select uid from tk_user where tk_user_login='$username'");
$num = mysql_num_rows($query);
//echo "$num";
if($num==1){
  echo '<script>alert("用户名已存在，请换个其他的用户名");window.history.go(-1);</script>';
  exit;
}
*/
//echo $displayname;
$regtime = time();
$email = trim($_GET['email']);

$type=1;
if (isset($_GET['TYPE'])) {
  $type = $_GET['TYPE'];
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
					<img src="/Capteam/Capteam/img/OK.png" >
				 </div>
				 <div class="form-group col-xs-9">
					<p id="titlemsg" name="titlemsg" style="font-size:24px;font-weight:bold;margin:15px">登录失败!<br></p>
					</div>
				</div>
			
				<div class="form-group col-xs-12" style="font-size:20px;line-height:1.8; word-break: break-all;">
					<div style="font-size:20px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;尊敬的:<?php echo $email;?>,您好，您暂时无法登录本系统，原因是：您的账号未激活，请登录到您注册本系统时所用邮箱进行邮箱验证。点击下方“登录邮箱按钮”进入到您的邮箱</div> 	
				</div>
			
				<div class="form-group col-xs-12">
					<div class="form-group col-xs-6"></div>
				<div class="form-group col-xs-2"><button type="button" class="btn btn-default" style="font-size:16px;" name="cont" data-loading-text="跳转中……" onclick="jumpto();">登录邮箱</button></div>	
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

</html>  
