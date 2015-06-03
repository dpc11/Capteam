<?php

//遍历数据库，更新任务状态
mysql_select_db($database_tankdb, $tankdb);
$all_task = sprintf("SELECT csa_plan_st,csa_plan_et,csa_status,tid from tk_task where csa_project in (SELECT tk_team_pid FROM tk_team where tk_team_del_status=1 and tk_team_uid=%d ) and csa_del_status=1 ", GetSQLValueString($_SESSION["MM_uid"], "int"));
$tasks = mysql_query($all_task, $tankdb) or die(mysql_error());
$row_tasks = mysql_fetch_assoc($tasks);

$start=$row_DetailRS1['project_start'];
$end=$row_DetailRS1['project_end'];

$fp = fopen("project_spare_time_data.json", "w"); 

for(){
fwrite($fp,"\r\n");
}
fclose($fp); 

?>