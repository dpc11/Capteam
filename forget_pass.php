<?php require_once('config/tank_config.php'); ?>
<?php


?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<style type="text/css">
.demo{width:400px; margin:40px auto 0 auto; min-height:250px;}
.demo h3{line-height:24px; text-align:center; color:#360; font-size:16px}
.demo p{line-height:30px; padding:4px}
.demo p span{margin-left:6px; color:#f30}
.input{width:240px; height:24px; padding:2px; line-height:24px; border:1px solid #999}
.btn{position: relative;overflow: hidden;display:inline-block;*display:inline;padding:4px 20px 4px;font-size:16px;line-height:20px;*line-height:22px;color:#fff;text-align:center;vertical-align:middle;cursor:pointer;background-color:#5bb75b;border:1px solid #cccccc;border-color:#e6e6e6 #e6e6e6 #bfbfbf;border-bottom-color:#b3b3b3;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;}
</style>
<script type="text/javascript" src="../js/jquery.js"></script>

</head>

<body>

<?php require_once('head.php'); ?>
<div id="main">
  
   <div class="demo">
   	<form id="reg" action="user_findpass.php" method="post">
        	<p>用户可以通过邮箱找回密码</p>
        	<p><strong>输入您注册的电子邮箱，找回密码：</strong></p>
        	<p><input type="text" class="input" name="email" id="email"><span id="chkmsg"></span></p><!--填写用户邮箱-->
            <p><input type="submit" class="btn" id="sub_btn" value="提 交"></p>
	</form>
	</div>
 <br/><div class="ad_76090"><script src="/js/ad_js/bd_76090.js" type="text/javascript"></script></div><br/>
</div>


<p id="stat"><script type="text/javascript" src="/js/tongji.js"></script></p>
</body>
</html>