<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session_admin.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS - <?php echo $multilingual_projectsub_title; ?></title>
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script type="text/javascript" src="srcipt/js.js"></script>
<link href="skin/themes/base/custom.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript">
<!--
function GP_popupConfirmMsg(msg) { //v1.0
  document.MM_returnValue = confirm(msg);
}
//-->
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
</script>
</head>

<body>
<?php require('admin_head.php'); ?>
<table border="0" cellspacing="5" cellpadding="12" width="100%">
  <tr>
    <td width="200px" class="set_menu_bg" valign="top"><?php require('setting_menu.php'); ?></td>
	<td >
<div class="ui-widget"  style="margin:auto; width:580px;">
<div class="ui-state-highlight fontsize-s" style=" padding: 5px; width:100%;"> 
				<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
	<strong class="font_big glink"><?php echo $multilingual_error_oursite; ?></strong> <br /><br />
    
    <li class="feature01">
<span class="fontbold "><?php echo $multilingual_projectmem_title; ?></span><br />
<?php echo $multilingual_projectmem_text; ?>
</li>
    </div>
    </div>
</div>
	</td>
  </tr>
</table>
<?php require('foot.php'); ?>
</body>
</html>