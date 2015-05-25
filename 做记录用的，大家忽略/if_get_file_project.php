<?php require_once('config/tank_config.php'); 

$getjson = file_get_contents('php://input');
$dataarr =json_decode($getjson, true);
$token=$dataarr['token'];

$uid = check_token($token);
if($uid <> 3){

    $get_function =  project_list( 0, "project_lastupdate", "DESC", "0", "allprj", "file" );

$redata = json_encode($get_function);
echo $redata;
} else {
echo 3;
}
?>
