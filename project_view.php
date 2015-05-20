<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php require_once('function/config_function.php'); ?>
<?php require_once('function/user_function.php'); ?>
<?php require_once('function/project_function.php'); ?>
<?php require_once('function/stage_function.php');  ?>
<?php require_once('function/team_function.php'); ?>
<?php require_once('function/schedule_function.php'); ?>
<?php require_once('function/file_log_function.php'); ?>
<?php  
	$currentPage = $_SERVER["PHP_SELF"];
   
	$pagetabs = "allprj";
	if (isset($_GET['pagetab'])) {
		$pagetabs = $_GET['pagetab'];
	}

	$prjlisturl;
	if($pagetabs=="mprj"){
		$prjlisturl = "project.php?pagetab=mprj";
	}else if ($pagetabs=="jprj") {
		$prjlisturl = "project.php?pagetab=jprj";
	}else if ($pagetabs=="allprj"){
		$prjlisturl = "project.php?pagetab=allprj";
	} 

	$maxRows_DetailRS1 = 25;
	$pageNum_DetailRS1 = 0;
	if (isset($_GET['pageNum_DetailRS1'])) {
		$pageNum_DetailRS1 = $_GET['pageNum_DetailRS1'];
	}
	$startRow_DetailRS1 = $pageNum_DetailRS1 * $maxRows_DetailRS1;
  
	//获得项目id
	$colname_DetailRS1 = "-1";
	if (isset($_GET['recordID'])) {
		$colname_DetailRS1 = $_GET['recordID'];
	}
  
	//项目log的显示操作
	mysql_select_db($database_tankdb, $tankdb);
	$pid= $_GET['recordID'];
	//echo $tid;
	$uid= $_SESSION['MM_uid'];
	$selProjectLog="SELECT * FROM tk_log,tk_user WHERE tk_log_type=$pid AND tk_log_class=1 AND tk_log.tk_log_user=tk_user.uid";
	$ProjectLog_Result=mysql_query($selProjectLog, $tankdb) or die(mysql_error());

	//获得日程数据
	$data = get_team_events($colname_DetailRS1);

	//授权的id是否有权限
	if (isset($_GET['authority_user_id'])&&isset($_GET['authority_ulimit'])) {
		$authority_user_id = $_GET['authority_user_id'];
		$authority_ulimit = $_GET['authority_ulimit'];

		$tk_team_pid=$colname_DetailRS1;//项目id
		$tk_team_uid=$authority_user_id;//用户id
		if($authority_ulimit == "1"){//当前为组员
			set_user_authority($tk_team_uid,$tk_team_pid,2);//将权限修改为副组长
		}else{//当前为副组长
			set_user_authority($tk_team_uid,$tk_team_pid,1);//将权限修改为组员
		}
	}

	//获得当前项目相关的user
	$user_arr = get_user_select_by_project($colname_DetailRS1);


	//数据库修改后的SQL语句，查找对应的项目
	mysql_select_db($database_tankdb, $tankdb);
	$query_DetailRS1 = sprintf("SELECT * FROM tk_project 
	inner join tk_user on tk_project.project_to_user=tk_user.uid 
	WHERE tk_project.id = %s", GetSQLValueString($colname_DetailRS1, "int"));
	$query_limit_DetailRS1 = sprintf("%s LIMIT %d, %d", $query_DetailRS1, $startRow_DetailRS1, $maxRows_DetailRS1);
	$DetailRS1 = mysql_query($query_limit_DetailRS1, $tankdb) or die(mysql_error());
	$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);

	//查找该项目所对应的文件夹
	mysql_select_db($database_tankdb, $tankdb);
	$selProFolder = "SELECT * FROM tk_document WHERE tk_doc_pid=$colname_DetailRS1 AND tk_doc_parentdocid=-1";
	$ProFolderRS = mysql_query($selProFolder, $tankdb) or die(mysql_error());
	$row_folder = mysql_fetch_assoc($ProFolderRS);
	$project_folder_id = $row_folder['docid'];

	$file_log_Result = get_project_file_log($project_folder_id);

	if (isset($_GET['totalRows_DetailRS1'])) {
		$totalRows_DetailRS1 = $_GET['totalRows_DetailRS1'];
	} else {
		$all_DetailRS1 = mysql_query($query_DetailRS1);
		$totalRows_DetailRS1 = mysql_num_rows($all_DetailRS1);
	}
	$totalPages_DetailRS1 = ceil($totalRows_DetailRS1/$maxRows_DetailRS1)-1;

	$maxRows_Recordset_task = 15;
	$pageNum_Recordset_task = 0;
	if (isset($_GET['pageNum_Recordset_task'])) {
		$pageNum_Recordset_task = $_GET['pageNum_Recordset_task'];
	}
	$startRow_Recordset_task = $pageNum_Recordset_task * $maxRows_Recordset_task;

	$colname_Recordset_task = $row_DetailRS1['id'];

	//修改数据库后的SQL语句，寻找相关task
	mysql_select_db($database_tankdb, $tankdb);
	$query_Recordset_task = sprintf("SELECT *
					FROM tk_task                           
					inner join tk_user on tk_task.csa_to_user=tk_user.uid 
					inner join tk_status on tk_task.csa_status=tk_status.id 
					WHERE csa_project = %s ORDER BY csa_last_update DESC", GetSQLValueString($colname_Recordset_task, "text"));
	$query_limit_Recordset_task = sprintf("%s LIMIT %d, %d", $query_Recordset_task, $startRow_Recordset_task, $maxRows_Recordset_task);
	$Recordset_task = mysql_query($query_limit_Recordset_task, $tankdb) or die(mysql_error());
	$row_Recordset_task = mysql_fetch_assoc($Recordset_task);

	if (isset($_GET['totalRows_Recordset_task'])) {
		$totalRows_Recordset_task = $_GET['totalRows_Recordset_task'];
	} else {
		$all_Recordset_task = mysql_query($query_Recordset_task);
		$totalRows_Recordset_task = mysql_num_rows($all_Recordset_task);
	}
	$totalPages_Recordset_task = ceil($totalRows_Recordset_task/$maxRows_Recordset_task)-1;

	$queryString_Recordset_task = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
		$params = explode("&", $_SERVER['QUERY_STRING']);
		$newParams = array();
		foreach ($params as $param) {
			if (stristr($param, "pageNum_Recordset_task") == false && 
				stristr($param, "totalRows_Recordset_task") == false && 
				stristr($param, "tab") == false) {
					
				array_push($newParams, $param);
			}
		}
		if (count($newParams) != 0) {
			$queryString_Recordset_task = "&" . htmlentities(implode("&", $newParams));
		}
	}
	$queryString_Recordset_task = sprintf("&totalRows_Recordset_task=%d%s", $totalRows_Recordset_task, $queryString_Recordset_task);

	//显示评论和对评论的操作
	$maxRows_Recordset_comment = 10;
	$pageNum_Recordset_comment = 0;
	if (isset($_GET['pageNum_Recordset_comment'])) {
		$pageNum_Recordset_comment = $_GET['pageNum_Recordset_comment'];
	}
	$startRow_Recordset_comment = $pageNum_Recordset_comment * $maxRows_Recordset_comment;
	mysql_select_db($database_tankdb, $tankdb);
	$query_Recordset_comment = sprintf("SELECT * FROM tk_comment 
	inner join tk_user on tk_comment.tk_comm_user =tk_user.uid 
  								 WHERE tk_comm_pid = %s AND tk_comm_type = 2 
  								
  								ORDER BY tk_comm_lastupdate DESC", 
  								GetSQLValueString($colname_DetailRS1, "text")
  								);
	$query_limit_Recordset_comment = sprintf("%s LIMIT %d, %d", $query_Recordset_comment, 	$startRow_Recordset_comment, $maxRows_Recordset_comment);
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
				stristr($param, "totalRows_Recordset_comment") == false && 
				stristr($param, "tab") == false) {
				
				array_push($newParams, $param);
			}
		}
		if (count($newParams) != 0) {
			$queryString_Recordset_comment = "&" . htmlentities(implode("&", $newParams));
		}
	}
	$queryString_Recordset_comment = sprintf("&totalRows_Recordset_comment=%d%s", $totalRows_Recordset_comment,$queryString_Recordset_comment);

	$host_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER["QUERY_STRING"];
	$host_url=strtr($host_url,"&","!");
?>

<?php require('head.php'); ?>
<link rel="stylesheet" type="text/css" href="plug-in/calendar/css/fullcalendar.css">
<link rel="stylesheet" type="text/css" href="plug-in/calendar/css/fancybox.css">
<style type="text/css">
    .fc-day
    {
        cursor: pointer; 
    }
    .fc-widget-content
    {
        cursor: pointer; 
    }
</style>
<script src='js/jquery/jquery-1.9.1.js'></script>
<script src='js/jquery/jquery-ui-1.10.4.min.js'></script>
<script src='plug-in/calendar/js/fullcalendar.js'></script>
<script src='plug-in/calendar/js/jquery.fancybox-1.3.1.pack.js'></script>
<script type="text/javascript">
$(function() {
	$('#calendar').fullCalendar({
		header: {
			left: 'prev today next',
			center: 'title',
			right: 'month,agendaWeek'
    },
   
    events: <?php echo json_encode($data); ?>,
  });
  
});
</script>
<script type="text/javascript" src="js/lhgcore/lhgcore.js"></script>
<script type="text/javascript" src="js/lhgcore/lhgdialog.js"></script>
<script type="text/javascript" src="plug-in/chart/js/swfobject.js"></script> 
<script type="text/javascript"> 
	var flashvars = {"data-file":"chart_pie_project.php?recordID=<?php echo $row_DetailRS1['id']; ?>"};  
	var params = {menu: "false",scale: "noScale",wmode:"opaque"};  
	swfobject.embedSWF("chart/open-flash-chart.swf", "chart", "600px", "230px", 
		"9.0.0","expressInstall.swf", flashvars,params);  
 
	//禁止滚动条
	$(document.body).css({
		"overflow-x":"hidden",
		"overflow-y":"hidden"
	});
      
	function addcomm()
	{
		J.dialog.get({ id: "test1", title: '<?php echo $multilingual_default_addcom; ?>', width: 600, height: 500, page: "comment_add.php?taskid=<?php echo $row_DetailRS1['id']; ?>&projectid=1&type=2" });
	}

	function   searchtask() 
    {
		document.form1.action= "project_view.php?#task "; 
        document.form1.submit(); 
        return   true; 
    } 

	function   exportexcel() 
    {
		document.form1.action= "excel_log.php "; 
        document.form1.submit(); 
        return   false; 
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
	function addfolder()
	{
		J.dialog.get({ id: "test2", title: '<?php echo $multilingual_project_file_addfolder; ?>', width: 600, height: 500, page: "file_add_folder.php?projectid=<?php echo $row_DetailRS1['id']; ?>&pid=0&folder=1&pagetab=allfile" });
	}
</script>
<?php 
	$tab = "-1";
	if (isset($_GET['tab'])) {
		$tab = $_GET['tab'];
	}

	$tabid = $tab + 1;

	if($tab <> "-1"){
		echo "
			<script language='javascript'>
			function tabs1()
			{
				var len = ".$tabid.";
				for (var i = 1; i <= len; i++)
				{
					document.getElementById('tab_a' + i).style.display = (i == ".$tabid.") ? 'block' : 'none';
					document.getElementById('tab_' + i).className = (i == ".$tabid.") ? 'onhover' : 'none';
				}
			}
			</script>
			";
	}
?>

<script language="javascript">
	function tabs(n)
	{
		var len = 3;
		for (var i = 1; i <= len; i++)
		{
			document.getElementById('tab_a' + i).style.display = (i == n) ? 'block' : 'none';
			document.getElementById('tab_' + i).className = (i == n) ? 'onhover' : 'none';
		}
	}
</script>


	<body <?php if($tab <> "-1"){ echo "onload='tabs1();'";} ?>>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<!-- 左边20%的宽度的树或者说明  -->
				<td width="20%" height="100%"  class="input_task_right_bg" valign="top">
					<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
						<tr>
							<td valign="top" class="gray2"><?php
								$project_id = $row_DetailRS1['id'];
								$project_name = $row_DetailRS1['project_name'];
								$node_id_task = -1;
								require_once('tree.php'); ?>
							</td>
						</tr>
					</table>
				</td>
        
				<!-- 右边80%宽度的主体内容 -->
				<td width="80%" valign="top">
					<div style="overflow:auto; " id="main_right"><!-- right main --> 
						<table width="98%" border="0" cellspacing="0" cellpadding="5" align="center">
							<tr>
								<td >
									<div class="breakwords"><h2>[<?php echo $multilingual_head_project; ?>]<?php echo $row_DetailRS1['project_name']; ?></h2></div> 
								</td>
							</tr>
							<tr>
								<td>
									<!-- 项目基本信息 -->		  
									<table width="100%" border="0" cellspacing="0" cellpadding="5"  class="info_task_bg">
										<tr>
											<!-- 显示项目状态 -->
											<td width="12%" class="info_task_title"><?php echo $multilingual_project_status; ?></td>   
											<td  width="40%">
												<div class="status_view">        
												<?php 
													$today_date = date('Y-m-d');//今天的日期，用于计算项目状态
													if($today_date < $row_DetailRS1['project_start']){
													  //表示项目还没有开始
													  echo "<div style='background-color: #FF6666; width:100%; text-align:center;'>未开始</div>";
													}elseif ($today_date > $row_DetailRS1['project_end']) {
													  //表示项目已结结束
													  echo "<div style='background-color: #B3B3B3; width:100%; text-align:center;'>已结束</div>";
													}else{
													  //表示项目正在进行中
													  echo "<div style='background-color: #6ABD78; width:100%; text-align:center;'>进行中</div>";
													}
												?>
												</div>
											</td>
											<td width="12%" class="info_task_title">
												<?php if ($row_DetailRS1['project_start'] <> "0000-00-00") {  ?>
												<?php echo $multilingual_project_start; ?>
												<?php } ?>	</td>
												  <td>
												<?php if ($row_DetailRS1['project_start'] <> "0000-00-00") {  ?>
												<?php echo $row_DetailRS1['project_start']; ?>
												<?php } ?>		
											</td>
										</tr>
										<tr>
											<td class="info_task_title"><?php echo $multilingual_project_captain; ?></td>
											<td><a href="user_view.php?recordID=<?php echo $row_DetailRS1['project_to_user']; ?>"><?php echo $row_DetailRS1['tk_display_name']; ?></a></td>     
											<td class="info_task_title">
												<?php if ($row_DetailRS1['project_end'] <> "0000-00-00") {  ?>
												<?php echo $multilingual_project_end; ?>
												<?php } ?>	</td>
												  <td><?php if ($row_DetailRS1['project_end'] <> "0000-00-00") {  ?>
												<?php echo $row_DetailRS1['project_end']; ?>
												<?php } ?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<!-- 项目基本操作 -->
							<tr>
								<td>
									<table width="100%"  style="line-height:40px;">
										<tr>
											<!-- 分解阶段 -->
											<?php 
											$tk_team_pid=$colname_DetailRS1;//项目id
											$tk_team_uid=$_SESSION['MM_uid'];//用户id
											$user_authority = get_user_authority($tk_team_uid,$tk_team_pid);//获得当前用户的权限
											if($user_authority > 2) { //只有组长才能分解阶段?>
												<td width="12%">
												<a href="stage_add.php?pid=<?php echo $row_DetailRS1['id']; ?>&formproject=1" >
												<span class="glyphicon glyphicon-random"></span> <?php echo $multilingual_project_newstage; ?></a></td>
												<!-- 新建文件夹 -->
												<td width="13%">
												<a onClick="addfolder()" class="mouse_hover"><span class="glyphicon glyphicon-folder-open"></span> <?php echo $multilingual_project_file_addfolder; ?></a></td>
											 <?php }  ?>
										  
											<!-- 上传文档 -->
											 <td width="12%">
											 <a  target="_blank" href="file_add.php?projectid=<?php echo $row_DetailRS1['id']; ?>&pid=<?php echo $project_folder_id;?>&pagetab=allfile"><span class="glyphicon glyphicon-file"></span> <?php echo $multilingual_project_file_addfile; ?></a></td>
											 
											 <!-- 项目看板 -->
											 <td width="12%">
											 <a   href="board_view.php?pid=<?php echo $colname_DetailRS1;?>"><span class="glyphicon glyphicon-board"></span> <?php echo $multilingual_project_board_view; ?></a></td>
											<!-- 增加评论 -->
											 <td width="12%">
											 <a onClick="addcomm();" class="mouse_over"><span class="glyphicon glyphicon-comment"></span> <?php echo $multilingual_default_addcom; ?></a>
											 </td>
											
											<!-- 项目修改 -->
											 <?php 
												$tk_team_pid=$colname_DetailRS1;//项目id
												$tk_team_uid=$_SESSION['MM_uid'];//用户id
												$user_authority = get_user_authority($tk_team_uid,$tk_team_pid);//获得当前用户的权限
										   
												if($user_authority > 2 || ($_SESSION['MM_uid'] == $row_DetailRS1['project_to_user'])) { //只有组长才可以修改?>
													<td width="10%">
														<a href="project_edit.php?editID=<?php echo $row_DetailRS1['id']; ?>">
														<span class="glyphicon glyphicon-pencil"></span> <?php echo $multilingual_global_action_edit; ?>			 </a>
													</td>
													<!--删除项目-->
													 <td width="10%">
													 <a class="mouse_over" onClick="javascript:if(confirm( '<?php 
														 if($totalRows_Recordset_task == "0"){  
														  echo $multilingual_global_action_delconfirm;
														  } else { echo $multilingual_global_action_delconfirm3;} ?>'))self.location='project_del.php?delID=<?php echo $row_DetailRS1['id']; ?>';">
														  <span class="glyphicon glyphicon-remove"></span> <?php echo $multilingual_global_action_del; ?>	  </a>			 
													</td>
												<?php }  ?> 
  			 
												<!-- 返回 -->
												<td>
													<a class="mouse_over" href="<?php echo $prjlisturl; ?>">
													<span class="glyphicon glyphicon-arrow-left"></span> <?php echo $multilingual_global_action_back; ?></a>
												</td>
										</tr>
									</table>	  
								</td> 
							</tr>
            
						<!-- 项目概述 -->
							<tr>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td><span class="font_big18 fontbold"><?php echo $multilingual_project_description; ?></span></td>
							</tr>
							<tr>
								<td>
								<?php if ($row_DetailRS1['project_text'] <> "&nbsp;" && $row_DetailRS1['project_text'] <> "") {  echo $row_DetailRS1['project_text'];} else {echo "暂无";} ?></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
							<!-- 项目成员 -->
							<tr>
							  <td><span class="font_big18 fontbold"><?php echo $multilingual_project_memeber; ?></span></td>
							</tr>
							<tr>
								<td colspan="2">
									<table class="table table-striped table-hover glink">
										<tbody>                 
											<?php foreach($user_arr as $key => $val){ 
												 ?>
													<tr>
													<td><?php echo "姓名:".$val["name"]; ?></td>
													<td><?php echo "邮箱:".$val["email"]; ?></td>
													<td><?php echo "电话:".$val["phone_num"]; ?></td>
													<td><?php echo "总得分:".$val["score"]; ?></td>
													<?php 
														$tk_team_pid=$colname_DetailRS1;//项目id
														$tk_team_uid=$_SESSION['MM_uid'];//用户id
														$user_authority = get_user_authority($tk_team_uid,$tk_team_pid);//获得当前用户的权限

														if($user_authority > 2){ //只有组长才能分配权限?>
															<td><a type="button" class="btn btn-default btn-sm" href=<?php echo 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?recordID='.$colname_DetailRS1.'&authority_user_id='.$val["uid"].'&authority_ulimit='.$val["ulimit"]; ?>>
															  <?php
															  if($val["ulimit"] == 1){
																  echo $multilingual_privilege_grant;
															  }elseif($val["ulimit"] == 2){
																  echo $multilingual_privilege_remove;
															  }   
																?></a></td>  
														<?php } ?>  
													</tr>                      
											<?php }?>                                 
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
							<!--操作记录，如果有-->
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><span class="font_big18 fontbold"><?php echo $multilingual_log_title; ?></span><a name="log"></td>
        </tr>
        <tr>
          <td><table class="table table-striped table-hover glink" style="margin-bottom:3px;">
              <?php while ($row_log = mysql_fetch_assoc($ProjectLog_Result)) { ?>
              <tr>
                <td ><?php echo $row_log['tk_log_time']; ?>     <!--<a href="user_view.php?recordID=<?php echo $row_Recordset_actlog['tk_log_user']; ?>">-->
                  <?php echo $row_log['tk_user_login']; ?><!--</a>-->  
                  <?php echo $row_log['tk_log_action']; ?>
                </td>              
              </tr>
              <?php } ?>
              <?php while ($row_file_log = mysql_fetch_assoc($file_log_Result)) {?>
                <tr>
                  <td ><?php echo $row_file_log['tk_log_time']; ?>     <!--<a href="user_view.php?recordID=<?php echo $row_Recordset_actlog['tk_log_user']; ?>">-->
                    <?php echo $row_file_log['tk_user_login']; ?><!--</a>-->  
                    <?php echo $row_file_log['tk_log_action']; ?>     
                    <?php if(isDeleteFile($row_file_log['logid']) == 1)
                          {
                               echo "【";echo $row_file_log['tk_doc_title'];echo "】";
                          }
                          else 
                          {?>
                          <a href="file_view.php?newWin=0&recordID=<?php echo $row_file_log['docid']; ?>">
                            <?php echo "【"; echo $row_file_log['tk_doc_title']; echo "】";?>
                          </a>
                          <?php } ?>
                  </td>              
                </tr>
              <?php } ?>
            </table>
            <p><?php echo '<br>'?></p>
            <!--<table class="rowcon" border="0" align="center">
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
            </table>-->
          </td>
        </tr>
  <!-- 工作量饼图 -->
  		  <?php if ($sum_hour > 0.5) {  ?>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><span class="font_big18 fontbold"><?php echo $multilingual_project_taskoverlay; ?></span></td>
            </tr>
            <tr>
              <td>
  	<div id="chart"></div>		</td>
            </tr>
  		  <?php }  ?>
  		  <tr>
              <td>&nbsp;</td>
            </tr>
  		  
  <!-- 项目评论 -->
  		<?php if($totalRows_Recordset_comment > 0){ ?>
  	<tr >
        <td><span class="font_big18 fontbold"><?php echo $multilingual_default_comment; ?></span><a name="comment"></a></td>
      </tr>
  		  <tr>
  		    <td>
  			<table class="table table-striped table-hover glink" style="margin-bottom:3px;">
  			<?php do { ?>
  		<tr >
        <td >
  	  <div class="float_left gray">
  	  
  	  <a href="user_view.php?recordID=<?php echo $row_Recordset_comment['tk_comm_user']; ?>"><?php echo $row_Recordset_comment['tk_display_name']; ?></a> 
  	  <?php echo $multilingual_default_by; ?>
  	  <?php echo $row_Recordset_comment['tk_comm_lastupdate']; ?> 
  	  <?php echo $multilingual_default_at; ?>	  </div>
  	  <div class="float_right">
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
  	  <a onClick="editcomm<?php echo $coid; ?>();" class="mouse_hover">
  	  <?php echo $multilingual_global_action_edit; ?></a>
  	  
  	  <?php if ($_SESSION['MM_Username'] <> $multilingual_dd_user_readonly) {  ?>
  	   <a  class="mouse_hover" 
  	  onclick="javascript:if(confirm( '<?php 
  	  echo $multilingual_global_action_delconfirm; ?>'))self.location='comment_del.php?delID=<?php echo $row_Recordset_comment['coid']; ?>&projectID=<?php echo $row_DetailRS1['id']; ?>';"
  	  ><?php echo $multilingual_global_action_del; ?></a>
  	  <?php } else {  
  	   echo $multilingual_global_action_del; 
  	    }  ?>
  	  </div>
  	  <?php 
  	echo "<br />".$row_Recordset_comment['tk_comm_title']; 
  	?>	  </td>
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
  			
  <!-- 项目评论分页？ -->
  			<table class="rowcon" border="0" align="center">
  <tr>
  <td>   <table border="0">
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
  <td align="right">   <?php echo ($startRow_Recordset_comment + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_comment + $maxRows_Recordset_comment, $totalRows_Recordset_comment) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_comment ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
  </table>			</td>
  	    </tr>
  		<?php } ?>

  		  <tr>
              <td>
              
  <!-- 项目详细切换部分 -->
  <div class="tab">
  <ul class="menu" id="menutitle">

  <li id="tab_1"  class="onhover" 
  <?php if ($totalRows_Recordset_task == 0) { echo "style='display:none'"; }?>>

  <a href="javascript:void(0)" onClick="tabs('1');" >
  <?php echo $multilingual_default_task_substage; ?></a></li>

  <li id="tab_2" 
  <?php if ($totalRows_Recordset_task == 0 ) { echo "class='onhover'"; }?> 
  <?php if ($totalRows_Recordset_file == 0) { echo "style='display:none'"; }?>>
  <a href="javascript:void(0)" onClick="tabs('2');" >
  <?php echo $multilingual_project_file; ?></a></li>

  <li id="tab_3" 
  <?php if ($totalRows_Recordset_task == 0 && $totalRows_Recordset_file == 0) { echo "class='onhover'"; }?> 
  <?php if ($totalRows_Recordset_task == 0) { echo "style='display:none'"; }?>>
  <a href="javascript:void(0)" onClick="tabs('3');" >
  <?php echo $multilingual_project_view_log; ?></a></li>

  <?php if ($totalRows_Recordset_file <> 0 ||  $totalRows_Recordset_task <> 0) { ?>
  <li >&nbsp;</li><li >&nbsp;</li>
  <?php }?><a name="task"></a>
  </ul>

  <!-- 分解阶段，需要重写填充数据 -->
  <div class="tab_b" id="tab_a1" 

  <?php if ($totalRows_Recordset_task > 0) { 
  echo "style='display:block'";
  } else {
  echo "style='display:none'";
  } ?>>

  <?php if ($totalRows_Recordset_task > 0) { // Show if recordset not empty ?>
    <table width="100%">
    <tr>
      <td colspan="2">

  <!-- 阶段详细信息表 -->
      <table class="table table-striped table-hover glink">
  <thead >
          
          <tr>
  <!--          <th><?php echo $multilingual_default_task_id; ?></th>-->
            <th><?php echo $multilingual_default_task_title; ?></th>
  <!--          <th><?php echo $multilingual_default_task_to; ?></th>-->
  <!--          <th><?php echo $multilingual_default_task_status; ?></th>-->
            <th><?php echo $multilingual_default_task_planstart; ?></th>
            <th><?php echo $multilingual_default_task_planend; ?></th>
  <!--          <th><?php echo $multilingual_default_task_priority; ?></th>-->
  <!--          <th class="hide"><?php echo $multilingual_default_tasklevel; ?></th>-->
          </tr>
          </thead>
          <tbody>
          <?php $stage_arr = get_stages($colname_DetailRS1);//获得阶段的数组
          foreach($stage_arr as $key => $val){ //对数组进行遍历  ?>
              <tr>
                  <td class="task_title">
  			          <div  class="text_overflow_150 task_title"  title="<?php echo $row_Recordset_task['csa_text']; ?>">
  			              <a href="stage_view.php?sid=<?php echo $val['sid']; ?>&pid=<?php echo $val['pid']; ?>" >
  			              <?php echo $val['title']; ?>			
  			              </a>			
  			          </div>
  			      </td>
                  <td><?php echo $val['start_time']; ?></td>
                  <td><?php echo $val['end_time']; ?></td>
              </tr>
          <?php } ?>
  		  </tbody>
        </table>

        <table class="rowcon" border="0" align="center">
  <tr>
  <td>   <table border="0">
          <tr>
            <td><?php if ($pageNum_Recordset_task > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_Recordset_task=%d%s", $currentPage, 0, $queryString_Recordset_task); ?>#task"><?php echo $multilingual_global_first; ?></a>
                <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_Recordset_task > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_Recordset_task=%d%s", $currentPage, max(0, $pageNum_Recordset_task - 1), $queryString_Recordset_task); ?>#task"><?php echo $multilingual_global_previous; ?></a>
                <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_Recordset_task < $totalPages_Recordset_task) { // Show if not last page ?>
                <a href="<?php 
  			  printf("%s?pageNum_Recordset_task=%d%s", $currentPage, min($totalPages_Recordset_task, $pageNum_Recordset_task + 1), $queryString_Recordset_task); ?>#task" ><?php echo $multilingual_global_next; ?></a>
  			  <?php } // Show if not last page ?>			  </td>
            <td><?php if ($pageNum_Recordset_task < $totalPages_Recordset_task) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_Recordset_task=%d%s", $currentPage, $totalPages_Recordset_task, $queryString_Recordset_task); ?>#task"><?php echo $multilingual_global_last; ?></a>
                <?php } // Show if not last page ?></td>
          </tr>
        </table></td>
  <td align="right">   <?php echo ($startRow_Recordset_task + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_task + $maxRows_Recordset_task, $totalRows_Recordset_task) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_task ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
  </table>      </td>
  </tr>
  </table>
  <?php } // Show if recordset not empty ?>
  </div>

  <!-- 文件管理部分 -->
  <div class="tab_b" id="tab_a2" 
  <?php if ($totalRows_Recordset_task > 0) { 
  echo "style='display:none'";
  } else {
  echo "style='display:block'";
  } ?>
  >

  <?php if ($totalRows_Recordset_file > 0) {  ?>
  <table class="table table-striped table-hover glink" >
  <thead>
    <tr>
      <th>
  	<?php echo $multilingual_project_file_management; ?>	</th>
  	<th width="100px">
  	<?php echo $multilingual_project_file_update_by; ?>	</th>
  	<th width="160px">
  	<?php echo $multilingual_project_file_update; ?>	</th>
  	<th width="160px">	</th>
    </tr>
  </thead>
  <tbody>
  	<?php do { ?>
  		<tr>
        <td nowrap="nowrap">
  	   <?php 
  	  if($row_Recordset_file['tk_doc_backup1']=="1"){ ?>
  	  <a href="file.php?recordID=<?php echo $row_Recordset_file['docid']; ?><?php 
  	  if($row_Recordset_file['tk_doc_backup1']=="1"){
  	  echo "&folder=".$row_Recordset_file['tk_doc_backup1'];
  	  } ?>&projectID=<?php echo $colname_DetailRS1; ?>&pagetab=allfile
  	  " class="icon_folder">
  	  <?php echo $row_Recordset_file['tk_doc_title']; ?></a>
  	  <?php }else{ ?>
  	  
  	  <a href="file_view.php?recordID=<?php echo $row_Recordset_file['docid']; ?>&projectID=<?php echo $colname_DetailRS1; ?>&pagetab=allfile
  	  " class="icon_file" target="_blank">
  	  <?php echo $row_Recordset_file['tk_doc_title']; ?></a>
  	  <?php } ?>

  	  <?php if ($row_Recordset_file['tk_doc_attachment'] <> null && $row_Recordset_file['tk_doc_attachment'] <> " ") {  ?>
  	  <a href="<?php echo $row_Recordset_file['tk_doc_attachment']; ?>" class="icon_atc"><?php echo $multilingual_project_file_download; ?></a>
  	  <?php } ?>	  </td>
  	  <td>
  	  <a href="user_view.php?recordID=<?php echo $row_Recordset_file['tk_doc_edit']; ?>">
  	  <?php echo $row_Recordset_file['tk_display_name']; ?>	  </a>	  </td>
  	  <td>
  	  <?php echo $row_Recordset_file['tk_doc_edittime']; ?>	  </td>
  	  <td>
  	   <?php if ($row_Recordset_file['tk_doc_backup1'] <> "1") {  ?>
  	   <a href="word.php?fileid=<?php echo $row_Recordset_file['docid']; ?>" class="icon_word"><?php echo $multilingual_project_file_word; ?></a> 
  	 <?php } ?>
  	 &nbsp;
  	 
  	 <?php if($_SESSION['MM_rank'] > "1") { ?>
  	 <?php if ($row_Recordset_file['tk_doc_backup1'] == "1") {  ?>
  	 <script type="text/javascript">
  function editfolder<?php echo $row_Recordset_file['docid']; ?>()
  {
      J.dialog.get({ id: "test", title: '<?php echo $multilingual_project_file_editfolder; ?>', width: 600, height: 500, page: "file_edit_folder.php?editID=<?php echo $row_Recordset_file['docid']; ?>&projectID=<?php echo $row_DetailRS1['id']; ?>&pid=0&folder=<?php echo $row_Recordset_file['tk_doc_backup1']; ?>" });
  }
  </script>
  	   <a onClick="editfolder<?php echo $row_Recordset_file['docid']; ?>()" class="mouse_hover">
  	  <?php echo $multilingual_global_action_edit; ?></a> 
  	  <?php }else{ //如果是编辑文件 ?>
  	  <a href="file_edit.php?editID=<?php echo $row_Recordset_file['docid']; ?>&projectID=<?php echo $row_DetailRS1['id']; ?>&pid=0" target="_blank">
  	  <?php echo $multilingual_global_action_edit; ?></a> 
  	  <?php } ?>
  	  
  	  
  	  &nbsp;
  	  <?php if ($_SESSION['MM_rank'] > "4" || $row_Recordset_file['tk_doc_create'] == $_SESSION['MM_uid']) {  ?>
  	  
  	  <?php if ($_SESSION['MM_Username'] <> $multilingual_dd_user_readonly) {  ?>
  	   <a  class="mouse_hover" 
  	  onclick="javascript:if(confirm( '<?php 
  	  if ($row_Recordset_file['tk_doc_backup1'] == 0){
  	  echo $multilingual_global_action_delconfirm;}
  	  else {
  	  echo $multilingual_global_action_delconfirm5;}
  	   ?>'))self.location='file_del.php?delID=<?php echo $row_Recordset_file['docid']; ?>&projectID=<?php echo $row_DetailRS1['id']; ?>&pid=0&url=<?php echo $host_url; ?>';"
  	  ><?php echo $multilingual_global_action_del; ?></a>
  	  <?php } else {  
  	   echo $multilingual_global_action_del; 
  	    }  ?>
  	  <?php }  ?><?php }  ?>	  </td>
      </tr>
      
  	<?php
  } while ($row_Recordset_file = mysql_fetch_assoc($Recordset_file));
    $rows = mysql_num_rows($Recordset_file);
    if($rows > 0) {
        mysql_data_seek($Recordset_file, 0);
  	  $row_Recordset_file = mysql_fetch_assoc($Recordset_file);
    }
  ?>
  </table>  
  <table class="rowcon" border="0" align="center">
  <tr>
  <td>   <table border="0">
          <tr>
            <td><?php if ($pageNum_Recordset_file > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_Recordset_file=%d%s", $currentPage, 0, $queryString_Recordset_file); ?>&tab=1#task"><?php echo $multilingual_global_first; ?></a>
                <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_Recordset_file > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_Recordset_file=%d%s", $currentPage, max(0, $pageNum_Recordset_file - 1), $queryString_Recordset_file); ?>&tab=1#task"><?php echo $multilingual_global_previous; ?></a>
                <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_Recordset_file < $totalPages_Recordset_file) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_Recordset_file=%d%s", $currentPage, min($totalPages_Recordset_file, $pageNum_Recordset_file + 1), $queryString_Recordset_file); ?>&tab=1#task"><?php echo $multilingual_global_next; ?></a>
                <?php } // Show if not last page ?></td>
            <td><?php if ($pageNum_Recordset_file < $totalPages_Recordset_file) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_Recordset_file=%d%s", $currentPage, $totalPages_Recordset_file, $queryString_Recordset_file); ?>&tab=1#task"><?php echo $multilingual_global_last; ?></a>
                <?php } // Show if not last page ?></td>
          </tr>
        </table></td>
  <td align="right">   <?php echo ($startRow_Recordset_file + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_file + $maxRows_Recordset_file, $totalRows_Recordset_file) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_file ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
  </table>
  <?php }  ?>
  </div>
  <!--file end -->


  <!-- 项目日志部分 -->
  <div class="tab_b" id="tab_a3" 
  <?php if ($totalRows_Recordset_task == 0 && $totalRows_Recordset_file == 0) { 
  echo "style='display:block'";
  } else {
  echo "style='display:none'";
  } ?>
  >
  <?php if ($totalRows_Recordset_task  > 0) {  ?>

  <table width="100%" cellpadding="5">
    <tr>
    <td>
    <div class="condition">
    <span>
  <form id="form1" name="form1" method="get" class="taskform form-inline">
    <input name="recordID" id="recordID" value="<?php echo $colname_DetailRS1; ?>" style="display:none" />
    <input name="tab" id="tab" value="2" style="display:none" />
        <select name="logyear" id="logyear"  class="form-control input-sm">
  	  <option value=""><?php echo $multilingual_taskf_year; ?></option>
  	  <?php for($i = 2009; $i <= 2050; $i++) { ?>
           <option value="<?php echo $i; ?>" <?php 
  		if (isset($_SESSION['ser_logyear'])) {	
  		if (!(strcmp($i, "{$_SESSION['ser_logyear']}"))) {
  			echo "selected=\"selected\"";
  			}
  		}
  else if (!(strcmp($i, date("Y")))) {echo "selected=\"selected\"";} ?>><?php echo $i; ?></option>
  <?php  }?>
        </select>
  	  
  	  <select  name="logmonth" id="logmonth" class="form-control input-sm">
        <option value=""><?php echo $multilingual_taskf_month; ?></option>
        <?php for($i = 1; $i <= 12; $i++) { ?>
           <option value="<?php $xi = $i; if($i<=9){$xi ="0".$i;}   echo $xi; ?>" <?php 
  	  if (isset($_SESSION['ser_logmonth'])) {	
  		if (!(strcmp($xi, "{$_SESSION['ser_logmonth']}"))) {
  			echo "selected=\"selected\"";
  			}
  		}
  else if (!(strcmp($i, date("n")))) {echo "selected=\"selected\"";} ?>><?php echo $xi; ?></option>
  <?php  }?>
      </select>
  	
  	<select name="logday" id="logday"  class="form-control input-sm">
        <option value="" selected="selected"><?php echo $multilingual_taskf_day; ?></option>
  	  <?php for($i = 1; $i <= 31; $i++) { ?>
           <option value="<?php $yi = $i; if($i<=9){$yi ="0".$i;}   echo $yi; ?>" <?php 
  	  if (isset($_SESSION['ser_logday'])) {	
  		if (!(strcmp($yi, "{$_SESSION['ser_logday']}"))) {
  			echo "selected=\"selected\"";
  			}
  		} ?>><?php echo $yi; ?></option>
  <?php  }?>
      </select>

  	 <button type="button" class="btn btn-default btn-sm" onclick= "return   searchtask(); " /><span class="glyphicon glyphicon-filter" style="display:inline;"></span> 
  	  <?php echo $multilingual_global_filterbtn; ?>
  	  </button>
  	  
  	  <button type="button" class="btn btn-link btn-sm" name="export" id="export"  onclick= "return   exportexcel(); " ><?php echo $multilingual_global_excel; ?></button>

   </form>
   </span>
   </div>  </td>
    </tr>
    <tr>
      <td>
  	<?php if ($totalRows_Recordset_log > 0) { ?>
      <div >
      <table class="table table-striped table-hover"  width="100%" >

   <thead>
  <tr>
  <th>
  <?php echo $multilingual_global_log; ?></th>
  <th>
  <?php echo $multilingual_user_view_cost; ?></th>
  <th>
  <?php echo $multilingual_user_view_status; ?></th>
  <th>
  <?php echo $multilingual_user_view_project2; ?></th>
  <th>
  <?php echo $multilingual_project_file_update; ?></th>
  <th></th>
  </tr>
  </thead>
  <tbody>
    <?php do { ?>
  <tr>
        <td class="glink">
  <?php echo $row_Recordset_log['tk_display_name']; ?> <?php echo $multilingual_user_view_by; ?> 
  	   
  <?php 
  $logdate = $row_Recordset_log['csa_tb_year'];
  $logyear = str_split($logdate,4);
  $logmonth = str_split($logyear[1],2);
  echo $logyear[0]; ?>-<?php echo $logmonth[0]; ?>-<?php echo $logmonth[1]; ?>	

  	  <?php echo $multilingual_user_view_do; ?>  
  	  <?php echo $row_Recordset_log['task_tpye']; ?> - 
  	  <a href="default_task_edit.php?editID=<?php echo $row_Recordset_log['TID']; ?>" >
  	  <?php echo $row_Recordset_log['csa_text']; ?></a>

  	  <?php if($row_Recordset_log['csa_tb_text']<>null){ echo "<br/><span class='gray'>".$row_Recordset_log['csa_tb_text']."</span>"; }?>  </td>

  <td class="glink" width="80px">
   <?php echo $row_Recordset_log['csa_tb_manhour']; ?> <?php echo $multilingual_user_view_hour; ?></td>

  <td class="glink" width="120px">
   <?php echo $row_Recordset_log['task_status_display']; ?></td>

  <td class="glink" width="160px" >
   <a href="project_view.php?recordID=<?php echo $row_Recordset_log['csa_project']; ?>">
    <?php echo $row_Recordset_log['project_name']; ?></a></td>

    <td class="glink" width="160px" >
  <?php echo $row_Recordset_log['csa_tb_lastupdate']; ?>  </td>
    <td class="glink" width="60px" >
  <script>	  
  function addcomment<?php echo $row_Recordset_log['tbid']; ?>()
  {
      J.dialog.get({ id: 'test', title: '<?php echo $multilingual_default_task_section5; ?>', page: 'log_view.php?date=<?php echo $row_Recordset_log['csa_tb_year']; ?>&taskid=<?php echo $row_Recordset_log['csa_tb_backup1']; ?>' });
  }
  </script>
    <a class="mouse_hover" onClick="addcomment<?php echo $row_Recordset_log['tbid']; ?>()"><?php echo $multilingual_log_comment; ?><?php 
    if ($row_Recordset_log['csa_tb_comment'] > 0) {
    echo "(".$row_Recordset_log['csa_tb_comment'].")"; 
    }?></a>  </td>
  </tr>
       <?php
  } while ($row_Recordset_log = mysql_fetch_assoc($Recordset_log));
    $rows = mysql_num_rows($Recordset_log);
    if($rows > 0) {
        mysql_data_seek($Recordset_log, 0);
  	  $row_Recordset_log = mysql_fetch_assoc($Recordset_log);
    }
  ?>
  </tbody>
  </table>
  </div>
  <table class="rowcon" border="0" align="center">
  <tr>
  <td>   <table border="0">
          <tr>
            <td><?php if ($pageNum_Recordset_log > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_Recordset_log=%d%s", $currentPage, 0, $queryString_Recordset_log); ?>&tab=2#task"><?php echo $multilingual_global_first; ?></a>
                <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_Recordset_log > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_Recordset_log=%d%s", $currentPage, max(0, $pageNum_Recordset_log - 1), $queryString_Recordset_log); ?>&tab=2#task"><?php echo $multilingual_global_previous; ?></a>
                <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_Recordset_log < $totalPages_Recordset_log) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_Recordset_log=%d%s", $currentPage, min($totalPages_Recordset_log, $pageNum_Recordset_log + 1), $queryString_Recordset_log); ?>&tab=2#task"><?php echo $multilingual_global_next; ?></a>
                <?php } // Show if not last page ?></td>
            <td><?php if ($pageNum_Recordset_log < $totalPages_Recordset_log) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_Recordset_log=%d%s", $currentPage, $totalPages_Recordset_log, $queryString_Recordset_log); ?>&tab=2#task"><?php echo $multilingual_global_last; ?></a>
                <?php } // Show if not last page ?></td>
          </tr>
        </table></td>
  <td align="right">   <?php echo ($startRow_Recordset_log + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_log + $maxRows_Recordset_log, $totalRows_Recordset_log) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_log ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
  </table> 

  <?php } else { ?>
  <div class="alert alert-warning" style="margin:6px;">
      <?php echo $multilingual_user_view_nolog; ?></div>

  <?php }  ?> </td>
  </tr>
  </table>  
  <?php }  ?>
  </div>
          </td>
          </tr>
          </table>
          <!-- 日历表 -->
          <div class="clearboth"></div>
            <div class="pagemargin">

                <!-- 所有日程表主体部分 -->
                <div>
                    <div id='calendar'>
                    </div>
                </div>
            </div>

          <?php require('foot.php'); ?>
               </div><!-- right main -->
          </td>
      </tr>
    </table>

  </body>
  </html>
  <?php
  mysql_free_result($DetailRS1);
  mysql_free_result($Recordset_task);
  ?>