<?php require_once('config/tank_config.php'); 

$getjson = file_get_contents('php://input');
$dataarr =json_decode($getjson, true);
$token=$dataarr['token'];


//echo $dataarr;

$redata = json_encode(del_token( $token ));
//echo $redata;

?>
