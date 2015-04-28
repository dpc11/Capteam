<?php require_once('config/tank_config.php'); ?>

<?php
//$_SESSION['MM_rank'];
$userid=1;
$sql = "select * from tk_schedule where uid='$userid'";
$query = mysql_query($sql);
while($row=mysql_fetch_array($query)){
	//$allday = $row['allday'];
	//$is_allday = $allday==1?true:false;
	
	$data[] = array(
		'id' => $row['id'],
		'title' => $row['name'],
		//'title' => $row['title'],
		'start' => $row['start_time'],
		'end' => $row['end_time'],
		'url' => $row['url'],
		//'allDay' => $is_allday,
		'color' => '#008573'
	);
}
echo json_encode($data);
?>


