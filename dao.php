<?php 
require_once('config/tank_config.php'); 

/**
* 对项目数据库操作的类
*/
class project_dao
{

    //根据项目id获得项目信息的数据库操作
    public function get_project_by_id($project_id){
        global $tankdb;
        global $database_tankdb;
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

    //获取某个用户负责的项目的数量
    public function get_my_total_project_num($user_id){
        global $tankdb;
        global $database_tankdb;
        mysql_select_db($database_tankdb, $tankdb);
        $query_Recordset_sumtotal = sprintf("SELECT COUNT(*) as count_prj   
                                             FROM tk_project         
                                             WHERE project_to_user = %s", 
                                             GetSQLValueString($user_id, "int"));
        $Recordset_sumtotal = mysql_query($query_Recordset_sumtotal, $tankdb) or die(mysql_error());
        $row_Recordset_sumtotal = mysql_fetch_assoc($Recordset_sumtotal);
        $my_totalprj=$row_Recordset_sumtotal['count_prj'];
        return $my_totalprj;
    }


}


/**
* 对用户信息数据库操作类
*/
class user_dao 
{
    //根据用户id获得用户信息
    public function get_user_by_userid($userid){
        global $tankdb;
        global $database_tankdb;
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
        global $tankdb;
        global $database_tankdb;
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
        global $tankdb;
        global $database_tankdb;
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


/**
* 对团队的数据库操作
*/
class team_dao 
{
    //设置某一个用户在某一个项目中的权限
    public function set_user_authority($uid,$pid,$ulimit){
        global $tankdb;
        global $database_tankdb;
        $modmemSQL="UPDATE tk_team SET tk_team_ulimit=$ulimit WHERE  tk_team_uid=$uid and tk_team_pid=$pid";//修改权限
        mysql_select_db($database_tankdb, $tankdb);
        $Result2 = mysql_query($modmemSQL, $tankdb) or die(mysql_error());
    }

    //获得某一个用户在这个项目里的权限
    public function get_user_authority($uid,$pid){
        global $tankdb;
        global $database_tankdb;
        $user_authority =1; 
        
        mysql_select_db($database_tankdb, $tankdb);
        $query_team =  "SELECT * FROM tk_team WHERE tk_team_uid=$uid and tk_team_pid=$pid";  
        $team = mysql_query($query_team, $tankdb) or die(mysql_error());
        $row_team = mysql_fetch_assoc($team);
        //获得权限
        $user_authority = $row_team['tk_team_ulimit'];

        return $user_authority;
    }

}


/**
* 对个人日程的数据库操作
*/
class schedule_dao
{
    //获取个人事件的数据
    public function get_person_events($userid){
        $sql = "select * from tk_schedule where uid='$userid'";
        $query = mysql_query($sql);
        while($row=mysql_fetch_array($query)){
            if($row['is_allday'] ==0){
                $allday = FALSE;
            }else{
                $allday = TRUE;
            }
            $data[] = array(
            'id' => $row['id'],
            'title' => $row['name'],
            'start' => $row['start_time'],
            'end' => $row['end_time'],
            'url' => $row['url'],
            'color' => '#008573',
            'allDay' => $allday
            );
        }
        return $data;
    }

    //获取团队事件的数据
    public function get_team_events($project_id){
        //获得user数据库操作类
        $user_dao_obj = new user_dao();
        //获得该项目的所有成员
        $user_arr = $user_dao_obj->get_user_select_by_project($project_id);
        foreach($user_arr as $key => $val){ 
            //获得用户id
            $userid = $val['uid'];
            //获得用户在本项目中的任务信息
            $projectid=56;
            $sql = "select * from tk_task where csa_to_user=$userid and csa_project=56";
            // $sql = "select * from tk_task where csa_to_user=$userid and csa_project=56";
            $query = mysql_query($sql);
            while($row=mysql_fetch_array($query)){
                $data[] = array(
                    'id' => $row['tid'],
                    'title' => '[任务]-['.$val['name'].']'.$row['csa_text'],
                    'start' => $row['csa_plan_et'],
                    'end' => $row['csa_plan_et'],
                    'url' => $row['url'],
                    'allDay' => TRUE,
                    'color' => '#1874CD'
                );
            }
            // //获得用户不在本项目中的任务信息
            // $sql = "select * from tk_task where csa_to_user='$userid' and '$csa_project'<>$project_id";
            // $query = mysql_query($sql);
            // while($row=mysql_fetch_array($query)){
            //     $data[] = array(
            //         'id' => $row['tid'],
            //         'title' => '[其他项目任务]-['.$val['name'].']'.$row['csa_text'],
            //         'start' => $row['csa_plan_et'],
            //         'end' => $row['csa_plan_et'],
            //         'url' => $row['url'],
            //         'allDay' => TRUE,
            //         'color' => '#104E8B'
            //     );
            // }
            //获得用户的个人日程
            $sql = "select * from tk_schedule where uid='$userid'";
            $query = mysql_query($sql);
            while($row=mysql_fetch_array($query)){
                if($row['is_allday'] ==0){
                    $allday = FALSE;
                }else{
                    $allday = TRUE;
                }
                $data[] = array(
                'id' => $row['id'],
                'title' => '[个人]-['.$val['name'].']'.$row['name'],
                'start' => $row['start_time'],
                'end' => $row['end_time'],
                'url' => $row['url'],
                'color' => '#008573',
                'allDay' => $allday
                );
            }


        }    

        return $data;
    }


}





?>
