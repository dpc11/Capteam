<?php require_once('config/tank_config.php'); 

$getjson = file_get_contents('php://input');
$dataarr =json_decode($getjson, true);
$token=$dataarr['token'];

//$tab=$dataarr['tab'];
$fileid=$dataarr['fileid'];

$uid = check_token($token);
if($uid <> 3){

    $get_function = file_view( $fileid );

$redata = json_encode($get_function);
echo $redata;
} else {
echo 3;
}
?>
