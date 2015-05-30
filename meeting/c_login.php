<?php
include("inc.php");

$username=$_COOKIE['user_name'];
$room=$_GET['room_name'];
$key=$_COOKIE['admin_key'];

$administrator=0;
if ($key) if ($key==md5($adminkey.$room)) $administrator=1;

$loggedin=1;
if (!$room||!$username)
	{
		$loggedin=0;
		$message=urlencode("<a href=\"index.php\">你需要一个能缓存 Cookie 的浏览器!</a>");
}

if ($administrator)
{
$change_background=1;
$regularCams=1;
$regularWatch=1;
$privateTextchat=1;
$externalStream=1;
$slideShow=1;
$publicVideosAdd=1;
}

$debug="$key--$adminkey--$room";

//layout obtained by sending in public chat box "/videowhisper layout"; fill in new line between layoutEND markers
$layoutCode=<<<layoutEND
layoutEND;

//replace bad words or expression
$filterRegex=urlencode("(?i)(fuck|cunt)(?-i)");
$filterReplace=urlencode(" ** ");

//message
$welcome=urlencode("欢迎来到房间 $room!<BR><font color=\"#3CA2DE\">&#187;</font> 点击顶部的按钮来激活或者停止相关的功能和面板。<BR><font color=\"#3CA2DE\">&#187;</font> 点击用户列表会弹出权限允许的对用户的操作 <BR><font color=\"#3CA2DE\">&#187;</font> 在文本框中粘贴视频地址、网页地址、邮箱或者其他链接，将会自动解析为可点击的链接。 <BR><font color=\"#3CA2DE\">&#187;</font>文件列表里可以下载每天的日志。");

?>firstParameter=fix&server=<?=$rtmp_server?>&serverRecord=<?=$rtmp_server_record?>&serverAMF=<?=$rtmp_amf?>&serverRTMFP=<?=$rtmfp_server?>&p2pGroup=VideoWhisper&supportRTMP=1&supportP2P=0&alwaysRTMP=1&alwaysP2P=0&room=<?=$room?>&welcome=<?=$welcome?>&username=<?=$username?>&msg=<?=$message?>&visitor=0&loggedin=<?=$loggedin?>&background_url=<?=urlencode("templates/consultation/background.jpg")?>&change_background=<?=$change_background?>&room_limit=30&administrator=<?=$administrator?>&showTimer=1&showCredit=1&disconnectOnTimeout=1&statusInterval=10000&regularCams=<?=$regularCams?>&regularWatch=<?=$regularWatch?>&camWidth=640&camHeight=480&camFPS=15&micRate=11&camBandwidth=65536&showCamSettings=1&advancedCamSettings=1&camMaxBandwidth=131072&configureSource=1&disableVideo=0&disableSound=0&bufferLive=0.1&bufferFull=0.1&bufferLivePlayback=0.1&bufferFullPlayback=0.1&disableBandwidthDetection=0&disableUploadDetection=0&limitByBandwidth=1&videoCodec=H264&codecProfile=main&codecLevel=3.1&soundCodec=Speex&soundQuality=9&files_enabled=1&file_upload=1&file_delete=1&chat_enabled=1&floodProtection=3&writeText=1&privateTextchat=<?=$privateTextchat?>&externalStream=<?=$externalStream?>&slideShow=<?=$slideShow?>&users_enabled=1&publicVideosN=4&publicVideosAdd=<?=$publicVideosAdd?>&publicVideosMax=8&publicVideosW=251&publicVideosH=250&publicVideosX=10&publicVideosY=600&publicVideosColumns=5&publicVideosRows=0&autoplayServer=&autoplayStream=&layoutCode=<?=urlencode($layoutCode)?>&fillWindow=0&filterRegex=<?=$filterRegex?>&filterReplace=<?=$filterReplace?>&generateSnapshots=1&pushToTalk=1&loadstatus=1&verboseLevel=2&videoRecorder=1&internalOpen=0&debugmessage=<?=urlencode($debug)?>