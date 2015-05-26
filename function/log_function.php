<?php 
//活动和我有关的所有日志信息
function get_logs($user_id,$where,$startday,$endday,$orderlist,$sortlist){

    global $tankdb;
    global $database_tankdb;
    $log_arr = array();
if($sortlist=='project'){
	mysql_select_db($database_tankdb, $tankdb);
	$query_Recordset_project = sprintf("SELECT id, project_name,project_text, project_start, project_end, project_to_user, project_lastupdate, project_create_time FROM tk_project inner join tk_team on tk_team.tk_team_pid=tk_project.id WHERE tk_team_uid = %s AND tk_team_del_status=1 $where ORDER BY tk_project.project_name %s",GetSQLValueString($user_id, "int"),
							GetSQLValueString($orderlist, "defined", $orderlist, "NULL"));
	$Recordset_project = mysql_query($query_Recordset_project, $tankdb) or die(mysql_error());
	$row_Recordset_project = mysql_fetch_assoc($Recordset_project);
	do{
    	$project_id = $row_Recordset_project['id'];
        //根据项目id找到对应的项目log
        mysql_select_db($database_tankdb, $tankdb);
        $query_Recordset_log = sprintf("SELECT * FROM tk_log WHERE ((tk_log_class=1 and tk_log_type= %s ) or (tk_log_class=4 and tk_log_type in (SELECT distinct docid FROM tk_document WHERE tk_document.tk_doc_pid in (SELECT id FROM tk_project WHERE project_del_status=1 and tk_project.id = %s ) and tk_document.tk_doc_del_status=1 and tk_doc_create !=0))) and (tk_log_time <=%s AND tk_log_time >=%s) ", 
							GetSQLValueString($project_id, "int"),
							GetSQLValueString($project_id, "int"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($startday , "text"));
        $Recordset_log1 = mysql_query($query_Recordset_log, $tankdb) or die(mysql_error()); 
        while($row_Recordset_log1 = mysql_fetch_assoc($Recordset_log1)){
        	$log_arr[$row_Recordset_log1['logid']]['id'] =  $row_Recordset_log1['logid'];
        	$log_arr[$row_Recordset_log1['logid']]['user'] =  $row_Recordset_log1['tk_log_user'];
        	$log_arr[$row_Recordset_log1['logid']]['action'] =  $row_Recordset_log1['tk_log_action'];
        	$log_arr[$row_Recordset_log1['logid']]['time'] =  $row_Recordset_log1['tk_log_time'];
        	$log_arr[$row_Recordset_log1['logid']]['type'] =  $row_Recordset_log1['tk_log_type'];//代表对应的项目、阶段、任务的id
        	$log_arr[$row_Recordset_log1['logid']]['class'] =  $row_Recordset_log1['tk_log_class'];//1代表项目，2代表阶段，3代表任务，4代表文件操作
        	$log_arr[$row_Recordset_log1['logid']]['pid'] =  get_pid_by_option($row_Recordset_log1['tk_log_class'],$row_Recordset_log1['tk_log_type']);
			//根据项目找到对应的阶段
			mysql_select_db($database_tankdb, $tankdb);
			$query_stage ="SELECT * FROM tk_stage WHERE  tk_stage_pid= $project_id and tk_stage_delestatus = 1";
			$stageRS = mysql_query($query_stage, $tankdb) or die(mysql_error());
			$row_stage = mysql_fetch_assoc($stageRS);

			do{
				$stage_id = $row_stage['stageid'];
				//根据阶段id找到对应的阶段log
				mysql_select_db($database_tankdb, $tankdb);
				$query_Recordset_log = sprintf("SELECT * FROM tk_log WHERE tk_log_class=2 and tk_log_type =%s   
				and (tk_log_time <=%s AND tk_log_time >=%s) ", 
												 GetSQLValueString($stage_id, "int"),
								GetSQLValueString($endday , "text"),
								GetSQLValueString($startday , "text"));
				$Recordset_log2 = mysql_query($query_Recordset_log, $tankdb) or die(mysql_error());  

				while($row_Recordset_log2 = mysql_fetch_assoc($Recordset_log2)){
					$log_arr[$row_Recordset_log2['logid']]['id'] =  $row_Recordset_log2['logid'];
					$log_arr[$row_Recordset_log2['logid']]['user'] =  $row_Recordset_log2['tk_log_user'];
					$log_arr[$row_Recordset_log2['logid']]['action'] =  $row_Recordset_log2['tk_log_action'];
					$log_arr[$row_Recordset_log2['logid']]['time'] =  $row_Recordset_log2['tk_log_time'];
					$log_arr[$row_Recordset_log2['logid']]['type'] =  $row_Recordset_log2['tk_log_type'];//代表对应的项目、阶段、任务的id
					$log_arr[$row_Recordset_log2['logid']]['class'] =  $row_Recordset_log2['tk_log_class'];//1代表项目，2代表阶段，3代表任务，4代表文件操作
					$log_arr[$row_Recordset_log2['logid']]['pid'] =  get_pid_by_option($row_Recordset_log2['tk_log_class'],$row_Recordset_log2['tk_log_type']);
					
					mysql_select_db($database_tankdb, $tankdb);
					$query_task ="SELECT * FROM tk_task WHERE  csa_project= $project_id and csa_del_status = 1";
					$taskRS = mysql_query($query_task, $tankdb) or die(mysql_error());
					$row_task = mysql_fetch_assoc($taskRS);
					do{
						$task_id = $row_task['tid'];
						//根据阶段id找到对应的阶段log
						mysql_select_db($database_tankdb, $tankdb);
						$query_Recordset_log = sprintf("SELECT * FROM tk_log WHERE tk_log_class=3 and tk_log_type= %s  
						and (tk_log_time <=%s AND tk_log_time >=%s) ", 
														 GetSQLValueString($task_id, "int"),
										GetSQLValueString($endday , "text"),
										GetSQLValueString($startday , "text"));
						$Recordset_log3 = mysql_query($query_Recordset_log, $tankdb) or die(mysql_error());   
						while($row_Recordset_log3 = mysql_fetch_assoc($Recordset_log3)){
							$log_arr[$row_Recordset_log3['logid']]['id'] =  $row_Recordset_log3['logid'];
							$log_arr[$row_Recordset_log3['logid']]['user'] =  $row_Recordset_log3['tk_log_user'];
							$log_arr[$row_Recordset_log3['logid']]['action'] =  $row_Recordset_log3['tk_log_action'];
							$log_arr[$row_Recordset_log3['logid']]['time'] =  $row_Recordset_log3['tk_log_time'];
							$log_arr[$row_Recordset_log3['logid']]['type'] =  $row_Recordset_log3['tk_log_type'];//代表对应的项目、阶段、任务的id
							$log_arr[$row_Recordset_log3['logid']]['class'] =  $row_Recordset_log3['tk_log_class'];//1代表项目，2代表阶段，3代表任务，4代表文件操作
							$log_arr[$row_Recordset_log3['logid']]['pid'] =  get_pid_by_option($row_Recordset_log3['tk_log_class'],$row_Recordset_log3['tk_log_type']);
						}       
					}while($row_task = mysql_fetch_assoc($taskRS));
				}        
			}while($row_stage = mysql_fetch_assoc($stageRS));
        }
	}while($row_Recordset_project = mysql_fetch_assoc($Recordset_project));
}else if($sortlist=='tk_log_user'){
	mysql_select_db($database_tankdb, $tankdb);
	$query_Recordset2 = sprintf("SELECT  * FROM tk_user WHERE tk_user_del_status=1 AND tk_user.uid in ( select distinct tk_team_uid from tk_team,tk_project WHERE tk_team_del_status=1 AND project_del_status=1 AND tk_team_pid in (SELECT id FROM tk_project inner join tk_team on tk_team.tk_team_pid=tk_project.id WHERE tk_team_uid = %s AND project_del_status=1 AND tk_team_del_status=1 ) ) ORDER BY  tk_display_name ASC",GetSQLValueString($user_id, "int"));

	$Recordset2 = mysql_query($query_Recordset2, $tankdb) or die(mysql_error());
	$row_Recordset2 = mysql_fetch_assoc($Recordset2);
	do{
				$uid = $row_Recordset2['uid'];
				mysql_select_db($database_tankdb, $tankdb);
				$query_Recordset_log = sprintf("SELECT * FROM tk_log WHERE tk_log_user = %s  
				and (tk_log_time <=%s AND tk_log_time >=%s)  and ((tk_log_class=1 and tk_log_type in (SELECT id FROM tk_project WHERE project_del_status=1 $where )) or (tk_log_class=4 and tk_log_type in (SELECT distinct docid FROM tk_document WHERE tk_document.tk_doc_pid in (SELECT id FROM tk_project WHERE project_del_status=1 $where) and tk_document.tk_doc_del_status=1 and tk_doc_create !=0)) or (tk_log_class=2 and tk_log_type in (SELECT stageid FROM tk_stage,tk_project WHERE tk_stage.tk_stage_pid=tk_project.id and tk_stage.tk_stage_delestatus=1 and tk_project.project_del_status=1 $where ))  or (tk_log_class=3 and tk_log_type in (SELECT tid FROM tk_task,tk_project WHERE tk_task.csa_project=tk_project.id and tk_task.csa_del_status=1 and tk_project.project_del_status=1  $where )))
								 
								ORDER BY %s %s", 
												 GetSQLValueString($uid, "int"),
								GetSQLValueString($endday , "text"),
								GetSQLValueString($startday , "text"),
								GetSQLValueString($sortlist, "defined", $sortlist, "NULL"),
								GetSQLValueString($orderlist, "defined", $orderlist, "NULL"));
					$Recordset_log = mysql_query($query_Recordset_log, $tankdb) or die(mysql_error());   
					while($row_Recordset_log = mysql_fetch_assoc($Recordset_log)){
					$log_arr[$row_Recordset_log['logid']]['id'] =  $row_Recordset_log['logid'];
					$log_arr[$row_Recordset_log['logid']]['user'] =  $row_Recordset_log['tk_log_user'];
					$log_arr[$row_Recordset_log['logid']]['action'] =  $row_Recordset_log['tk_log_action'];
					$log_arr[$row_Recordset_log['logid']]['time'] =  $row_Recordset_log['tk_log_time'];
					$log_arr[$row_Recordset_log['logid']]['type'] =  $row_Recordset_log['tk_log_type'];//代表对应的项目、阶段、任务的id
					$log_arr[$row_Recordset_log['logid']]['class'] =  $row_Recordset_log['tk_log_class'];//1代表项目，2代表阶段，3代表任务，4代表文件操作
					$log_arr[$row_Recordset_log['logid']]['pid'] =  get_pid_by_option($row_Recordset_log['tk_log_class'],$row_Recordset_log['tk_log_type']);
				}
	}while($row_Recordset2 = mysql_fetch_assoc($Recordset2));
}else{
	
		mysql_select_db($database_tankdb, $tankdb);
        $query_Recordset_log = sprintf("SELECT * FROM tk_log WHERE (tk_log_time <=%s AND tk_log_time >=%s) and  tk_log_user in (SELECT  uid FROM tk_user WHERE tk_user_del_status=1 AND tk_user.uid in ( select distinct tk_team_uid from tk_team,tk_project WHERE tk_team_del_status=1 AND project_del_status=1 AND tk_team_pid in (SELECT id FROM tk_project inner join tk_team on tk_team.tk_team_pid=tk_project.id WHERE tk_team_uid = %s AND project_del_status=1 AND tk_team_del_status=1  $where ) ))  and ((tk_log_class=1 and tk_log_type in (SELECT id FROM tk_project WHERE project_del_status=1 $where )) or (tk_log_class=4 and tk_log_type in (SELECT distinct docid FROM tk_document WHERE tk_document.tk_doc_pid in (SELECT id FROM tk_project WHERE project_del_status=1 $where) and tk_document.tk_doc_del_status=1 and tk_doc_create !=0)) or (tk_log_class=2 and tk_log_type in (SELECT stageid FROM tk_stage,tk_project WHERE tk_stage.tk_stage_pid=tk_project.id and tk_stage.tk_stage_delestatus=1 and tk_project.project_del_status=1 $where ))  or (tk_log_class=3 and tk_log_type in (SELECT tid FROM tk_task,tk_project WHERE tk_task.csa_project=tk_project.id and tk_task.csa_del_status=1 and tk_project.project_del_status=1  $where )))
							 
							ORDER BY %s %s", 							
							GetSQLValueString($endday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($user_id, "int"),
							GetSQLValueString($sortlist, "defined", $sortlist, "NULL"),
							GetSQLValueString($orderlist, "defined", $orderlist, "NULL"));

        $Recordset_log = mysql_query($query_Recordset_log, $tankdb) or die(mysql_error());   
        while($row_Recordset_log = mysql_fetch_assoc($Recordset_log)){
        	$log_arr[$row_Recordset_log['logid']]['id'] =  $row_Recordset_log['logid'];
        	$log_arr[$row_Recordset_log['logid']]['user'] =  $row_Recordset_log['tk_log_user'];
        	$log_arr[$row_Recordset_log['logid']]['action'] =  $row_Recordset_log['tk_log_action'];
        	$log_arr[$row_Recordset_log['logid']]['time'] =  $row_Recordset_log['tk_log_time'];
        	$log_arr[$row_Recordset_log['logid']]['type'] =  $row_Recordset_log['tk_log_type'];//代表对应的项目、阶段、任务的id
        	$log_arr[$row_Recordset_log['logid']]['class'] =  $row_Recordset_log['tk_log_class'];//1代表项目，2代表阶段，3代表任务，4代表文件操作
        	$log_arr[$row_Recordset_log['logid']]['pid'] =  get_pid_by_option($row_Recordset_log['tk_log_class'],$row_Recordset_log['tk_log_type']);
		}
}
    return $log_arr;
}

function get_pid_by_option($class,$type){
	global $tankdb;
    global $database_tankdb;
	mysql_select_db($database_tankdb, $tankdb);
	if($class==1){
		$query_Recordset_log = "select tk_project.project_name from tk_project where id=".$type;
	}else if($class==2){
		$query_Recordset_log = "select tk_project.project_name from tk_project,tk_stage where tk_project.id=tk_stage.tk_stage_pid and tk_stage.stageid=".$type;
	}else if($class==3){
		$query_Recordset_log = "select tk_project.project_name from tk_project,tk_task where tk_project.id=tk_task.csa_project and tk_task.tid=".$type;
	}else if($class==4){
		$query_Recordset_log = "select tk_project.project_name from tk_project,tk_document where tk_project.id=tk_document.tk_doc_pid and tk_document.docid=".$type;
	}
	$Recordset_log = mysql_query($query_Recordset_log, $tankdb) or die(mysql_error()); 
	$row_Recordset_log = mysql_fetch_assoc($Recordset_log);	
	
return $row_Recordset_log['project_name'];
}
?>