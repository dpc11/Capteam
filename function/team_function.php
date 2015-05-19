<?php
	$version = "1.3.3b";
	$maxRows = 30;
	$tasklevel = 0;
	mysql_select_db($database_tankdb,$tankdb);

	//得到文件夹的父文件夹id
	function get_leader_id($id){

		global $tankdb;
		$query_Recordset_pfilename = sprintf("SELECT * FROM tk_project WHERE docid = %s", GetSQLValueString($id, "int"));
		$Recordset_pfilename = mysql_query($query_Recordset_pfilename, $tankdb) or die(mysql_error());
		$row_Recordset_pfilename = mysql_fetch_assoc($Recordset_pfilename);

		return $row_Recordset_pfilename['tk_doc_parentdocid'];
	}
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
?>