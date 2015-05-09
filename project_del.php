<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php
$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "4") {   
  header("Location: ". $restrictGoTo); 
  exit;
}
 

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

if ((isset($_GET['delID'])) && ($_GET['delID'] != "") && ($_SESSION['MM_Username'] <> $multilingual_dd_user_readonly)) {
  $del_project_id = GetSQLValueString($_GET['delID'], "int");
  $del_project_lastupdate = date("Y-m-d H:i:s",time());
  //将项目中的删除位置为-1
  $delProject = "DELETE from tk_project WHERE id = $del_project_id";
  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($delProject, $tankdb) or die(mysql_error());
  //删除team表中的数据
  $delTeam = "DELETE from tk_team WHERE tk_team_pid = $del_project_id"; 
  mysql_select_db($database_tankdb, $tankdb);
  $Result2 = mysql_query($delTeam, $tankdb) or die(mysql_error());
  
  date_default_timezone_set('PRC');
      
              $timenow=date('Y-m-d H:i:s',time());
              $insertSQLLog=sprintf("INSERT into tk_log(tk_log_user,tk_log_action,tk_log_time,tk_log_type,tk_log_class)
                VALUES(%s,'删除了项目','$timenow','$del_project_id','1')",GetSQLValueString($_SESSION['MM_uid'], "int"));
 
               mysql_select_db($database_tankdb, $tankdb);
              $Result2 = mysql_query($insertSQLLog, $tankdb) or die(mysql_error());
  // //将项目中的删除位置为-1
  // $delProject = "UPDATE tk_project SET project_del_status = -1, project_lastupdate = '$del_project_lastupdate' WHERE id = $del_project_id";
  // mysql_select_db($database_tankdb, $tankdb);
  // $Result1 = mysql_query($delProject, $tankdb) or die(mysql_error());
  // //删除team表中的数据
  // $delTeam = "UPDATE tk_team SET tk_team_del_status = -1 WHERE tk_team_pid = $del_project_id"; 
  // mysql_select_db($database_tankdb, $tankdb);
  // $Result2 = mysql_query($delTeam, $tankdb) or die(mysql_error());


  $deleteGoTo = "project.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>
<body>
</body>
</html>