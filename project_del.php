<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php require_once('function/project_function.php'); ?>
<?php

$restrictGoTo = "user_error3.php";

if ((isset($_GET['delID'])) && ($_GET['delID'] != "")) {
  $user_id = $_SESSION['MM_uid'];
  $project_id = $_GET['delID'];
  $project_info = get_project_by_id($project_id);
  //当前用户不是项目的组长，则没有权限删除该项目
  if ($user_id <> $project_info['project_to_user'] || $user_id == "") {   
    header("Location: ". $restrictGoTo); 
    exit;
  }
  

  $del_project_id = GetSQLValueString($_GET['delID'], "int");
  $del_project_lastupdate = date("Y-m-d H:i:s",time());
  //将项目中的删除位置为-1
  $delProject = "UPDATE tk_project set project_del_status = -1 WHERE id = $del_project_id";
  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($delProject, $tankdb) or die(mysql_error());

  //将项目中的删除位置为-1
  $delProject = "UPDATE tk_project SET project_del_status = -1, project_lastupdate = '$del_project_lastupdate' WHERE id = $del_project_id";
  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($delProject, $tankdb) or die(mysql_error());
  
  //删除team表中的数据
  $delTeam = "UPDATE tk_team SET tk_team_del_status = -1 WHERE tk_team_pid = $del_project_id"; 
  mysql_select_db($database_tankdb, $tankdb);
  $Result2 = mysql_query($delTeam, $tankdb) or die(mysql_error());

  //删除阶段表中的数据
  $delStage = "UPDATE tk_stage SET tk_stage_delestatus = -1 WHERE tk_stage_pid = $del_project_id"; 
  mysql_select_db($database_tankdb, $tankdb);
  $Result3 = mysql_query($delStage, $tankdb) or die(mysql_error());

  //删除任务表中的数据
  $delTask = "UPDATE tk_task SET csa_del_status = -1 WHERE csa_project = $del_project_id"; 
  mysql_select_db($database_tankdb, $tankdb);
  $Result4 = mysql_query($delTask, $tankdb) or die(mysql_error());

  //删除board表中的数据
  $delBoard = "UPDATE tk_board SET board_del_status = -1 WHERE board_pid = $del_project_id"; 
  mysql_select_db($database_tankdb, $tankdb);
  $Result5 = mysql_query($delBoard, $tankdb) or die(mysql_error());

  //删除doc表中的数据
  $delDoc = "UPDATE tk_document SET tk_doc_del_status = -1 WHERE tk_doc_pid = $del_project_id"; 
  mysql_select_db($database_tankdb, $tankdb);
  $Result6 = mysql_query($delDoc, $tankdb) or die(mysql_error());  

  //插入日志
  $insertSQLLog=sprintf("INSERT into tk_log(tk_log_user,tk_log_action,tk_log_time,tk_log_type,tk_log_class)
    VALUES(%s,'删除了项目','$del_project_lastupdate','$del_project_id','1')",GetSQLValueString($_SESSION['MM_uid'], "int"));
  mysql_select_db($database_tankdb, $tankdb);
  $Result7 = mysql_query($insertSQLLog, $tankdb) or die(mysql_error());

  $deleteGoTo = "project.php";
  header(sprintf("Location: %s", $deleteGoTo));
}else{
  header("Location: ". $restrictGoTo); 
  exit;
}
?>
<!DOCTYPE html PUBLIC>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>
<body>
</body>
</html>