<?php
error_reporting(0);
$hostname_tankdb = "paytonsql.mysql.rds.aliyuncs.com:3306";   //database host 
$database_tankdb = "wss";   //database name
$username_tankdb = "wss";   //mysql user name
$password_tankdb = "capteam";   //mysql password
$tankdb = mysql_connect($hostname_tankdb, $username_tankdb, $password_tankdb) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_query("set names 'utf8'");

require "function/function.class.php";

$language = "cn";
$advsearch = get_item( 'advsearch' );
$outofdate = get_item( 'outofdate' ) ;
?>
<?php require "config/language_$language".".php"; ?>