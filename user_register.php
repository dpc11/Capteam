<?php require_once('config/tank_config.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Capteam- <?php echo $multilingual_register_title; ?></title>
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgcheck.js"></script>
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/lhgdialog.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="srcipt/lhgdialog.js"></script>
<style type="text/css"> 
.pw-strength {clear: both;position: relative;top: 8px;width: 110px;}
.pw-bar{background: url(img/pwd-1.png) no-repeat;height: 9px;overflow: hidden;width: 110px;}
.pw-bar-on{background:  url(img/pwd-2.png) no-repeat; width:0px; height:9px;position: absolute;top: 1px;left: 2px;transition: width .5s ease-in;-moz-transition: width .5s ease-in;-webkit-transition: width .5s ease-in;-o-transition: width .5s ease-in;}
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
	{ name: 'textfield5', mid: 'email_msg', requir: true, type: 'email', warn: '邮箱格式错误' },
	{ name: 'textfield5', mid: 'email_msg', requir: true, type: 'ajax', url: 'page.php',warn: '该邮箱已经注册，请直接登陆' }
];

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
	
window.onload = function()
{
    J.check.regform('myform');
	
    var x= $(for1).offset(); var y=$(h1).offset();
	document.getElementById("textfield1_1_div").style.top=(y.top)+'px';
	document.getElementById("textfield1_1_div").style.left=(x.left+document.getElementById("for1").clientWidth-7)+'px';
    x= $(for3).offset(); y=$(h3).offset();
	document.getElementById("textfield3_3_div").style.top=(y.top)+'px';
	document.getElementById("textfield3_3_div").style.left=(x.left+document.getElementById("for3").clientWidth-7)+'px';
	document.getElementById("keycheck").style.top=(y.top)+'px';
	document.getElementById("keycheck").style.left=(x.left+document.getElementById("for3").clientWidth-7+310)+'px';
	
    x= $(for4).offset(); y=$(h4).offset();
	document.getElementById("textfield4_4_div").style.top=(y.top)+'px';
	document.getElementById("textfield4_4_div").style.left=(x.left+document.getElementById("for4").clientWidth-7)+'px';
    x= $(for5).offset(); y=$(h5).offset();
	document.getElementById("textfield5_5_div").style.top=(y.top)+'px';
	document.getElementById("textfield5_5_div").style.left=(x.left+document.getElementById("for5").clientWidth-7)+'px';
	
	document.getElementById("left_img").style.height=document.getElementById("container").clientHeight+'px';
	document.getElementById("logo").style.marginTop=(document.getElementById("right_table").clientHeight-document.getElementById("logo").clientHeight)/2+'px';
	
}

</script>

</head>
<?php require('head_sub.php'); ?>
<body >
<div  id="container" class="container" style="padding-top:120px; ">
	<div id="left_img" style="float:left;width:45%;">
	 <div  id="logo" class="ping_logo" ></div>
	</div>
	
	<div id="right_table"   class="span8" style="width:55%;float:right;">
						
					<form  role="form" class="form-horizontal" action="" method="post" name="myform" id="myform" >
					
		<fieldset>	
			<div style="width:70%">
			<legend  style="border-bottom-width: 3px;font-weight:bold;font-size:20px;">&nbsp;&nbsp;用户注册</legend>
			</div>
			
						<div id="h1">
						<div  class="control-group">
						<label  class="control-label" id="for1" for="textfield1" style="font-size:17px;"  >&nbsp;&nbsp;&nbsp;&nbsp;昵&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;称&nbsp;&nbsp; ：&nbsp;&nbsp;</label> 
						 
							<input  class="input-xlarge" type="text"  id="textfield1" name="textfield1" style="z-index:2;width:280px;" ><span id="user_name_msg" style="margin-left:10px;"></span>
							
						</div>
						<div style="width: 400px;margin-top: 6px;margin-left:125px;">
						<p class="text-muted" style="font-size:15px;width:400px">最多5个中文字符或10个字母、数字</p>
						</div>
						</div>
						
						<div id="h3">
						<div  class="control-group">					
						<label  class="control-label" id="for3" for="textfield3" style="font-size:17px;" >&nbsp;&nbsp;&nbsp;&nbsp;登陆密码&nbsp;&nbsp; ：&nbsp;&nbsp;</label> 
							<input  class="input-xlarge" type="password" id="textfield3" name="textfield3" style="z-index:2;width:280px;" >
										<span id="password_msg" style="margin-left:10px;"></span>
							<table id="keycheck"  style="z-index:3;position:absolute;left:20;top:40;display:none;">
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
							
						</div>
						<div style="width: 400px;margin-top: 6px;margin-left:125px;font-size:15px;" >
						<p class="text-muted" style="font-size:15px;width:400px"><?php echo $passwordMsg.'"'; ?></p>
						</div>
						</div>
						<div id="h4">
						<div  class="control-group">	 
						<label  class="control-label" id="for4"  for="textfield4" style="font-size:17px;" >&nbsp;&nbsp;&nbsp;&nbsp;确认密码&nbsp;&nbsp; ：&nbsp;&nbsp;</label>
							<input  class="input-xlarge" type="password" id="textfield4"  name="textfield4" style="z-index:2;width:280px;"><span id="re_password_msg" style="margin-left:10px;"></span>
						</div>
						<div style="width: 400px;margin-top: 6px;margin-left:125px;" >
						<p class="text-muted" style="font-size:15px;width:400px">再次输入密码以确保密码无误</p>
						</div>
						</div>
						
						<div id="h5">
						<div  class="control-group">	 
						<label  class="control-label" id="for5"  for="textfield5" style="font-size:17px;" >&nbsp;&nbsp;&nbsp;邮&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;箱 &nbsp;&nbsp;：&nbsp;&nbsp;</label> 
							<input class="input-xlarge" type="text" id="textfield5"  name="textfield5" style="z-index:2;width:280px;" ><span id="email_msg" style="margin-left:10px;"></span>
						</div>
						<div style="width: 400px;margin-top: 6px;margin-left:125px;">
						<p class="text-muted" style="font-size:13px;width:310px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;邮箱注册后不能更改，将作为您的Capteam账号唯一确认您的身份，也可用于找回密码，建议使用163、sina、gmail、QQ邮箱。</p>
						</div>
						</div>
						
						 <div class="form-actions">
						 <button type="button" class="btn btn-default" style="width: 70px;margin-top: 40px;margin-left:180px;margin-right:2px;" onclick="return registeruser('<?php echo $editFormAction;  ?>');" > <?php echo $multilingual_user_register; ?></button>
						 <button type="button" class="btn btn-link" style="width: 150px;margin-top: 40px;" > 已有账号，直接登陆</button>
						</div>	 
					
						<input type="hidden" name="MM_insert" id="MM_insert" value="form1" />
							
						</fieldset> 
					 </form> 
						<div id="textfield1_1_div" style="z-index:3;position:absolute;left:20;top:40;">
							<input  class="form-control"   type="text"  id="temp_textfield1_1" name="temp_textfield1_1" style="width:300px;" onblur='changemsg("temp_textfield1_1","textfield1");'>
						</div>
						<div id="textfield3_3_div" style="z-index:3;position:absolute;left:20;top:40;">
							<input  class="form-control"   type="password"  id="temp_textfield3_3" name="temp_textfield3_3" style="width:300px;" onkeyup="judgepassword();" onblur='changemsg("temp_textfield3_3","textfield3");' />
						</div>
						<div id="textfield4_4_div" style="z-index:3;position:absolute;left:20;top:40;">
							<input  class="form-control"   type="password"  id="temp_textfield4_4" name="temp_textfield4_4" style="width:300px;" onblur='changemsg("temp_textfield4_4","textfield4");' />
						</div>
						<div id="textfield5_5_div" style="z-index:3;position:absolute;left:20;top:40;">
							<input  class="form-control"   type="text"  id="temp_textfield5_5" name="temp_textfield5_5" style="width:300px;" onblur='changemsg("temp_textfield5_5","textfield5");'>
						</div>
				
	  </div>
	  </div>
	  

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