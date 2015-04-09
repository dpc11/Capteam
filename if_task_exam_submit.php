<?php require_once('config/tank_config.php'); 

$getjson = file_get_contents('php://input');
$dataarr =json_decode($getjson, true);
$token=$dataarr['token'];
$taskid=$dataarr['taskid'];
$uid=$dataarr['uid'];
$status=$dataarr['status'];
$comment=$dataarr['comment'];

$uid = check_token($token);
if($uid <> 3){

    $get_function = submit_exam( $uid, $taskid, $status, $comment );


$redata = json_encode($get_function);
echo $redata;
} else {
echo 3;
}
?>
