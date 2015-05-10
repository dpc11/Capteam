<?php require_once('config/tank_config.php'); ?>

<!DOCTYPE html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Capteam- <?php echo $multilingual_register_title; ?></title>
<script type="text/javascript" src="js/lhgcore/lhgcore.js"></script>
<script type="text/javascript" src="js/lhgcore/lhgcheck.js"></script>
<link href="css/tk_style.css" rel="stylesheet" type="text/css" />
<link href="css/lhgcore/lhgdialog.css" rel="stylesheet" type="text/css" />
<link href="css/lhgcore/lhgcheck.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap/bootstrap.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="js/jquery/jquery.js"></script>
<script type="text/javascript" src="js/bootstrap/bootstrap.js"></script>
<script type="text/javascript" src="js/lhgcore/lhgdialog.js"></script>
<style type="text/css"> 
.pw-strength {clear: both;position: relative;top: 8px;width: 110px;}
.pw-bar{background: url(images/register/pwd-1.png) no-repeat;height: 9px;overflow: hidden;width: 110px;}
.pw-bar-on{background:  url(images/register/pwd-2.png) no-repeat; width:0px; height:9px;position: absolute;top: 1px;left: 2px;transition: width .5s ease-in;-moz-transition: width .5s ease-in;-webkit-transition: width .5s ease-in;-o-transition: width .5s ease-in;}
.pw-weak .pw-defule{ width:0px;}
.pw-weak .pw-bar-on {width: 60px;}
.pw-medium .pw-bar-on {width: 120px;}
.pw-strong .pw-bar-on {width: 110px;}
.pw-txt {padding-top: 0px;width: 110px;overflow: hidden;}
.pw-txt span {color: #707070;float: left;font-size: 9px;text-align: center;width: 36px;}</style>
<script type="text/javascript" > 

 function judgepassword(){ 
	
 	var enoughRegex = new RegExp("(?=.{6,25}).*", "g"); 
	var strongRegex = new RegExp("(?=.{8,25})((?=.*[A-Z])|(?=.*[a-z]))(?=.*[0-9])(?=.*[\{\}\+\?\[\]=`\-;',\.\/~!@#\$%\^&\*\(\)\|_><:\"]).*$", "g"); 
			
		var mediumRegex = new RegExp("^(?=.{7,25})((((?=.*[A-Z])|(?=.*[a-z]))(?=.*[0-9]))|(((?=.*[A-Z])|(?=.*[a-z]))(?=.*[\{\}\+\?\[\]=`\-;',\.\/~!@#\$%\^&\*\(\)\|_><:\"]))|((?=.*[0-9])(?=.*[\{\}\+\?\[\]=`\-;',\.\/~!@#\$%\^&\*\(\)\|_><:\"]))).*", "g"); 
	
		var smallRegex = new RegExp("((?=.{6,25})|(((?=.*[A-Z])|(?=.*[a-z]))|(?=.*[0-9])|(?=.*[\{\}\+\?\[\]=`\-;',\.\/~!@#\$%\^&\*\(\)\|_><:\"]))).*", "g");	
		//var illegalRegex = new RegExp("(?=.*[^A-Z])(?=.*[^a-z])(?=.*[^0-9])(?=.*[^\{\}\+\?\[\]=`\-;',\.\/~!@#\$%\^&\*\(\)\|_><:\"]).*$", "g"); 
		var JUDGE = new RegExp("(?=.*[A-Z])|(?=.*[a-z])|(?=.*[0-9])|(?=.*[\{\}\+\?\[\]=`\-;',\.\/~!@#\$%\^&\*\(\)\|_><:\"]).*", "g"); 
	
		var v = $('#temp_textfield3_3').val();
		var num = 0, chr='';
        for (var i = 0, j = v.length; i < j; i++) {
            chr = v.charAt(i);
            if (JUDGE.test(chr)) num += 1;
        }
		
		if(num!=v.length){			
			changemsg("temp_textfield3_3","textfield3");
			return false;
			//含有非法字符
		}else if(num>25){			
			//密码数量大于25
			changemsg("temp_textfield3_3","textfield3");
			return false;
		}else if (false == enoughRegex.test($('#temp_textfield3_3').val())) { 
			$('#keycheck').show();
			$('#password_msg').hide();
			$('#level').removeClass('pw-weak'); 
			$('#level').removeClass('pw-medium'); 
			$('#level').removeClass('pw-strong'); 
			$('#level').addClass(' pw-defule'); 
			//document.getElementById("temp_textfield3_3").focus();
			 //密码小于六位的时候，密码强度图片都为灰色 
		} else if (strongRegex.test($('#temp_textfield3_3').val())) { 
			$('#keycheck').show();
			$('#password_msg').hide();
			$('#level').removeClass('pw-weak'); 
			$('#level').removeClass('pw-medium'); 
			$('#level').removeClass('pw-strong'); 
			$('#level').addClass(' pw-strong'); 
			//document.getElementById("temp_textfield3_3").focus();
			 //密码为八位及以上并且字母数字特殊字符三项都包括,强度最强 
		} 
		else if (mediumRegex.test($('#temp_textfield3_3').val())) { 
			$('#keycheck').show();
			$('#password_msg').hide();
			$('#level').removeClass('pw-weak'); 
			$('#level').removeClass('pw-medium'); 
			$('#level').removeClass('pw-strong'); 
			$('#level').addClass(' pw-medium'); 
			//document.getElementById("temp_textfield3_3").focus();
			 //密码为七位及以上并且字母、数字、特殊字符三项中有两项，强度是中等 
		} 
		else if(smallRegex.test($('#temp_textfield3_3').val())){ 
			$('#keycheck').show();
			$('#password_msg').hide();
			$('#level').removeClass('pw-weak'); 
			$('#level').removeClass('pw-medium'); 
			$('#level').removeClass('pw-strong'); 
			$('#level').addClass('pw-weak'); 
			//document.getElementById("temp_textfield3_3").focus();
			 //如果密码为6为及以下，就算字母、数字、特殊字符三项都包括，强度也是弱的 
		} 
		return true; 
	}
	
	function checkpassword1(){//小于6位
		var enoughRegex = new RegExp("(?=.{6,}).*", "g"); 
		if (false == enoughRegex.test($('#textfield3').val())) { 
			$('#keycheck').hide();
			$('#password_msg').show();
			return false;
		} 
		return true;
	}
	
	function checkpassword2(){//大于25位
		var enoughRegex = new RegExp("(?=.{26,}).*", "g"); 
		if (enoughRegex.test($('#textfield3').val())) { 
			$('#keycheck').hide();
			$('#password_msg').show();
			return false;
		} 
		return true;
	}
	function checkpassword3(){//位数正确，含有非法字符
		var strongRegex = new RegExp("(?=.*[A-Z])|(?=.*[a-z])|(?=.*[0-9])|(?=.*[\{\}\+\?\[\]=`\-;',\.\/~!@#\$%\^&\*\(\)\|_><:\"]).*", "g"); 
	
		var v = $('#textfield3').val();
		var num = 0, chr='';
        for (var i = 0, j = v.length; i < j; i++) {
            chr = v.charAt(i);
			chr = chr.replace(/\"/g, "");
            if (strongRegex.test(chr)) num += 1;
        }
		
		if(num!=v.length){
			$('#keycheck').hide();
			$('#password_msg').show();
			return false;
			//含有非法字符
		} 
		return true;
	}

</script>

<?php   

	$email="";
	$passwordMsg="6-25位数字、字母和字符`-=\[];',./~!@#$%^&*()_+|?><:{}";
	
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

function getstyle(DOWN){
	var x= $(DOWN).offset().top;
	var y = $(DOWN).offset().left;
	
	return "position:absolute;left:"+y+";top:"+x;
}
</script>


<script type="text/javascript">

J.check.rules = [
	{ name: 'textfield1', mid: 'user_name_msg', requir: true, type: 'cusfn', cusfunc: 'checkname2()', warn: '昵称过长' },
	{ name: 'textfield1', mid: 'user_name_msg', requir: true, type: 'cusfn', cusfunc: 'checkname1()', warn: '昵称包含非法字符' },
	{ name: 'textfield3', mid: 'password_msg', requir: true, type: 'cusfn', cusfunc: 'checkpassword1()', warn: '密码长度至少为6位' },
	{ name: 'textfield3', mid: 'password_msg', requir: true, type: 'cusfn', cusfunc: 'checkpassword2()', warn: '密码长度至多为25位' },
	{ name: 'textfield3', mid: 'password_msg', requir: true, type: 'cusfn', cusfunc: 'checkpassword3()', warn: '密码中含有非法字符' },
	{ name: 'textfield4', mid: 're_password_msg', requir: true, type: 'match', to: 'textfield3', warn: '两次密码不一致' },
	{ name: 'textfield5', mid: 'email_msg', requir: true, type: 'email|cusfn', warn: '邮箱格式错误|该邮箱已经注册，请直接登陆' },
];

function checkemail(){
	
	$.post('if_email_register.php?email='+document.getElementById("textfield5").value,function(data){
  if(data ==0){
   return true;
  }else{
	  return false;
  }
});
}
function changemsg(UP,DOWN){
	
		document.getElementById(DOWN).focus();
		var contentmsg = document.getElementById(UP).value;
		document.getElementById(DOWN).value=contentmsg;
		document.getElementById(DOWN).blur();

}
        

 function checkname1() {
		var v = document.getElementById("textfield1").value;
        var rx = /[a-z\d]/i, rxcn = /[\u4e00-\u9fff]/, num = 0, chr;
        for (var i = 0, j = v.length; i < j; i++) {
            chr = v.charAt(i);
			chr = chr.replace(/\"/g, "");
            if (rx.test(chr)) num += 1;
            else if (rxcn.test(chr)) num += 2;
            else { 
			return false;
			}
        }
        return true;
    }
	function checkname2() {
		var v = document.getElementById("textfield1").value;
        var rx = /[a-z\d]/i, rxcn = /[\u4e00-\u9fa5]/, num = 0, chr;
        for (var i = 0, j = v.length; i < j; i++) {
            chr = v.charAt(i);
			chr = chr.replace(/\"/g, "");
            if (rx.test(chr)) num += 1;
            else if (rxcn.test(chr)) num += 2;
        }
		if (num > 10) { return false; }
        return true;
    }
	
window.onresize = function()
{	
	//document.getElementById("outofall").style.marginLeft =(document.body.clientWidth-document.getElementById("outofall").clientWidth)/2+'px';
	document.getElementById("outdiv").style.height = (document.body.clientHeight-126) +'px';  
	
	var x= $(textfield1).offset(); 
	document.getElementById("temp_textfield1_1").style.top=(x.top-7)+'px';
	document.getElementById("temp_textfield1_1").style.left=(x.left-2)+'px';
	document.getElementById("temp_textfield1_1").style.width=(document.getElementById("textfield1").clientWidth+6)+'px';
	document.getElementById("ms1").style.top=(x.top-7+35)+'px';
	document.getElementById("ms1").style.left=(x.left)+'px';
	document.getElementById("ms1_text").style.width=(document.getElementById("textfield1").clientWidth+4)+'px';
	
    x= $(textfield3).offset(); 
	document.getElementById("temp_textfield3_3").style.top=(x.top-7)+'px';
	document.getElementById("temp_textfield3_3").style.left=(x.left-2)+'px';
	document.getElementById("temp_textfield3_3").style.width=(document.getElementById("textfield3").clientWidth+6)+'px';
	document.getElementById("ms3").style.top=(x.top-7+35)+'px';
	document.getElementById("ms3").style.left=(x.left)+'px';
	document.getElementById("ms3").style.width=(document.getElementById("textfield3").clientWidth+4)+'px';
	document.getElementById("ms3_text").style.width=(document.getElementById("textfield3").clientWidth+4)+'px';
	
	document.getElementById("keycheck").style.top=(x.top-7)+'px';
	document.getElementById("keycheck").style.left=(x.left+document.getElementById("textfield3").clientWidth+10)+'px';
	document.getElementById("password_msg").style.top=(x.top-7)+'px';
	document.getElementById("password_msg").style.left=(x.left+document.getElementById("textfield3").clientWidth+10)+'px';
	
    x= $(textfield4).offset(); 
	document.getElementById("temp_textfield4_4").style.top=(x.top-7)+'px';
	document.getElementById("temp_textfield4_4").style.left=(x.left-2)+'px';
	document.getElementById("temp_textfield4_4").style.width=(document.getElementById("textfield4").clientWidth+6)+'px';
	document.getElementById("ms4").style.top=(x.top-7+35)+'px';
	document.getElementById("ms4").style.left=(x.left)+'px';
	document.getElementById("ms4").style.width=(document.getElementById("textfield4").clientWidth+4)+'px';
	document.getElementById("ms4_text").style.width=(document.getElementById("textfield4").clientWidth+4)+'px';
	
    x= $(textfield5).offset(); 
	document.getElementById("temp_textfield5_5").style.top=(x.top-7)+'px';
	document.getElementById("temp_textfield5_5").style.left=(x.left-2)+'px';
	document.getElementById("temp_textfield5_5").style.width=(document.getElementById("textfield5").clientWidth+6)+'px';
	document.getElementById("ms5").style.top=(x.top-7+35)+'px';
	document.getElementById("ms5").style.left=(x.left)+'px';
	document.getElementById("ms5_text").style.width=(document.getElementById("textfield5").clientWidth+4)+'px';
	document.getElementById("ms5").style.width=(document.getElementById("textfield5").clientWidth+4)+'px';	
}

window.onload = function()
{
    J.check.regform('myform');
	
document.getElementById("outdiv").style.height = (document.body.clientHeight-126) +'px';  
	
	var x= $(textfield1).offset(); 
	document.getElementById("temp_textfield1_1").style.top=(x.top-7)+'px';
	document.getElementById("temp_textfield1_1").style.left=(x.left-2)+'px';
	document.getElementById("temp_textfield1_1").style.width=(document.getElementById("textfield1").clientWidth+6)+'px';
	document.getElementById("ms1").style.top=(x.top-7+35)+'px';
	document.getElementById("ms1").style.left=(x.left)+'px';
	document.getElementById("ms1_text").style.width=(document.getElementById("textfield1").clientWidth+4)+'px';
	document.getElementById("user_name_msg").style.top=(x.top-10)+'px';
	document.getElementById("user_name_msg").style.left=(x.left+document.getElementById("textfield1").clientWidth+60)+'px';
	
    x= $(textfield3).offset(); 
	document.getElementById("temp_textfield3_3").style.top=(x.top-7)+'px';
	document.getElementById("temp_textfield3_3").style.left=(x.left-2)+'px';
	document.getElementById("temp_textfield3_3").style.width=(document.getElementById("textfield3").clientWidth+6)+'px';
	document.getElementById("ms3").style.top=(x.top-7+35)+'px';
	document.getElementById("ms3").style.left=(x.left)+'px';
	document.getElementById("ms3").style.width=(document.getElementById("textfield3").clientWidth+4)+'px';
	document.getElementById("ms3_text").style.width=(document.getElementById("textfield3").clientWidth+4)+'px';
	
	document.getElementById("keycheck").style.top=(x.top-7)+'px';
	document.getElementById("keycheck").style.left=(x.left+document.getElementById("textfield3").clientWidth+10)+'px';
	document.getElementById("password_msg").style.top=(x.top-7)+'px';
	document.getElementById("password_msg").style.left=(x.left+document.getElementById("textfield3").clientWidth+10)+'px';
	
    x= $(textfield4).offset(); 
	document.getElementById("temp_textfield4_4").style.top=(x.top-7)+'px';
	document.getElementById("temp_textfield4_4").style.left=(x.left-2)+'px';
	document.getElementById("temp_textfield4_4").style.width=(document.getElementById("textfield4").clientWidth+6)+'px';
	document.getElementById("ms4").style.top=(x.top-7+35)+'px';
	document.getElementById("ms4").style.left=(x.left)+'px';
	document.getElementById("ms4").style.width=(document.getElementById("textfield4").clientWidth+4)+'px';
	document.getElementById("ms4_text").style.width=(document.getElementById("textfield4").clientWidth+4)+'px';
	document.getElementById("re_password_msg").style.top=(x.top-7)+'px';
	document.getElementById("re_password_msg").style.left=(x.left+document.getElementById("temp_textfield4_4").clientWidth+10)+'px';
	
    x= $(textfield5).offset(); 
	document.getElementById("temp_textfield5_5").style.top=(x.top-7)+'px';
	document.getElementById("temp_textfield5_5").style.left=(x.left-2)+'px';
	document.getElementById("temp_textfield5_5").style.width=(document.getElementById("textfield5").clientWidth+6)+'px';
	document.getElementById("ms5").style.top=(x.top-7+35)+'px';
	document.getElementById("ms5").style.left=(x.left)+'px';
	document.getElementById("ms5_text").style.width=(document.getElementById("textfield5").clientWidth+4)+'px';
	document.getElementById("ms5").style.width=(document.getElementById("textfield5").clientWidth+4)+'px';	
	document.getElementById("email_msg").style.top=(x.top-7)+'px';
	document.getElementById("email_msg").style.left=(x.left+document.getElementById("temp_textfield5_5").clientWidth+10)+'px';
}

</script>

</head>
<div class="topbar" id="headerlink">
    <div class="logo" ><a href="index.php" class="logourl" >&nbsp;</a></div>
</div>
<body ><center>

<div id="outdiv" style="min-width:1200px;min-height:800px;">
<div style="width:979.167px;-webkit-transform: scale( 1.2,1.2 );" >

<table align="center"  border="0" cellspacing="0" cellpadding="0" ><!-- width:67%;margin-bottom:197.344px; -->
	<tr>
	<td style="width:50%">
	<div  id="logo" class="ping_logo"  style="margin-top:150px;margin-left:80px;"></div>
	</td>
	<td style="width:50%;min-width:490px;" >
	<div >
	<form  role="form" class="form-horizontal" action="" method="post" name="myform" id="myform" >
					
		<fieldset>	
			<div style="width:90%">
			<legend  style="border-bottom-width: 3px;font-weight:bold;font-size:20px;">&nbsp;&nbsp;&nbsp;用户注册</legend>
			</div>
			
						<div id="h1">
						<div  class="control-group">
						<label  class="control-label" id="for1" for="textfield1" style="font-size:17px;"  >&nbsp;&nbsp;&nbsp;&nbsp;昵&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;称&nbsp;&nbsp; ：&nbsp;&nbsp;</label> 
						 
							<input  class="input-xlarge" type="text"  id="textfield1" name="textfield1" style="z-index:2;width:280px;" />
							
						</div>
						</div>
						
						<div id="h3 "style="margin-top: 40px;">
						<div  class="control-group">					
						<label  class="control-label" id="for3" for="textfield3" style="font-size:17px;" >&nbsp;&nbsp;&nbsp;&nbsp;登陆密码&nbsp;&nbsp; ：&nbsp;&nbsp;</label> 
							<input  class="input-xlarge" type="password" id="textfield3" name="textfield3" style="z-index:2;width:280px;" />
							
						</div>
						
						</div>
						<div id="h4" style="margin-top: 64px;">
						<div class="control-group">	 
						<label  class="control-label" id="for4"  for="textfield4" style="font-size:17px;" >&nbsp;&nbsp;&nbsp;&nbsp;确认密码&nbsp;&nbsp; ：&nbsp;&nbsp;</label>
							<input  class="input-xlarge" type="password" id="textfield4"  name="textfield4" style="z-index:2;width:280px;">
						</div>
						</div>
						
						<div id="h5" style="margin-top: 44px;">
						<div  class="control-group">	 
						<label  class="control-label" id="for5"  for="textfield5" style="font-size:17px;" >&nbsp;&nbsp;&nbsp;&nbsp;邮&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;箱 &nbsp;&nbsp;：&nbsp;&nbsp;</label> 
							<input class="input-xlarge" type="text" id="textfield5"  name="textfield5" style="z-index:2;width:280px;" />
						</div>
						
						</div>
												
					
						<input type="hidden" name="MM_insert" id="MM_insert" value="form1" />
							
						</fieldset> 
					 </form> 
						
						</div>
	</td>
	</tr>	
<div style="clear:both"></div> 
	  
	  </table>
	</div></div>  
						<div  id="ms1" style="margin-top: 8px;position:absolute;-webkit-transform: scale( 1.2 );-webkit-transform-origin: 0 0;">
						<p class="text-muted"  id="ms1_text"  style="font-size:15px;width:400px;text-align:left">最多5个中文字符或10个字母、数字</p>
						</div>
	
						<div id="ms3"  style="width: 400px;margin-top:10px;position:absolute;-webkit-transform: scale( 1.2 );-webkit-transform-origin: 0 0;" >
						<p class="text-muted" id="ms3_text" style="font-size:15px;width:400px;text-align:left;word-break:break-all;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $passwordMsg.'"'; ?></p>
						</div>
						<div id="ms4"  style="width: 400px;margin-top:12px;position:absolute;-webkit-transform: scale( 1.2 );-webkit-transform-origin: 0 0;" >
						<p class="text-muted" id="ms4_text"  style="font-size:15px;width:400px;text-align:left">再次输入密码以确保密码无误</p>
						</div>
	
						<div  id="ms5" style="width: 400px;margin-top: 10px;position:absolute;-webkit-transform: scale( 1.2 );-webkit-transform-origin: 0 0;">
						<p class="text-muted"id="ms5_text"  style="font-size:15px;width:300px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;邮箱注册后不能更改，将作为您的Capteam账号唯一确认您的身份，也可用于找回密码，建议使用163、sina、gmail、QQ邮箱。</p>
						
						<div style="margin-top: 20px;float:right">
						 <a  style="margin-top: 7px;width: 130px;float:right;"  href="user_login.php"> 已有账号，直接登陆</a>
						 <button type="button" class="btn btn-default" style="width: 70px;margin-right:10px;float:right;" onclick="return registeruser('<?php echo $editFormAction;  ?>');" > <?php echo $multilingual_user_register; ?></button>
						<div style="clear:both;">  </div>
						</div>
						
						</div>
						
	
	<span id="user_name_msg" style="margin-top:10px;z-index:3;position:absolute;font-size:12px;min-width:125px;-webkit-transform: scale( 1.2 );-webkit-transform-origin: 0 0;"></span>
							
	
							<span id="password_msg" style="margin-top:10px;z-index:3;position:absolute;font-size:12px;display:none;min-width:125px;"></span>
								
							<table id="keycheck"  style="z-index:3;position:absolute;left:20;top:40;display:none;min-width:125px;">
								<tr>    
									<th></th>   
									<td id="level" class="pw-strength" >           	
										<div class="pw-bar"></div>
										<div class="pw-bar-on"></div>
										<div class="pw-txt">
										<span>弱</span>
										<span>中</span>
										<span>强</span>
										</div>
									</td>								
								</tr>
							</table>
							
	<span id="re_password_msg" style="margin-top:10px;z-index:3;position:absolute;font-size:12px;min-width:125px;"></span>
	<span id="email_msg" style="margin-top:10px;z-index:3;position:absolute;font-size:12px;min-width:125px;"></span>
	
	
						<input  class="form-control"   type="text"  id="temp_textfield1_1" name="temp_textfield1_1" style="width:300px;z-index:3;position:absolute;left:20;top:40;-webkit-transform: scale( 1.2 );-webkit-transform-origin: 0 0;" onblur='changemsg("temp_textfield1_1","textfield1");'/>
						
						<input  class="form-control"   type="password"  id="temp_textfield3_3" name="temp_textfield3_3" style="width:300px;z-index:3;position:absolute;left:20;top:40;-webkit-transform: scale( 1.2 );-webkit-transform-origin: 0 0;" onkeyup="judgepassword();" onblur='changemsg("temp_textfield3_3","textfield3");' />
						
						<input  class="form-control"   type="password"  id="temp_textfield4_4" name="temp_textfield4_4" style="width:300px;z-index:3;position:absolute;left:20;top:40;-webkit-transform: scale( 1.2 );-webkit-transform-origin: 0 0;" onblur='changemsg("temp_textfield4_4","textfield4");' />
						
						<input  class="form-control"   type="text"  id="temp_textfield5_5" name="temp_textfield5_5" style="width:300px;z-index:3;position:absolute;left:20;top:40;-webkit-transform: scale( 1.2 );-webkit-transform-origin: 0 0;" onblur='changemsg("temp_textfield5_5","textfield5");'/>
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
</center></body>
</html>