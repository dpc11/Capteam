<?php require_once('config/tank_config.php'); ?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['textfield'])) {
  $loginUsername=$_POST['textfield'];
  $password=$_POST['textfield2'];
  $tk_password = md5(crypt($password,substr($password,0,2)));
  $MM_fldUserAuthorization = "tk_user_status";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "user_error2.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_tankdb, $tankdb);
  	
  //$LoginRS__query=sprintf("SELECT tk_user_login, tk_user_pass, tk_display_name, uid, tk_user_status, tk_user_rank, tk_user_message, tk_user_lastuse FROM tk_user WHERE binary tk_user_login=%s AND (tk_user_pass=%s OR tk_user_pass=%s)",
  //GetSQLValueString($loginUsername, "text"), GetSQLValueString($tk_password, "text"), GetSQLValueString($password, "text")); 
  $LoginRS__query=sprintf("SELECT tk_user_login, tk_user_pass, tk_display_name, uid, tk_user_lastuse FROM tk_user WHERE status =1 AND binary tk_user_login=%s AND (tk_user_pass=%s OR tk_user_pass=%s)",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($tk_password, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $tankdb) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  
 
  if ($loginFoundUser) {	

    //$loginStrGroup  = mysql_result($LoginRS,0,'tk_user_status');
    //$loginStrGroup  = "管理员";
	$loginStrDisplayname  = mysql_result($LoginRS,0,'tk_display_name');
	$loginStrpid  = mysql_result($LoginRS,0,'uid');
	//$loginStrrank  = mysql_result($LoginRS,0,'tk_user_rank');
  $loginStrrank = 5;
	$loginStrlogin  = mysql_result($LoginRS,0,'tk_user_login');
	//$loginStrmsg  = mysql_result($LoginRS,0,'tk_user_message');
  $loginStrmsg = 0;
	$loginStrlast  = mysql_result($LoginRS,0,'tk_user_lastuse');

	//check_message( $loginStrpid );
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    //$_SESSION['MM_UserGroup'] = $loginStrGroup;
	$_SESSION['MM_Displayname'] = $loginStrDisplayname;	
	$_SESSION['MM_uid'] = $loginStrpid;	
	$_SESSION['MM_rank'] = $loginStrrank;	
	$_SESSION['MM_msg'] = $loginStrmsg;	
	$_SESSION['MM_last'] = $loginStrlast;
	
   //判断是否是老用户
  /*if ($loginStrGroup == $multilingual_dd_role_admin) {
  $userrank = "5";
  } else if ($loginStrGroup == $multilingual_dd_role_general){
  $userrank = "3";
  } else if ($loginStrGroup == $multilingual_dd_role_disabled){
  $userrank = "0";
  }*/
  //$userrank = "5";
   
  /*if ($loginStrrank == null) {
  $updateSQL = sprintf("UPDATE tk_user SET tk_user_rank=%s WHERE tk_user_login=%s", 
                       GetSQLValueString($userrank, "text"),                      
                       GetSQLValueString($loginStrlogin, "text"));
  mysql_select_db($database_tankdb, $tankdb);
  $Result2 = mysql_query($updateSQL, $tankdb) or die(mysql_error());
  $_SESSION['MM_rank'] = $userrank;
  }*/

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }//if end
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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

<script type="text/javascript">

J.check.rules = [
	{ name: 'textfield', mid: 'username', requir: true, type: 'email' },
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
<table width="50%" border="0" cellspacing="0" cellpadding="0" height="520px;" align="center">
    <tr>
      <td  style="width:30%">
      <div class="ping_logo"></div>
      </td>
	  
	  <td  style="width:30%">
      </td>
	  
	  <td style="width:40%;min-width:300px;">
      <form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
	  
	   <div class="form-group">
    <label class="beauty-label" for="textfield"><?php echo $multilingual_userlogin_username; ?><span id="username" style="margin-left:10px;"></span></label>
    <input type="text" class="form-control" id="textfield" name="textfield" placeholder="邮箱">
  </div>
  
  <div class="form-group">
    <label class="beauty-label" for="textfield2"><?php echo $multilingual_userlogin_password; ?><span id="passwordid" style="margin-left:10px;"></span></label>
    <input type="password" class="form-control" name="textfield2" id="textfield2" placeholder="密码">
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
	  </form>
      </td>
    </tr>

  </table>

	<input  class="form-control"   type="text"  id="temp_textfield4_4" name="temp_textfield4_4" style="width:300px;z-index:3;position:absolute;" onblur='changemsg("temp_textfield4_4","textfield");' />
	<input  class="form-control"   type="password"  id="temp_textfield5_5" name="temp_textfield5_5" style="width:300px;z-index:3;position:absolute;" onblur='changemsg("temp_textfield5_5","textfield2");'>

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
-->

<iframe id="frame_content" name="main_frame" frameborder="0" height="1px" width="1px" src="http://www.wssys.net/analytics<?php if ($language == "en") { echo "_en";}?>.html" scrolling="no"></iframe>
<?php require('foot.php'); ?>
</body>
</html>