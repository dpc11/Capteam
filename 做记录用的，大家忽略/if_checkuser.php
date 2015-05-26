<?php require_once('config/tank_config.php'); 

$getjson = file_get_contents('php://input');
$dataarr =json_decode($getjson, true);
$acc=$dataarr['username'];
$pss=$dataarr['password'];

//echo $dataarr;

$redata = json_encode(check_user( $acc, $pss ));
echo $redata;

?>
