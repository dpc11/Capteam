<?php require_once('config/tank_config.php'); 

$getjson = file_get_contents('php://input');
$dataarr =json_decode($getjson, true);
$token=$dataarr['token'];
$tab=$dataarr['tab'];

$uid = check_token($token);
if($uid <> 3){

$to = 0;
if ($tab == "mtask"){
$to = $uid;
}

$from = 0;
if ($tab == "ftask" || $tab == "etask" ){
$from = $uid;
}

$creat = 0;
if ($tab == "ctask"){
$creat = $uid;
}

$task_list = task_list( $to, $from, $creat, "", "", "", "+", "", "", "", "", "", $multilingual_dd_status_exam, "--", "--", "csa_last_update", "DESC","0", $tab );

$exam = sum_exam( $uid );

$rearr = array(
'exam'=>$exam, 	
'list'=>$task_list 	
);
$redata = json_encode($rearr);
echo $redata;
} else {
echo 3;
}
?>
