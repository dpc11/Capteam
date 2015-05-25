<?php require_once('config/tank_config.php'); 

$getjson = file_get_contents('php://input');
$dataarr =json_decode($getjson, true);
$token=$dataarr['token'];

$tab=$dataarr['tab'];
$pid=$dataarr['pid'];
$projectid=$dataarr['projectid'];

$uid = check_token($token);
if($uid <> 3){

    $get_function = file_list( $uid, "0", $projectid, $pid, $tab );

$redata = json_encode($get_function);
echo $redata;
} else {
echo 3;
}
?>
