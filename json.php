<?php
//include_once('connect.php');
require_once('config/tank_config.php');
$userid=1;
$sql = "select * from tk_task where csa_to_user='$userid'";
$query = mysql_query($sql);
while($row=mysql_fetch_array($query)){
	//$allday = $row['allday'];
	//$is_allday = $allday==1?true:false;
	
	$data[] = array(
		'id' => $row['tid'],
		'title' => $row['csa_text'],
		//'title' => $row['title'],
		'start' => $row['csa_plan_et'],
		'end' => $row['csa_plan_et'],
		'url' => $row['url'],
		//'allDay' => $is_allday,
		'color' => $row['color']
	);
}
echo json_encode($data);
?>