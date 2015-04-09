<?php require_once('config/tank_config.php'); 

$getjson = file_get_contents('php://input');
$dataarr =json_decode($getjson, true);
$token=$dataarr['token'];
$prjid=$dataarr['prjid'];

$uid = check_token($token);
if($uid <> 3){

$get_function = project_view( $prjid );


$redata = json_encode($get_function);
echo $redata;
} else {
echo 3;
}
?>
