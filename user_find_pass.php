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
		r = window.screen.height/1080
		$("body").css("min-width",1200*r+"px");
		//$("#centerdiv").css("min-width",1260*r+"px");  
		$("#contain_other").css("min-width",1100*r+"px");  
		$("#contain_other").css("min-height",480*r+"px"); 
		$("#headerlink").css("min-width",1200*r+"px"); 
		
		document.getElementById("contain_other").style.display="block";
		document.getElementById("frame_div").width=1200*r+"px";
		document.getElementById("frame_div").height=480*r+"px";
		
		
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
	//location.reload();
	
	r = window.screen.height/1080 ; 
	
		$("#headerlink").css("-webkit-transform","scale(" + r + ")"); 
		$("#headerlink").css("-webkit-transform-origin","0 0"); 
		$("#headerlink").css("width",document.getElementById("contain_other").clientWidth/r+"px"); 
		 
		$("#foot_div").css("width",document.getElementById("contain_other").clientWidth/r+"px"); 
		$("#foot_div").css("float","left");  
		$("#foot_div").css("-webkit-transform","scale(" + r + ")"); 
		$("#foot_div").css("-webkit-transform-origin","bottom left"); 
		
		document.getElementById("contain_other").style.height=($(window).height()-126)+"px";

		if(document.getElementById("contain_other").clientHeight>document.getElementById("frame_div").clientHeight){
		document.getElementById("contain_other").style.paddingTop=(document.getElementById("contain_other").clientHeight-document.getElementById("frame_div").clientHeight)/2*r+"px";
		}
		// $("body").css("height",(126/r+document.getElementById("contain_other").clientHeight)*r+"px"); 
		
		
		document.getElementById("foot_div").style.visibility="visible ";
		
		window.scrollTo(document.body.scrollWidth,0);
		
	}else{
		
		document.getElementById("foot_div").style.visibility="visible ";
	document.getElementById("outdiv").style.height = ( $(window).height()-186) +'px';
	document.getElementById("innerdiv").style.paddingTop = (document.getElementById("outdiv").clientHeight-300*1.2)/2 +'px'; 

		$("#foot_top").css("min-height",document.getElementById("headerlink").clientHeight+document.getElementById("centerdiv").clientHeight60+"px"); 
		$("#foot_div").css("margin-top","60px"); 
	
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


<div id="contain_other" align="center" style="display:none;overflow:hidden;">
             <div id="innerdiv" style="width:820.833px;-webkit-transform: scale( 1.2 );-webkit-transform-origin: 0 0;height:400px;" >
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%" align="center">
    <tr>
      <td  style="width:40%">
      <div class="ping_logo"></div>
      </td>
	  <td style="width:20%;">&nbsp;</td>
	  
	  <td style="width:40%;min-width:100px;">
	  <div>
			<legend  style="border-bottom-width: 3px;font-weight:bold;font-size:20px;">&nbsp;&nbsp;找回密码</legend>
		</div>		
		
		<div  style="width:5%;float:left;" > 
		&nbsp;
		</div>
			
		


      <div class="demo">
   	<form id="reg" action="user_findpass.php" method="post">
        	
        	<p><strong style="font-size:15px">输入您注册的电子邮箱，找回密码：</strong></p>
        	<p><input type="text" style="
    width: 150px;
    height: 30px;
"class="input" name="email" id="email"><span id="chkmsg"></span></p><!--填写用户邮箱-->
            <p><input type="submit" class="btn" id="sub_btn" value="提 交"></p>
	</form>
	</div>
      </td>
    </tr>

  </table>
</div>
			
			 </div>


</center> 	
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
