<?php
	$version = "1.3.3b";
	$maxRows = 30;
	$tasklevel = 0;
	mysql_select_db($database_tankdb,$tankdb);

    //获得任务id获得对应的task值
    public function get_task_by_id($tid){
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

?>