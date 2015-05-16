<?php

//遍历数据库，更新任务状态
mysql_select_db($database_tankdb, $tankdb);
$all_task = sprintf("SELECT csa_plan_st,csa_plan_et,csa_status,tid from tk_task where csa_project in (SELECT tk_team_pid FROM tk_team where tk_team_del_status=1 and tk_team_uid=%d ) and csa_del_status=1 ", GetSQLValueString($_SESSION["MM_uid"], "int"));
$tasks = mysql_query($all_task, $tankdb) or die(mysql_error());
$row_tasks = mysql_fetch_assoc($tasks);
do { 
	if(strtotime($row_tasks["csa_plan_st"])>=date("Y-m-d")&&$row_tasks["csa_status"]==6){
		mysql_select_db($database_tankdb, $tankdb);
		$updateSQL = sprintf("UPDATE tk_task SET csa_status=1 WHERE tid=%d",                       GetSQLValueString($row_tasks["tid"], "int"));
		$Result1 = mysql_query($updateSQL, $tankdb) or die(mysql_error());
	}
	if(strtotime($row_tasks["csa_plan_et"])>date("Y-m-d")&&($row_tasks["csa_status"]==1||$row_tasks["csa_status"]==6)){
		mysql_select_db($database_tankdb, $tankdb);
		$updateSQL = sprintf("UPDATE tk_task SET csa_status=2 WHERE tid=%d",                       GetSQLValueString($row_tasks["tid"], "int"));
		$Result1 = mysql_query($updateSQL, $tankdb) or die(mysql_error());
	}
 } while ($row_tasks = mysql_fetch_assoc($tasks)); 

?>