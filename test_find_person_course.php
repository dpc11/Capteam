<?php 
	require_once('config/tank_config.php');

	mysql_select_db($database_tankdb,$tankdb);	

	/*用时注释掉就好*/
	$uid=1;
	$date="2015-06-04";
	$hstart = "08:30:00";
	$hend = "09:30:00";

	//返回的字符串
	$src="";
 	
 	//从数据库读取课程信息
 	$selCSSQL = "SELECT * FROM tk_course_schedule WHERE cs_uid=$uid";
 	$RS1 = mysql_query($selCSSQL, $tankdb) or die(mysql_error());
 	$CSinfo = mysql_fetch_assoc($RS1);
 	$csid = $CSinfo['cs_id'];

 	$selCourseSQL = "SELECT * FROM tk_course WHERE course_csid = $csid";
 	$RS2 = mysql_query($selCourseSQL, $tankdb) or die(mysql_error());

	/* 判断该日期是本学期的第几周的周几*/
	$datearr = explode("-", $date); //将传来的时间使用“-”分割成数组
	$year = $datearr[0]; //获取年份
	$month = sprintf('%02d', $datearr[1]); //获取月份
	$day = sprintf('%02d', $datearr[2]); //获取日期
	$hour = $minute = $second = 0; //默认时分秒均为0
	$trans_date = mktime($hour, $minute, $second, $month, $day, $year); //将时间转换成时间戳
	$dayofweek =  date("w", $trans_date); //获取星期值
	$lastday = date('Y-m-d',strtotime("$date Sunday"));
	$this_Mon = date('Y-m-d',strtotime("$lastday -6 days"));//得到指定日期所在周的周一日期
	$week_num = (strtotime($this_Mon)-strtotime($CSinfo['cs_firstday']))/86400/7 + 1;//计算是第几周
	//echo $week_num."<br>";

	if($week_num < 1)//在开学日期之前
	{
		return $src;
	}

	//判断在给定时间段是否有课程
	while($row=mysql_fetch_assoc($RS2))
	{
		if($week_num>=$row['course_startweek'] && $week_num<=$row['course_endweek'])
		{
			if($row['course_day']==$dayofweek)
			{
				if($hstart <= $row['course_starttime'] && $hend> $row['course_starttime'])
				{
					$src = $src.$row['course_name']."(".$row['course_starttime']."~".$row['course_endtime'].");";
				}
				else if($hend >= $row['course_endtime'] && $hstart < $row['course_endtime'])
				{
					$src = $src.$row['course_name']."(".$row['course_starttime']."~".$row['course_endtime'].");";
				}
				else if($hstart > $row['course_starttime'] && $hend < $row['course_endtime'])
				{
					$src = $src.$row['course_name']."(".$row['course_starttime']."~".$row['course_endtime'].");";
				}
			}
		}
	}

	//改为return该值就好 格式：课程名(开始时间~结束时间);
	return $src;
 ?>