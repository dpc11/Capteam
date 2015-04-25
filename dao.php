<?php 
require_once('config/tank_config.php'); 

class project_dao
{
	//获得项目信息的数据库操作
	public function get_project($project_id){
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
}






?>
