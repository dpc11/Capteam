<?php require_once('config/tank_config.php'); 

$email=$_POST['email'];
mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_task = sprintf("SELECT *
FROM tk_user
WHERE tk_user_email = %s", GetSQLValueString($email, "text"));
$Recordset_task = mysql_query($query_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);
$totalRows_Recordset_task = mysql_num_rows($Recordset_task);

if($totalRows_Recordset_task>0)echo -1;
else echo 0;


?>