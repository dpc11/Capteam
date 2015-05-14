<?php require_once('config/tank_config.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

$errormsg=false;
$loginUsername="";
$password="";

$loginUsername=$_POST['textfield'];
$password=$_POST['textfield2'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Capteam - <?php echo $multilingual_userlogin_title; ?></title>
<link href="css/tk_style.css" rel="stylesheet" type="text/css" />
<link href="css/lhgcore/lhgdialog.css" rel="stylesheet" type="text/css" />
<link href="css/lhgcore/lhgcheck.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap/bootstrap.css" rel="stylesheet" media="screen">
<link href="css/custom.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/lhgcore/lhgcore.js"></script>
<script type="text/javascript" src="js/lhgcore/lhgcheck.js"></script>
<script type="text/javascript" src="js/jquery/jquery.js"></script>
<script type="text/javascript" src="js/bootstrap/bootstrap.js"></script>
<script type="text/javascript" src="js/lhgcore/lhgdialog.js"></script>

<script type="text/javascript">

J.check.rules = [
	{ name: 'textfield', mid: 'username', requir: true, type: 'email', warn: '邮箱格式错误' },
	{ name: 'textfield2', mid: 'passwordid', requir: true}
];

function register(){
	
	window.open('user_register.php','_self');
}

function changemsg(UP,DOWN){
	
		document.getElementById(DOWN).focus();
		var contentmsg = document.getElementById(UP).value;
		document.getElementById(DOWN).value=contentmsg;
		document.getElementById(DOWN).blur();

}

function changepsd(UP,DOWN){
	
		document.getElementById(DOWN).focus();
		var contentmsg = document.getElementById(UP).value;		
		document.getElementById(DOWN).value=contentmsg;
		document.getElementById(DOWN).blur();

}

function login(){
	window.parent.document.getElementById("textfield").value=document.getElementById("textfield").value;
	window.parent.document.getElementById("textfield2").value=document.getElementById("textfield2").value;
	window.parent.document.getElementById("login").click();
	return false;
}
	
	$(window).load(function()
	{
    J.check.regform('form1');
	
	var x= $(textfield).offset(); 
	document.getElementById("temp_textfield4_4").style.top=(x.top)+'px';
	document.getElementById("temp_textfield4_4").style.left=(x.left)+'px';
	document.getElementById("temp_textfield4_4").style.width=(document.getElementById("textfield").clientWidth+5)+'px';
    x= $(textfield2).offset();
	document.getElementById("temp_textfield5_5").style.top=(x.top)+'px';
	document.getElementById("temp_textfield5_5").style.left=(x.left)+'px';	
	document.getElementById("temp_textfield5_5").style.width=(document.getElementById("textfield2").clientWidth+5)+'px';
	
	x= $(textfield_label).offset();
	document.getElementById("username").style.top=(x.top+3)+'px';
	document.getElementById("username").style.left=(x.left+document.getElementById("textfield_label").clientWidth+13)+'px';
	
	x= $(textfield2_label).offset();
	document.getElementById("passwordid").style.top=(x.top+3)+'px';
	document.getElementById("passwordid").style.left=(x.left+document.getElementById("textfield2_label").clientWidth+13)+'px';
	
		var r = window.screen.height /1080; 
		$("body").css("-webkit-transform","scale(" + r + ")"); 
		$("body").css("-webkit-transform-origin","0 0"); 
		
		
	});

</script>

</head>

<body style="width:1000px;height:500px;">
<center>
<div id="innerdiv" style="width:820.833px;-webkit-transform: scale( 1.2 );-webkit-transform-origin: 0 0;height:400px;" >
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%" align="center">
    <tr>
      <td  style="width:40%">
      <div class="ping_logo"></div>
      </td>
	  <td style="width:20%;">&nbsp;</td>
	  
	  <td style="width:40%;min-width:100px;">
	  <div>
			<legend  style="border-bottom-width: 3px;font-weight:bold;font-size:20px;">&nbsp;&nbsp;用户登录</legend>
		</div>		
		<?php  if($errormsg==true){ ?>
		<div  style="width:5%;float:left;" > 
		&nbsp;
		</div>
				<div id="errormsg"class="ui-state-error ui-corner-all" style="width:90%;float:left;margin-bottom:8px;" > 
						<div style="width:11%;float:left;margin-top:10px;text-align:center;" >
						<span class="ui-icon ui-icon-alert"style="margin:0 auto;" ></span>
						</div>
						<div  style="width:89%;float:left;padding-top:4px;padding-bottom:4px;">
							<span>您输入的密码和账户名不匹配，请重新输入。<br>或者您忘记了密码？</span>
						</div>
				</div>
		<?php }
		?>


      <form id="form1" name="form1" method="POST" action="">
	  <div style="padding-left:15px;" >
	   <div class="form-group">
    <label class="beauty-label" for="textfield" id="textfield_label"  style="font-size:17px;font-weight:bold;"><?php echo $multilingual_userlogin_username; ?>&nbsp;&nbsp; ：&nbsp;&nbsp;</label>
    <input type="text" class="form-control" id="textfield"  style="width:300px;" name="textfield" placeholder="邮箱" value="<?php echo $loginUsername; ?>">
  </div>
  
  <div class="form-group">
    <label class="beauty-label" for="textfield2"  id="textfield2_label" style="font-size:17px;font-weight:bold;">密&nbsp;&nbsp;&nbsp;&nbsp;码&nbsp;&nbsp; ：&nbsp;&nbsp;</label>
    <input type="password" class="form-control" name="textfield2"  style="width:300px;" id="textfield2" placeholder="密码" value="<?php echo $password; ?>">
  </div>
  <div style="clear:both ">
	<div style="width: 10%;float:right; ">
	 </div>
	 <div style="width: 30%;float:right; ">
	  <a class="beauty-label" href="#">忘记密码？</a>
	 </div>
     
  </div>
  
  <div  style="clear:both ">
	  <div style="width: 10%;margin-top: 24px;float:right; ">
	 </div>
	  <div style="width: 30%;margin-top: 24px;float:right; ">
	  <button type="button" class="btn btn-default" style="width: 60px;float:right; " onclick="register();"><?php echo $multilingual_user_register; ?></button>
	  </div>
	  <div style="width: 30%;margin-top: 24px;float:right; ">
	  <button type="submit" class="btn btn-default" style="width: 60px;float:right; " onclick="return login();"><?php echo $multilingual_userlogin_login; ?></button>
	  </div>
	  
  </div>
  </div>
	  </form>
      </td>
    </tr>

  </table>
</div>
<span id="username" style="z-index:3;position:absolute;-webkit-transform: scale( 1.2 );-webkit-transform-origin: 0 0;min-width:150px;"></span>
<span id="passwordid"  style="z-index:3;position:absolute;-webkit-transform: scale( 1.2 );-webkit-transform-origin: 0 0;min-width:150px;"></span>

<input  class="form-control"   type="text"  id="temp_textfield4_4" name="temp_textfield4_4" style="width:300px;z-index:3;position:absolute;-webkit-transform: scale( 1.2 );-webkit-transform-origin: 0 0;" onblur='changemsg("temp_textfield4_4","textfield");' placeholder="邮箱"  value="<?php echo $loginUsername; ?>" />
	<input  class="form-control"   type="password"  id="temp_textfield5_5" name="temp_textfield5_5" style="width:300px;z-index:3;position:absolute;-webkit-transform: scale( 1.2 );-webkit-transform-origin: 0 0;" onblur='changepsd("temp_textfield5_5","textfield2");' placeholder="密码" value="<?php echo $password; ?>" >

</center>
</body>
</html>
