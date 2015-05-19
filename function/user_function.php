<?php
$version = "1.3.3b";
$maxRows = 30;
$tasklevel = 0;
mysql_select_db($database_tankdb,$tankdb);

//获得所有的用户信息
function get_all_user_select($uid) {
global $tankdb;
global $database_tankdb;
  
$query_user ="SELECT * FROM tk_user where tk_user_del_status=1 and uid !=0 and  uid !=".$uid ."  ORDER BY CONVERT(tk_display_name USING gbk )";
$userRS = mysql_query($query_user, $tankdb) or die(mysql_error());
  
return $userRS;
}


?>