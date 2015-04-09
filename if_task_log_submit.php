<?php require_once('config/tank_config.php'); 

$getjson = file_get_contents('php://input');
$dataarr =json_decode($getjson, true);
$token=$dataarr['token'];

$taskid=$dataarr['taskid'];
$user=$dataarr['uid'];
$status=$dataarr['status'];
$hour=$dataarr['hour'];
$date=$dataarr['date'];
$text=$dataarr['text'];

$uid = check_token($token);
if($uid <> 3){

    $get_function = submit_log( $text, $status, $hour, $user, $date, $taskid  );


$redata = json_encode($get_function);
echo $redata;
} else {
echo 3;
}
?>
