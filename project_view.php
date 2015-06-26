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
	$DetailRS1 = mysql_query($query_DetailRS1, $tankdb) or die(mysql_error());
	$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);

	//查找该项目所对应的文件夹
	mysql_select_db($database_tankdb, $tankdb);
	$selProFolder = "SELECT * FROM tk_document WHERE tk_doc_pid=$colname_DetailRS1 AND tk_doc_parentdocid=-1";
	$ProFolderRS = mysql_query($selProFolder, $tankdb) or die(mysql_error());
	$row_folder = mysql_fetch_assoc($ProFolderRS);
	$project_folder_id = $row_folder['docid'];

	$file_log_Result = get_project_file_log($project_folder_id);

	$colname_Recordset_task = $row_DetailRS1['id'];

	//修改数据库后的SQL语句，寻找相关task
	mysql_select_db($database_tankdb, $tankdb);
	$query_Recordset_task = sprintf("SELECT *
					FROM tk_task                           
					inner join tk_user on tk_task.csa_to_user=tk_user.uid 
					inner join tk_status on tk_task.csa_status=tk_status.id 
					WHERE csa_project = %s ORDER BY csa_last_update DESC", GetSQLValueString($colname_Recordset_task, "text"));
	$Recordset_task = mysql_query($query_Recordset_task, $tankdb) or die(mysql_error());
	$row_Recordset_task = mysql_fetch_assoc($Recordset_task);
	$totalRows_Recordset_task = mysql_num_rows($Recordset_task);
/*
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
*/

	$host_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER["QUERY_STRING"];
	$host_url=strtr($host_url,"&","!");
?>

<?php require('project_spare_time_update.php'); ?>
<?php require('head.php'); ?>
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

<div id="pagemargin">
<div class="clearboth"></div>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<!-- 左边20%的宽度的树或者说明  -->
				<td width="23%" class="input_task_right_bg" valign="top">
					<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center" id="tree">
						<tr>
							<?php
								$project_id = $row_DetailRS1['id'];
								$project_name = $row_DetailRS1['project_name'];
								$node_id_task = -1;
								require_once('tree.php'); ?>
                                
						</tr>
					</table>
				</td>
        
				<!-- 右边80%宽度的主体内容 -->
				<td width="77%" valign="top" height="100%">
					<div style="overflow:auto; " id="main_right"><!-- right main --> 
						<table width="90%" border="0" cellspacing="0" cellpadding="5" align="center">
							<tr>
								<td >
									<div class="breakwords">[<?php echo $multilingual_head_project; ?>]<?php echo $row_DetailRS1['project_name']; ?></div> 
								</td>
							</tr>
							<tr>
								<td>
									<!-- 项目基本信息 -->		  
									<table width="100%" border="0" cellspacing="0" cellpadding="5"  class="info_task_bg">
										<tr>
											<!-- 显示项目状态 -->
											<td width="40%">
											
							<div class="info_task_title"><div style="float:left"><strong>项目状态</strong>&nbsp;&nbsp;&nbsp;</div><?php 
													$today_date = date('Y-m-d');//今天的日期，用于计算项目状态
													if($today_date < $row_DetailRS1['project_start']){
													  //表示项目还没有开始
													  echo "<div class=\"float_left view_task_status\" style='background-color: #FF6666; width:160px; text-align:center;'>未开始</div>";
													}else if ($today_date > $row_DetailRS1['project_end']) {
													  //表示项目已结结束
													  echo "<div class=\"float_left view_task_status\"  style='background-color: #B3B3B3; width:160px; text-align:center;'>已结束</div>";
													}else{
													  //表示项目正在进行中
													  echo "<div class=\"float_left view_task_status\"    style='background-color: #6ABD78; width:160px; text-align:center;'>进行中</div>";
													}
												?></div>
											</td> 
											<td width="40%">
						<div class="info_task_title"><strong><?php echo $multilingual_project_start; ?></strong>&nbsp;&nbsp;&nbsp;<?php echo $row_DetailRS1['project_start']; ?></div></td>
										</tr>
										<tr>
											<td width="30%" ><div class="info_task_title"><strong>组&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;长</strong>&nbsp;&nbsp;&nbsp;<a href="user_view.php?recordID=<?php echo $row_DetailRS1['project_to_user']; ?>"><?php echo $row_DetailRS1['tk_display_name']; ?></a></div></td> 
											
											<td width="40%">
						<div class="info_task_title"><strong><?php echo $multilingual_project_end; ?></strong>&nbsp;&nbsp;&nbsp;<?php echo $row_DetailRS1['project_end']; ?></div></td>
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
												<td width="140px">
												<a href="stage_add.php?pid=<?php echo $row_DetailRS1['id']; ?>&formproject=1" >
												<span class="glyphicon glyphicon-random"></span> <?php echo $multilingual_project_newstage; ?></a></td>
												<!-- 新建文件夹 -->
												<td width="160px">
												<a onClick="addfolder()" class="mouse_hover"><span class="glyphicon glyphicon-folder-open"></span> <?php echo $multilingual_project_file_addfolder; ?></a></td>
											 <?php }  ?>
										  
											<!-- 上传文档 -->
											 <td width="140px">
											 <a target="_blank" href="file_add.php?projectid=<?php echo $row_DetailRS1['id']; ?>&pid=<?php echo $project_folder_id;?>&pagetab=allfile"><span class="glyphicon glyphicon-file"></span> <?php echo $multilingual_project_file_addfile; ?></a></td>
											 
											 <!-- 项目看板 -->
											 <td width="140px">
											 <a href="board_view.php?pid=<?php echo $colname_DetailRS1;?>"><span class="glyphicon glyphicon-board"></span> <?php echo $multilingual_project_board_view; ?></a></td>
                                            
                                            <!-- 进入会议 -->
											 <td width="140px">
                                            <!-- 组长和组员在会议中权限不同 -->
                                            <?php 
                                            if($user_authority > 2) { ?>
											 <a href="meeting/video.php?r=capteam<?php echo $row_DetailRS1['id']; ?>&key=<?php echo md5("videowhispercapteam".$row_DetailRS1['id']); ?>&u=<?php echo $_SESSION['MM_Displayname']; ?>" target="_blank">
                                            <?php } else { ?>
                                             <a href="meeting/video.php?r=capteam<?php echo $row_DetailRS1['id']; ?>&u=<?php echo $_SESSION['MM_Displayname']; ?>" target="_blank">
                                            <?php } ?>
                                                
                                                 <span class="glyphicon glyphicon-user"></span> <?php echo $multilingual_project_conference; ?></a></td>
                                            
											<!-- 项目修改 -->
											 <?php 
												$tk_team_pid=$colname_DetailRS1;//项目id
												$tk_team_uid=$_SESSION['MM_uid'];//用户id
												$user_authority = get_user_authority($tk_team_uid,$tk_team_pid);//获得当前用户的权限
										   
												if($user_authority > 2 || ($_SESSION['MM_uid'] == $row_DetailRS1['project_to_user'])) { //只有组长才可以修改?>
													<td width="100px">
														<a href="project_edit.php?editID=<?php echo $row_DetailRS1['id']; ?>">
														<span class="glyphicon glyphicon-pencil"></span> <?php echo $multilingual_global_action_edit; ?>			 </a>
													</td>
													<!--删除项目-->
													 <td width="100px">
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
							<tr style="border-bottom: 3px #D1D1D1 double;border-width:6px;margin-bottom:6px;" >
								<td><div class="float_left"><h5 ><?php echo $multilingual_project_description; ?></h5></div></td>
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
							  <td><h5 ><?php echo $multilingual_project_memeber; ?></h5></td>
							</tr>
							<tr>
								<td colspan="2">
									<table class="table table-striped table-hover glink">
									<thead>
									<th>姓名
									</th>
									<th>邮箱
									</th>
									<th>联系方式
									</th>
									<th>小组得分
									</th>
									<th>权限
									</th>
									</thead>
										<tbody>                 
											<?php foreach($user_arr as $key => $val){ 
												 ?>
													<tr>
													<td><a href="user_view.php?recordID=<?php echo $val["uid"]; ?>"><?php echo $val["name"]; ?></a></td>
													<td><?php echo $val["email"]; ?></td>
													<td><?php echo $val["phone_num"]; ?></td>
													<td><?php echo $val["score"]; ?></td>
													<?php 
														$tk_team_pid=$colname_DetailRS1;//项目id
														$tk_team_uid=$_SESSION['MM_uid'];//用户id
														$user_authority = get_user_authority($tk_team_uid,$tk_team_pid);//获得当前用户的权限

														if($user_authority > 2){ //只有组长才能分配权限
															if($val["ulimit"] < 3){?>

															<td><a type="button" class="btn btn-default btn-lg" href=<?php echo 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?recordID='.$colname_DetailRS1.'&authority_user_id='.$val["uid"].'&authority_ulimit='.$val["ulimit"]; ?>>
															  <?php
															  if($val["ulimit"] == 1){
																  echo $multilingual_privilege_grant;
															  }elseif($val["ulimit"] == 2){
																  echo $multilingual_privilege_remove;
															  }   
																?></a></td>  
														<?php }else{   ?>
                                                              <td><?php  echo "组长"; ?></td> 
															  <?php	 }
															}else{ ?>
	<td>
	<?php if($val["ulimit"] == 1){
																  echo "组员";
															  }else if($val["ulimit"] == 2){
																  echo "副组长";
															  }else if($val["ulimit"] == 3){
																  echo "组长";
															  }	?>	
	</td>										
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
  		  <tr>
              <td>
              
  <!-- 项目详细切换部分 -->
  <div class="tab">
  <ul class="menu" id="menutitle">
  <li id="tab_1"  class="onhover" >
  <a href="javascript:void(0)" onClick="tabs('1');" >
  团队空闲时间</a></li>
  <li id="tab_2" class="none" >
  <a href="javascript:void(0)" onClick="tabs('2');" >子阶段</a></li>

  <li id="tab_3" class="none">
  <a href="javascript:void(0)" onClick="tabs('3');" >
  <?php echo $multilingual_project_file; ?></a></li>

  <li id="tab_4"  class="none" >
  <a href="javascript:void(0)" onClick="tabs('4');" >
  <?php echo $multilingual_log_title; ?></a></li>
  </ul>
</div>

  <!-- 操作记录 -->
  <div class="tab_b" id="tab_a4"  style="display:none">
		  <div style="overflow-y: auto;max-height:300px">
		  <table class="table table-striped table-hover glink" >
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
			</div>
		</div>
  <!-- 分解阶段，需要重写填充数据 -->
  <div class="tab_b" id="tab_a2" style="display:none">
  <?php if ($totalRows_Recordset_task > 0) { // Show if recordset not empty ?>
    <table width="100%">
    <tr>
      <td colspan="2">

  <!-- 阶段详细信息表 -->
  <div style="overflow-y: auto;max-height:300px">
      <table class="table table-striped table-hover glink" >
  <thead >
          
          <tr>
            <th><?php echo $multilingual_default_task_title; ?></th>
            <th><?php echo $multilingual_default_task_planstart; ?></th>
            <th><?php echo $multilingual_default_task_planend; ?></th>
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
		</div>
		</td>
  </tr>
  </table>
  <?php } // Show if recordset not empty ?>
  </div>

  <!-- 文件管理部分 -->
  <div class="tab_b" id="tab_a3"  style="overflow-y: auto;max-height:300px"  style="display:none">

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
  	  } ?>&projectID=<?php echo $colname_DetailRS1; ?>&pagetab=allfile" class="icon_folder">
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
  	  echo $multilingual_global_action_delconfirm5; } ?>'))self.location='file_del.php?delID=<?php echo $row_Recordset_file['docid']; ?>&projectID=<?php echo $row_DetailRS1['id']; ?>&pid=0&url=<?php echo $host_url; ?>';"
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
  <?php }  ?>
  </div>
  <!--file end -->

          <!-- 日历表 -->
  <div class="tab_b" id="tab_a1"  style="overflow-y: auto;max-height:300px"  style="display:none">
            <div class="pagemargin">

                <!-- 所有日程表主体部分 -->
                <div>
					<button id="example-a-PreviousDomain-selector" class="btn" style="font-size:10px;height:30px;width:30px;margin-right: 10px;margin-left: 10px;padding:0px;"><span class="glyphicon glyphicon-chevron-left    "></span></button>
					<button id="example-a-NextDomain-selector" style="font-size:10px;height:30px;width:30px;margin-right: 10px;padding:0px;" class="btn"><span class="glyphicon glyphicon-chevron-right"></span></button>
					<p>颜色越深代表该时间段团队成员的日程越多</p>
                    <div id='calendar'  class="chart" >
                    </div>
                </div>
            </div>
               </div>
               </div><!-- right main -->
          </td>
      </tr>
    </table>
</div>
</div>

<?php require('foot.php'); ?>
<link rel="stylesheet" type="text/css" href="plug-in/calendar/css/fullcalendar.css">
<link rel="stylesheet" type="text/css" href="plug-in/calendar/css/fancybox.css">
<!-- <script src='plug-in/calendar/js/fullcalendar.js'></script>
<script src='plug-in/calendar/js/jquery.fancybox-1.3.1.pack.js'></script>-->
<script type="text/javascript" src="js/lhgcore/lhgcore.js"></script>
<script type="text/javascript" src="js/lhgcore/lhgdialog.js"></script>
<script type="text/javascript" src="plug-in/chart/js/swfobject.js"></script> 
<script src='plug-in/calendar/js/d3.min.js'></script>
<script src='plug-in/calendar/js/cal-heatmap.js'></script>
<link rel="stylesheet" type="text/css" href="plug-in/calendar/css/cal-heatmap.css">
<script src='plug-in/calendar/js/tipso.js'></script>
<link rel="stylesheet" type="text/css" href="plug-in/calendar/css/tipso.css">
<script type="text/javascript"> 
	var flashvars = {"data-file":"chart_pie_project.php?recordID=<?php echo $row_DetailRS1['id']; ?>"};  
	var params = {menu: "false",scale: "noScale",wmode:"opaque"};  
	swfobject.embedSWF("chart/open-flash-chart.swf", "chart", "600px", "230px", 
		"9.0.0","expressInstall.swf", flashvars,params);  
 
	function   exportexcel() 
    {
		document.form1.action= "excel_log.php "; 
        document.form1.submit(); 
        return   false; 
    } 
	
	function addfolder()
	{
		J.dialog.get({ id: "test2", title: '<?php echo $multilingual_project_file_addfolder; ?>', width: 600, height: 500, page: "file_add_folder.php?projectid=<?php echo $row_DetailRS1['id']; ?>&pid=0&folder=1&pagetab=allfile" });
	}
	
	function tabs(n)
	{
		var len = 3;
		for (var i = 1; i <= len; i++)
		{
			document.getElementById('tab_a' + i).style.display = (i == n) ? 'block' : 'none';
			document.getElementById('tab_' + i).className = (i == n) ? 'onhover' : 'none';
		}
	}
	
	function createCalendar(DOMElID, legendcolors, considermData) {
        yearcal = new CalHeatMap();
        yearcal.init({
			itemSelector: "#calendar",
            domain: "day",
			colLimit: 6,
			cellSize:20,
            subDomain: "x_hour",
			data:"project_spare_time_data.json",
			legendVerticalPosition:"top",
			domainDynamicDimension:true,
			previousSelector: "#example-a-PreviousDomain-selector",
			nextSelector: "#example-a-NextDomain-selector",
			domainGutter:10,
			subDomainTextFormat: "%H",
            displayLegend: false,
            legendColors: legendcolors,
            considerMissingDataAsZero: considermData,
            start: new Date('<?php  echo $row_DetailRS1['project_start']; ?>')
        });
    }
	$(window).load(function()
	{
        var h = $(window).height();
        var h = h - <?php if($totalRows_Recordset_anc > 0) {echo "75";} else {echo "40";} ?>;
        $("#main_right").css("height", h);
		
		createCalendar("#calendar", {}, false);
		$('.tipsodiv').tipso({
							position: 'left',
							background: 'rgb(236,236,236)',
							color: '#663399',
							useTitle: false
						});
		/*
		$('#calendar').fullCalendar({
			header: {
				left: 'prev today next',
				center: 'title',
				right: 'month,agendaWeek'
			},
			events: <?php echo json_encode($data); ?>,
		});
		*/
		//$("button").ggtooltip({html:true}); 
	
		//$("rect").ggtooltip({html:true,title:"111"});
		
		$("#foot_top").css("min-height",document.getElementById("pagemargin").clientHeight+60+document.getElementById("top_height").clientHeight+"px");
		$(window).resize();	
	});
	$(window).resize(function()
	{	
        var h2 = $(this).height();
        $("#main_right").css("height", h2);
		$("#foot_top").css("height",$(window).height()+"px"); 
		$("#tree").css("height",document.getElementById("foot_top").clientHeight-66-60+"px"); 
	});

	function getmsg(id){
		 
			var d=id.split('|')[1];
			d=d.split('-');
			var date=d[0]+"-"+d[1]+"-"+d[2];
			var hour=d[3];
			var xmlhttp;
			if (window.XMLHttpRequest)
			  {// code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
			  }
			else
			  {// code for IE6, IE5
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			  }
			xmlhttp.onreadystatechange=function()
			  {
			  if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					//document.getElementById(id).setAttribute("data-tipso",xmlhttp.responseText);
					
					$('.'+id.split('|')[1]).tipso('update','content',xmlhttp.responseText);
					$('.tipsodiv').tipso('hide');
					$('.'+id.split('|')[1]).tipso('show');
				}
			  }
			xmlhttp.open("POST","project_spare_time_get.php",true);
			xmlhttp.send("<?php echo $colname_DetailRS1; ?>|"+hour+"|"+date);
		 }
</script>


  </body>
  </html>
  <?php
  mysql_free_result($DetailRS1);
  mysql_free_result($Recordset_task);
  ?>