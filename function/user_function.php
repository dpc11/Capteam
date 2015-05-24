<?php
	$version = "1.3.3b";
	$maxRows = 30;
	$tasklevel = 0;
	mysql_select_db($database_tankdb,$tankdb);

	//添加项目成员时，获得所有的用户信息
	function get_all_user_select($uid) {
		global $tankdb;
		global $database_tankdb;
		  
		$query_user ="SELECT * FROM tk_user where tk_user_del_status=1 and uid !=0 and  uid !=".$uid ."  ORDER BY CONVERT(tk_display_name USING gbk )";
		$userRS = mysql_query($query_user, $tankdb) or die(mysql_error());
		  
		return $userRS;
	}
	
	function get_user($userid, $channel ="default"){
		global $tankdb;
		global $database_tankdb;

		$query_touser =  sprintf("SELECT * FROM tk_user WHERE uid = %s AND tk_user_del_status=1",
							   GetSQLValueString($userid, "int"));  
		$touser = mysql_query($query_touser, $tankdb) or die(mysql_error());
		$row_touser = mysql_fetch_assoc($touser);

		$userinfo->name = $row_touser["tk_display_name"];
		$userinfo->email = $row_touser["tk_user_email"];
			if($channel == "infopage"){
			  $userinfo->remark = $row_touser["tk_user_remark"];  
			  $userinfo->phone = $row_touser["tk_user_contact"];  
			}

		return $userinfo;
	}
	//根据用户id获得用户信息
	function get_user_by_userid($userid){
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
    function get_user_select_by_project($prjid) {
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
        $user_arr[$row_user['uid']]['score'] =  $row_user['tk_team_score'];
        } while ($row_user = mysql_fetch_assoc($userRS));     
    
        return $user_arr;
    }


    //获得所有的用户信息
    function get_all_user() {
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

?>