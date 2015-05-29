  <p>
	<img src="images/flashplayer.png" alt="Flash Player" width="48" height="48" align="absmiddle"> This application requires Flash browser plugin. <script type="text/javascript" src="flash_detect_min.js"> </script>
	<script type="text/javascript">
	
	var updateWarning = false;

	if(FlashDetect.installed)
	{
	document.write("监测到的 Flash版本: " + FlashDetect.major + "."+ FlashDetect.minor + " "); 
	
	
	if(!FlashDetect.versionAtLeast(11, 2))
	{
		alert("检测到 Flash版本太旧，请先升级!"); 
		updateWarning = true;
	}
	
	}
	else
	{
		alert("在该浏览器中未检测到 Flash，无法进行视频会议!"); 
		updateWarning = true;
	}
	
	if (updateWarning)	document.write("<B class=warning>升级到最新版本 Flash Player: <a href=\"http://get.adobe.com/flashplayer/\" target=\"_blank\">http://get.adobe.com/flashplayer/</a> !</B>");
	</script>
  </p>