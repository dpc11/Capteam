<?php require_once('config/tank_config.php'); ?>
<?php
header("Content-type: text/html;charset=utf-8");
$verify = stripslashes(trim($_GET['verify']));
$nowtime = time();

mysql_select_db($database_tankdb, $tankdb); 

$query = mysql_query("select uid,token_exptime from tk_user where status='0' and `token`='$verify'");

$row = mysql_fetch_array($query);




$findsql="SELECT* FROM tk_user WHERE token='$verify'";
$result=mysql_query($findsql);
$email  = mysql_result($result,0,'tk_user_email');

//dail  = $row['tk_user_email'];


if($row){
	
	if($nowtime>$row['token_exptime']){ //24小时
	
		$msg = '该链接已过期';
		 $insertGoTo = "user_registerwitherror.php?&msg=".$msg."&email=".$email;

	
    header(sprintf("Location: %s", $insertGoTo));

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
