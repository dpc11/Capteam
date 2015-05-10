<?php require_once('config/tank_config.php'); ?>
<!DOCTYPE html PUBLIC >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/tk_style.css" rel="stylesheet" type="text/css" />
<link href="css/custom.css" rel="stylesheet" type="text/css" />
<title>Capteam - <?php echo $multilingual_error_login; ?></title>
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