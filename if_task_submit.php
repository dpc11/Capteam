<?php require_once('config/tank_config.php'); 

$getjson = file_get_contents('php://input');
$dataarr =json_decode($getjson, true);
$token=$dataarr['token'];

$title=$dataarr['title'];
$text=$dataarr['text'];
$tag=$dataarr['tag'];
$type=$dataarr['type'];
$to=$dataarr['to'];
$from=$dataarr['from'];

$create=$dataarr['create'];
$start=$dataarr['start'];
$end=$dataarr['end'];
$pv=$dataarr['pv'];
$prt=$dataarr['prt'];
$level=$dataarr['level'];

$status=$dataarr['status'];
$ptaskid=$dataarr['ptaskid'];
$wbsid=$dataarr['wbsid'];
$projectid=$dataarr['projectid'];

$uid = check_token($token);
if($uid <> 3){

     $get_function = submit_task( $title, $text, $tag, $type, $to, $from, $create, $start, $end, $pv, $prt, $level, $status, $ptaskid, $wbsid, $projectid );


$redata = json_encode($get_function);
echo $redata;
} else {
echo 3;
}
?>
