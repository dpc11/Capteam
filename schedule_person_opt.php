<?php
require_once('config/tank_config.php'); //连接数据库

$action = $_GET['action'];


if($action=='add'){
	$uid = $_POST['uid'];//用户Id
	$event = $_POST['event'];//事件名称    
	$startdate = $_POST['startdate'];//开始日期
	$enddate = $_POST['enddate'];//结束日期
	$s_time = $_POST['s_hour'].':'.$_POST['s_minute'].':00';//开始时间
	$e_time = $_POST['e_hour'].':'.$_POST['e_minute'].':00';//结束时间

	$starttime = $startdate.' '.$s_time;//开始时间
	$endtime = $enddate.' '.$e_time;//结束时间

	$query = mysql_query("insert into `tk_schedule` (`name`,`start_time`,`end_time`,`id`,`uid`) values ('$event','$starttime','$endtime','$id','$uid')");
	if(mysql_insert_id()>0){//插入成功刷新日程页面
		echo '1';
		// $insertGoTo .= "schedule_person.php";
		// header(sprintf("Location: %s", $insertGoTo));
	}else{
		echo '写入失败！';
	}
}
elseif($action=="edit"){
	$id = $_POST['id'];
	if($id==0){
		echo '事件不存在！';
		exit;	
	}
	$event = $_POST['event'];//事件内容
	$startdate = $_POST['startdate'];//开始日期
	$enddate = $_POST['enddate'];//结束日期
	$s_time = $_POST['s_hour'].':'.$_POST['s_minute'].':00';//开始时间
	
	$e_time = $_POST['e_hour'].':'.$_POST['e_minute'].':00';//结束时间
	$starttime = $startdate.' '.$s_time;//开始时间
	$endtime = $enddate.' '.$e_time;//结束时间

	mysql_query("update `tk_schedule` set `name`='$event',`start_time`='$starttime',`end_time`='$endtime' where `id`='$id'");
	if(mysql_affected_rows()==1){
		echo '1';
	}else{
		echo '出错了！';	
	}
}elseif($action=="del"){
	$id = $_POST['id'];
	if($id>0){
		mysql_query("delete from `tk_schedule` where `id`='$id'");
		if(mysql_affected_rows()==1){
			echo '1';
		}else{
			echo '出错了！';	
		}
	}else{
		echo '事件不存在！';
	}
}else{
	
}
?>