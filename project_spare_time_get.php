<?php require_once('config/tank_config.php'); ?>
<?php require_once('function/user_function.php'); ?>
<?php 
$data = file_get_contents('php://input');
$data=explode("|",$data);
$pid=$data[0];
$hour=$data[1];
$date=$data[2];

$user_arr = get_user_select_by_project($pid);

	//返回的字符串
	$src="<h5>课程：</h5>";		
	foreach($user_arr as $key => $val){ 
		$uid=$val["uid"];

		//查找课程
		mysql_select_db($database_tankdb,$tankdb);	
 	
			$date = date('Y-m-d',strtotime($date));
			$hstart = $hour.":00:00";
			$hend = $hour.":59:59";
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

		if($week_num >= 1)//在开学日期之前
		{
			//判断在给定时间段是否有课程
			while($row=mysql_fetch_assoc($RS2))
			{
				if($week_num>=$row['course_startweek'] && $week_num<=$row['course_endweek'])
				{
					if($row['course_day']==$dayofweek)
					{
						if($hstart <= $row['course_starttime'] && $hend> $row['course_starttime'])
						{
							$src = $src."<p>".$val['name']."  ".$row['course_name']."(".$row['course_starttime']."~".$row['course_endtime'].")</p>";
						}
						else if($hend >= $row['course_endtime'] && $hstart < $row['course_endtime'])
						{
							$src = $src."<p>".$val['name']."  ".$row['course_name']."(".$row['course_starttime']."~".$row['course_endtime'].")</p>";
						}
						else if($hstart > $row['course_starttime'] && $hend < $row['course_endtime'])
						{
							$src = $src."<p>".$val['name']."  ".$row['course_name']."(".$row['course_starttime']."~".$row['course_endtime'].")</p>";
						}
					}
				}
			}
		}
	}

	$user_arr = get_user_select_by_project($pid);

		//返回的字符串
		$src.="<h5>个人日程：</h5>";
		foreach($user_arr as $key => $val){ 
			$uid=$val["uid"];
			//查找个人日程
			 $sql = "select * from tk_schedule where uid=".$uid." and ( 
							(end_time<= '".date("Y-m-d H:i:s",strtotime($date." ".$hend))."' and  
							start_time>= '".date("Y-m-d H:i:s",strtotime($date." ".$hstart))."') or 
							(end_time>= '".date("Y-m-d H:i:s",strtotime($date." ".$hend))."' and  
							start_time<= '".date("Y-m-d H:i:s",strtotime($date." ".$hstart))."')or 
							(end_time>= '".date("Y-m-d H:i:s",strtotime($date." ".$hstart))."' and
							end_time<= '".date("Y-m-d H:i:s",strtotime($date." ".$hend))."' and  
							start_time<= '".date("Y-m-d H:i:s",strtotime($date." ".$hstart))."')or 
							(start_time>= '".date("Y-m-d H:i:s",strtotime($date." ".$hstart))."' and 
							start_time<= '".date("Y-m-d H:i:s",strtotime($date." ".$hend))."' and  
							end_time>= '".date("Y-m-d H:i:s",strtotime($date." ".$hend))."')
							)";
           
			$RS2 = mysql_query($sql, $tankdb) or die(mysql_error());
			while($row=mysql_fetch_assoc($RS2))
			{
				$src=$src."<p>".$val['name']."  ".$row['name']."(".$row['start_time']."~".$row['end_time'].")</p>";echo $src;
			}
		}
		
	$user_arr = get_user_select_by_project($pid);

		//返回的字符串
		$src.="<h5>任务：</h5>";
		foreach($user_arr as $key => $val){ 
			$uid=$val["uid"];
			//查找任务
			$sql = "select * , tk_project.project_name  from tk_task,tk_project where tk_project.id=tk_task.csa_project and  csa_to_user=".$uid." and csa_plan_st<= '".$date."' and  
							csa_plan_et>= '".$date."'";

            $RS2 = mysql_query($sql, $tankdb) or die(mysql_error());
			while($row=mysql_fetch_assoc($RS2))
			{
				$src=$src."<p>".$val['name']."  ".$row['project_name']."(".$row['csa_plan_st']."~".$row['csa_plan_et'].")</p>";
			}
		}
	//改为return该值就好 格式：课程名(开始时间~结束时间);
	echo $src;
 ?>