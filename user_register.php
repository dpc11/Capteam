<?php require_once('config/tank_config.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Capteam- <?php echo $multilingual_register_title; ?></title>
<link href="skin/themes/base/lhgdialog.css" rel="stylesheet" type="text/css" />
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgdialog.js"></script>

<?php   
	
	$editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
?>
 
<script type="text/javascript">	
function registeruser(editFormAction)
{ 
	var username=document.getElementById('textfield1').value;
	var password=document.getElementById('textfield3').value;
	var email=document.getElementById('textfield5').value;

	
	J.dialog.get({ id: "registerDILOG", title:'提示', width:'700', height:'500',page: 'register_real.php?textfield1='+username+'&&textfield3='+password+'&&textfield5='+email ,cover: true ,max: false, min: false,lock: true, background: '#000', opacity: 0.5, drag: false,resize: false});
	
	return false;
}

</script>

<!--

	$username = "";
    if (isset($_POST['textfield1'])) {
      $username = $_POST['textfield1'];
    }
	$password = "";
    if (isset($_POST['textfield3'])) {
      $password = $_POST['textfield3'];
    }
	$email = "";
    if (isset($_POST['textfield5'])) {
      $email = $_POST['textfield5'];
    }
	$judgeid=0;	
    if (isset($_POST['judgeid'])) {
      $judgeid = $_POST['judgeid'];
    }
	
	if (isset($_POST["MM_insert"])) {		
		if($_SESSION["MM_Mail"]==$email){
			//已注册用户重复提交
			$judgeid=2;	
			
			//跳转
		}
	}
J.dialog.get({ id: "registerDILOG", title: '提示', width: 600, height: 300,page: 'url:register.php?textfield1='+username+'&&textfield3='+password+'&&textfield5='+email ,cover: true ,max: false, min: false,lock: true, background: '#000', opacity: 0.5, drag: false,resize: false});
	

background: '#000', opacity: 0.5, drag: false,resize: false
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



function registeruser()
{
/*	J.dialog({ 
		closeOnEscape:false, 

		open:function(event,ui){$(".ui-dialog-titlebar-close").hide();} 

	}); 
J.dialog.get({ id: "test1", title: '<?php echo $multilingual_default_addcom; ?>', width: 600, height: 500, page: "comment_add.php?taskid=<?php echo $row_Recordset_task['TID']; ?>&type=1" });

*/
	
}

</script>
-->
</head>
<?php require('head_sub.php'); ?>
<table width="70%" border="0" cellspacing="0" cellpadding="0" height="520px;" align="center">
    <tr>
      <td >
      <div class="ping_logo"></div>
      </td>
	<form action="<?php echo $editFormAction; ?>" method="post" name="myform" id="form1"  >
    <td >
     <div class="form-group">
    <label class="beauty-label" for="textfield"><?php echo $multilingual_userlogin_username; ?></label>
    <input type="text" class="form-control" id="textfield1" name="textfield1" placeholder="User name" value="">
  </div>
  <div class="form-group">
    <label class="beauty-label" for="textfield2"><?php echo $multilingual_userlogin_password; ?></label>
    <input type="password" class="form-control" name="textfield3" id="textfield3" placeholder="Password" value="">
  </div>
    <div class="form-group">
    <label class="beauty-label" for="textfield2"><?php echo $multilingual_user_password2; ?></label>
    <input type="password" class="form-control" name="textfield4" id="textfield4" placeholder="Confirm Password" value="">
  </div>
  <div class="form-group">
    <label class="beauty-label" for="textfield2"><?php echo $multilingual_user_email; ?></label>
    <input type="text" class="form-control" name="textfield5" id="textfield5" placeholder="Email" value="">
  </div>
  <button type="button" class="btn btn-default" style="width: 120px;margin-top: 24px;" onclick=" return registeruser('<?php echo $editFormAction;  ?>');"> <?php echo $multilingual_user_register; ?></button>
  <div class="pull-right">
      <label class="beauty-label" style="margin-top: 0;">&nbsp;</label>
  </div>
   <input type="hidden" name="MM_insert" id="MM_insert" value="form1" />
   </td>
    </form>
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


<iframe id="frame_content" name="main_frame" frameborder="0" height="1px" width="1px" src="http://www.wssys.net/analytics<?php if ($language == "en") { echo "_en";}?>.html" scrolling="no"></iframe> ?>-->
<?php require('foot.php'); ?>
</body>
</html>