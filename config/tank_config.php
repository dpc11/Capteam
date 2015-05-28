<?php
error_reporting(0);
//$hostname_tankdb = "paytonsql.mysql.rds.aliyuncs.com:3306";   //database host
$hostname_tankdb = "172.31.34.191:3306";   //database host
date_default_timezone_set('PRC');
$database_tankdb = "wss";   //database name
$username_tankdb = "root";   //mysql user name
$password_tankdb = "capteam";   //mysql password
$tankdb = mysql_connect($hostname_tankdb, $username_tankdb, $password_tankdb) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_query("set names 'utf8'"); 
require "function/config_function.php";

$language = "cn";
$advsearch = get_item( 'advsearch' );
$outofdate = get_item( 'outofdate' ) ;

require "config/language_".$language.".php"; 
?>