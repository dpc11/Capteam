<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session_admin.php'); ?>
<?php
error_reporting(0);
function remote_file_exists($url_file){

$url_file = trim($url_file);
if (empty($url_file)) { return false; }
$url_arr = parse_url($url_file);
if (!is_array($url_arr) || empty($url_arr)){ return false; }

$host = $url_arr['host'];

$path = $url_arr['path'] ."?". $url_arr['query'];
$port = isset($url_arr['port']) ? $url_arr['port'] : "80";

$fp = fsockopen($host, $port, $err_no, $err_str, 1);
if (!$fp){ return false; }

$request_str = "GET ".$path." HTTP/1.1\r\n";
$request_str .= "Host: ".$host."\r\n";
$request_str .= "Connection: Close\r\n\r\n";

fwrite($fp, $request_str);
$first_header = fgets($fp, 1024);
fclose($fp);

if (trim($first_header) == ""){ return false; }
if (!preg_match("/200/", $first_header)){
return false;
}
return true;
}

$str_url = 'http://www.wssys.net/version.txt';
$exits = remote_file_exists($str_url);
if($exits){

$fp=fopen($str_url,"r");
$lastversion="";
while(!feof($fp))
{
$lastversion.=fread($fp,4096);
}
fclose($fp);

}else{
$lastversion = "-1";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS - <?php echo $multilingual_tasktype_title; ?></title>
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/custom.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php require('admin_head.php'); ?>
<table border="0" cellspacing="5" cellpadding="12" width="100%">
  <tr>
    <td width="200px" class="set_menu_bg" valign="top"><?php require('setting_menu.php'); ?></td>
	<td >
<?php if ($lastversion == "-1") { ?>
<div class="update_bg">
    <?php echo $multilingual_version_nonet; ?>
</div>
<?php } else if ($lastversion <= $version) { ?>
<div class="update_bg">
    <?php echo $multilingual_version_yourversion; ?><?php echo $version; ?>. <?php echo $multilingual_version_unew; ?>
</div>
<?php } else { ?>
<div class="update_bg glink">
<?php echo $multilingual_version_yourversion; ?><?php echo $version; ?>. <?php echo $multilingual_version_update; ?>
</div>
<?php } ?>
	</td>
  </tr>
</table>
<?php require('foot.php'); ?>
</body>
</html>