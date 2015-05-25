<?php require_once('config/tank_config.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

$errormsg=false;
$loginUsername="";
$password="";
$loginFormAction = $_SERVER['PHP_SELF'];
$MM_redirectLoginSuccess="index.php";
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
  $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
}
if (isset($_SESSION['PrevUrl'])) {
  $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
}

if (isset($_POST['textfield'])) {
  $loginUsername=$_POST['textfield'];
  $password=$_POST['textfield2'];
  $tk_password =  md5(crypt($password,substr($password,0,2))); 
  

  mysql_select_db($database_tankdb, $tankdb);  	
  $LoginRS__query=sprintf("SELECT tk_display_name, uid, tk_user_lastuse,status FROM tk_user WHERE tk_user_del_status =1 AND  tk_user_email=%s AND tk_user_pass=%s ",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($tk_password, "text"));    
  $LoginRS = mysql_query($LoginRS__query, $tankdb) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
   
  if ($loginFoundUser) {	
	$loginStrDisplayname  = mysql_result($LoginRS,0,'tk_display_name');
	$loginStrpid  = mysql_result($LoginRS,0,'uid');
	$loginStrlast  = mysql_result($LoginRS,0,'tk_user_lastuse');
	
   if(mysql_result($LoginRS,0,'status')==0){//未激活
   
   }else {
	   
	$_SESSION['MM_Displayname'] = $loginStrDisplayname;	
	$_SESSION['MM_uid'] = $loginStrpid;	
	$_SESSION['MM_last'] = $loginStrlast;
	
	  header("Location: " . "index.php" );
	  exit;
    }
  }
  else {
    $errormsg=true;
  }
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
 
	var r = 1;
	
	$(window).load(function()
	{

	if(window.screen.width!=1920||window.screen.height!=1080){
		r = window.screen.width/1920
		$("body").css("min-width",1300*r+"px");  
		$("#contain_other").css("min-width",1300*r+"px");  
		$("#contain_other").css("min-height",480*r+"px"); 
		
		document.getElementById("contain_other").style.display="block";
		document.getElementById("frame_div").width=1300*r+"px";
		document.getElementById("frame_div").height=480*r+"px";		
		
		$("#headerlink").css("-webkit-transform","scale(" + r + ")"); 
		$("#headerlink").css("-webkit-transform-origin","0 0");  
		$("#headerlink").css("width",document.getElementById("foot_top").clientWidth/r+"px");
		
		$("#foot_div").css("float","left");  
		$("#foot_div").css("-webkit-transform","scale(" + r + ")"); 
		$("#foot_div").css("-webkit-transform-origin","bottom left"); 
		$("#foot_div").css("width",document.getElementById("foot_top").clientWidth/r+"px");
		
		$("#foot_top").css("min-height",(document.getElementById("headerlink").clientHeight+document.getElementById("centerdiv").clientHeight+60)+"px"); 
		
		document.getElementById("contain_other").style.height=($(window).height()-126)+"px";

		if(document.getElementById("contain_other").clientHeight>document.getElementById("frame_div").clientHeight){
		document.getElementById("contain_other").style.paddingTop=(document.getElementById("contain_other").clientHeight-document.getElementById("frame_div").clientHeight)/2+"px";
		}
		
		$(window).resize();
	}else{
		$("body").css("min-width","1200px"); 
		$("#containdiv").css("min-width","1200px"); 
		document.getElementById("containdiv").style.display="block";
		document.getElementById("foot_div").style.display="block";
		J.check.regform('form1');
		$(window).resize();
	}
	});
$(window).resize(function()
{	
	if(window.screen.width!=1920||window.screen.height!=1080){
	
		r = window.screen.width/1920 ; 
		
		$("#headerlink").css("-webkit-transform","scale(" + r + ")"); 
		$("#headerlink").css("-webkit-transform-origin","0 0");  
		$("#headerlink").css("width",document.getElementById("foot_top").clientWidth/r+"px");
		
		$("#foot_div").css("float","left");  
		$("#foot_div").css("-webkit-transform","scale(" + r + ")"); 
		$("#foot_div").css("-webkit-transform-origin","bottom left"); 
		$("#foot_div").css("width",document.getElementById("foot_top").clientWidth/r+"px");
		
		$("#foot_top").css("min-height",(document.getElementById("headerlink").clientHeight+document.getElementById("centerdiv").clientHeight+60)+"px"); 
		
		document.getElementById("contain_other").style.height=($(window).height()-126)+"px";

		if(document.getElementById("contain_other").clientHeight>document.getElementById("frame_div").clientHeight){
		document.getElementById("contain_other").style.paddingTop=(document.getElementById("contain_other").clientHeight-document.getElementById("frame_div").clientHeight)/2+"px";
		}else{
			document.getElementById("contain_other").style.paddingTop=0+"px";
		}
		
		//$("body").css("height",(126/r+document.getElementById("contain_other").clientHeight)*r+"px"); 
		
		document.getElementById("foot_div").style.visibility="visible ";
		
		window.scrollTo(document.body.scrollWidth,0);
		
	}else{
		
		document.getElementById("foot_div").style.visibility="visible ";
		document.getElementById("outdiv").style.height = ( $(window).height()-186) +'px';
		document.getElementById("innerdiv").style.paddingTop = (document.getElementById("outdiv").clientHeight-300*1.2)/2 +'px'; 

		$("#foot_top").css("min-height",document.getElementById("headerlink").clientHeight+document.getElementById("centerdiv").clientHeight+60+"px"); 
		$("#foot_div").css("margin-top","-60px"); 
		
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
		
	
		window.scrollTo(document.body.scrollWidth,0);
	}
});

</script>

</head>

<body >
<div  id="foot_top" style="height:100%; width:100%; padding-bottom:60px;">
	<div class="topbar" id="headerlink" >
		<div class="logo" id="logo" ><a href="index.php" class="logourl" >&nbsp;</a></div>
	</div>
	<center id="centerdiv">
		<div id="containdiv" style="display:none;">
			<div id="outdiv" style="min-width:1200px;min-height:500px;height:500px;">
				<div id="innerdiv" style="width:980.167px;-webkit-transform: scale( 1.2 );-webkit-transform-origin: 0 0;height:300px;" >
					<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%" align="center">
						<tr>
							<td  style="width:30%">
								<div class="ping_logo"></div>
							</td>
							<td style="width:10%;">&nbsp;</td>
						  
							<td style="width:40%;min-width:100px;">
								<div style="width:90%;" >
									<legend  style="border-bottom-width: 3px;font-weight:bold;font-size:20px;">&nbsp;&nbsp;用户登录</legend>
								</div>		
								<?php  if($errormsg==true){ ?>
									<div  style="width:5%;float:left;" > &nbsp;</div>
									<div id="errormsg"class="ui-state-error ui-corner-all" style="width:90%;float:left;margin-bottom:8px;" > 
											<div style="width:11%;float:left;margin-top:10px;text-align:center;" >
											<span class="ui-icon ui-icon-alert"style="margin:0 auto;" ></span>
											</div>
											<div  style="width:89%;float:left;padding-top:4px;padding-bottom:4px;">
												<span>您输入的密码和账户名不匹配，请重新输入。<br>或者您忘记了密码？</span>
											</div>
									</div>
								<?php }	?>
								<form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
									<div style="padding-left:15px;" >
										<div class="form-group">
											<label class="beauty-label" id="textfield_label"  for="textfield" style="font-size:17px;font-weight:bold;"><?php echo $multilingual_userlogin_username; ?>&nbsp;&nbsp; ：&nbsp;&nbsp;</label>
											<input type="text" class="form-control" id="textfield"  style="width:300px;" name="textfield" placeholder="邮箱" value="<?php echo $loginUsername; ?>"/>
										</div>
						  
										<div class="form-group">
											<label class="beauty-label" for="textfield2" id="textfield2_label"  style="font-size:17px;font-weight:bold;">密&nbsp;&nbsp;&nbsp;&nbsp;码&nbsp;&nbsp; ：&nbsp;&nbsp;</label>
											<input type="password" class="form-control" name="textfield2"  style="width:300px;" id="textfield2" placeholder="密码" value="<?php echo $password; ?>"/>
										</div>
										<div style="clear:both ">
											<div style="width: 10%;float:right; "></div>
											<div style="width: 30%;float:right; ">
												<a class="beauty-label" href="head.php">忘记密码？</a>
											</div>
										 
										</div>
						  
										<div  style="clear:both ">
											<div style="width: 10%;margin-top: 24px;float:right; "> </div>
											<div style="width: 30%;margin-top: 24px;float:right; ">
												<button type="button" class="btn btn-default" style="width: 60px;float:right; " onclick="register();"><?php echo $multilingual_user_register; ?></button>
											</div>
											<div style="width: 30%;margin-top: 24px;float:right; ">
												<button type="submit" id="login" class="btn btn-default" style="width: 60px;float:right; "><?php echo $multilingual_userlogin_login; ?></button>
											</div>
										</div>
									</div>
								</form>
							</td>
							<td style="width:20%;">&nbsp;</td>
						</tr>
					</table>
				</div>
			</div>
			<span id="username" style="z-index:3;position:absolute;-webkit-transform: scale( 1.2 );-webkit-transform-origin: 0 0;min-width:150px;"></span>
			<span id="passwordid"  style="z-index:3;position:absolute;-webkit-transform: scale( 1.2 );-webkit-transform-origin: 0 0;min-width:150px;"></span>

			<input  class="form-control"   type="text"  id="temp_textfield4_4" name="temp_textfield4_4" style="width:300px;z-index:3;position:absolute;-webkit-transform: scale( 1.2 );-webkit-transform-origin: 0 0;" onblur='changemsg("temp_textfield4_4","textfield");' placeholder="邮箱"  value="<?php echo $loginUsername; ?>" />
			<input  class="form-control"   type="password"  id="temp_textfield5_5" name="temp_textfield5_5" style="width:300px;z-index:3;position:absolute;-webkit-transform: scale( 1.2 );-webkit-transform-origin: 0 0;" onblur='changepsd("temp_textfield5_5","textfield2");' placeholder="密码" value="<?php echo $password; ?>" >

		</div>
		<div id="contain_other" align="center" style="display:none;overflow:hidden;height:100%">
             <iFrame  id="frame_div" src="user_login_contain.php" scrolling="no"  frameborder="no">
             </iFrame>
		</div>
	</center>
</div> 	
<center>
	<div class="foot" id="foot_div" style="visibility:hidden;" >
		<div class="wss_title" id="foot_title" style="font-size: 22px;">
			<div class="wss_ver" id="capteamfoot">
            © 2014 - 2015 Capteam 大学生团队协作管理平台 &nbsp;&nbsp;|&nbsp;&nbsp; 北京交通大学软件学院
			</div>
		</div>
	</div>
</center> 	
</body>
</html>
