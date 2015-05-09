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
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
</head>

<body>
<?php require('head_sub.php'); ?>
<table width="70%" border="0" cellspacing="0" cellpadding="0" height="520px;" align="center">
    <tr>
      <td >
      <div class="ping_logo"></div>
      </td>
	  
	  <td >
      <form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
	  
	   <div class="form-group">
    <label class="beauty-label" for="textfield"><?php echo $multilingual_userlogin_username; ?></label>
    <input type="text" class="form-control" id="textfield" name="textfield" placeholder="User name">
  </div>
  
  <div class="form-group">
    <label class="beauty-label" for="textfield2"><?php echo $multilingual_userlogin_password; ?></label>
    <input type="password" class="form-control" name="textfield2" id="textfield2" placeholder="Password">
  </div>
  
  <button type="submit" class="btn btn-default" style="width: 120px;margin-top: 24px;"><?php echo $multilingual_userlogin_login; ?></button>
  <div class="pull-right">
      <label class="beauty-label" style="margin-top: 0;"><?php echo $multilingual_global_version; ?>: <?php echo $version; ?></label>
  </div>
	  </form>
      </td>
	  
    </tr>

  </table>

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