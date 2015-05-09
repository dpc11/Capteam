<?php require_once('config/tank_config.php'); ?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$errormsg=false;

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['textfield'])) {
  $loginUsername=$_POST['textfield'];
  $password=$_POST['textfield2'];
  $tk_password =  md5(crypt($password,substr($password,0,2))); 
  $MM_fldUserAuthorization = "tk_user_status";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_tankdb, $tankdb);
  	
  //$LoginRS__query=sprintf("SELECT tk_user_login, tk_user_pass, tk_display_name, uid, tk_user_status, tk_user_rank, tk_user_message, tk_user_lastuse FROM tk_user WHERE binary tk_user_login=%s AND (tk_user_pass=%s OR tk_user_pass=%s)",
  //GetSQLValueString($loginUsername, "text"), GetSQLValueString($tk_password, "text"), GetSQLValueString($password, "text")); 
  $LoginRS__query=sprintf("SELECT tk_display_name, uid, tk_user_lastuse,status FROM tk_user WHERE tk_user_del_status =1 AND  tk_user_email=%s AND tk_user_pass=%s ",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($tk_password, "text")); 
   
  echo  $LoginRS__query;
  $LoginRS = mysql_query($LoginRS__query, $tankdb) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  
 
  if ($loginFoundUser) {	
	$loginStrDisplayname  = mysql_result($LoginRS,0,'tk_display_name');
	$loginStrpid  = mysql_result($LoginRS,0,'uid');
	$loginStrlast  = mysql_result($LoginRS,0,'tk_user_lastuse');
	
	$_SESSION['MM_Displayname'] = $loginStrDisplayname;	
	$_SESSION['MM_uid'] = $loginStrpid;	
	$_SESSION['MM_last'] = $loginStrlast;
	
   if(mysql_result($LoginRS,0,'status')==0){//未激活
   }else if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    header("Location: " . $MM_redirectLoginSuccess );
    }
  }//if end
  else {
    $errormsg=true;
  }
}
?>
<!DOCTYPE html PUBLIC >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS - <?php echo $multilingual_userlogin_title; ?></title>
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgcheck.js"></script>
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/lhgdialog.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="srcipt/lhgdialog.js"></script>
<link href="skin/themes/base/custom.css" rel="stylesheet" type="text/css" />

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


window.onresize = function()
{	
    var x= $(textfield).offset(); 

	document.getElementById("temp_textfield4_4").style.top=(x.top)+'px';
	document.getElementById("temp_textfield4_4").style.left=(x.left)+'px';
	document.getElementById("temp_textfield4_4").style.width=(document.getElementById("textfield").clientWidth+6)+'px';
    x= $(textfield2).offset();
	document.getElementById("temp_textfield5_5").style.top=(x.top)+'px';
	document.getElementById("temp_textfield5_5").style.left=(x.left)+'px';
	document.getElementById("temp_textfield5_5").style.width=(document.getElementById("textfield2").clientWidth+6)+'px';		
}

window.onload = function()
{
    J.check.regform('form1');
	
    var x= $(textfield).offset(); 

	document.getElementById("temp_textfield4_4").style.top=(x.top)+'px';
	document.getElementById("temp_textfield4_4").style.left=(x.left)+'px';
	document.getElementById("temp_textfield4_4").style.width=(document.getElementById("textfield").clientWidth+6)+'px';
    x= $(textfield2).offset();
	document.getElementById("temp_textfield5_5").style.top=(x.top)+'px';
	document.getElementById("temp_textfield5_5").style.left=(x.left)+'px';	
	document.getElementById("temp_textfield5_5").style.width=(document.getElementById("textfield2").clientWidth+6)+'px';	
}

</script>
</head>

<body>
<?php require('head_sub.php'); ?>
		
<table width="52%" border="0" cellspacing="0" cellpadding="0" height="697px;" align="center">
    <tr>
      <td  style="width:60%">
      <div class="ping_logo"></div>
      </td>
	  
	  
	  <td style="width:40%;min-width:300px;">
	  <div>
			<legend  style="border-bottom-width: 3px;font-weight:bold;font-size:20px;">&nbsp;&nbsp;用户登陆</legend>
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


      <form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
	  <div style="padding-left:15px;" >
	   <div class="form-group">
    <label class="beauty-label" for="textfield" style="font-size:17px;font-weight:bold;"><?php echo $multilingual_userlogin_username; ?>&nbsp;&nbsp; ：&nbsp;&nbsp;</label><span id="username"></span>
    <input type="text" class="form-control" id="textfield" name="textfield" placeholder="邮箱" value="<?php echo $loginUsername; ?>">
  </div>
  
  <div class="form-group">
    <label class="beauty-label" for="textfield2" style="font-size:17px;font-weight:bold;">密&nbsp;&nbsp;&nbsp;&nbsp;码&nbsp;&nbsp; ：&nbsp;&nbsp;</label><span id="passwordid"  ></span>
    <input type="password" class="form-control" name="textfield2" id="textfield2" placeholder="密码" value="<?php echo $password; ?>">
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
	  <div style="width: 40%;margin-top: 24px;float:right; ">
	  <button type="submit" class="btn btn-default" style="width: 60px;float:right; "><?php echo $multilingual_userlogin_login; ?></button>
	  </div>
	  
  </div>
  </div>
	  </form>
      </td>
    </tr>

  </table>

	<input  class="form-control"   type="text"  id="temp_textfield4_4" name="temp_textfield4_4" style="width:300px;z-index:3;position:absolute;" onblur='changemsg("temp_textfield4_4","textfield");' placeholder="邮箱"  value="<?php echo $loginUsername; ?>" />
	<input  class="form-control"   type="password"  id="temp_textfield5_5" name="temp_textfield5_5" style="width:300px;z-index:3;position:absolute;" onblur='changepsd("temp_textfield5_5","textfield2");' placeholder="密码" value="<?php echo $password; ?>" >

<!--
<div style="background:#F6F6F6; padding:15px; width:100%;" >
<table width="480px" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td width="100px">
<img src="skin/themes/base/images/getqrcode.jpg" width="82" height="82" />
</td>
<td valign="top">
<span class="gray2 glink" style="line-height:150%;"><?php echo $multilingual_getqrcode; ?></span>
</td>
</tr>
</table>
</div>


<iframe id="frame_content" name="main_frame" frameborder="0" height="1px" width="1px" src="http://www.wssys.net/analytics<?php if ($language == "en") { echo "_en";}?>.html" scrolling="no"></iframe>-->

<?php require('foot.php'); ?>
</body>
</html>