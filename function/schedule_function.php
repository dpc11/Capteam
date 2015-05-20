<?php require_once('function/user_function.php'); ?>
<?php
	$version = "1.3.3b";
	$maxRows = 30;
	$tasklevel = 0;
	mysql_select_db($database_tankdb,$tankdb);

	//»ñÈ¡¸öÈËÈÎÎñµÄÊý¾Ý
	function get_task_events($userid){
		$sql = "select * from tk_task where csa_to_user='$userid'";
		$query = mysql_query($sql);
		while($row=mysql_fetch_array($query)){
			$data[] = array(
				'id' => $row['tid'],
				'title' => $row['csa_text'],
				'start' => $row['csa_plan_et'],
				'end' => $row['csa_plan_et'],
				'url' => $row['url'],
				'allDay' => TRUE,
				// 'color' => $row['color']
			);
		}
		return $data;
	}

    //»ñÈ¡¸öÈËÈÕ³ÌµÄÊý¾Ý
    function get_person_events($userid){
        $sql = "select * from tk_schedule where uid='$userid'";
        $query = mysql_query($sql);
        while($row=mysql_fetch_array($query)){
            if($row['is_allday'] ==0){
                $allday = FALSE;
            }else{
                $allday = TRUE;
            }
            $data[] = array(
            'id' => $row['id'],
            'title' => $row['name'],
            'start' => $row['start_time'],
            'end' => $row['end_time'],
            'url' => $row['url'],
            'color' => '#008573',
            'allDay' => $allday
            );
        }
        return $data;
    }

    //»ñÈ¡¸öÈËËùÓÐÈÕ³ÌµÄÊý¾Ý
    function get_person_all_events($userid){
        //»ñµÃÓÃ»§µÄ¸öÈËÈÕ³ÌÐÅÏ¢
        $sql = "select * from tk_schedule where uid='$userid'";
        $query = mysql_query($sql);
        while($row=mysql_fetch_array($query)){
            if($row['is_allday'] ==0){
                $allday = FALSE;
            }else{
                $allday = TRUE;
            }
            $data[] = array(
            'id' => $row['id'],
            'title' => '[个人]'.$row['name'],
            'start' => $row['start_time'],
            'end' => $row['end_time'],
            'url' => $row['url'],
            'color' => '#008573',
            'allDay' => $allday
            );
        }

        //»ñµÃÓÃ»§µÄÈÎÎñÐÅÏ¢
        $sql = "select * from tk_task where csa_to_user=$userid";
        $query = mysql_query($sql);
        while($row=mysql_fetch_array($query)){
            $data[] = array(
                'id' => $row['tid'],
                'title' => '[任务]'.$row['csa_text'],
                'start' => $row['csa_plan_et'],
                'end' => $row['csa_plan_et'],
                'url' => $row['url'],
                'allDay' => TRUE,
                // 'color' => '#1874CD'
            );
        }
        //ÕâÀï»¹ÐèÒªÌí¼Ó¿ÎÒµÐÅÏ¢

        return $data;
    }

    //»ñÈ¡ÍÅ¶ÓÊÂ¼þµÄÊý¾Ý
    function get_team_events($project_id){
        //»ñµÃ¸ÃÏîÄ¿µÄËùÓÐ³ÉÔ±
		global $tankdb;
        global $database_tankdb;
        $query_user ="SELECT * 
        FROM tk_user 
        inner join tk_team on tk_team.tk_team_uid=tk_user.uid 
        WHERE tk_team.tk_team_pid = $project_id ORDER BY CONVERT(tk_display_name USING gbk )";
        $userRS = mysql_query($query_user, $tankdb) or die(mysql_error());
        $row_user = mysql_fetch_assoc($userRS);
 
        $user_arr = array ();
        do { 
        $user_arr[$row_user['uid']]['uid'] =  $row_user['uid'];
        $user_arr[$row_user['uid']]['name'] =  $row_user['tk_display_name'];
        $user_arr[$row_user['uid']]['email'] =  $row_user['tk_user_email'];
        $user_arr[$row_user['uid']]['phone_num'] =  $row_user['tk_user_contact'];
        $user_arr[$row_user['uid']]['ulimit'] =  $row_user['tk_team_ulimit'];
        } while ($row_user = mysql_fetch_assoc($userRS)); 
		
        foreach($user_arr as $key => $val){ 
            //»ñµÃÓÃ»§id
            $userid = $val['uid'];
            //»ñµÃÓÃ»§ÔÚ±¾ÏîÄ¿ÖÐµÄÈÎÎñÐÅÏ¢
            $sql = "select * from tk_task where csa_to_user=$userid and csa_project=$project_id";
            $query = mysql_query($sql);
            while($row=mysql_fetch_array($query)){
                $data[] = array(
                    'id' => $row['tid'],
                    'title' => '[任务]-['.$val['name'].']'.$row['csa_text'],
                    'start' => $row['csa_plan_et'],
                    'end' => $row['csa_plan_et'],
                    'url' => $row['url'],
                    'allDay' => TRUE,
                    // 'color' => '#1874CD'
                );
            }
            //»ñµÃÓÃ»§µÄ¸öÈËÈÕ³Ì
            $sql = "select * from tk_schedule where uid='$userid'";
            $query = mysql_query($sql);
            while($row=mysql_fetch_array($query)){

                if($row['is_allday'] ==0){
                    $allday = FALSE;
                }else{
                    $allday = TRUE;
                }
                $data[] = array(
                'id' => $row['id'],
                'title' => '[个人]-['.$val['name'].']'.$row['name'],
                'start' => $row['start_time'],
                'end' => $row['end_time'],
                'url' => $row['url'],
                'color' => '#008573',
                'allDay' => $allday
                );
            }
            //ÕâÀï»¹ÐèÒªÌí¼ÓÃ¿¸ö³ÉÔ±µÄ¿ÎÒµÐÅÏ¢

        }   

        return $data;
    }
?>