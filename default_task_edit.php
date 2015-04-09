<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];
 
$colname_Recordset_task = "-1";
if (isset($_GET['editID'])) {
  $colname_Recordset_task = $_GET['editID'];
}

$pagetabs = "mtask";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}

if($pagetabs=="mtask"){
$tasklisturl = "index.php?select=&select_project=&select_year=".date("Y")."&textfield=".date("m")."&select3=-1&select4=".$_SESSION['MM_uid']."&select_prt=&select_temp=&inputtitle=&select1=-1&select2=%&create_by=%&select_type=&inputid=&inputtag=";
}else if ($pagetabs=="ftask") {
$tasklisturl = "index.php?select=&select_project=&select_year=".date("Y")."&textfield=".date("m")."&select3=-1&select4=%&select_prt=&select_temp=&inputtitle=&select1=-1&select2=".$_SESSION['MM_uid']."&create_by=%&select_type=&inputid=&inputtag=&pagetab=ftask";
}else if ($pagetabs=="ctask"){
$tasklisturl = "index.php?select=&select_project=&select_year=".date("Y")."&textfield=".date("m")."&select3=-1&select4=%&select_prt=&select_temp=&inputtitle=&select1=-1&select2=%&create_by=".$_SESSION['MM_uid']."&select_type=&inputid=&inputtag=&pagetab=ctask";
} else if ($pagetabs=="etask"){
$tasklisturl = "index.php?select=&select_project=&select_year=--&textfield=--&select3=-1&select4=%&select_prt=&select_temp=&select_exam=".$multilingual_dd_status_exam."&inputtitle=&select1=-1&select2=".$_SESSION['MM_uid']."&select_type=&inputid=&inputtag=&pagetab=etask";
}  else if ($pagetabs=="alltask"){
$tasklisturl = "index.php?select=&select_project=&select_year=".date("Y")."&textfield=".date("m")."&select3=-1&select4=%&select_prt=&select_temp=&inputtitle=&select1=-1&select2=%&create_by=%&select_type=&inputid=&inputtag=&pagetab=alltask";
}else if ($pagetabs=="cctome"){
$tasklisturl = "index.php?select=&select_project=&select_year=".date("Y")."&textfield=".date("m")."&select3=-1&select4=%&select_prt=&select_temp=&inputtitle=&select1=-1&select2=%&select_type=&inputid=&inputtag=&pagetab=cctome";
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_task = sprintf("SELECT *, 
tk_user1.tk_display_name as tk_display_name1, 
tk_user2.tk_display_name as tk_display_name2, 
tk_user3.tk_display_name as tk_display_name3, 
tk_user4.tk_display_name as tk_display_name4,
tk_project.id as proid    
FROM tk_task 
inner join tk_task_tpye on tk_task.csa_type=tk_task_tpye.id 
inner join tk_status on tk_task.csa_remark2=tk_status.id 
inner join tk_user as tk_user1 on tk_task.csa_to_user=tk_user1.uid 
inner join tk_user as tk_user2 on tk_task.csa_from_user=tk_user2.uid 
inner join tk_user as tk_user3 on tk_task.csa_create_user=tk_user3.uid 
inner join tk_user as tk_user4 on tk_task.csa_last_user=tk_user4.uid 
inner join tk_project on tk_task.csa_project=tk_project.id 
WHERE TID = %s", GetSQLValueString($colname_Recordset_task, "int"));
$Recordset_task = mysql_query($query_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);
$totalRows_Recordset_task = mysql_num_rows($Recordset_task);


$taskid = $_GET['editID'];

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_sumlog = sprintf("SELECT sum(csa_tb_manhour) as sum_hour FROM tk_task_byday WHERE csa_tb_backup1= %s", GetSQLValueString($taskid, "int"));
$Recordset_sumlog = mysql_query($query_Recordset_sumlog, $tankdb) or die(mysql_error());
$row_Recordset_sumlog = mysql_fetch_assoc($Recordset_sumlog);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_countlog = sprintf("SELECT COUNT(*) as count_log FROM tk_task_byday WHERE csa_tb_backup1= %s", GetSQLValueString($taskid, "int"));
$Recordset_countlog = mysql_query($query_Recordset_countlog, $tankdb) or die(mysql_error());
$row_Recordset_countlog = mysql_fetch_assoc($Recordset_countlog);

$maxRows_Recordset_comment = 10;
$pageNum_Recordset_comment = 0;
if (isset($_GET['pageNum_Recordset_comment'])) {
  $pageNum_Recordset_comment = $_GET['pageNum_Recordset_comment'];
}
$startRow_Recordset_comment = $pageNum_Recordset_comment * $maxRows_Recordset_comment;

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_comment = sprintf("SELECT * FROM tk_comment 
inner join tk_user on tk_comment.tk_comm_user =tk_user.uid 
								 WHERE tk_comm_pid = %s AND tk_comm_type = 1 
								
								ORDER BY tk_comm_lastupdate DESC", 
								GetSQLValueString($colname_Recordset_task, "text")
								);
$query_limit_Recordset_comment = sprintf("%s LIMIT %d, %d", $query_Recordset_comment, $startRow_Recordset_comment, $maxRows_Recordset_comment);
$Recordset_comment = mysql_query($query_limit_Recordset_comment, $tankdb) or die(mysql_error());
$row_Recordset_comment = mysql_fetch_assoc($Recordset_comment);

if (isset($_GET['totalRows_Recordset_comment'])) {
  $totalRows_Recordset_comment = $_GET['totalRows_Recordset_comment'];
} else {
  $all_Recordset_comment = mysql_query($query_Recordset_comment);
  $totalRows_Recordset_comment = mysql_num_rows($all_Recordset_comment);
}
$totalPages_Recordset_comment = ceil($totalRows_Recordset_comment/$maxRows_Recordset_comment)-1;

$queryString_Recordset_comment = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_comment") == false && 
        stristr($param, "totalRows_Recordset_comment") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_comment = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_comment = sprintf("&totalRows_Recordset_comment=%d%s", $totalRows_Recordset_comment, $queryString_Recordset_comment);

$maxRows_Recordset_actlog = 10;
$pageNum_Recordset_actlog = 0;
if (isset($_GET['pageNum_Recordset_actlog'])) {
  $pageNum_Recordset_actlog = $_GET['pageNum_Recordset_actlog'];
}
$startRow_Recordset_actlog = $pageNum_Recordset_actlog * $maxRows_Recordset_actlog;

//权限小于4只能看自己的任务 nbnat.com QQ:39299672
if($_SESSION['MM_rank'] < 4){
	$zwhere.= "AND tk_log_user={$_SESSION['MM_uid']} ";
}
mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_actlog = sprintf("SELECT * FROM tk_log 
inner join tk_user on tk_log.tk_log_user =tk_user.uid 
								WHERE tk_log_type = %s AND tk_log_class = 1 
								{$zwhere}
								
								ORDER BY tk_log_time DESC", 
								GetSQLValueString($colname_Recordset_task, "text")
								);
$query_limit_Recordset_actlog = sprintf("%s LIMIT %d, %d", $query_Recordset_actlog, $startRow_Recordset_actlog, $maxRows_Recordset_actlog);
echo $query_limit_Recordset_actlog;
$Recordset_actlog = mysql_query($query_limit_Recordset_actlog, $tankdb) or die(mysql_error());
$row_Recordset_actlog = mysql_fetch_assoc($Recordset_actlog);

if (isset($_GET['totalRows_Recordset_actlog'])) {
  $totalRows_Recordset_actlog = $_GET['totalRows_Recordset_actlog'];
} else {
  $all_Recordset_actlog = mysql_query($query_Recordset_actlog);
  $totalRows_Recordset_actlog = mysql_num_rows($all_Recordset_actlog);
}
$totalPages_Recordset_actlog = ceil($totalRows_Recordset_actlog/$maxRows_Recordset_actlog)-1;

$queryString_Recordset_actlog = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_actlog") == false && 
        stristr($param, "totalRows_Recordset_actlog") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_actlog = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_actlog = sprintf("&totalRows_Recordset_actlog=%d%s", $totalRows_Recordset_actlog, $queryString_Recordset_actlog);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_sumtotal = sprintf("SELECT 
							COUNT(*) as count_task   
							FROM tk_status  							
							WHERE task_status_backup2 = '1'"
								);
$Recordset_sumtotal = mysql_query($query_Recordset_sumtotal, $tankdb) or die(mysql_error());
$row_Recordset_sumtotal = mysql_fetch_assoc($Recordset_sumtotal);
$exam_totaltask=$row_Recordset_sumtotal['count_task'];

//for wbs!

$maxRows_Recordset_subtask = 15;
$pageNum_Recordset_subtask = 0;
if (isset($_GET['pageNum_Recordset_subtask'])) {
  $pageNum_Recordset_subtask = $_GET['pageNum_Recordset_subtask'];
}
$startRow_Recordset_subtask = $pageNum_Recordset_subtask * $maxRows_Recordset_subtask;

//$colname_Recordset_subtask = $row_DetailRS1['tk_user_login'];

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_subtask = sprintf("SELECT * 
							FROM tk_task 
							inner join tk_task_tpye on tk_task.csa_type=tk_task_tpye.id								
							inner join tk_user on tk_task.csa_to_user=tk_user.uid 
							inner join tk_status on tk_task.csa_remark2=tk_status.id 
							WHERE tk_task.csa_remark4 = %s ORDER BY csa_last_update DESC", 
								GetSQLValueString($colname_Recordset_task, "text")
								);
$query_limit_Recordset_subtask = sprintf("%s LIMIT %d, %d", $query_Recordset_subtask, $startRow_Recordset_subtask, $maxRows_Recordset_subtask);
$Recordset_subtask = mysql_query($query_limit_Recordset_subtask, $tankdb) or die(mysql_error());
$row_Recordset_subtask = mysql_fetch_assoc($Recordset_subtask);

if (isset($_GET['totalRows_Recordset_subtask'])) {
  $totalRows_Recordset_subtask = $_GET['totalRows_Recordset_subtask'];
} else {
  $all_Recordset_subtask = mysql_query($query_Recordset_subtask);
  $totalRows_Recordset_subtask = mysql_num_rows($all_Recordset_subtask);
}
$totalPages_Recordset_subtask = ceil($totalRows_Recordset_subtask/$maxRows_Recordset_subtask)-1;

$queryString_Recordset_subtask = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_subtask") == false && 
        stristr($param, "totalRows_Recordset_subtask") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_subtask = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_subtask = sprintf("&totalRows_Recordset_subtask=%d%s", $totalRows_Recordset_subtask, $queryString_Recordset_subtask);


if ($row_Recordset_task['csa_remark6'] == "-1" ){
$wbs_id = "1";
} else {
$wbs_id = $row_Recordset_task['csa_remark6'];
}


$wbsID = $wbs_id + 1;

if ($row_Recordset_task['csa_remark6'] == "-1"){
$wbssum = $row_Recordset_task['TID'].">".$wbsID;
}else {
$wbssum = $row_Recordset_task['csa_remark5'].">".$row_Recordset_task['TID'].">".$wbsID;
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_sumplan = "SELECT round(sum(csa_plan_hour),1) as sum_plan_hour FROM tk_task 
inner join tk_task_tpye on tk_task.csa_type=tk_task_tpye.id 
WHERE task_tpye NOT LIKE '$multilingual_dd_status_ca' AND csa_remark5 LIKE '$wbssum%'";
$Recordset_sumplan = mysql_query($query_Recordset_sumplan, $tankdb) or die(mysql_error());
$row_Recordset_sumplan = mysql_fetch_assoc($Recordset_sumplan);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_sumsublog = "SELECT round(sum(csa_tb_manhour),1) as sum_sublog FROM tk_task  
inner join tk_task_byday on tk_task.TID=tk_task_byday.csa_tb_backup1 
WHERE csa_remark5 LIKE '$wbssum%'";
$Recordset_sumsublog = mysql_query($query_Recordset_sumsublog, $tankdb) or die(mysql_error());
$row_Recordset_sumsublog = mysql_fetch_assoc($Recordset_sumsublog);

$pattaskid = $row_Recordset_task['csa_remark4'];

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_pattask = "SELECT * FROM tk_task inner join tk_task_tpye on tk_task.csa_type=tk_task_tpye.id WHERE TID = '$pattaskid'";
$Recordset_pattask = mysql_query($query_Recordset_pattask, $tankdb) or die(mysql_error());
$row_Recordset_pattask = mysql_fetch_assoc($Recordset_pattask);

$task_arr = array ();
$task_arr['name'] = $row_Recordset_task['csa_text'];
$task_arr['type'] = $row_Recordset_task['csa_type'];
$task_arr['to'] = $row_Recordset_task['csa_to_user'];
$task_arr['from'] = $row_Recordset_task['csa_from_user'];
$task_arr['priority'] = $row_Recordset_task['csa_priority'];
$task_arr['start'] = $row_Recordset_task['csa_plan_st'];
$task_arr['end'] = $row_Recordset_task['csa_plan_et'];
$task_arr['hour'] = $row_Recordset_task['csa_plan_hour'];
$task_arr['text'] = $row_Recordset_task['csa_remark1'];
$task_arr['status'] = $row_Recordset_task['csa_remark2'];
$task_arr['cc'] = $row_Recordset_task['test01'];
$task_arr['tag'] = $row_Recordset_task['test02'];

$_SESSION['copytask'] = $task_arr;
?>
<?php require('head.php'); ?>
<script type="text/javascript" language="javascript">    
//禁止滚动条
$(document.body).css({
   "overflow-x":"hidden",
   "overflow-y":"hidden"
});

 
function TuneHeight()    
{    
var frm = document.getElementById("frame_content");    
var subWeb = document.frames ? document.frames["main_frame"].document : frm.contentDocument;    
if(frm != null && subWeb != null)    
{ frm.height = subWeb.body.scrollHeight;}    
}    
 

        $(document).ready(function() {
            var h = $(window).height(), h2;
            var h = h - <?php if($totalRows_Recordset_anc > 0) {echo "75";} else {echo "40";} ?>;
            $("#main_right").css("height", h);
            $(window).resize(function() {
                h2 = $(this).height();
                $("#main_right").css("height", h2);
            });
        })
</script>

<script type="text/javascript">

function addcomm()
{
    J.dialog.get({ id: "test1", title: '<?php echo $multilingual_default_addcom; ?>', width: 600, height: 500, page: "comment_add.php?taskid=<?php echo $row_Recordset_task['TID']; ?>&type=1" });
}

function addtask()
{
    J.dialog.get({ id: "taskadd", title: '<?php echo $multilingual_default_task_copyto; ?>', width: 400, height: 350, page: "task_add_selprj.php?section=1&copy=1" });
}
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td width="295px" class="input_task_right_bg" valign="top">
      <table width="280px" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td valign="top"><?php
		  $project_id = $row_Recordset_task['csa_project'];
		  $project_name = $row_Recordset_task['project_name'];
		  $node_id_task = $row_Recordset_task['TID'];
		   require_once('tree.php'); ?></td>
        </tr>
      
      </table></td>
    <td  valign="top">
        <div style="overflow:auto; " id="main_right"><!-- right main -->
        <table width="98%" border="0" cellspacing="0" cellpadding="5" align="center">
        <tr>
          <td >			
		<ul class="breadcrumb" style="margin-top:10px;">
			  <li><?php echo $multilingual_default_taskproject; ?>: <a href="project_view.php?recordID=<?php echo $row_Recordset_task['id']; ?>" ><?php echo $row_Recordset_task['project_name']; ?></a></li>
			  <li><?php if ($row_Recordset_task['csa_remark4'] <> -1) { ?>
            <?php echo $multilingual_default_task_parent; ?>: <a href="default_task_edit.php?editID=<?php echo $row_Recordset_pattask['TID']; ?>" >[<?php echo $row_Recordset_pattask['task_tpye']; ?>] <?php echo $row_Recordset_pattask['csa_text']; ?></a> 
            <?php } else {
	 echo $multilingual_subtask_root;
	  } ?></li>
	  <div class="float_right gray"><?php echo $multilingual_global_action_create; ?>: <a href="user_view.php?recordID=<?php echo $row_Recordset_task['csa_create_user']; ?>"><?php echo $row_Recordset_task['tk_display_name3']; ?></a>&nbsp;&nbsp; <?php echo $multilingual_default_taskid; ?>: <?php echo $row_Recordset_task['TID']; ?></div>
            </ul>	
			</td>
        </tr>
        <tr>
          <td >
            <span class="breakwords float_left"><h2>[<?php echo $row_Recordset_task['task_tpye']; ?>] <?php echo htmlentities($row_Recordset_task['csa_text'], ENT_COMPAT, 'utf-8'); ?></h2></span> </td>
        </tr>
        <?php if($row_Recordset_task['test02'] <> " " && $row_Recordset_task['test02'] <> "" ) { ?>
        <tr>
          <td><span class="gray"><?php echo $row_Recordset_task['test02']; ?></span> </td>
        </tr>
        <?php } ?>
		<tr>
		<td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="info_task_bg" style="margin-bottom:10px;">
  
  <tr>
    <td width="12%" class="info_task_title"><?php echo $multilingual_default_task_to; ?></td>
    <td width="40%"><a href="user_view.php?recordID=<?php echo $row_Recordset_task['csa_to_user']; ?>"><?php echo $row_Recordset_task['tk_display_name1']; ?></a>
            <?php if($row_Recordset_countlog['count_log'] == "0" && $_SESSION['MM_uid'] == $row_Recordset_task['csa_to_user'] && $_SESSION['MM_rank'] > "1") { ?>
&nbsp;&nbsp;            

<a data-toggle="modal" href="default_task_edituser.php?taskid=<?php echo $row_Recordset_task['TID']; ?>" data-target="#edituserModal">[<?php echo $multilingual_tasklog_changeuser; ?>]</a>
            <?php } else { ?>
            <b title="<?php echo $multilingual_tasktype_lock; ?>">[?]</b>
            <?php }  ?></td>
    <td width="12%" class="info_task_title"><?php echo $multilingual_default_task_from; ?></td>
    <td><a href="user_view.php?recordID=<?php echo $row_Recordset_task['csa_from_user']; ?>"><?php echo $row_Recordset_task['tk_display_name2']; ?></a></td>
    </tr>
	<?php if ($row_Recordset_task['test01'] <> null) {?>
  <tr>
    <td class="info_task_title"><?php echo $multilingual_default_task_cc; ?></td>
    <td colspan="3"><?php //var_dump(json_decode($row_Recordset_task['test01'], true));  
	
	$ccarr = json_decode($row_Recordset_task['test01'], true);
	foreach($ccarr as $k=>$v){

if(isset($v['uid']) && isset($v['uname'])  ){
 echo "<a href=user_view.php?recordID=".$v['uid'].">".$v['uname']."</a> &nbsp;&nbsp;&nbsp;";
}
}
	
	
	
	
	?></td>
    </tr>
	<?php } ?>
</table>
		
		
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="info_task_bg" >
  <tr>
    <td width="12%" class="info_task_title"><?php echo $multilingual_default_task_status; ?></td>
    <td  width="40%"><div class="float_left view_task_status"><?php echo $row_Recordset_task['task_status_display']; ?></div></td>
    <td  width="12%" class="info_task_title"><?php echo $multilingual_default_task_priority; ?></td>
    <td><?php
switch ($row_Recordset_task['csa_priority'])
{
case 5:
  echo $multilingual_dd_priority_p5;
  break;
case 4:
  echo $multilingual_dd_priority_p4;
  break;
case 3:
  echo $multilingual_dd_priority_p3;
  break;
case 2:
  echo $multilingual_dd_priority_p2;
  break;
case 1:
  echo $multilingual_dd_priority_p1;
  break;
}
?></td>
    </tr>
  <tr>
    <td class="info_task_title"><?php echo $multilingual_tasklog_cost; ?></td>
    <td><?php if($row_Recordset_sumlog["sum_hour"] == null){
	  $sum_hour = 0;
	  } else {
	  $sum_hour = $row_Recordset_sumlog["sum_hour"];
	  }
	  echo $sum_hour;?>
        <?php echo $multilingual_global_hour; ?></td>
    <td class="info_task_title"><?php echo $multilingual_default_task_planstart; ?></td>
    <td><?php echo $row_Recordset_task['csa_plan_st']; ?></td>
    </tr>
  <tr>
    <td class="info_task_title"><?php echo $multilingual_default_task_planhour; ?></td>
    <td><?php if($row_Recordset_task['csa_plan_hour'] == null){
	  $plan_hour = 0;
	  } else {
	  $plan_hour = $row_Recordset_task['csa_plan_hour'];
	  }
	  echo $plan_hour;?>
        <?php echo $multilingual_global_hour; ?></td>
    <td class="info_task_title"><?php echo $multilingual_default_task_planend; ?></td>
    <td><?php echo $row_Recordset_task['csa_plan_et']; ?></td>
    </tr>
  <tr>
    <td class="info_task_title"><?php if($row_Recordset_task['csa_plan_hour'] <> null){ ?>
        <?php 
	  $over_hour=$plan_hour - $sum_hour;
	  if ($over_hour < 0) {
	  echo "<span class='red'>".$multilingual_tasklog_over."</span>";
	  } else if ($over_hour >= 0) {
	  echo $multilingual_tasklog_live;
	  }
	  ?>
        <?php } ?>    </td>
    <td><?php if($row_Recordset_task['csa_plan_hour'] <> null){ ?>
        <?php 
	  if ($over_hour < 0) {
	  echo "<span class='red'>".-$over_hour." ".$multilingual_global_hour."</span>";
	  } else if ($over_hour >= 0) {
	  echo $over_hour." ".$multilingual_global_hour;
	  }
	  ?>
        <?php } ?></td>
    <td class="info_task_title"><?php 
	  $live_days = (strtotime($row_Recordset_task['csa_plan_et']) - strtotime(date("Y-m-d")))/86400;
	  if ($live_days < 0){
	  echo $multilingual_tasklog_overday;
	  } else {
	  echo $multilingual_tasklog_liveday;
	  }
	  ?></td>
    <td><?php 
	  if ($live_days < 0){
	  //echo "<span class='red'>".$live_days." ".$multilingual_tasklog_day."</span>";
	  } else {
	  echo $live_days." ".$multilingual_tasklog_day;
	  }
	  ?></td>
    </tr>
	<?php if($tasklevel==1){?>
  <tr>
    <td class="info_task_title"><?php echo $multilingual_default_tasklevel; ?></td>
    <td><?php
switch ($row_Recordset_task['csa_temp'])
{
case 5:
  echo $multilingual_dd_level_l5;
  break;
case 4:
  echo $multilingual_dd_level_l4;
  break;
case 3:
  echo $multilingual_dd_level_l3;
  break;
case 2:
  echo $multilingual_dd_level_l2;
  break;
case 1:
  echo $multilingual_dd_level_l1;
  break;
}
?></td>
    <td class="info_task_title">&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
	<?php } ?>
</table>

		</td>
		</tr>
        
        <tr>
          <td>
		  <table width="100%" style="line-height:40px;">
		  <tr>
            <?php if($_SESSION['MM_rank'] > "2") { ?>
			<td width="16%">
			<a onclick="javascript:self.location='default_task_add.php?taskID=<?php echo $colname_Recordset_task; ?>&projectID=<?php echo $row_Recordset_task['proid']; ?>&wbsID=<?php echo $wbsID; ?>';"  class="mouse_over"><span class="glyphicon glyphicon-random"></span> <?php echo $multilingual_project_newtask; ?>(<?php echo $multilingual_global_break; ?>)</a>
            </td>
            <?php }  ?>
		  <?php if ($exam_totaltask > "0") { ?>
            <?php if (($row_Recordset_task['csa_from_user'] == $_SESSION['MM_uid'] && $_SESSION['MM_rank'] > "1") || $_SESSION['MM_rank'] > "4"  ) { ?>
            <td width="10%">
			<a data-toggle="modal" href="default_task_exam.php?taskid=<?php echo $row_Recordset_task['TID']; ?>" data-target="#checkModal"><span class="glyphicon glyphicon-check"></span> <?php echo $multilingual_exam_title; ?></a>
            </td>
			<?php }  ?>
            <?php }  ?>
            <?php if($_SESSION['MM_rank'] > "1") { ?>
			<td width="12%">
			
			<a href="#" onclick="addcomm();"><span class="glyphicon glyphicon-comment"></span> <?php echo $multilingual_default_addcom; ?></a>
            </td>
			
			<td width="10%">
			<a onClick="addtask();" class="mouse_over"><span class="glyphicon glyphicon-share"></span> <?php echo $multilingual_default_task_copy; ?></a>
            </td>
			<?php } ?>
            
            <?php if (($row_Recordset_task['csa_create_user'] == $_SESSION['MM_uid'] && $_SESSION['MM_rank'] > "1") || $_SESSION['MM_rank'] > "4"  ) { ?>			
			<td width="10%">
			<a onClick="javascript:self.location='default_task_plan.php?editID=<?php echo $row_Recordset_task['TID']; ?>';" class="mouse_over"><span class="glyphicon glyphicon-pencil"></span> <?php echo $multilingual_global_action_edit; ?></a>
            </td>
			<?php }  ?>
            <?php if ($_SESSION['MM_rank'] > "4") {  ?>
			<td width="10%">
			<a  class="mouse_over" onClick="javascript:if(confirm( '<?php 
	 if($row_Recordset_countlog['count_log'] == "0"){  
	  echo $multilingual_global_action_delconfirm;
	  } else { echo $multilingual_global_action_delconfirm2;} ?>'))self.location= 'task_del.php?delID=<?php echo $row_Recordset_task['TID']; ?>';"><span class="glyphicon glyphicon-remove"></span> <?php echo $multilingual_global_action_del; ?></a>
            </td>
			<?php }  ?>
			<td>
			<a class="mouse_over" href="<?php echo $tasklisturl; ?>"><span class="glyphicon glyphicon-arrow-left"></span> <?php echo $multilingual_global_action_back; ?></a>
			</td>
			<td>&nbsp;
			</td>
			</tr>
			
			</table>
		  </td>
        </tr>
		<?php if ($row_Recordset_task['csa_remark1'] <> "&nbsp;" && $row_Recordset_task['csa_remark1'] <> "") { ?>
		<tr>
          <td>&nbsp;</td>
        </tr>
		<tr>
          <td><div class="float_left"><span class="font_big18 fontbold" ><?php echo $multilingual_default_task_description; ?></span><a name="comment"></a></div>
          </td>
        </tr>
        <tr>
          <td><?php 
	echo $row_Recordset_task['csa_remark1']; 
	?></td>
        </tr>
        <?php } ?>
		<?php if($totalRows_Recordset_comment > 0){ //如果有评论?>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><div class="float_left"><span class="font_big18 fontbold" ><?php echo $multilingual_default_comment; ?></span><a name="comment"></a></div>
          </td>
        </tr>
        <tr>
          <td><table class="table table-striped table-hover glink" style="margin-bottom:3px;">
              <?php do { ?>
              <tr>
                <td ><div class="float_left gray"> <a href="user_view.php?recordID=<?php echo $row_Recordset_comment['tk_comm_user']; ?>"><?php echo $row_Recordset_comment['tk_display_name']; ?></a> <?php echo $multilingual_default_by; ?> <?php echo $row_Recordset_comment['tk_comm_lastupdate']; ?> <?php echo $multilingual_default_at; ?></div>
                  <div class="float_right">
                    <?php if ($_SESSION['MM_rank'] > "4" || ($row_Recordset_comment['tk_comm_user'] == $_SESSION['MM_uid'] && $_SESSION['MM_rank'] > "1")) {  ?>
                    <?php
	  $coid =$row_Recordset_comment['coid'];
	  $editcomment_row = "
<script type='text/javascript'>
	  function editcomm$coid()
{
    J.dialog.get({ id: 'test3', title: '$multilingual_default_editcom', width: 600, height: 500, page: 'comment_edit.php?editcoID=$coid' });
}
</script>";

echo $editcomment_row;
?>
                    <a onclick="editcomm<?php echo $coid; ?>();" class="mouse_hover"> <?php echo $multilingual_global_action_edit; ?></a>
                    <?php if ($_SESSION['MM_Username'] <> $multilingual_dd_user_readonly) {  ?>
                    <a  class="mouse_hover" 
	  onclick="javascript:if(confirm( '<?php 
	  echo $multilingual_global_action_delconfirm; ?>'))self.location='comment_del.php?delID=<?php echo $row_Recordset_comment['coid']; ?>&taskID=<?php echo $row_Recordset_task['TID']; ?>';"
	  ><?php echo $multilingual_global_action_del; ?></a>
                    <?php } else {  
	   echo $multilingual_global_action_del; 
	    }  ?>
                    <?php } ?>
                  </div>
                  <?php 
	echo "<br/>".$row_Recordset_comment['tk_comm_title']; 
	?>                </td>
              </tr>
              <?php
} while ($row_Recordset_comment = mysql_fetch_assoc($Recordset_comment));
  $rows = mysql_num_rows($Recordset_comment);
  if($rows > 0) {
      mysql_data_seek($Recordset_comment, 0);
	  $row_Recordset_comment = mysql_fetch_assoc($Recordset_comment);
  }
?>
            </table>
            <table class="rowcon" border="0" align="center">
              <tr>
                <td><table border="0">
                    <tr>
                      <td><?php if ($pageNum_Recordset_comment > 0) { // Show if not first page ?>
                          <a href="<?php printf("%s?pageNum_Recordset_comment=%d%s", $currentPage, 0, $queryString_Recordset_comment); ?>#comment"><?php echo $multilingual_global_first; ?></a>
                          <?php } // Show if not first page ?></td>
                      <td><?php if ($pageNum_Recordset_comment > 0) { // Show if not first page ?>
                          <a href="<?php printf("%s?pageNum_Recordset_comment=%d%s", $currentPage, max(0, $pageNum_Recordset_comment - 1), $queryString_Recordset_comment); ?>#comment"><?php echo $multilingual_global_previous; ?></a>
                          <?php } // Show if not first page ?></td>
                      <td><?php if ($pageNum_Recordset_comment < $totalPages_Recordset_comment) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_Recordset_comment=%d%s", $currentPage, min($totalPages_Recordset_comment, $pageNum_Recordset_comment + 1), $queryString_Recordset_comment); ?>#comment"><?php echo $multilingual_global_next; ?></a>
                          <?php } // Show if not last page ?></td>
                      <td><?php if ($pageNum_Recordset_comment < $totalPages_Recordset_comment) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_Recordset_comment=%d%s", $currentPage, $totalPages_Recordset_comment, $queryString_Recordset_comment); ?>#comment"><?php echo $multilingual_global_last; ?></a>
                          <?php } // Show if not last page ?></td>
                    </tr>
                  </table></td>
                <td align="right"><?php echo ($startRow_Recordset_comment + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_comment + $maxRows_Recordset_comment, $totalRows_Recordset_comment) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_comment ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
              </tr>
            </table></td>
        </tr>
        <?php } //如果有评论 ?>
        <?php if($totalRows_Recordset_actlog > 0){ //如果有操作记录 ?>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><span class="font_big18 fontbold"><?php echo $multilingual_log_title; ?></span><a name="log"></td>
        </tr>
        <tr>
          <td><table class="table table-striped table-hover glink" style="margin-bottom:3px;">
              <?php do { ?>
              <tr>
                <td ><?php echo $row_Recordset_actlog['tk_log_time']; ?> <a href="user_view.php?recordID=<?php echo $row_Recordset_actlog['tk_log_user']; ?>"><?php echo $row_Recordset_actlog['tk_display_name']; ?></a><?php echo $row_Recordset_actlog['tk_log_action']; ?>
              <td>              </tr>
              <?php
} while ($row_Recordset_actlog = mysql_fetch_assoc($Recordset_actlog));
  $rows = mysql_num_rows($Recordset_actlog);
  if($rows > 0) {
      mysql_data_seek($Recordset_actlog, 0);
	  $row_Recordset_actlog = mysql_fetch_assoc($Recordset_actlog);
  }
?>
            </table>
            <table class="rowcon" border="0" align="center">
              <tr>
                <td><table border="0">
                    <tr>
                      <td><?php if ($pageNum_Recordset_actlog > 0) { // Show if not first page ?>
                          <a href="<?php printf("%s?pageNum_Recordset_actlog=%d%s", $currentPage, 0, $queryString_Recordset_actlog); ?>#log"><?php echo $multilingual_global_first; ?></a>
                          <?php } // Show if not first page ?></td>
                      <td><?php if ($pageNum_Recordset_actlog > 0) { // Show if not first page ?>
                          <a href="<?php printf("%s?pageNum_Recordset_actlog=%d%s", $currentPage, max(0, $pageNum_Recordset_actlog - 1), $queryString_Recordset_actlog); ?>#log"><?php echo $multilingual_global_previous; ?></a>
                          <?php } // Show if not first page ?></td>
                      <td><?php if ($pageNum_Recordset_actlog < $totalPages_Recordset_actlog) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_Recordset_actlog=%d%s", $currentPage, min($totalPages_Recordset_actlog, $pageNum_Recordset_actlog + 1), $queryString_Recordset_actlog); ?>#log"><?php echo $multilingual_global_next; ?></a>
                          <?php } // Show if not last page ?></td>
                      <td><?php if ($pageNum_Recordset_actlog < $totalPages_Recordset_actlog) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_Recordset_actlog=%d%s", $currentPage, $totalPages_Recordset_actlog, $queryString_Recordset_actlog); ?>#log"><?php echo $multilingual_global_last; ?></a>
                          <?php } // Show if not last page ?></td>
                    </tr>
                  </table></td>
                <td align="right"><?php echo ($startRow_Recordset_actlog + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_actlog + $maxRows_Recordset_actlog, $totalRows_Recordset_actlog) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_actlog ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
              </tr>
            </table></td>
        </tr>
        <?php } //如果有操作记录  ?>
        <?php if($totalRows_Recordset_subtask > 0){ //如果有子任务 ?>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>
		  <span class="font_big18 fontbold"><?php echo $multilingual_default_task_subtask; ?></span>
		  <div class="float_right gray"> <?php echo $multilingual_subtask_cost; ?>:
              <?php if($row_Recordset_sumsublog['sum_sublog'] == null){
	  $sum_subhour = 0;
	  } else {
	  $sum_subhour = $row_Recordset_sumsublog['sum_sublog'];
	  }
	  echo $sum_subhour;?>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $multilingual_subtask_plan; ?>:
              <?php if($row_Recordset_sumplan['sum_plan_hour'] == null){
	  $plan_subhour = 0;
	  } else {
	  $plan_subhour = $row_Recordset_sumplan['sum_plan_hour'];
	  }
	  echo $plan_subhour;?>
              <?php echo $multilingual_global_hour; ?> </div>
             </td>
        </tr>
        <tr>
          <td><table class="table table-striped table-hover glink"  style="margin-bottom:3px;">
              <thead>
                <tr>
                  <th><?php echo $multilingual_default_task_id; ?></th>
                  <th><?php echo $multilingual_default_task_title; ?></th>
                  <th><?php echo $multilingual_default_task_to; ?></th>
                  <th><?php echo $multilingual_default_task_status; ?></th>
                  <th><?php echo $multilingual_default_task_planstart; ?></th>
                  <th><?php echo $multilingual_default_task_planend; ?></th>
                  <th><?php echo $multilingual_default_task_priority; ?></th>
                  <th><?php echo $multilingual_default_task_temp; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php do { ?>
                  <tr>
                    <td><?php echo $row_Recordset_subtask['TID']; ?></td>
                    <td class="task_title"><div  class="text_overflow_150 task_title"  title="<?php echo $row_Recordset_subtask['csa_text']; ?>"> <a href="default_task_edit.php?editID=<?php echo $row_Recordset_subtask['TID']; ?>" > <b>[<?php echo $row_Recordset_subtask['task_tpye']; ?>]</b> <?php echo $row_Recordset_subtask['csa_text']; ?> </a> </div></td>
                    <td ><a href="user_view.php?recordID=<?php echo $row_Recordset_subtask['csa_to_user']; ?>"><?php echo $row_Recordset_subtask['tk_display_name']; ?></a></td>
                    <td><?php echo $row_Recordset_subtask['task_status_display']; ?></td>
                    <td><?php echo $row_Recordset_subtask['csa_plan_st']; ?></td>
                    <td><?php echo $row_Recordset_subtask['csa_plan_et']; ?></td>
                    <td><?php
switch ($row_Recordset_subtask['csa_priority'])
{
case 5:
  echo $multilingual_dd_priority_p5;
  break;
case 4:
  echo $multilingual_dd_priority_p4;
  break;
case 3:
  echo $multilingual_dd_priority_p3;
  break;
case 2:
  echo $multilingual_dd_priority_p2;
  break;
case 1:
  echo $multilingual_dd_priority_p1;
  break;
}
?>                    </td>
                    <td><?php
switch ($row_Recordset_subtask['csa_temp'])
{
case 5:
  echo $multilingual_dd_level_l5;
  break;
case 4:
  echo $multilingual_dd_level_l4;
  break;
case 3:
  echo $multilingual_dd_level_l3;
  break;
case 2:
  echo $multilingual_dd_level_l2;
  break;
case 1:
  echo $multilingual_dd_level_l1;
  break;
}
?>                    </td>
                  </tr>
                  <?php } while ($row_Recordset_subtask = mysql_fetch_assoc($Recordset_subtask)); ?>
              </tbody>
            </table>
            <table class="rowcon" border="0" align="center">
              <tr>
                <td><table border="0">
                    <tr>
                      <td><?php if ($pageNum_Recordset_subtask > 0) { // Show if not first page ?>
                          <a href="<?php printf("%s?pageNum_Recordset_subtask=%d%s", $currentPage, 0, $queryString_Recordset_subtask); ?>#task"><?php echo $multilingual_global_first; ?></a>
                          <?php } // Show if not first page ?></td>
                      <td><?php if ($pageNum_Recordset_subtask > 0) { // Show if not first page ?>
                          <a href="<?php printf("%s?pageNum_Recordset_subtask=%d%s", $currentPage, max(0, $pageNum_Recordset_subtask - 1), $queryString_Recordset_subtask); ?>#task"><?php echo $multilingual_global_previous; ?></a>
                          <?php } // Show if not first page ?></td>
                      <td><?php if ($pageNum_Recordset_subtask < $totalPages_Recordset_subtask) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_Recordset_subtask=%d%s", $currentPage, min($totalPages_Recordset_subtask, $pageNum_Recordset_subtask + 1), $queryString_Recordset_subtask); ?>#task"><?php echo $multilingual_global_next; ?></a>
                          <?php } // Show if not last page ?></td>
                      <td><?php if ($pageNum_Recordset_subtask < $totalPages_Recordset_subtask) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_Recordset_subtask=%d%s", $currentPage, $totalPages_Recordset_subtask, $queryString_Recordset_subtask); ?>#task"><?php echo $multilingual_global_last; ?></a>
                          <?php } // Show if not last page ?></td>
                    </tr>
                  </table></td>
                <td align="right"><?php echo ($startRow_Recordset_subtask + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_subtask + $maxRows_Recordset_subtask, $totalRows_Recordset_subtask) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_subtask ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
              </tr>
            </table></td>
        </tr>
        <?php } //如果有子任务  ?>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><span class="font_big18 fontbold"><?php echo $multilingual_default_task_section5; ?></span></td>
        </tr>
        <tr>
          <td><iframe id="frame_content" name="main_frame" frameborder="0" height="" width="100%" src="default_task_calendar.php?taskid=<?php echo $row_Recordset_task['TID']; ?>&userid=<?php echo $row_Recordset_task['csa_to_user']; ?>&projectid=<?php echo $row_Recordset_task['csa_project']; ?>&tasktype=<?php echo $row_Recordset_task['csa_type']; ?>" onLoad="TuneHeight()" scrolling="no"></iframe></td>
        </tr>
      </table>
            <?php require('foot.php'); ?>
        </div><!-- right main -->
</td>
  </tr>
</table>
<!-- Modal -->
<div class="modal fade" id="edituserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">     
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="checkModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


</body>
</html>
<?php
mysql_free_result($Recordset_task);
?>
