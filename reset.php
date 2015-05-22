<?php
require_once('config/tank_config.php'); 
header("Content-Type:text/html;   charset=utf-8"); 
mysql_select_db($database_tankdb,$tankdb);

$token = stripslashes(trim($_GET['token']));
$email = stripslashes(trim($_GET['email']));

$sql = "SELECT* FROM tk_user where tk_user_email='$email'";

$query = mysql_query($sql);
$row = mysql_fetch_array($query);


if($row){
	$mt = md5($row['uid'].$row['tk_display_name'].$row['tk_user_pass']);


	if($mt==$token){
		if(time()-$row['getpasstime']>24*60*60){
			$msg = '该链接已过期！';
		}else{
			//重置密码...
			$msg = '请重新设置密码，显示重置密码表单，<br/>这里只是演示，略过。';
			//echo "woshi！！！";
		}
	}else{
		$msg =  '无效的链接<br/>'.$mt.'<br/>'.$token;
	}
}else{
	$msg =  '错误的链接！';	
}
//echo $msg;
if($msg=='请重新设置密码，显示重置密码表单，<br/>这里只是演示，略过。'){//如果链接有效，显示重置密码页面
	$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "myform")) {//获得本页表单myform
      
       $newpass=$_POST['textfield2'];//新密码
       $newconpass=$_POST['textfield3'];//确认密码

    
       $tk_newpass=md5(crypt($newpass,substr($newpass,0,2)));//md5加密


   	$updatesql="UPDATE tk_user SET tk_user_pass='$tk_newpass' WHERE tk_user_email='$email'";//修改密码
    $update=mysql_query($updatesql);
   
    $insertGoTo = "user_login.php";
     if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
    //header(sprintf("Location: %s", $insertGoTo));
  echo "<script> alert('修改密码成功，为您转到登录界面'); </script>"; 
echo "<meta http-equiv='Refresh' content='0;URL=$insertGoTo'>"; 
}
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
function chk_form(){
  
   var pass2 = document.getElementById("textfield2");//新密码
    var pass3 = document.getElementById("textfield3");//确认新密码

   if(pass2.value==""){
    alert("新密码不能为空！");
    return false;
    //pass.focus();
  }
   if(pass3.value==""){
    alert("确认密码不能为空！");
    return false;
    //pass.focus();
  }
   if(pass2.value!=pass3.value){
    alert("您输入的新密码和确认密码不一致，请重新输入！");
    return false;
    //pass.focus();
  }
}
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
			<legend  style="border-bottom-width: 3px;font-weight:bold;font-size:20px;">&nbsp;&nbsp;重置密码</legend>
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


      <form id="form1" name="form1" method="POST" onsubmit="return chk_form();" action="<?php echo $editFormAction; ?>">
	  <div style="padding-left:15px;" >
  
  <div class="form-group">
    <label class="beauty-label" for="textfield2"  id="textfield2_label" style="font-size:17px;font-weight:bold;">新&nbsp;&nbsp;&nbsp;&nbsp;密&nbsp;&nbsp;&nbsp;&nbsp;码&nbsp;&nbsp; ：&nbsp;&nbsp;</label>
    <input type="password" class="form-control" name="textfield2"  style="width:300px;" id="textfield2" placeholder="新密码" >
  </div>
  <div class="form-group">
    <label class="beauty-label" for="textfield3" id="textfield_label"  style="font-size:17px;font-weight:bold;">确认新密码</label>
    <input type="password" class="form-control" id="textfield3"  style="width:300px;" name="textfield3" placeholder="确认新密码" >
  </div>
  <div style="clear:both ">
	<div style="width: 10%;float:right; ">
	 </div>
	
     
  </div>
  
  <div  style="clear:both ">
	  <div style="width: 10%;margin-top: 24px;float:right; ">
	 </div>
	  <div style="width: 30%;margin-top: 24px;float:right; ">
	  <button type="submit" class="btn btn-default" style="width: 80px;float:right;float:right "data-loading-text="跳转中……" >确认</button>
	  </div>
	 
	  <input type="hidden" name="MM_insert" value="myform" /></td>
  </div>
  </div>
	  </form>
      </td>
    </tr>

  </table>
</div>


</center>
</body>
</html>
<?php }?>