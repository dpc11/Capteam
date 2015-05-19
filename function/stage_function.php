<?php
	$version = "1.3.3b";
	$maxRows = 30;
	$tasklevel = 0;
	mysql_select_db($database_tankdb,$tankdb);

	//新建插入日志
	function insert_log($stageid,$myid){

		global $tankdb;
		$log_action = "创建了阶段";
		$insert_log=sprintf("INSERT INTO tk_log(tk_log_user,tk_log_action,tk_log_type,tk_log_class)
				  VALUES($myid,%s,$stageid,2)",
				GetSQLValueString($log_action,"text"));
		$Recordset_pfilename = mysql_query($insert_log, $tankdb) or die(mysql_error());
		$log_id = mysql_insert_id();

		return $log_id;
	}

	//编辑插入日志
	function update_log($stageid,$myid){

		global $tankdb;
		$log_action = "编辑了阶段";
		$update_log=sprintf("INSERT INTO tk_log(tk_log_user,tk_log_action,tk_log_type,tk_log_class)
				VALUES($myid,%s,$stageid,2)",
				GetSQLValueString($log_action,"text"));
		$Recordset_pfilename = mysql_query($update_log, $tankdb) or die(mysql_error());
		$log_id = mysql_insert_id();

	  return $log_id;
	}

	function get_stage_log($stageid)
	{
		global $tankdb;
		$selStageLog="SELECT * FROM tk_log,tk_user WHERE tk_log_type=$stageid AND tk_log_class=2 AND tk_log_user=uid";
		$StageLog_Result=mysql_query($selStageLog, $tankdb) or die(mysql_error());

		return $StageLog_Result;
	}

	//得到阶段的文件编号
	function get_stage_folder($stageid)
	{
		global $tankdb;
		$selStageFolder="SELECT * FROM tk_stage WHERE  stageid=$stageid";
		$StageFolder_Result=mysql_query($selStageFolder, $tankdb) or die(mysql_error());
		$row = mysql_fetch_assoc($StageFolder_Result);

		return $row['tk_stage_folder_id'];
	}
    //根据项目id获得stages
    public function get_stages($project_id)
    {
        global $tankdb;
        global $database_tankdb;
        $query_stage ="SELECT * FROM tk_stage WHERE  tk_stage_pid= $project_id";
        $stageRS = mysql_query($query_stage, $tankdb) or die(mysql_error());
        $row_stage = mysql_fetch_assoc($stageRS);
 
        $stage_arr = array ();
        do { 
        $stage_arr[$row_stage['sid']]['sid'] =  $row_stage['stageid'];
        $stage_arr[$row_stage['sid']]['pid'] =  $row_stage['tk_stage_pid'];
        $stage_arr[$row_stage['sid']]['title'] =  $row_stage['tk_stage_title'];
        $stage_arr[$row_stage['sid']]['start_time'] =  $row_stage['tk_stage_st'];
        $stage_arr[$row_stage['sid']]['end_time'] =  $row_stage['tk_stage_et'];
        } while ($row_stage = mysql_fetch_assoc($userRS));     
    
        return $stage_arr;
    }
?>