<?php
$version = "1.3.3b";
$maxRows = 30;
$tasklevel = 0;
mysql_select_db($database_tankdb,$tankdb);

//获得所有的用户信息
function get_all_user_select() {
global $tankdb;
global $database_tankdb;
  
$query_user ="SELECT * FROM tk_user ORDER BY CONVERT(tk_display_name USING gbk )";
// $query_user ="SELECT * 
// FROM tk_user 
// inner join tk_team on tk_team.tk_team_uid=tk_user.uid 
// WHERE tk_team.tk_team_pid = $prjid ORDER BY CONVERT(tk_display_name USING gbk )";
$userRS = mysql_query($query_user, $tankdb) or die(mysql_error());
$row_user = mysql_fetch_assoc($userRS);
 
$user_arr = array ();
do { 

$user_arr[$row_user['uid']]['uid'] =  $row_user['uid'];
$user_arr[$row_user['uid']]['name'] =  $row_user['tk_display_name'];
$user_arr[$row_user['uid']]['email'] =  $row_user['tk_user_email'];
$user_arr[$row_user['uid']]['phone_num'] =  $row_user['tk_user_contact'];
} while ($row_user = mysql_fetch_assoc($userRS));     
    
return $user_arr;
}


?>