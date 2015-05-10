<?php require_once('config/tank_config.php'); ?>
<!DOCTYPE html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/tk_style.css" rel="stylesheet" type="text/css" />
<title>WSS</title>
<style type="text/css">
<!--
body,html{ overflow:hidden;  }
-->
</style>
</head>

<body>
<div class="about_div">
<div class="about_bg"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="about_text">
  <tr>
    <td rowspan="2" style="width:98px;"><img src="skin/themes/base/images/about_wss.gif" width="89" height="27" alt="WSS" /></td>
    <td valign="bottom" class="fontbold ">White Shark System</td>
    <td align="right" valign="bottom"><?php echo $multilingual_global_version; ?>: <?php echo $version; ?></td>
  </tr>
  <tr>
    <td>© 2009 - <?php echo date("Y"); ?> WSS Lab.</td>
    <td align="right"><a href="http://www.wssys.net" target="_blank">www.wssys.net</a></td>
  </tr>
</table>

</div>

<div class="about_bottom"><input type="button" value="<?php echo $multilingual_global_action_ok; ?>" onClick="window.close();" class="button"/></div>
</body>
</html>