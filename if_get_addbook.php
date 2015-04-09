<?php require_once('config/tank_config.php'); 

$getjson = file_get_contents('php://input');
$dataarr =json_decode($getjson, true);
$token=$dataarr['token'];

$uid = check_token($token);
if($uid <> 3){

    $get_function = get_user_select();

$redata = json_encode($get_function);
echo $redata;
} else {
echo 3;
}
?>
