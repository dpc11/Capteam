<?php
require_once('config/tank_config.php'); //连接数据库

$action = $_GET['action'];
$csid = $_GET['csid'];
   // echo $csid;

if($action=='add'){
	$uid = $_POST['uid'];//用户Id
	$event = $_POST['event'];//课程名字  
	$event1 = $_POST['event2'];//上课地点
    
    //echo $csid;
    //$startdate = $_POST['startdate'];//开始日期
    //$enddate = $_POST['enddate'];//开始日期
    //$isallday = $_POST['isallday'];//是否是全天


    $s_week = $_POST['s_week'];//开始周
    $e_week = $_POST['e_week'];//结束周
    
    $weekday=$_POST['weekday'];

	$s_time = $_POST['s_hour'].':'.$_POST['s_minute'].':00';//开始时间
	$e_time = $_POST['e_hour'].':'.$_POST['e_minute'].':00';//结束时间
     

	
     

    mysql_select_db($database_tankdb, $tankdb);
       $sql="INSERT INTO tk_course (course_csid,course_name,course_place,course_day,course_startweek,course_endweek,course_starttime
       	,course_endtime) VALUES('$csid','$event','$event1','$weekday','$s_week','$e_week','$s_time','$e_time')"; 
       $Result1 = mysql_query($sql, $tankdb) or die(mysql_error());   
   


	if(mysql_insert_id()>0){//插入成功刷新日程页面
		echo '1';
		// $insertGoTo .= "schedule_course.php";
		//header(sprintf("Location: %s", $insertGoTo));
	}else{
		echo '写入失败！';
	}
}
elseif($action=="edit"){
	$id = $_POST['id'];
	if($id==0){
		echo '事件不存在1！';
		exit;	
	}

    $isallday = $_POST['isallday'];//是否是全天
	if($isallday == 'on'){
        $isallday1 = 1;
    }else{
    	$isallday1 = 0;
    }
	$event = $_POST['event'];//事件内容

    $startdate = $_POST['startdate'];//开始日期
    $enddate = $_POST['enddate'];//开始日期

	$s_time = $_POST['s_hour'].':'.$_POST['s_minute'].':00';//开始时间	
	$e_time = $_POST['e_hour'].':'.$_POST['e_minute'].':00';//结束时间
	$starttime = $startdate.' '.$s_time;//开始时间
	$endtime = $enddate.' '.$e_time;//结束时间

	mysql_query("update `tk_schedule` set `name`='$event',`start_time`='$starttime',`end_time`='$endtime',`is_allday`='$isallday1' where `id`='$id'");
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