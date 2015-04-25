<?php 
require_once('config/tank_config.php'); 

/**
* 对项目数据库操作的类
*/
class project_dao
{
    global $tankdb;
    global $database_tankdb;
    //根据项目id获得项目信息的数据库操作
    public function get_project_by_id($project_id){
    mysql_select_db($database_tankdb, $tankdb);
    $query_project =  sprintf("SELECT * FROM tk_project WHERE id = %s",GetSQLValueString($project_id, "int"));  
    $project = mysql_query($query_project, $tankdb) or die(mysql_error());
    $row_project = mysql_fetch_assoc($project);
          
    $projectinfo->id = $row_project['id'];
    $projectinfo->name = $row_project['project_name'];
    $projectinfo->text = $row_project['project_text'];
    $projectinfo->start = $row_project['project_start'];
    $projectinfo->end = $row_project['project_end'];
    $projectinfo->leader = $row_project['project_to_user'];
    $projectinfo->lastupdate = $row_project['project_lastupdate'];
    $projectinfo->del_status = $row_project['project_del_status'];
    $projectinfo->create_time = $row_project['project_create_time'];

    return $projectinfo;
    }



}


/**
* 对用户信息数据库操作类
*/
class user_dao 
{
    global $tankdb;
    global $database_tankdb;

    //根据用户id获得用户信息
    public function get_user_by_userid($userid){
        $query_touser =  sprintf("SELECT * FROM tk_user WHERE uid = %s AND tk_user_del_status=1",
                                GetSQLValueString($userid, "int"));  
        $touser = mysql_query($query_touser, $tankdb) or die(mysql_error());
        $row_touser = mysql_fetch_assoc($touser);

        $userinfo->name = $row_touser["tk_display_name"];
        $userinfo->id = $row_touser["uid"];
        $userinfo->user_login = $row_touser["tk_user_login"];
        $userinfo->register = $row_touser["tk_user_registered"];
        $userinfo->contact = $row_touser["tk_user_contact"];
        $userinfo->email = $row_touser["tk_user_email"];
        $userinfo->user_lastuse = $row_touser["tk_user_lastuse"];
        $userinfo->del_status = $row_touser["tk_user_del_status"];
        $userinfo->token_exptime = $row_touser["token_exptime"];
        $userinfo->status = $row_touser["status"];

        return $userinfo;
    }

    //获得和该项目有关的用户信息
    public function get_user_select_by_project($prjid) {
        $query_user ="SELECT * 
        FROM tk_user 
        inner join tk_team on tk_team.tk_team_uid=tk_user.uid 
        WHERE tk_team.tk_team_pid = $prjid ORDER BY CONVERT(tk_display_name USING gbk )";
        $userRS = mysql_query($query_user, $tankdb) or die(mysql_error());
        $row_user = mysql_fetch_assoc($userRS);
 
        $user_arr = array ();
        do { 
        $user_arr[$row_user['uid']]['uid'] =  $row_user['uid'];
        $user_arr[$row_user['uid']]['name'] =  $row_user['tk_display_name'];
        $user_arr[$row_user['uid']]['email'] =  $row_user['tk_user_email'];
        $user_arr[$row_user['uid']]['phone_num'] =  $row_user['tk_user_contact'];
        $user_arr[$row_user['uid']]['ulimit'] =  $row_user['tk_team_ulimit'];
        } while ($row_user = mysql_fetch_assoc($userRS));     
    
        return $user_arr;
    }


    //获得所有的用户信息
    public function get_all_user() {
        $query_user ="SELECT * FROM tk_user ORDER BY CONVERT(tk_display_name USING gbk )";
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

    

}






?>
