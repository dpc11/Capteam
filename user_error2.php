<?php require_once('config/tank_config.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/custom.css" rel="stylesheet" type="text/css" />
<title>WSS - <?php echo $multilingual_error_login; ?></title>
</head>

<body>

<p>&nbsp;</p>
<div class="ui-widget" style="margin:auto auto; width:350px;" >
			<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"> 
				<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
				<?php echo $multilingual_error_logintext; ?> <a href="javascript:history.go(-1)"><?php echo $multilingual_global_action_back; ?></a> / <a href="user_login.php"><?php echo $multilingual_error_login; ?></a></p>
			</div>
		</div>
</body>
</html>