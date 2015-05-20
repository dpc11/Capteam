<?php 
//活动和我有关的所有日志信息
function get_logs($user_id){
    global $tankdb;
    global $database_tankdb;
    $log_arr = array();

    //找到和我相关的项目
    mysql_select_db($database_tankdb, $tankdb);
    $query_Recordset_project = sprintf("SELECT * FROM tk_project WHERE project_to_user = %s and project_del_status = 1", 
                                         GetSQLValueString($user_id, "int"));
    $Recordset_project = mysql_query($query_Recordset_project, $tankdb) or die(mysql_error());   
    $row_Recordset_project = mysql_fetch_assoc($Recordset_project);
    do{
    	$project_id = $row_Recordset_project['id'];
        
        //根据项目id找到对应的项目log
        mysql_select_db($database_tankdb, $tankdb);
        $query_Recordset_log = sprintf("SELECT * FROM tk_log WHERE tk_log_type = %s and ( tk_log_class = 1 or tk_log_class = 4)", 
                                         GetSQLValueString($project_id, "int"));
        $Recordset_log = mysql_query($query_Recordset_log, $tankdb) or die(mysql_error());   
        while($row_Recordset_log = mysql_fetch_assoc($Recordset_log)){
        	$log_arr[$row_Recordset_log['logid']]['id'] =  $row_Recordset_log['logid'];
        	$log_arr[$row_Recordset_log['logid']]['user'] =  $row_Recordset_log['tk_log_user'];
        	$log_arr[$row_Recordset_log['logid']]['action'] =  $row_Recordset_log['tk_log_action'];
        	$log_arr[$row_Recordset_log['logid']]['time'] =  $row_Recordset_log['tk_log_time'];
        	$log_arr[$row_Recordset_log['logid']]['type'] =  $row_Recordset_log['tk_log_type'];//代表对应的项目、阶段、任务的id
        	$log_arr[$row_Recordset_log['logid']]['class'] =  $row_Recordset_log['tk_log_class'];//1代表项目，2代表阶段，3代表任务，4代表文件操作
        	$log_arr[$row_Recordset_log['logid']]['pid'] =  $project_id;
        }

        //根据项目找到对应的阶段
        mysql_select_db($database_tankdb, $tankdb);
        $query_stage ="SELECT * FROM tk_stage WHERE  tk_stage_pid= $project_id and tk_stage_delestatus = 1";
        $stageRS = mysql_query($query_stage, $tankdb) or die(mysql_error());
        $row_stage = mysql_fetch_assoc($stageRS);
        do{
        	$stage_id = $row_stage['stageid'];
        	//根据阶段id找到对应的阶段log
            mysql_select_db($database_tankdb, $tankdb);
	        $query_Recordset_log = sprintf("SELECT * FROM tk_log WHERE tk_log_type = %s and tk_log_class = 2 ", 
	                                         GetSQLValueString($stage_id, "int"));
	        $Recordset_log = mysql_query($query_Recordset_log, $tankdb) or die(mysql_error());   
	        while($row_Recordset_log = mysql_fetch_assoc($Recordset_log)){
	        	$log_arr[$row_Recordset_log['logid']]['id'] =  $row_Recordset_log['logid'];
	        	$log_arr[$row_Recordset_log['logid']]['user'] =  $row_Recordset_log['tk_log_user'];
	        	$log_arr[$row_Recordset_log['logid']]['action'] =  $row_Recordset_log['tk_log_action'];
	        	$log_arr[$row_Recordset_log['logid']]['time'] =  $row_Recordset_log['tk_log_time'];
	        	$log_arr[$row_Recordset_log['logid']]['type'] =  $row_Recordset_log['tk_log_type'];//代表对应的项目、阶段、任务的id
	        	$log_arr[$row_Recordset_log['logid']]['class'] =  $row_Recordset_log['tk_log_class'];//1代表项目，2代表阶段，3代表任务，4代表文件操作
	        	$log_arr[$row_Recordset_log['logid']]['pid'] =  $project_id;
	        }        
        }while($row_stage = mysql_fetch_assoc($stageRS));

        //根据项目找到对应的任务
        mysql_select_db($database_tankdb, $tankdb);
        $query_task ="SELECT * FROM tk_task WHERE  csa_project= $project_id and csa_del_status = 1";
        $taskRS = mysql_query($query_task, $tankdb) or die(mysql_error());
        $row_task = mysql_fetch_assoc($taskRS);
        do{
        	$task_id = $row_task['tid'];
        	//根据阶段id找到对应的阶段log
            mysql_select_db($database_tankdb, $tankdb);
	        $query_Recordset_log = sprintf("SELECT * FROM tk_log WHERE tk_log_type = %s and tk_log_class = 2 ", 
	                                         GetSQLValueString($task_id, "int"));
	        $Recordset_log = mysql_query($query_Recordset_log, $tankdb) or die(mysql_error());   
	        while($row_Recordset_log = mysql_fetch_assoc($Recordset_log)){
	        	$log_arr[$row_Recordset_log['logid']]['id'] =  $row_Recordset_log['logid'];
	        	$log_arr[$row_Recordset_log['logid']]['user'] =  $row_Recordset_log['tk_log_user'];
	        	$log_arr[$row_Recordset_log['logid']]['action'] =  $row_Recordset_log['tk_log_action'];
	        	$log_arr[$row_Recordset_log['logid']]['time'] =  $row_Recordset_log['tk_log_time'];
	        	$log_arr[$row_Recordset_log['logid']]['type'] =  $row_Recordset_log['tk_log_type'];//代表对应的项目、阶段、任务的id
	        	$log_arr[$row_Recordset_log['logid']]['class'] =  $row_Recordset_log['tk_log_class'];//1代表项目，2代表阶段，3代表任务，4代表文件操作
	        	$log_arr[$row_Recordset_log['logid']]['pid'] =  $project_id;
	        }       
        }while($row_task = mysql_fetch_assoc($taskRS));

    }while($row_Recordset_project = mysql_fetch_assoc($Recordset_project));
 

    // foreach($log_arr as $key => $val){
    // 	echo $val['id']." " ;
    // 	echo $val['action']." " ;
    // }

    return $log_arr;
}



?>