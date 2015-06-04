<?php
	
//$uid=$_SESSION["MM_uid"];
$start=$row_DetailRS1['project_start'];
$end=$row_DetailRS1['project_end'];
$length=floor((strtotime($end)-strtotime($start))/86400);

$fp = fopen("project_spare_time_data.json", "w"); 
fwrite($fp,"{\r\n");

for($i=0;$i<$length;$i++){//每天
	for($j=0;$j<24;$j++){//每个小时
	
		$grade=0;
		
		foreach($user_arr as $key => $val){ 
			$uid=$val["uid"];
			//查找课程
			mysql_select_db($database_tankdb,$tankdb);	

			$date = date('Y-m-d',strtotime("$start +$i days"));
			$hstart = sprintf('%02d', $j).":00:00";
			$hend = sprintf('%02d', $j).":59:59";

			//从数据库读取课程信息
			$selCSSQL = "SELECT * FROM tk_course_schedule WHERE cs_uid=$uid";
			$RS1 = mysql_query($selCSSQL, $tankdb) or die(mysql_error());
			$CSinfo = mysql_fetch_assoc($RS1);
			$csid = $CSinfo['cs_id'];

			$selCourseSQL = "SELECT * FROM tk_course WHERE course_csid = $csid";
			$RS2 = mysql_query($selCourseSQL, $tankdb) or die(mysql_error());

			//判断该日期是本学期的第几周的周几
			$datearr = explode("-", $date); //将传来的时间使用“-”分割成数组
			$year = $datearr[0]; //获取年份
			$month = sprintf('%02d', $datearr[1]); //获取月份
			$day = sprintf('%02d', $datearr[2]); //获取日期
			$hour = $minute = $second = 0; //默认时分秒均为0
			$trans_date = mktime($hour, $minute, $second, $month, $day, $year); //将时间转换成时间戳
			$dayofweek =  date("w", $trans_date); //获取星期值
			$lastday = date('Y-m-d',strtotime("$date Sunday"));
			//echo $lastday." ";
			$this_Mon = date('Y-m-d',strtotime("$lastday -6 days"));//得到指定日期所在周的周一日期
			//echo $this_Mon." ";
			//echo $CSinfo['cs_firstday']." ";
			$week_num = (strtotime($this_Mon)-strtotime($CSinfo['cs_firstday']))/86400/7 + 1;//计算是第几周
			//echo $week_num."<br>";
			//echo $week_num;

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
								//$src = $src.$row['course_name']."(".$row['course_starttime']."~".$row['course_endtime'].");";
								$grade=$grade+20;
							}
							else if($hend >= $row['course_endtime'] && $hstart < $row['course_endtime'])
							{
								//$src = $src.$row['course_name']."(".$row['course_starttime']."~".$row['course_endtime'].");";
								$grade=$grade+20;
							}
							else if($hstart > $row['course_starttime'] && $hend < $row['course_endtime'])
							{
								//$src = $src.$row['course_name']."(".$row['course_starttime']."~".$row['course_endtime'].");";
								$grade=$grade+20;
							}
										//$grade=$grade+20;
						}
					}
				}
			}
			
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
            $query = mysql_query($sql);
            $row=mysql_num_rows($query);
			$grade=$grade+$row*10;
			
			//查找任务
			$sql = "select * from tk_task where csa_to_user=".$uid." and csa_plan_st<= '".$date."' and  
							csa_plan_et>= '".$date."'";
			$query = mysql_query($sql);
            $row=mysql_num_rows($query);
			$grade=$grade+$row*5;
					
		}
		if($grade>0){
			fwrite($fp,"\"".strtotime($date." ".$hstart)."\":".$grade.",\r\n");
		}
	}
}
fwrite($fp,"\"".strtotime("$start -1 days")."\":0\r\n}");
fclose($fp); 

?>