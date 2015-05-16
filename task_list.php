<?php 

$phpself =$_SERVER['PHP_SELF'];
$temp = explode("/",$phpself);
$pagenames = end($temp);

//<!--设置任务标签，初始值为所有的任务 -->
$pagetabs = "alltask";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}

//<!--得到设置里的每页显示的任务数 -->
$maxRows_Recordset1 = get_item( 'maxrows_task' );
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;
$R_list=$startRow_Recordset1+1;


//<!--得到设置里的每页显示的任务过期数 -->   
//<!--pageNum_timeout=0 为第一页 -->
//显示的过期的任务最大数量
$maxRows_timeout = get_item( 'maxrows_timeout' );
//当前显示的过期任务的页面号
$pageNum_timeout = 0;
if (isset($_GET['pageNum_timeout'])) {
  $pageNum_timeout = $_GET['pageNum_timeout'];
}
$startRow_timeout = $pageNum_timeout * $maxRows_timeout;

//当前显示的即将过期任务的页面号
$pageNum_nearout = 0;
if (isset($_GET['pageNum_nearout'])) {
  $pageNum_nearout = $_GET['pageNum_nearout'];
}
$startRow_nearout = $pageNum_nearout * $maxRows_timeout;


//<!--设置执行人 %表示不限执行人 -->  
$colname_Recordset1 = "%";
$_SESSION['ser_touser'] = $colname_Recordset1;
if (isset($_GET['select4'])) {
  $colname_Recordset1 = $_GET['select4'];
  $_SESSION['ser_touser'] = $colname_Recordset1;
}


//<!--设置创建人 %表示不限创建人--> 
$colcreate_Recordset1 = "%";
$_SESSION['ser_createuser'] = $colcreate_Recordset1;
if (isset($_GET['create_by'])) {
  $colcreate_Recordset1 = $_GET['create_by'];
  $_SESSION['ser_createuser'] = $colcreate_Recordset1;
}

//<!--设置创建月份--> 
$colmonth_Recordset1 = date("m");
$_SESSION['ser_month'] = $colmonth_Recordset1;
if (isset($_GET['textfield'])) {
  $colmonth_Recordset1 = $_GET['textfield'];
  $_SESSION['ser_month'] = $colmonth_Recordset1;
}

//<!--设置创建年份--> 
$colyear_Recordset1 = date("Y");
$_SESSION['ser_year'] = $colyear_Recordset1;
if (isset($_GET['select_year'])) {
  $colyear_Recordset1 = $_GET['select_year'];
  $_SESSION['ser_year'] = $colyear_Recordset1;
}
$YEAR = $colyear_Recordset1;
$MONTH = $colmonth_Recordset1;


//<!--选择月份必须选年份，选年份可以不选月份--> 
//<!--若年份为--，则为查找所有任务；若月份为空，则查找这一年的任务；--> 
if ($colyear_Recordset1 == "--"){
$startday = "1975-09-23";
$endday = "3000-13-31";
} else if ($colmonth_Recordset1 == "--"){
$startday = $colyear_Recordset1."-01-01";
$endday = $colyear_Recordset1."-12-31";
} else {
$startday = $colyear_Recordset1."-".$colmonth_Recordset1."-01";
$endday = $colyear_Recordset1."-".$colmonth_Recordset1."-31";
}

//<!--设置优先级--> 
$colprt_Recordset1 = "";
$_SESSION['ser_tkprt'] = $colprt_Recordset1;
if (isset($_GET['select_prt'])) {
  $colprt_Recordset1 = $_GET['select_prt'];
  $_SESSION['ser_tkprt'] = $colprt_Recordset1;
}


//<!--设置任务状态( ""--所有任务； 1--未开始；2--进行中； 3--已完成； 4--已验收； 5--被驳回； 6--已过期)--> 
if (isset($_GET['select_st'])) {
  $colstatus_Recordset1 = $_GET['select_st'];
  $_SESSION['ser_status'] = $colstatus_Recordset1;
} else {
$colstatus_Recordset1 = "进行中"; 
$_SESSION['ser_status'] = "进行中";
}

//<!--选择某一项目--> 
$colproject_Recordset1 = "";
$_SESSION['ser_project'] = $colproject_Recordset1;
if (isset($_GET['select_project'])) {
  $colproject_Recordset1 = $_GET['select_project'];
  $_SESSION['ser_project'] = $colproject_Recordset1;
}

//<!--必须选择一个项目以后才能选择阶段--> 
//<!--选择某一阶段--> 
$colstage_Recordset1 = "";
$_SESSION['ser_stage'] = $colstage_Recordset1;
if (isset($_GET['ser_stage'])) {
  $colstage_Recordset1 = $_GET['ser_stage'];
  $_SESSION['ser_stage'] = $colstage_Recordset1;
}

//<!--搜索条件--> 
$searchby = "";
$colinputtitle_Recordset1 = "";
$colinputtag_Recordset1 = "";
if (isset($_GET['searchby'])) {
  $searchby= $_GET['searchby'];
  if($searchby == "tit"){
  $colinputtitle_Recordset1 =  $_GET['inputval'];
  } else if($searchby == "tag"){
  $colinputtag_Recordset1 = $_GET['inputval'];
  }  
}

//设置排序标准
$sortlist = "csa_plan_st";
if (isset($_GET['sort'])) {
  $sortlist = $_GET['sort'];
}

//升序 降序
$orderlist = "DESC";
if (isset($_GET['order'])) {
  $orderlist= $_GET['order'];
}
	
$coltouser = GetSQLValueString($colname_Recordset1, "int");
$colcreateuser = GetSQLValueString($colcreate_Recordset1, "int");


$colprt = GetSQLValueString("%%" . str_replace("%","%%",$colprt_Recordset1) . "%%", "text");
$colstatus = GetSQLValueString("%%" . str_replace("%","%%",$colstatus_Recordset1) . "%%", "text");
$colproject = GetSQLValueString($colproject_Recordset1, "int");
$colstage = GetSQLValueString($colstage_Recordset1, "int");
$colinputtitle = GetSQLValueString("%%" . str_replace("%","%%",$colinputtitle_Recordset1) . "%%", "text");
$colinputtag = GetSQLValueString("%%" . str_replace("%","%%",$colinputtag_Recordset1) . "%%", "text");
$cc_tome = '"uid":"'.$_SESSION['MM_uid'].'"';
$cc_tome = GetSQLValueString("%%" . str_replace("%","%%",$cc_tome) . "%%", "text");

		$where = "";
			$where=' WHERE';

			//执行人
			if($colname_Recordset1 <> '%' )
			{
				$where.= " tk_task.csa_to_user = $coltouser AND";
			}
			
			//优先级
			if(!empty($colprt_Recordset1))
			{
				$where.= " tk_task.csa_priority LIKE $colprt AND";
			}
			
			//任务状态
			if(!empty($colstatus_Recordset1))
			{
				$where.= " tk_status.task_status LIKE $colstatus AND";
			}
			
			//所属项目
			if(!empty($colproject_Recordset1))
			{
				$where.= " tk_task.csa_project = $colproject AND";
			}
			
			//所属阶段
			if(!empty($colstage_Recordset1))
			{
				$where.= " tk_task.csa_project_stage = $colstage AND";
			}
			
			//任务标题
			if(!empty($colinputtitle_Recordset1))
			{
				$where.= " tk_task.csa_text LIKE $colinputtitle AND";
			}
			
			//任务标签
			if(!empty($colinputtag_Recordset1))
			{
				$where.= " tk_task.csa_tag LIKE $colinputtag AND";
			}
			
			//创建人
			if($colcreate_Recordset1 <> '%')
			{
				$where.= " tk_task.csa_from_user = $colcreateuser AND";
			}
			
			//抄送人
			if($pagetabs == "cctome")
			{
				$where.= " tk_task.csa_testto LIKE $cc_tome AND";
			}

//条件查询结果
mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT *, 
							
							tk_project.project_name as project_name_prt,
							tk_stage.tk_stage_title as stage_name_prt,
							tk_user1.tk_display_name as tk_display_name1, 
							tk_user2.tk_display_name as tk_display_name2
							
							FROM tk_task  
							inner join tk_project on tk_task.csa_project=tk_project.id 
							
							inner join tk_stage on tk_task.csa_project_stage=tk_stage.stageid 
							
							inner join tk_user as tk_user1 on tk_task.csa_to_user=tk_user1.uid 
							inner join tk_user as tk_user2 on tk_task.csa_from_user=tk_user2.uid 
							
							inner join tk_status on tk_task.csa_status=tk_status.id 
							
							$where 
							(tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s)
							OR (tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s)
							OR (tk_task.csa_plan_st >=%s
 							AND tk_task.csa_plan_et <=%s)
								
							 AND csa_del_status=1
							 
							ORDER BY %s %s", 
							
							GetSQLValueString($startday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($sortlist, "defined", $sortlist, "NULL"),
							GetSQLValueString($orderlist, "defined", $orderlist, "NULL")
							);
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);





if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysql_query($query_Recordset1);
  $totalRows_Recordset1 = mysql_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;

$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset1") == false && 
        stristr($param, "totalRows_Recordset1") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);

/*
if($YEAR <> "0000" && $MONTH <> "00"){
mysql_select_db($database_tankdb, $tankdb);
$query_Reclog = sprintf("SELECT *							
							FROM tk_task  
							inner join tk_task_byday on tk_task.TID=tk_task_byday.csa_tb_backup1
							inner join tk_status on tk_task.csa_status=tk_status.id 
							inner join tk_status as tk_status1 on tk_task_byday.csa_tb_status=tk_status1.id 

							$where 
							(tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st >=%s
 							AND tk_task.csa_plan_et <=%s)
														
							ORDER BY csa_last_update DESC", 
							
							GetSQLValueString($startday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text")
							);
$Reclog = mysql_query($query_Reclog, $tankdb) or die(mysql_error());

$strslog=null;
while($row_Reclog=mysql_fetch_array($Reclog)){
$rowstatus = str_replace("'",   "\'",   $row_Reclog['task_status_display']);

$strtext =   htmlspecialchars($row_Reclog['csa_tb_text']);
$strtext =  stripslashes($strtext);
$strtext = str_replace("\n",   "<br>",   $strtext);  
$strtext = str_replace("\r",   "",   $strtext);  
$strtext = str_replace("  ",   "&nbsp;",   $strtext); 
$strtext = str_replace("'",   " ",   $strtext); 

$strtexttip =   htmlspecialchars($row_Reclog['csa_tb_text']);
$strtexttip =  stripslashes($strtexttip);
$strtexttip = str_replace("\n",   " ",   $strtexttip);  
$strtexttip = str_replace("\r",   " ",   $strtexttip);  
$strtexttip = str_replace("'",   " ",   $strtexttip); 


$logyear = str_split($row_Reclog['csa_tb_year'],4);
$logmonth = str_split($logyear[1],2);
$logdate = $logyear[0]."-".$logmonth[0]."-".$logmonth[1];

$strslog.="var "."d".$row_Reclog['TID'].$row_Reclog['csa_tb_year']."="."'<span title=\'$logdate  $multilingual_calendar_view\'>"."$rowstatus"."</span>"."</td><td width=\'30px\'  class=\'week_style_padtd\'>".$row_Reclog['csa_tb_manhour']."'; ";
}
} else {$strslog=null;}

mysql_select_db($database_tankdb, $tankdb);
$query_Sumlog = sprintf("SELECT *,
							sum(csa_tb_manhour) as sumhour							
							FROM tk_task  
							
							inner join tk_status on tk_task.csa_status=tk_status.id 

							$where 
							(tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st >=%s
 							AND tk_task.csa_plan_et <=%s)
														
							GROUP BY TID", 
							
							GetSQLValueString($startday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text")
							);
$Sumlog = mysql_query($query_Sumlog, $tankdb) or die(mysql_error());

$strssumlog=null;
while($row_Sumlog=mysql_fetch_array($Sumlog)){
$strssumlog.="var "."sum".$row_Sumlog['TID']."='".$row_Sumlog['sumhour']."'; ";
}

mysql_select_db($database_tankdb, $tankdb);
$query_Sumtotal = sprintf("SELECT sum(csa_tb_manhour) as sumtotal							
							FROM tk_task  
							
							inner join tk_status on tk_task.csa_status=tk_status.id 

							$where 
							(tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st >=%s
 							AND tk_task.csa_plan_et <=%s)													
							", 
							
							GetSQLValueString($startday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text")
							);
$Sumtotal = mysql_query($query_Sumtotal, $tankdb) or die(mysql_error());
$row_Sumtotal = mysql_fetch_assoc($Sumtotal);

if($YEAR <> "0000" && $MONTH <> "00"){
mysql_select_db($database_tankdb, $tankdb);
$query_Sumbyday = sprintf("SELECT *, sum(csa_tb_manhour) as Sumbyday							
							FROM tk_task  
							 
							inner join tk_status on tk_task.csa_status=tk_status.id 

							$where 
							(tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st >=%s
 							AND tk_task.csa_plan_et <=%s)													
							GROUP BY csa_tb_year", 
							
							GetSQLValueString($startday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text")
							);
$Sumbyday = mysql_query($query_Sumbyday, $tankdb) or die(mysql_error());
$strssumbyday=null;
while($row_Sumbyday=mysql_fetch_array($Sumbyday)){
$strssumbyday.="var "."sumbd".$row_Sumbyday['csa_tb_year']."='".$row_Sumbyday['Sumbyday']."'; ";
} 
} else {$strssumbyday=null;}
*/

if ($pagetabs <> "etask") {
//查找过期任务
$outday = date("Y-m-d");

$outstfinish = GetSQLValueString("%" . $multilingual_dd_status_stfinish . "%", "text");
$outday = GetSQLValueString($outday , "text");

$outwhere = "";
			$outwhere=' WHERE';
            
   //          //查找指派给我的过期的任务
			// if($colname_Recordset1 <> '%')
			// {
			// 	$outwhere.= " tk_task.csa_to_user = $coltouser AND";
			// }
			$outwhere.= " tk_task.csa_to_user = $coltouser AND";			
			// if($colcreate_Recordset1 <> '%')
			// {
			// 	$outwhere.= " tk_task.csa_from_user = $colcreateuser AND";
			// }
			// if($pagetabs == "cctome")
			// {
			// 	$outwhere.= " tk_task.csa_testto LIKE $cc_tome AND";
			// }
			$outwhere.= " tk_status.task_status NOT LIKE $outstfinish AND";
			//设置过期时间不包括已完成、已验收的
			$outwhere.= " tk_task.csa_status <> 3 AND tk_task.csa_status <> 4 AND";
			$outwhere.= " tk_task.csa_plan_et <= $outday  AND csa_del_status=1";

mysql_select_db($database_tankdb, $tankdb);
$query_timeout = "SELECT *, 
							
							tk_project.project_name as project_name_prt,
							tk_user1.tk_display_name as tk_display_name1, 
							tk_user2.tk_display_name as tk_display_name2
							
							FROM tk_task  
							inner join tk_project on tk_task.csa_project=tk_project.id
							
							inner join tk_user as tk_user1 on tk_task.csa_to_user=tk_user1.uid 
							inner join tk_user as tk_user2 on tk_task.csa_from_user=tk_user2.uid 
							
							inner join tk_status on tk_task.csa_status=tk_status.id
							
							$outwhere 
														
							ORDER BY csa_plan_et DESC";
$query_limit_timeout = sprintf("%s LIMIT %d, %d", $query_timeout, $startRow_timeout, $maxRows_timeout);
$timeout = mysql_query($query_limit_timeout, $tankdb) or die(mysql_error());
$row_timeout = mysql_fetch_assoc($timeout);

if (isset($_GET['totalRows_timeout'])) {
  $totalRows_timeout = $_GET['totalRows_timeout'];
} else {
  $all_timeout = mysql_query($query_timeout);
  $totalRows_timeout = mysql_num_rows($all_timeout);
}
$totalPages_timeout = ceil($totalRows_timeout/$maxRows_timeout)-1;

$queryString_timeout = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_timeout") == false && 
        stristr($param, "totalRows_timeout") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_timeout = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_timeout = sprintf("&totalRows_timeout=%d%s", $totalRows_timeout, $queryString_timeout);
}







if ($pagetabs <> "etask") {
//查找即将过期任务
$outday = date("Y-m-d");
//设置即将过期的时间为剩下两天
$nearday = date("Y-m-d",strtotime('-2 day')); 

$outstfinish = GetSQLValueString("%" . $multilingual_dd_status_stfinish . "%", "text");
//将日期转化为文本
$outday = GetSQLValueString($outday , "text");
$nearday = GetSQLValueString($nearday , "text");

$outwhere = "";
			$outwhere=' WHERE';
            
   //          //查找指派给我的过期的任务
			// if($colname_Recordset1 <> '%')
			// {
			// 	$outwhere.= " tk_task.csa_to_user = $coltouser AND";
			// }
			$outwhere.= " tk_task.csa_to_user = $coltouser AND";			
			// if($colcreate_Recordset1 <> '%')
			// {
			// 	$outwhere.= " tk_task.csa_from_user = $colcreateuser AND";
			// }
			// if($pagetabs == "cctome")
			// {
			// 	$outwhere.= " tk_task.csa_testto LIKE $cc_tome AND";
			// }
			$outwhere.= " tk_status.task_status NOT LIKE $outstfinish AND";
			//设置即将过期的任务为进行中的任务
			$outwhere.= " tk_task.csa_status =2 AND";
			$outwhere.= " tk_task.csa_plan_et <= $outday AND tk_task.csa_plan_et >= $nearday AND csa_del_status=1";

mysql_select_db($database_tankdb, $tankdb);
$query_nearout = "SELECT *, 
							
							tk_project.project_name as project_name_prt,
							tk_user1.tk_display_name as tk_display_name1, 
							tk_user2.tk_display_name as tk_display_name2
							
							FROM tk_task  
							inner join tk_project on tk_task.csa_project=tk_project.id
							
							inner join tk_user as tk_user1 on tk_task.csa_to_user=tk_user1.uid 
							inner join tk_user as tk_user2 on tk_task.csa_from_user=tk_user2.uid 
							
							inner join tk_status on tk_task.csa_status=tk_status.id
							
							$outwhere 
														
							ORDER BY csa_plan_et DESC";
$query_limit_nearout = sprintf("%s LIMIT %d, %d", $query_nearout, $startRow_nearout, $maxRows_timeout);
$nearout = mysql_query($query_limit_nearout, $tankdb) or die(mysql_error());
$row_nearout = mysql_fetch_assoc($nearout);

if (isset($_GET['totalRows_nearout'])) {
  $totalRows_nearout = $_GET['totalRows_nearout'];
} else {
  $all_nearout = mysql_query($query_nearout);
  $totalRows_nearout = mysql_num_rows($all_nearout);
}
$totalPages_nearout = ceil($totalRows_nearout/$maxRows_timeout)-1;

$queryString_nearout = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_nearout") == false && 
        stristr($param, "totalRows_nearout") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_nearout = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_nearout = sprintf("&totalRows_nearout=%d%s", $totalRows_nearout, $queryString_nearout);
}










//任务状态的所有搜索条件
mysql_select_db($database_tankdb, $tankdb);
$query_tkstatus = "SELECT id, task_status, task_status_display FROM tk_status ORDER BY id ASC";
$tkstatus = mysql_query($query_tkstatus, $tankdb) or die(mysql_error());
$row_tkstatus = mysql_fetch_assoc($tkstatus);
$totalRows_tkstatus = mysql_num_rows($tkstatus);

//查找与自己相关的所有项目
mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_project = sprintf("SELECT id, project_name,project_text, project_start, project_end, project_to_user, project_lastupdate, project_create_time FROM tk_project inner join tk_team on tk_team.tk_team_pid=tk_project.id WHERE tk_team_uid = %s AND tk_team_del_status=1 ORDER BY tk_project.project_name ASC",GetSQLValueString($_SESSION['MM_uid'],"int"));
$Recordset_project = mysql_query($query_Recordset_project, $tankdb) or die(mysql_error());
$row_Recordset_project = mysql_fetch_assoc($Recordset_project);
$totalRows_Recordset_project = mysql_num_rows($Recordset_project);

//与自己相关的所有组的组员
mysql_select_db($database_tankdb, $tankdb);
$query_Recordset2 = sprintf("SELECT * FROM tk_user, tk_team,tk_project WHERE tk_user_del_status=1 AND tk_user.uid in ( select distinct tk_team_uid from tk_team,tk_project WHERE tk_team_del_status=1 AND project_del_status=1 AND tk_team_pid in (SELECT id FROM tk_project inner join tk_team on tk_team.tk_team_pid=tk_project.id WHERE tk_team_uid = %s AND project_del_status=1 AND tk_team_del_status=1)) ORDER BY  tk_display_name ASC",GetSQLValueString($_SESSION['MM_uid'],"int"));
$Recordset2 = mysql_query($query_Recordset2, $tankdb) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);
?>


<script type="text/JavaScript">

function FindStages(projectid){
/*	<?php do { ?>
        <option value="<?php echo $row_Recordset_project['id']?>"
		<?php 
			if (isset($_SESSION['ser_project'])) {	
			if (!(strcmp($row_Recordset_project['id'], "{$_SESSION['ser_project']}"))) {
				echo "selected=\"selected\"";
				}
			}
		?>>
		<?php echo $row_Recordset_project['project_name']?>
		</option>
    <?php
	} while ($row_Recordset_project = mysql_fetch_assoc($Recordset_project));
		$rows = mysql_num_rows($Recordset_project);
		if($rows > 0) {
		mysql_data_seek($Recordset_project, 0);
		$row_Recordset_project = mysql_fetch_assoc($Recordset_project);
	}
	?>*/
}

function GP_popupConfirmMsg(msg) { 
  document.MM_returnValue = confirm(msg);
}



function MM_goToURL() { 
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}

function   searchtask() 
      {document.myform.action= ""; 
        document.myform.submit(); 
        return   true; 
      
      } 
function   exportexcel() 
      {document.myform.action= "excel.php "; 
        document.myform.submit(); 
        return   false; 
      
      } 
	  

<?php 
//echo $strssumlog;
//echo $strslog; 
//echo $strssumbyday;
?>
</script>
<!-- 此处显示过期的任务 -->
<?php if ($pagetabs <> "etask") { // Show outofdate if recordset not empty ?>
<?php if ($totalRows_timeout > 0 && $outofdate=="on") { // Show outofdate if recordset not empty ?>

<div class="panel panel-warning timeout_color pagemarginfix">
  <!-- Default panel contents -->
  <div class="panel-heading"><span class="glyphicon glyphicon-info-sign"></span> <b><?php echo $multilingual_outofdate_title; ?></b> &nbsp;&nbsp;<span ><?php echo $multilingual_outofdate_p; ?></span></div>
  <!-- Table -->
  <table  class="table  table-hover " style="border-bottom:1px #ddd solid;">
         <?php do { ?>
        <tr>
            <td>
                <a href="default_task_edit.php?editID=<?php echo $row_timeout['tid']; ?>" target="_parent">
                [<?php echo $row_timeout['tid']; ?>] <?php echo $row_timeout['csa_text']; ?> 
                </a>
            </td>
            <?php if($pagetabs <> "mtask"){ ?>
            <td>
                <a href="user_view.php?recordID=<?php echo $row_timeout['csa_to_user']; ?> "><?php echo $multilingual_default_task_to; ?>: <?php echo $row_timeout['tk_display_name1']; ?></a>
            </td>
            <?php } ?>
            <td class="gray">
                <?php 
	  $live_days = (strtotime(date("Y-m-d")) - strtotime($row_timeout['csa_plan_et']))/86400;
	  echo $multilingual_outofdate_outofdate.": ".$live_days." ".$multilingual_outofdate_date;
	  ?>
            </td>
        </tr>
        <?php } while ($row_timeout = mysql_fetch_assoc($timeout)); ?>
    </table>
   
   

   
   <table class="rowcon" border="0" align="center" style="margin:10px; ">
<tr>
<td>   <table border="0">
        <tr>
          <td><?php if ($pageNum_timeout > 0) { // Show if not first page ?>
		  
			<!--第一页 -->
              <a href="<?php printf("%s?pageNum_timeout=%d%s", $currentPage, 0, $queryString_timeout); ?>"><?php echo $multilingual_global_first; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_timeout > 0) { // Show if not first page ?>
             
			 <!--上一页 -->
			 <a href="<?php printf("%s?pageNum_timeout=%d%s", $currentPage, max(0, $pageNum_timeout - 1), $queryString_timeout); ?>"><?php echo $multilingual_global_previous; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_timeout < $totalPages_timeout) { // Show if not last page ?>
              
			  <!--下一页 -->
			  <a href="<?php printf("%s?pageNum_timeout=%d%s", $currentPage, min($totalPages_timeout, $pageNum_timeout + 1), $queryString_timeout); ?>"><?php echo $multilingual_global_next; ?></a>
              <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_timeout < $totalPages_timeout) { // Show if not last page ?>
             
			<!--最后一页 -->
			 <a href="<?php printf("%s?pageNum_timeout=%d%s", $currentPage, $totalPages_timeout, $queryString_timeout); ?>"><?php echo $multilingual_global_last; ?></a>
              <?php } // Show if not last page ?></td>
        </tr>
      </table></td>
<td align="right">   <?php echo ($startRow_timeout + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_timeout + $maxRows_timeout, $totalRows_timeout) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_timeout ?> <?php echo $multilingual_outofdate_totle; ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
</table>
</div>
<?php } // Show outofdate if recordset not empty ?>
<?php } // Show outofdate if recordset not empty ?>








<!-- 此处显示即将过期的任务 -->
<?php if ($pagetabs <> "etask") { // Show outofdate if recordset not empty ?>
<?php if ($totalRows_nearout > 0 && $outofdate=="on") { // Show outofdate if recordset not empty ?>

<div class="panel panel-warning timeout_color pagemarginfix">
  <!-- Default panel contents -->
  <div class="panel-heading"><span class="glyphicon glyphicon-info-sign"></span> <b><?php echo $multilingual__near_outofdate_title; ?></b> &nbsp;&nbsp;<span ><?php echo $multilingual_outofdate_p; ?></span></div>
  <!-- Table -->
  <table  class="table  table-hover " style="border-bottom:1px #ddd solid;">
         <?php do { ?>
        <tr>
            <td>
                <a href="default_task_edit.php?editID=<?php echo $row_nearout['tid']; ?>" target="_parent">
                [<?php echo $row_nearout['tid']; ?>] <?php echo $row_nearout['csa_text']; ?> 
                </a>
            </td>
            <?php if($pagetabs <> "mtask"){ ?>
            <td>
                <a href="user_view.php?recordID=<?php echo $row_nearout['csa_to_user']; ?> "><?php echo $multilingual_default_task_to; ?>: <?php echo $row_nearout['tk_display_name1']; ?></a>
            </td>
            <?php } ?>
            <td class="gray">
                <?php 
	  $live_days = (strtotime(date("Y-m-d")) - strtotime($row_nearout['csa_plan_et']))/86400;
	  echo $multilingual_outofdate_outofdate.": ".$live_days." ".$multilingual_outofdate_date;
	  ?>
            </td>
        </tr>
        <?php } while ($row_nearout = mysql_fetch_assoc($nearout)); ?>
    </table>
   
   

   
   <table class="rowcon" border="0" align="center" style="margin:10px; ">
<tr>
<td>   <table border="0">
        <tr>
          <td><?php if ($pageNum_nearout > 0) { // Show if not first page ?>
		  
			<!--第一页 -->
              <a href="<?php printf("%s?pageNum_nearout=%d%s", $currentPage, 0, $queryString_nearout); ?>"><?php echo $multilingual_global_first; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_nearout > 0) { // Show if not first page ?>
             
			 <!--上一页 -->
			 <a href="<?php printf("%s?pageNum_nearout=%d%s", $currentPage, max(0, $pageNum_nearout - 1), $queryString_nearout); ?>"><?php echo $multilingual_global_previous; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_nearout < $totalPages_nearout) { // Show if not last page ?>
              
			  <!--下一页 -->
			  <a href="<?php printf("%s?pageNum_nearout=%d%s", $currentPage, min($totalPages_nearout, $pageNum_nearout + 1), $queryString_nearout); ?>"><?php echo $multilingual_global_next; ?></a>
              <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_nearout < $totalPages_nearout) { // Show if not last page ?>
             
			<!--最后一页 -->
			 <a href="<?php printf("%s?pageNum_nearout=%d%s", $currentPage, $totalPages_nearout, $queryString_nearout); ?>"><?php echo $multilingual_global_last; ?></a>
              <?php } // Show if not last page ?></td>
        </tr>
      </table></td>
<td align="right">   <?php echo ($startRow_nearout + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_nearout + $maxRows_nearout, $totalRows_nearout) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_nearout ?> <?php echo $multilingual_outofdate_totle; ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
</table>
</div>
<?php } // Show outofdate if recordset not empty ?>
<?php } // Show outofdate if recordset not empty ?>




















<div class="tasktab" id="tasktab">
<div class="clearboth"></div>
<?php if($pagetabs <> "etask"){ // Show search bar ?>
<div class="condition">
<a name="task"></a><span>
<form id="form1" name="myform" method="get" class="taskform form-inline">


<select class="form-control " style="width:110px;" name="select_year" id="select_year" >
<option value="--"><?php echo $multilingual_taskf_year; ?></option>
<?php for($i = 2009; $i <= 2050; $i++) { ?>
         <option value="<?php echo $i; ?>" <?php 
		if (isset($_SESSION['ser_year'])) {	
		if (!(strcmp($i, "{$_SESSION['ser_year']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp($i, date("Y")))) {echo "selected=\"selected\"";} ?>><?php echo $i; ?></option>
<?php  }?>
</select>



<select class="form-control"  style="width:110px;" name="textfield" id="textfield">
<option value="--"><?php echo $multilingual_taskf_month; ?></option>
<?php for($i = 1; $i <= 12; $i++) { ?>
         <option value="<?php $xi = $i; if($i<=9){$xi ="0".$i;}   echo $xi; ?>" <?php 
	  if (isset($_SESSION['ser_month'])) {	
		if (!(strcmp($xi, "{$_SESSION['ser_month']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp($i, date("n")))) {echo "selected=\"selected\"";} ?>><?php echo $xi; ?></option>
<?php  }?>
</select>


<!--选择任务的状态-->
<select class="form-control"  style="width:110px;" name="select_st" id="select_st">
		<!--所有状态-->
        <option value="">所有</option>
		
		<!--6种状态 1--未开始；2--进行中； 3--已完成； 4--已验收； 5--被驳回； 6--已过期-->
        <?php
		do {  
		?>
			<option value="<?php echo $row_tkstatus['task_status']; ?>" <?php 
				if (isset($_SESSION['ser_status'])) {	
					if (!(strcmp($row_tkstatus['task_status'], "{$_SESSION['ser_status']}"))) {
						echo "selected=\"selected\"";
					}
				}	
			?>><?php echo $row_tkstatus['task_status']?></option>
		<?php
		}while ($row_tkstatus = mysql_fetch_assoc($tkstatus));
			$rows = mysql_num_rows($tkstatus);
			if($rows > 0) {
			mysql_data_seek($tkstatus, 0);
			$row_tkstatus = mysql_fetch_assoc($tkstatus);
		}
		?>
      </select>
	    
	  <!--//优先级搜索选项-->
	  <select class="form-control"style="width:120px;" name="select_prt" id="select_prt">
        <option value="">所有</option>//全部优先级
        <option value="<?php echo $multilingual_dd_priority_p5; ?>" <?php if (isset($_SESSION['ser_tkprt'])) {	
		if (!(strcmp($multilingual_dd_priority_p5, "{$_SESSION['ser_tkprt']}"))) {
			echo "selected=\"selected\"";
			}
		}?>><?php echo $multilingual_dd_priority_p5; ?></option>

        <option value="<?php echo $multilingual_dd_priority_p4; ?>" <?php if (isset($_SESSION['ser_tkprt'])) {	
		if (!(strcmp($multilingual_dd_priority_p4, "{$_SESSION['ser_tkprt']}"))) {
			echo "selected=\"selected\"";
			}
		}?>><?php echo $multilingual_dd_priority_p4; ?></option>

        <option value="<?php echo $multilingual_dd_priority_p3; ?>" <?php if (isset($_SESSION['ser_tkprt'])) {	
		if (!(strcmp($multilingual_dd_priority_p3, "{$_SESSION['ser_tkprt']}"))) {
			echo "selected=\"selected\"";
			}
		}?>><?php echo $multilingual_dd_priority_p3; ?></option>

        <option value="<?php echo $multilingual_dd_priority_p2; ?>" <?php if (isset($_SESSION['ser_tkprt'])) {	
		if (!(strcmp($multilingual_dd_priority_p2, "{$_SESSION['ser_tkprt']}"))) {
			echo "selected=\"selected\"";
			}
		}?>><?php echo $multilingual_dd_priority_p2; ?></option>

        <option value="<?php echo $multilingual_dd_priority_p1; ?>" <?php if (isset($_SESSION['ser_tkprt'])) {	
		if (!(strcmp($multilingual_dd_priority_p1, "{$_SESSION['ser_tkprt']}"))) {
			echo "selected=\"selected\"";
			}
		}?>><?php echo $multilingual_dd_priority_p1; ?></option>
      </select> 
	  
	  
	  
	  
	  	<!--查找该用户所属的所有项目-->	  
	  <select class="form-control " style="width:200px;"  name="select_project" id="select_project" onclic=>
        <option value="">所有项目</option>
        <?php
		if(mysql_num_rows($Recordset_project)>0){
		do {  
		?>
        <option value="<?php echo $row_Recordset_project['id']?>"
		<?php 
		if (isset($_SESSION['ser_project'])) {	
		if (!(strcmp($row_Recordset_project['id'], "{$_SESSION['ser_project']}"))) {
			echo "selected=\"selected\"";
			}
		}
 ?>><?php echo $row_Recordset_project['project_name']?></option>
        <?php
} while ($row_Recordset_project = mysql_fetch_assoc($Recordset_project));
  $rows = mysql_num_rows($Recordset_project);
  if($rows > 0) {
      mysql_data_seek($Recordset_project, 0);
	  $row_Recordset_project = mysql_fetch_assoc($Recordset_project);
  }
		}
?>
      </select>
	  
	  
	  <!--查找选定项目中的所有阶段-->  
	  <select class="form-control "style="width:200px;"  name="select_stage" id="select_stage">
        <option value=""><?php echo $multilingual_taskf_stage; ?></option>
      </select>
	  
	  <!--执行人-->  
	  <?php if ($pagetabs <> "mtask") {  ?>
	  <select class="form-control " style="width:160px;" id="select4" name="select4">
        <option value="%"><?php echo $multilingual_taskf_touser; ?></option>
		<!-- 不限执行人 -->
        <?php
do {  
?>
        <option value="<?php echo $row_Recordset2['uid']?>"
		<?php 
		if (isset($_SESSION['ser_touser'])) {	
		if (!(strcmp($row_Recordset2['uid'], "{$_SESSION['ser_touser']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if(!(strcmp($row_Recordset2['uid'], "{$_SESSION['MM_uid']}"))) {
				echo "selected=\"selected\"";
				} ?>><?php echo $row_Recordset2['tk_display_name']?></option>
        <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  $rows = mysql_num_rows($Recordset2);
  if($rows > 0) {
      mysql_data_seek($Recordset2, 0);
	  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  }
?>
      </select>
	  <?php } ?>

	  <!--查找创建人--> 
	  <select  class="form-control " style="width:160px;" name="create_by" id="create_by" <?php if ($pagetabs <> "alltask") { echo "style='display:none'"; }?>>
      <option value="%"><?php echo $multilingual_taskf_createuser; ?></option>
      <?php
do {  
?>
      <option value="<?php echo $row_Recordset2['uid']?>"
	  <?php 
		if (isset($_SESSION['ser_createuser'])) {	
		if (!(strcmp($row_Recordset2['uid'], "{$_SESSION['ser_createuser']}"))) {
			echo "selected=\"selected\"";
			}
		}
 ?>><?php echo $row_Recordset2['tk_display_name']?></option>
      <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  $rows = mysql_num_rows($Recordset2);
  if($rows > 0) {
      mysql_data_seek($Recordset2, 0);
	  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  }
?>
    </select>


	  <input name="pagetab" id="pagetab" value="<?php echo $pagetabs; ?>" style="display:none" />
	  <button type="submit" name="search" id="search"  class="btn btn-default btn-sm" onclick= "return   searchtask(); " /><span class="glyphicon glyphicon-filter" style="display:inline;"></span> 
	  <?php echo $multilingual_global_filterbtn; ?>
	  </button>
	  <button type="button" class="btn btn-default" name="export" id="export"  onclick= "return   exportexcel(); " ><?php echo $multilingual_global_excel; ?></button>
	</form>
	</span>
	<?php if($pagetabs == "alltask") { // Show searchbox if page is alltask ?>
	<span>
<form id="form2" name="myform2" method="get" class="taskform form-inline">
		  <select class="form-control "  style="width:200px;float:left;" name="searchby" id="searchby" >
		    <option value="tit"><?php echo $multilingual_tasks_title; ?></option>
		    <option value="tag"><?php echo $multilingual_tasks_tag; ?></option>
	      </select>
		  <input class="form-control " type="text" style="width:200px;float:left;"name="inputval" id="inputval" value="" />
		  <input style="display:none" type="text" name="pagetab" value="alltask" />
		  <input style="display:none" type="text" name="select4" value="%" />
		  <input style="display:none" type="text" name="select_year" value="--" />
		  <input style="display:none" type="text" name="textfield" value="--" />

		  <button type="submit" style="width:110px;float:left;" name="search1" id="search1" class="btn btn-default btn-sm" /><span class="glyphicon glyphicon-search" style="display:inline;"></span> <?php echo $multilingual_global_searchbtn; ?></button>
          </form>
		  </span>
<?php }  // Show searchbox if page is alltask ?>
<div  class="clearboth"> </div>
</div>
<?php } // Show search bar ?>
<?php if ($totalRows_Recordset1 > 0) { // Show task list if recordset not empty ?>

<!--table left -->
<div class="tbody_bl" id="tbody_bl">

<table  border="0" cellspacing="0" cellpadding="0" align="center"  class="maintable tasktab_bl" >

 <thead  class="toptable tasktab_tl" >
    <tr>
      <th width="15%;"   class="topic">No.</th>      
      <th width="55%;" class="topic" >	  
	  <a href="<?php echo $pagenames; ?>?<?php echo $current_url; ?>&sort=csa_text&order=<?php 
	  if ( $sortlist <> "csa_text"){
	  echo "DESC";
	  }else if( $sortlist == "csa_text" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="csa_text" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="csa_text" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>><?php echo $multilingual_default_task_title; ?></a></th>
      <th width="30%" class="topic"><a href="<?php echo $pagenames; ?>?<?php echo $current_url; ?>&sort=csa_to_user&order=<?php 
	  if ( $sortlist <> "csa_to_user"){
	  echo "DESC";
	  }else if( $sortlist == "csa_to_user" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="csa_to_user" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="csa_to_user" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>><?php echo $multilingual_default_task_to; ?></a></th>
      </tr>
   </thead>
   <tbody >
        <?php do { ?>
        <tr  title="<?php echo $row_Recordset1['csa_text']; ?>" class="<?php if($R_list%2==1){ echo "odd_line"; }else{echo "even_line"; } ?>" >
      <td class="week_style_padtd"   ><?php echo $R_list; ?></td>
      <td class="week_style_padtd" 
	data-ellipsis="true"
	data-ellipsis-max-width="200px"><a href="default_task_edit.php?editID=<?php echo $row_Recordset1['tid']; ?>&pagetab=<?php echo $pagetabs; ?>"  target="_parent"> <?php echo $row_Recordset1['csa_text']; ?></a></td>
      <td class="week_style_padtd" >
	  <a href="user_view.php?recordID=<?php echo $row_Recordset1['csa_to_user']; ?>" target="_parent">
	  <?php echo $row_Recordset1['tk_display_name1']; ?></a></td>
      </tr>
    <?php 	$R_list++;
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));

	$rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>  
    </tbody>

  </table>
  
</div>

<script src="js/jquery/jquery.ellipsis.js"></script>
<script src="js/jquery/jquery.ellipsis.unobtrusive.js"></script>
<!--table right -->
<div class="tbody_br"  id="tbody_br"  >
  <table  border="0" cellspacing="0" cellpadding="0" align="center"  class="maintable tasktab_br" >
   
     <thead  class="toptable " >
	 <!--状态-->
    <tr class="righttable_head" >
      <th rowspan="2"  class="status">
	  <a href="<?php echo $pagenames; ?>?<?php echo $current_url; ?>&sort=csa_status&order=<?php 
	  if ( $sortlist <> "csa_status"){
	  echo "DESC";
	  }else if( $sortlist == "csa_status" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="csa_status" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="csa_status" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	  <?php echo $multilingual_default_task_status; ?></a></th>
	  <!--优先级-->
      <th rowspan="2" class="attr">
	  <a href="<?php echo $pagenames; ?>?<?php echo $current_url; ?>&sort=csa_priority&order=<?php 
	  if ( $sortlist <> "csa_priority"){
	  echo "DESC";
	  }else if( $sortlist == "csa_priority" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>#fromsite" 
	  <?php 
	  if($sortlist=="csa_priority" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="csa_priority" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	  <?php echo $multilingual_default_task_priority; ?></a></th>
	   <!--工作量-->
      <th rowspan="2" class="planpv"><?php echo $multilingual_default_task_planpv; ?></th>    
	  <!--计划开始时间-->
	  <th rowspan="2"  class="time" >
	  <a href="<?php echo $pagenames; ?>?<?php echo $current_url; ?>&sort=csa_plan_st&order=<?php 
	  if ( $sortlist <> "csa_plan_st"){
	  echo "DESC";
	  }else if( $sortlist == "csa_plan_st" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="csa_plan_st" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="csa_plan_st" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	  <?php echo $multilingual_default_task_planstart; ?></a></th>
	  <!--计划完成时间-->
      <th rowspan="2"  class="time"  >
	  <a href="<?php echo $pagenames; ?>?<?php echo $current_url; ?>&sort=csa_plan_et&order=<?php 
	  if ( $sortlist <> "csa_plan_et"){
	  echo "DESC";
	  }else if( $sortlist == "csa_plan_et" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="csa_plan_et" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="csa_plan_et" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	  <?php echo $multilingual_default_task_planend; ?></a></th>
	  <!--所属项目-->
      <th rowspan="2" class="attr"  >
	  <a href="<?php echo $pagenames; ?>?<?php echo $current_url; ?>&sort=csa_project&order=<?php 
	  if ( $sortlist <> "csa_project"){
	  echo "DESC";
	  }else if( $sortlist == "csa_project" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="csa_project" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="csa_project" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	  <?php echo $multilingual_default_task_project; ?></a></th>
	  <!--所属阶段-->
      <th rowspan="2" class="attr" >
	  <a href="<?php echo $pagenames; ?>?<?php echo $current_url; ?>&sort=ser_stage&order=<?php 
	  if ( $sortlist <> "ser_stage"){
	  echo "DESC";
	  }else if( $sortlist == "ser_stage" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="ser_stage" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="ser_stage" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	  <?php echo $multilingual_default_task_stage; ?></a></th>
	  <!--来自-->
      <th rowspan="2" class="attr" >
	  <a href="<?php echo $pagenames; ?>?<?php echo $current_url; ?>&sort=csa_from_user&order=<?php 
	  if ( $sortlist <> "csa_from_user"){
	  echo "DESC";
	  }else if( $sortlist == "csa_from_user" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>#fromsite" 
	  <?php 
	  if($sortlist=="csa_from_user" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="csa_from_user" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	  <?php echo $multilingual_default_task_from; ?></a></th>
	<!--上次更新时间-->
      <th rowspan="2" class="lasttime">
	  <a href="<?php echo $pagenames; ?>?<?php echo $current_url; ?>&sort=csa_last_update&order=<?php 
	  if ( $sortlist <> "csa_last_update"){
	  echo "DESC";
	  }else if( $sortlist == "csa_last_update" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>#fromsite" 
	  <?php 
	  if($sortlist=="csa_last_update" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="csa_last_update" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	  <?php echo $multilingual_default_task_update_time; ?></a></th>
	  
      </tr>
   </thead>     
<tbody class="tasktab_t2">
     <?php 
	 $R_list=1;
	 do { ?>
     <tr  class="<?php if($R_list%2==1){ echo "odd_line"; }else{echo "even_line"; } ?>">
         <td class="week_style_padtd"  width="100px" align="center"><?php echo $row_Recordset1['task_status_display']; ?></td>
		 
       <td class="week_style_padtd" width="70px" align="center">
	   <?php echo $row_Recordset1['csa_priority']; ?>
	   </td>
         <td class="week_style_padtd" width="80px" align="center">
       
       <?php echo $row_Recordset1['csa_plan_hour']; ?>&nbsp;
       </td>
       <td class="week_style_padtd" width="80px" align="center">
       
       <?php echo $row_Recordset1['csa_plan_st']; ?>&nbsp;
       </td>  
		<td class="week_style_padtd <?php 

		$today = date("Y-m-d");   
		if($today > $row_Recordset1['csa_plan_et'] && strpos($row_Recordset1['task_status_display'], $multilingual_dd_status_stfinish) == false){
	   echo "red";
	   }   
	   ?>" width="70px" align="center">
       
       <?php echo $row_Recordset1['csa_plan_et']; ?>&nbsp;
       </td>

       <td class="week_style_padtd" width="200px" >
		 <a href="project_view.php?recordID=<?php echo $row_Recordset1['csa_project']; ?>" target="_parent" title="<?php echo $row_Recordset1['project_name_prt']; ?>"><?php echo $row_Recordset1['project_name_prt']; ?></a>
       </td>
		<td class="week_style_padtd "  width="200px">
		 <a href="stage_view.php?recordID=<?php echo $row_Recordset1['csa_project_stage']; ?>" target="_parent" title="<?php echo $row_Recordset1['stage_name_prt']; ?>"><?php echo $row_Recordset1['stage_name_prt']; ?></a>
       </td>
	   
       <td class="week_style_padtd"  width="100px" align="center">
	   <a href="user_view.php?recordID=<?php echo $row_Recordset1['csa_from_user']; ?>" target="_parent">
	   <?php echo $row_Recordset1['tk_display_name2']; ?></a>
	   </td>
       
      <td class="week_style_padtd" width="112px" align="center"><span 
	   <?php 
		$lonelastday = $row_Recordset1['csa_last_update']; 
		$lastday = substr($lonelastday,0,10);   
		if($lastday > $row_Recordset1['csa_plan_et']){
	   echo "class='red'";
	   }   
	   ?>
	   >
       <?php 
		
	   echo $lastday;
	   ?></span>&nbsp;
       </td>
	  
    </tr>
     <?php $R_list++;
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>  
   </tbody>
  </table>
  

</div>
<?php } // Show task list if recordset not empty ?>
<div class="clearboth"></div>
</div>
<?php if ($totalRows_Recordset1 > 0) { // Show nextpage if task list recordset not empty ?>
<table class="rowcon" border="0" align="center">
<tr>
<td>  <table border="0">

<td align="left"><?php echo ($startRow_Recordset1 + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset1 + $maxRows_Recordset1, $totalRows_Recordset1) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset1 ?>)</td>
</tr>
  <tr>
    <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, 0, $queryString_Recordset1); ?>"><?php echo $multilingual_global_first; ?></a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, max(0, $pageNum_Recordset1 - 1), $queryString_Recordset1); ?>"><?php echo $multilingual_global_previous; ?></a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, min($totalPages_Recordset1, $pageNum_Recordset1 + 1), $queryString_Recordset1); ?>"><?php echo $multilingual_global_next; ?></a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, $totalPages_Recordset1, $queryString_Recordset1); ?>"><?php echo $multilingual_global_last; ?></a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
      </td>
</table>

<?php } // Show nextpage if task list recordset not empty ?>

<?php if ($totalRows_Recordset1 == 0) { // Show tips if recordset empty ?>
<div class="alert alert-warning search_warning" style="margin:6px;">
  <?php echo $multilingual_default_sorrytipup; ?>
</div>
<?php } // Show tips if recordset empty ?>