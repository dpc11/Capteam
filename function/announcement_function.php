<?php
	$version = "1.3.3b";
	$maxRows = 30;
	$tasklevel = 0;
	mysql_select_db($database_tankdb,$tankdb);

    function get_teamleader_nums($uid){
		global $tankdb;
		global $database_tankdb;
		  
		$query_user ="SELECT count(*) FROM tk_team where tk_team_uid=".$uid ." and tk_team_del_status=1 and tk_team_ulimit=3";
		$userRS = mysql_query($query_user, $tankdb) or die(mysql_error());
		$nums = mysql_fetch_assoc($userRS);
		
		return $nums;
	}
	
    function get_teamleader_lists($uid){
		global $tankdb;
		global $database_tankdb;
		  
		$query_user ="SELECT tk_project.project_name,tk_team_pid FROM tk_team,tk_project where tk_project.id=tk_team_pid and tk_team_uid=".$uid ." and tk_team_del_status=1 and tk_team_ulimit=3";
		$userRS = mysql_query($query_user, $tankdb) or die(mysql_error());

		return $userRS;
	}
?>