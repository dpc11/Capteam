<?php require_once('config/tank_config.php'); ?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS - <?php echo $multilingual_userlogin_title; ?></title>
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>



<script type="text/javascript">
function chk_form(){
  var user = document.getElementById("textfield1");
  if(user.value==""){
    alert("用户名不能为空!");
    return false;
    //user.focus();
  }
  var display = document.getElementById("textfield2");
  if(display.value==""){
    alert("昵称不能为空！");
    return false;
    //user.focus();
  }
  var pass = document.getElementById("textfield3");
  if(pass.value==""){
    alert("密码不能为空！");
    return false;
    //pass.focus();
  }
  var confirmpass = document.getElementById("textfield4");
  if(confirmpass.value==""){
    alert("确认密码不能为空！");
    return false;
    //email.focus();
  }
   if(confirmpass.value!=pass.value){
    alert("两次输入密码不一致，请重新输入！");
    
    return false;
    //email.focus();
  }
  var email = document.getElementById("textfield5");
  if(email.value==""){
    alert("邮箱不能为空！");
    return false;
    //email.focus();
  }

  var preg = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/; //匹配Email
  if(!preg.test(email.value)){ 
    alert("Email格式错误！");
    return false;
    //email.focus();
  }

}
</script>
</head>



<?php require('head_sub.php'); ?>
<table width="70%" border="0" cellspacing="0" cellpadding="0" height="520px;" align="center">
    <tr>
      <td >
      <div class="ping_logo"></div>
      </td>
    
    <td >
      <form id="form1" name="form1" method="POST" action="register.php" onsubmit="return chk_form();">
    
     <div class="form-group">
    <label class="beauty-label" for="textfield"><?php echo $multilingual_userlogin_username; ?></label>
    <input type="text" class="form-control" id="textfield1" name="textfield1" placeholder="User name">
  </div>
  
    <div class="form-group">
    <label class="beauty-label" for="textfield"><?php echo $multilingual_user_title; ?></label>
    <input type="text" class="form-control" id="textfield2" name="textfield2" placeholder="Display name">
  </div>

  <div class="form-group">
    <label class="beauty-label" for="textfield2"><?php echo $multilingual_userlogin_password; ?></label>
    <input type="password" class="form-control" name="textfield3" id="textfield3" placeholder="Password">
  </div>
    <div class="form-group">
    <label class="beauty-label" for="textfield2"><?php echo $multilingual_user_password2; ?></label>
    <input type="password" class="form-control" name="textfield4" id="textfield4" placeholder="Confirm Password">
  </div>
  <div class="form-group">
    <label class="beauty-label" for="textfield2"><?php echo $multilingual_user_email; ?></label>
    <input type="text" class="form-control" name="textfield5" id="textfield5" placeholder="Email">
  </div>

  <button type="submit" class="btn btn-default" style="width: 120px;margin-top: 24px;"><?php echo $multilingual_user_register; ?></button>
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