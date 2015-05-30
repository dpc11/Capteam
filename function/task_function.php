<?php
	$version = "1.3.3b";
	$maxRows = 30;
	$tasklevel = 0;
	mysql_select_db($database_tankdb,$tankdb);

    //获得任务id获得对应的task值
    function get_task_by_id($tid){
        global $tankdb;
        global $database_tankdb;
        mysql_select_db($database_tankdb, $tankdb);
        $query_task =  sprintf("SELECT * FROM tk_task WHERE tid = %s",GetSQLValueString($tid, "int")); 
        $task = mysql_query($query_task, $tankdb) or die(mysql_error());
        $row_task = mysql_fetch_assoc($task);

        $taskinfo->id = $tid;
        $taskinfo->from = $row_task['csa_from_user'];
        $taskinfo->to = $row_task['csa_to_user'];
        $taskinfo->project = $row_task['csa_project'];
        $taskinfo->project_stage = $row_task['csa_project_stage'];
        $taskinfo->text = $row_task['csa_text'];
        $taskinfo->priority = $row_task['csa_priority'];
        $taskinfo->plan_st = $row_task['csa_plan_st'];
        $taskinfo->plan_et = $row_task['csa_plan_et'];
        $taskinfo->plan_hour = $row_task['csa_plan_hour'];
        $taskinfo->description = $row_task['csa_description'];
        $taskinfo->status = $row_task['csa_status'];
        $taskinfo->check_time = $row_task['csa_check_time'];
        $taskinfo->check_context = $row_task['csa_check_context'];
        $taskinfo->link = $row_task['csa_linl'];
        $taskinfo->tag = $row_task['csa_tag'];
        $taskinfo->last_update = $row_task['csa_last_update'];
        $taskinfo->leader_grade = $row_task['csa_leader_grade'];
        $taskinfo->final_grade = $row_task['csa_final_grade'];
        $taskinfo->document_id = $row_task['csa_document_id'];
        $taskinfo->commint_time = $row_task['csa_commit_time'];
        $taskinfo->del_status = $row_task['csa_del_status'];
        $taskinfo->testto = $row_task['csa_testto'];

        return $taskinfo;
    }
	
	//add task
	function add_task( $ccuser, $fuser, $tuser, $projectid, $stage_id, $text, $priority, $start, $end, $hour, $status, $tag,$csa_description ) {
		global $tankdb;
		global $database_tankdb;
		global $multilingual_log_addtask;
		$timenow=date('Y-m-d H:i:s',time());
		$insertSQL = sprintf("INSERT INTO tk_task (csa_testto, csa_from_user, csa_to_user, csa_project, csa_project_stage, csa_text, csa_priority, csa_plan_st, csa_plan_et, csa_plan_hour, csa_tag,csa_description,csa_status,csa_last_update) VALUES (%s, %s, %s, %s, %s ,%s, %s, %s, %s, %s, %s, $csa_description, %s ,'$timenow')",
						   GetSQLValueString($ccuser, "text"),
						   GetSQLValueString($fuser, "int"),
						   GetSQLValueString($tuser, "int"),
						   GetSQLValueString($projectid, "int"),
						   GetSQLValueString($stage_id, "int"),
						   GetSQLValueString($text, "text"),
						   GetSQLValueString($priority, "text"),
						   GetSQLValueString($start, "text"),
						   GetSQLValueString($end, "text"),
						   GetSQLValueString($hour, "text"),
						   GetSQLValueString($tag, "text"),
						   GetSQLValueString($status, "int"));
						   echo $insertSQL;
		mysql_select_db($database_tankdb, $tankdb);
		$Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());
	  
		$newID = mysql_insert_id();
		return $newID;
	}

?>