<style type="text/css">
<!--
a {
	color: #57AD01;
}
input {
	border: 1px solid #CCC;
	color: #666;
	font-weight: normal;
}

input:focus
{
    background-color: #FFFFE0;
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
.info
{
	text-align:left;
	padding: 10px;
	margin: 10px;
	background-color: #F3FFCE;
	border: 1px dotted #390;
}

.warning
{
		color: #F53;
		font-size: 17px;
}

-->
</style>

<title>Video Consultation by VideoWhisper.com</title>
<BODY onLoad="document.forms.form1.button.focus()">
<div style="background-color:#333; padding:10px">
<a href="http://www.videowhisper.com/?p=Video+Consultation"><img src="templates/consultation/logo.png" alt="Online Video Consultation Software" width="196" height="28" border="0"></a></div>
<p>
 <?php
$room=$_POST['room'];
$r=$_GET['r'];
$key=$_GET['key'];

include_once("incsan.php");
sanV($room);
sanV($r);
sanV($key);

if ($r)
{
	?>
<div class="info">
  <strong>Who are you? </strong>
<form id="form1" name="form1" method="post" action="video.php">
  I am
  <input name="username" type="text" id="username" value="Guest" size="12" maxlength="16" />.
  <input name="r" type="hidden" id="r" value="<?=$r?>" />
  <input name="key" type="hidden" id="key" value="<?=$key?>" />
  <br>
  <br>
  <input type="submit" name="button" id="button" value="Enter Video Consultation"  onclick="this.disabled=true; this.value='Loading...'; form1.submit();"/>
</form>
</div>
<div class="info">
  <p><strong>Instructions</strong></p>
  <?php include_once("flash_detect.php"); ?>
  <p><img src="images/headphones.png" alt="Headphones" width="48" height="48" align="absmiddle">To avoid echo make sure your microphone is not pointed to your speakers or  use headphones. <br>
  <img src="images/settings.png" alt="Settings" width="48" height="48" align="absmiddle">Allow flash to send your stream and select the right video and audio devices you want to use:</p>
  <table width="100%" border="0">
    <tr valign="top">
      <td><img src="images/flashsettings.png" alt="Use Headphones"></td>
      <td><p>        When the webcam panel is opened, flash will ask you if you want to start streaming your camera and microphone. Click in this order:<br>
          <strong>1</strong>. <u>Allow,</u> to enable streaming your webcam and microphone.<br>
          <strong>2</strong>. <u>Remember</u>, so you don't get asked about this each time.<br>
          <strong>3</strong>. <u>Close</u>, to close this dialog and start chatting.</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>          Flash settings will also show for changing camera or microphone device. <br>
        </p>
        <p>Hardware selection can be started in 2 ways:<br>
          a.
From the chat application by clicking the panel with your webcam an clicking webcam or microhone icon and title.<br>
b. Right
click flash and select Settings... in the popup menu.
Click bottom icons to see different setting panels (i.e. the webcam icon on the right for webcam selection).</p>
<p>        Depending on  computer hardware and installed drivers multiple audio video devices can be available including internal/external cameras, tuners, virtual screen sharing drivers. If you don't know which one  is the webcam you want to use, just try each item in the list. </p></td>
    </tr>
  </table>
</div>
    <?php
}
elseif ($room)
{
	include("inc.php");

	if ($room=="Consultation") $room="Consultation_".base_convert((time()-1225500000).rand(0,10),10,36);
	$url="http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];
	$roomlink=$url."?r=".urlencode($room);

?>   <div class="info">
      <p>Step 2:
        <strong>Instant Video Consultation Room
        <em>
  <?=$room?>
        </em>Created! </strong>
        <SCRIPT LANGUAGE="JavaScript">
function copytext(theField) {
var tempval=eval("document."+theField);
tempval.focus();
tempval.select();
textrange=tempval.createTextRange()
textrange.execCommand("Copy");
}
        </script>
</p>
      </p>
      <form name="linkform" id="linkform" method="post">
        <div align="center">
  <p align="left">
  <u>Room Access Link</u><br>
    <input name="linktext" id="linktext" type="text" value="<?=$roomlink?>
" size="85" maxlength="200" readonly="readonly" />
  <input onClick="copytext('linkform.linktext')" id="button" type="button" value="Select" name="cpy">
  </p>
  <p align="left">1. Send the link above to invite people by email or instant message to this video consultation!<br />
    2. <a href="<?=$roomlink."&key=".md5($adminkey.$room)?>" target="_blank">Click here to access the room</a> as admin and wait for the other parties to join. You can also send this key link to other moderators.</p>
</div>
</form></div>
<?php
}else
{
	?>
			<script language="JavaScript">
			function censorName()
			{
				document.adminForm.room.value = document.adminForm.room.value.replace(/^[\s]+|[\s]+$/g, '');
				document.adminForm.room.value = document.adminForm.room.value.replace(/[^0-9a-zA-Z_\-]+/g, '-');
				document.adminForm.room.value = document.adminForm.room.value.replace(/\-+/g, '-');
				document.adminForm.room.value = document.adminForm.room.value.replace(/^\-+|\-+$/g, '');
				if (document.adminForm.room.value.length>2) return true;
				else return false;
			}
			</script>
	<div class="info">
<p>Step 1: <strong>Create instantly a Video Consultation room</strong></p>
<form id="adminForm" name="adminForm" method="post" action="index.php" onSubmit="return censorName()">
  <input name="room" type="text" id="room" value="Consultation" size="20" maxlength="64" onChange="censorName()"/>
  <input type="submit" name="button" id="button" value="Create" onclick="this.disabled=true; this.value='Loading...'; adminForm.submit();" />
    <?php
include("settings.php");
if (strstr($rtmp_server, "://localhost/")) echo "<P class='warning'>Warning: You are using a localhost based rtmp address ( $rtmp_server ). Unless you are just testing this with a rtmp server on your own computer, make sure you fill a <a href='http://www.videowhisper.com/?p=RTMP+Applications'>compatible rtmp address</a> in settings.php.</P>";
?>
</form></div>
    <?php

   include_once("clean_older.php");

}

?>
