<?php
$r=$_GET['r'];
$key=$_POST['key'];
$username=$_GET['u'];

include_once("incsan.php");
sanV($username);
sanV($r);
sanV($key);

if ($key) setcookie("admin_key",urlencode($key),time()+50000);
setcookie("user_name",urlencode($username),time()+50000);
setcookie("room_name",urlencode($r),time()+50000);

$swfurl="consultation.swf?room=" . urlencode($r);
$bgcolor="#333333";
$baseurl="";
$wmode="transparent";
?>
	
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Video Consultation by VideoWhisper.com</title>
<style type="text/css">
<!--
a {
	color: #57AD01;
}

body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 15px;
	color: #666;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	letter-spacing: -1px;
}

-->
</style>
</head>
<body bgcolor="#223333">
<center>
<div id="videowhisperConsultation">
<object width="100%" height="100%">
<param name="movie" id="movie" value="<?=$swfurl?>" /><param name="bgcolor" value="<?=$bgcolor?>" /><param name="salign" value="lt" /><param name="scale" value="noscale" /><param name="allowFullScreen" value="true" /><param name="allowscriptaccess" value="always" /> <param name="base" value="<?=$baseurl?>" /> <param name="wmode" value="<?=$wmode?>" /> <embed name="videowhisper_chat" width="100%" height="100%" scale="noscale" salign="lt" src="<?=$swfurl?>" bgcolor="<?=$bgcolor?>" base="<?=$baseurl?>" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="<?=$wmode?>"></embed>
</object>
<noscript>
<p align=center>
<a href="http://www.videowhisper.com/?p=Video+Consultation"><img src="templates/consultation/logo.png" alt="Online Video Consultation and Presentation Software" width="196" height="28" border="0"> Online Video Consultation and Presentation Software</a></p>
<p align="center"><strong>This content requires the Adobe Flash Player:
<a href="http://get.adobe.com/flashplayer/">Get Latest Flash</a></strong>!</p>
</noscript>
</div>		
</center>
</body>
</html>
