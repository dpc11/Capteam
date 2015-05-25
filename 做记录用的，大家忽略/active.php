<?php require_once('config/tank_config.php'); ?>
<?php
header("Content-type: text/html;charset=utf-8");
$verify = stripslashes(trim($_GET['verify']));
$nowtime = time();


$query = mysql_query("select uid,token_exptime from tk_user where status='0' and `token`='$verify'");
$row = mysql_fetch_array($query);
if($row){
	
	if($nowtime>$row['token_exptime']){ //30min
	
		$msg = '您的激活有效期已过，请登录您的帐号重新发送激活邮件.';
	}else{
		
		mysql_query("update tk_user set status=1 where uid=".$row['uid']);
		//if(mysql_affected_rows($link)!=1) die(0);
		$msg = '激活成功！马上为您转入登录页面……';
 }
$url = "http://localhost/Capteam/user_login.php";   
?> 
<html>     
<head>     
<meta http-equiv="refresh" content="2;url=<?php echo $url; ?>">     
</head>     

</html> 
	<?php
}else{
	$msg = 'error.';	
}

echo $msg;

?>
