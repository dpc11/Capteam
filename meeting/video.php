<?php
$r=$_GET['r'];
$key=$_GET['key'];
$username=$_GET['u'];

include_once("incsan.php");
sanV($username);
sanV($r);
sanV($key);

if ($key) setcookie("admin_key",urlencode($key),time()+50000);
setcookie("user_name",urlencode($username),time()+50000);
setcookie("room_name",urlencode($r),time()+50000);

$swfurl="consultation.swf?room=" . urlencode($r);
$bgcolor="#626262";
$baseurl="";
$wmode="transparent";
?>
	
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Capteam Meeting</title>
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

<body bgcolor="#444444">
    <center>
        <div id="video">
            <object width="100%" height="120%">
                <param name="movie" id="movie" value="<?=$swfurl?>" />
                <param name="bgcolor" value="<?=$bgcolor?>" />
                <param name="salign" value="lt" />
                <param name="scale" value="noscale" />
                <param name="allowFullScreen" value="true" />
                <param name="allowscriptaccess" value="always" />
                <param name="base" value="<?=$baseurl?>" />
                <param name="wmode" value="<?=$wmode?>" />
                <embed name="videowhisper_chat" width="100%" height="100%" scale="noscale" salign="lt" src="<?=$swfurl?>" bgcolor="<?=$bgcolor?>" base="<?=$baseurl?>" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="<?=$wmode?>"></embed>
            </object>

            <noscript>
                <p align=center>
                    <img src="../images/ui/logo.png" width="300" height="60" border="0">
                </p>
                <p align=center>
                    <a href="index.php" style="font-size: 1.8em;">大学生团队协作管理工具</a>
                </p>
                <p align="center" style="color: #dddddd; font-size: 1.4em">视频会议需要最新的 Adobe Flash Player :
                    <a href="http://get.adobe.com/flashplayer/">获取最新 Flash</a>
                </p>
            </noscript>

        </div>
    </center>
</body>

</html>