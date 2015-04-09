<?php require_once('config/tank_config.php'); 

$getjson = file_get_contents('php://input');
$dataarr =json_decode($getjson, true);
$token=$dataarr['token'];
$taskid=$dataarr['tid'];

$uid = check_token($token);
if($uid <> 3){

$task_view = task_view( $taskid );


$redata = json_encode($task_view);
echo $redata;
} else {
echo 3;
}
?>
