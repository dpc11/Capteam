<?php require_once('config/tank_config.php'); 

$getjson = file_get_contents('php://input');
$dataarr =json_decode($getjson, true);
$token=$dataarr['token'];

$pid=$dataarr['pid'];
$poster=$dataarr['uid'];
$type=$dataarr['type'];
$text=$dataarr['text'];
$taskid=$dataarr['taskid'];

$date=$dataarr['date'];

$uid = check_token($token);
if($uid <> 3){

    $get_function = submit_comment( $text, $poster, $pid, $type, $date, $taskid );


$redata = json_encode($get_function);
echo $redata;
} else {
echo 3;
}
?>
