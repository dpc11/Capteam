<?php require_once('config/tank_config.php'); 

$getjson = file_get_contents('php://input');
$dataarr =json_decode($getjson, true);
$token=$dataarr['token'];
$taskid=$dataarr['taskid'];
$date=$dataarr['date'];

$uid = check_token($token);
if($uid <> 3){

    $log_view = log_view( $taskid, $date);


$redata = json_encode($log_view);
echo $redata;
} else {
echo 3;
}
?>
