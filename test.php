<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php
//ÏÈÅÐ¶ÏÔ­À´ÏîÄ¿³ÉÔ±¶¼ÓÐË­
$arr[] = array(); //将要被删除的成员数组
mysql_select_db($database_tankdb, $tankdb);
$query_deleterecordset = "SELECT * FROM tk_team WHERE tk_team_pid=103";
$Recordset2 = mysql_query($query_deleterecordset, $tankdb) or die(mysql_error());

$totalRows_Recordset2 = mysql_num_rows($Recordset2);

  while($row = mysql_fetch_array($Recordset2,MYSQL_ASSOC))
{  
      $arr[]=$row;
}


$arr1[] = array(); //将要被添加的成员数组
mysql_select_db($database_tankdb, $tankdb);
$query_deleterecordset1 = "SELECT * FROM tk_team WHERE tk_team_pid=102";
$Recordset3 = mysql_query($query_deleterecordset1, $tankdb) or die(mysql_error());
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

  while($row1 = mysql_fetch_array($Recordset3,MYSQL_ASSOC))
{  
      $arr1[]=$row1;
}


$ids1 = array();
$ids1 = array_reduce($arr, create_function('$v,$w', '$v[$w["tk_team_uid"]]=$w["tk_team_uid"];return $v;'));
print_r($ids1);
$ids2 = array();
$ids2 = array_reduce($arr1, create_function('$v,$w', '$v[$w["tk_team_uid"]]=$w["tk_team_uid"];return $v;'));
print_r($ids2);

$onemoretwo=array();
$onemoretwo=array_diff($ids2,$ids1);
print_r($onemoretwo);

/*mysql_select_db($database_tankdb, $tankdb);
foreach($onemoretwo as $a){//在循环中只要用到值
date_default_timezone_set('PRC');
    $action='删除了成员:'.$a;
              $timenow=date('Y-m-d H:i:s',time());
              $insertSQLLog=sprintf("INSERT into tk_log(tk_log_user,tk_log_action,tk_log_time,tk_log_type,tk_log_class)
                VALUES(%s,'$action','$timenow','$a','1')",GetSQLValueString($_SESSION['MM_uid'], "int"));
 
               
              $Result2 = mysql_query($insertSQLLog, $tankdb) or die(mysql_error());}
*/

$twomoreone=array();
$twomoreone=array_diff($ids1,$ids2);
print_r($twomoreone);

/*mysql_select_db($database_tankdb, $tankdb);
foreach($twomoreone as $b){//在循环中只要用到值
date_default_timezone_set('PRC');
    $action='删除了成员:'.$b;
              $timenow=date('Y-m-d H:i:s',time());
              $insertSQLLog=sprintf("INSERT into tk_log(tk_log_user,tk_log_action,tk_log_time,tk_log_type,tk_log_class)
                VALUES(%s,'$action','$timenow','$b','1')",GetSQLValueString($_SESSION['MM_uid'], "int"));
 
               
              $Result2 = mysql_query($insertSQLLog, $tankdb) or die(mysql_error());}*/
?>