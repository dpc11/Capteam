<?php require_once('config/tank_config.php'); 

$getjson = file_get_contents('php://input');
$dataarr =json_decode($getjson, true);
$token=$dataarr['token'];
$task_id=$dataarr['taskid'];

$uid = check_token($token);
if($uid <> 3){

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_task = sprintf("SELECT *, 
tk_project.id as proid  
FROM tk_task 
inner join tk_project on tk_task.csa_project=tk_project.id 
WHERE TID = %s", GetSQLValueString($task_id, "int"));
$Recordset_task = mysql_query($query_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);
    
    $get_type = get_task_type();
    $get_status = get_task_status();
    $get_user = get_user_select();

$data = array();
    $data["tasktitle"]= $row_Recordset_task['csa_text'];
    $data["type"]= $get_type; 
    $data["status"]= $get_status; 
    $data["user"]= $get_user; 
    
    
$redata = json_encode($data);
echo $redata;
} else {
echo 3;
}
?>
