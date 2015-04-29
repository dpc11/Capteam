<?php
require_once('config/tank_config.php'); //连接数据库

$action = $_GET['action'];
$uid = $_POST['uid'];//用户Id

if($action=='add'){
	$event = $_POST['event'];//事件名称
    
	$startdate = $_POST['startdate'];//开始日期
	$enddate = $_POST['enddate'];//结束日期

	$s_time = $_POST['s_hour'].':'.$_POST['s_minute'].':00';//开始时间
	$e_time = $_POST['e_hour'].':'.$_POST['e_minute'].':00';//结束时间

	$starttime = $startdate.' '.$s_time;//开始时间
	$endtime = $enddate.' '.$e_time;//结束时间
	
	$query = mysql_query("insert into `tk_schedule` (`name`,`start_time`,`end_time`,`id`,`uid`) values ('$event','$starttime','$endtime','$id','$uid')");
	if(mysql_insert_id()>0){//插入成功刷新日程页面
		echo '添加成功！';
		// $insertGoTo .= "schedule_person.php";
		// header(sprintf("Location: %s", $insertGoTo));
	}else{
		echo '写入失败！';
	}
}
elseif($action=="edit"){
	$id = intval($_POST['id']);
	if($id==0){
		echo '事件不存在！';
		exit;	
	}
	$events = stripslashes(trim($_POST['event']));//事件内容
	$events=mysql_real_escape_string(strip_tags($events),$link); //过滤HTML标签，并转义特殊字符

	$isallday = $_POST['isallday'];//是否是全天事件
	$isend = $_POST['isend'];//是否有结束时间

	$startdate = trim($_POST['startdate']);//开始日期
	$enddate = trim($_POST['enddate']);//结束日期

	$s_time = $_POST['s_hour'].':'.$_POST['s_minute'].':00';//开始时间
	$e_time = $_POST['e_hour'].':'.$_POST['e_minute'].':00';//结束时间

	if($isallday==1 && $isend==1){
		$starttime = strtotime($startdate);
		$endtime = strtotime($enddate);
	}elseif($isallday==1 && $isend==""){
		$starttime = strtotime($startdate);
		$endtime = 0;
	}elseif($isallday=="" && $isend==1){
		$starttime = strtotime($startdate.' '.$s_time);
		$endtime = strtotime($enddate.' '.$e_time);
	}else{
		$starttime = strtotime($startdate.' '.$s_time);
		$endtime = 0;
	}

	$isallday = $isallday?1:0;
	mysql_query("update `calendar` set `title`='$events',`starttime`='$starttime',`endtime`='$endtime',`allday`='$isallday' where `id`='$id'");
	if(mysql_affected_rows()==1){
		echo '1';
	}else{
		echo '出错了！';	
	}
}elseif($action=="del"){
	$id = intval($_POST['id']);
	if($id>0){
		mysql_query("delete from `calendar` where `id`='$id'");
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