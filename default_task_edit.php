<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];
 
 //得到任务id
$colname_Recordset_task = "-1";
if (isset($_GET['editID'])) {
  $colname_Recordset_task = $_GET['editID'];
}

$pagetabs = "alltask";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}
//任务log的显示操作
mysql_select_db($database_tankdb, $tankdb);
$tid= $_GET['editID'];
// echo $tid;
$uid= $_SESSION['MM_uid'];
              //$Result2 = mysql_query($insertSQLLog, $tankdb) or die(mysql_error());
  $selTaskLog="SELECT * FROM tk_log,tk_user WHERE tk_log_type=$tid AND tk_log_class=3 AND tk_log.tk_log_user=tk_user.uid";
  $TaskLog_Result=mysql_query($selTaskLog, $tankdb) or die(mysql_error());
//<!--我参与的任务 -->
if($pagetabs=="mtask"){
$tasklisturl = "index.php?select=&select_project=&select_year=".date("Y")."&textfield=".date("m")."&select3=-1&select4=".$_SESSION['MM_uid']."&select_prt=&select_temp=&inputtitle=&select1=-1&select2=%&create_by=%&select_type=&inputid=&inputtag=";
}
//<!--我创建的任务 -->
else if ($pagetabs=="ctask"){
$tasklisturl = "index.php?select=&select_project=&select_year=".date("Y")."&textfield=".date("m")."&select3=-1&select4=%&select_prt=&select_temp=&inputtitle=&select1=-1&select2=%&create_by=".$_SESSION['MM_uid']."&select_type=&inputid=&inputtag=&pagetab=ctask";
} 
// <!--待我审核的任务 --> 
else if ($pagetabs=="etask"){
$tasklisturl = "index.php?select=&select_project=&select_year=--&textfield=--&select3=-1&select4=%&select_prt=&select_temp=&select_exam=".$multilingual_dd_status_exam."&inputtitle=&select1=-1&select2=".$_SESSION['MM_uid']."&select_type=&inputid=&inputtag=&pagetab=etask";
}  
//<!--所有任务 --> 
else if ($pagetabs=="alltask"){
$tasklisturl = "index.php?select=&select_project=&select_year=".date("Y")."&textfield=".date("m")."&select3=-1&select4=%&select_prt=&select_temp=&inputtitle=&select1=-1&select2=%&create_by=%&select_type=&inputid=&inputtag=&pagetab=alltask";
}
//<!--抄送给我的任务 --> 
else if ($pagetabs=="cctome"){
$tasklisturl = "index.php?select=&select_project=&select_year=".date("Y")."&textfield=".date("m")."&select3=-1&select4=%&select_prt=&select_temp=&inputtitle=&select1=-1&select2=%&select_type=&inputid=&inputtag=&pagetab=cctome";
}

//读取数据库，得到对应的任务的具体信息
mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_task = sprintf("SELECT *, 
tk_user1.tk_display_name as tk_display_name1, 
tk_user2.tk_display_name as tk_display_name2,
tk_status.task_status_display,
tk_project.project_name as proname,
tk_stage.tk_stage_title as staname
FROM tk_task 
inner join tk_project on tk_project.id=tk_task.csa_project 
inner join tk_stage on tk_stage.stageid=tk_task.csa_project_stage 
inner join tk_user as tk_user1 on tk_task.csa_to_user=tk_user1.uid 
inner join tk_user as tk_user2 on tk_task.csa_from_user=tk_user2.uid 

inner join tk_status on tk_task.csa_status=tk_status.id

WHERE tid = %s", GetSQLValueString($colname_Recordset_task, "int"));

$Recordset_task = mysql_query($query_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);
$totalRows_Recordset_task = mysql_num_rows($Recordset_task);


$taskid = $_GET['editID'];


$row_Recordset_countlog="";
$maxRows_Recordset_comment = 10;
$pageNum_Recordset_comment = 0;
$startRow_Recordset_comment =0;

//日志操作，评论
/////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
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

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_actlog = sprintf("SELECT * FROM tk_log 
inner join tk_user on tk_log.tk_log_user =tk_user.uid 
								 WHERE tk_log_type = %s AND tk_log_class = 1 
								
								ORDER BY tk_log_time DESC", 
								GetSQLValueString($colname_Recordset_task, "text")
								);
$query_limit_Recordset_actlog = sprintf("%s LIMIT %d, %d", $query_Recordset_actlog, $startRow_Recordset_actlog, $maxRows_Recordset_actlog);
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
*/
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

</script>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
      
<!-- 左边20%的宽度的树或者说明  -->
	<td width="20%" class="input_task_right_bg" valign="top">
      <table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td valign="top"><?php
		  $project_id = $row_Recordset_task['csa_project'];
		  $project_name = $row_Recordset_task['project_name'];
		  $node_id_task = $row_Recordset_task['tid'];
		   require_once('tree.php'); 		   
		   ?></td>  	   
        </tr>
      
      </table>
	</td>
      
<!-- 右边80%宽度的主体内容 -->
    <td width="80%" valign="top">
        <div style="overflow:auto; " id="main_right"><!-- right main -->
        <table width="98%" border="0" cellspacing="0" cellpadding="5" align="center">
		
		<!-- 所属项目，所属阶段 -->
        <tr>
          <td >			
		<ul class="breadcrumb" style="margin-top:10px;">
			  <li><?php echo $multilingual_default_taskproject; ?>: <a href="project_view.php?recordID=<?php echo $row_Recordset_task['csa_project']; ?>" ><?php echo $row_Recordset_task['project_name']; ?></a></li>
			  <li><?php echo $multilingual_default_task_parent; ?>: <a href="default_task_edit.php?editID=<?php echo $row_Recordset_task['csa_project_stage']; ?>" ><?php echo $row_Recordset_task['staname']; ?></a></li>
            </ul>	
			</td>
        </tr>
		<!-- 任务标题 -->
		                <tr>
                            <td>
                                <div class="breakwords">
                                    <h2>[<?php echo $multilingual_head_task; ?>]<?php echo htmlentities($row_Recordset_task['csa_text'], ENT_COMPAT, 'utf-8'); ?></h2>
                                </div>

                            </td>
                        </tr>
		<tr>
			<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="info_task_bg" style="margin-bottom:10px;">
                        
					
  
					<tr>
						<!-- 指派给谁 -->
						<td width="12%" class="info_task_title"><?php echo $multilingual_default_task_to; ?></td>
						<td width="40%"><a href="user_view.php?recordID=<?php echo $row_Recordset_task['csa_to_user']; ?>"><?php echo $row_Recordset_task['tk_display_name1']; ?></a></td>
						
						<!-- 来自谁 -->
						<td width="12%" class="info_task_title"><?php echo $multilingual_default_task_from; ?></td>
						<td><a href="user_view.php?recordID=<?php echo $row_Recordset_task['csa_from_user']; ?>"><?php echo $row_Recordset_task['tk_display_name2']; ?></a></td>
					</tr>
					
					<!-- 抄送 -->
					<tr>
						<td class="info_task_title"><?php echo $multilingual_default_task_cc; ?></td>
						<td colspan="3">
						<?php if ($row_Recordset_task['testto'] <> "") {
							//var_dump(json_decode($row_Recordset_task['test01'], true));  
	
							$ccarr = json_decode($row_Recordset_task['testto'], true);
							foreach($ccarr as $k=>$v){

								if(isset($v['uid']) && isset($v['uname'])  ){
									echo "<a href=user_view.php?recordID=".$v['uid'].">".$v['uname']."</a> &nbsp;&nbsp;&nbsp;";
								}
							}
						}else{ 
						echo "无";
						} ?>
						</td>
					</tr>
				</table>
		
				<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="info_task_bg" >
					<tr>
						<!-- 状态 -->
						<td width="12%" class="info_task_title"><?php echo $multilingual_default_task_status; ?></td>
						<td  width="40%"><div class="float_left view_task_status"><?php echo $row_Recordset_task['task_status_display']; ?></div></td>
						
						<!-- 优先级 -->
						<td  width="12%" class="info_task_title"><?php echo $multilingual_default_task_priority; ?></td>
						<td><?php echo$row_Recordset_task['csa_priority']; ?></td>
					</tr>
					<tr>
						<!-- 计划开始时间 -->
						<td class="info_task_title"><?php echo $multilingual_default_task_planstart; ?></td>
						<td><?php echo $row_Recordset_task['csa_plan_st']; ?></td>
                        
                        <!-- 计划完成 -->
						<td class="info_task_title"><?php echo $multilingual_default_task_planend; ?></td>
						<td><?php echo $row_Recordset_task['csa_plan_et']; ?></td>
					</tr>
					<tr>
						<!-- 工作量 -->
						<td class="info_task_title"><?php echo $multilingual_default_task_planpv; ?></td>
						<td><?php echo $row_Recordset_task['csa_plan_hour']; ?>
							<?php echo $multilingual_global_hour; ?></td>
                        
						<!-- 据完工日期 -->
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
				</table>

			</td>
		</tr>
        
        <tr>
			<td>
				<table width="100%" style="line-height:40px;">
					<tr>
						<!-- 该用户为任务的创建者-->
						<?php if ($row_Recordset_task['csa_from_user'] == $_SESSION['MM_uid']) { ?>
						
                        <!-- 审核-->
						<?php if ($row_Recordset_task['csa_status']==3) { ?>
						<td width="10%">
						<a href="default_task_check.php?taskid=<?php echo $row_Recordset_task['tid']; ?>"><span class="glyphicon glyphicon-check"></span> <?php echo $multilingual_exam_title; ?></a>
						</td>
						<?php }  ?>						
						
						<!-- 编辑修改-->
						<?php if ($row_Recordset_task['csa_status']<3) { ?>
						<td width="10%">
						<a onClick="javascript:self.location='default_task_plan.php?editID=<?php echo $row_Recordset_task['tid']; ?>';" class="mouse_over"><span class="glyphicon glyphicon-pencil"></span> <?php echo $multilingual_global_action_edit; ?></a>
						</td>
						<?php }  ?>	
						
						<!-- 删除-->
						<?php if ($row_Recordset_task['csa_status']<3) { ?>
						<td width="10%">
						<a  class="mouse_over" onClick="javascript:if(confirm( '<?php echo $multilingual_global_action_delconfirm; ?>'))self.location= 'task_del.php?delID=<?php echo $row_Recordset_task['tid']; ?>';"><span class="glyphicon glyphicon-remove"></span> <?php echo $multilingual_global_action_del; ?></a>
						</td>
						<?php }  ?>		
						<?php }  ?>	
						
						
						<!-- 该用户为任务的执行者-->
						<?php if ($row_Recordset_task['csa_to_user'] == $_SESSION['MM_uid']) { ?>
						
						<!-- 提交任务-->
						<?php if ($row_Recordset_task['csa_status']==2) { ?>
						<td width="10%">
						<a href="default_task_submit.php?taskid=<?php echo $row_Recordset_task['tid']; ?>&stageid=<?php echo $row_Recordset_task['csa_project_stage']; ?>&projectid=<?php echo $row_Recordset_task['csa_project']; ?>"><span class="glyphicon glyphicon-check"></span> <?php echo $multilingual_submit_task; ?></a>
						</td>
						<?php }  ?>			
						
						<!-- 替换任务-->
						<?php if ($row_Recordset_task['csa_status']==3) { ?>
						<td width="10%">
						<a href="default_task_submit.php?taskid=<?php echo $row_Recordset_task['tid']; ?>&stageid=<?php echo $row_Recordset_task['csa_project_stage']; ?>&projectid=<?php echo $row_Recordset_task['csa_project']; ?>"><span class="glyphicon glyphicon-check"></span> <?php echo $multilingual_change_submit_task; ?></a>
						</td>
						<?php }  ?>	
						
						<!-- 驳回后重新提交-->
						<?php if ($row_Recordset_task['csa_status']==5) { ?>
						<td width="10%">
						<a href="default_task_submit.php?taskid=<?php echo $row_Recordset_task['tid']; ?>&stageid=<?php echo $row_Recordset_task['csa_project_stage']; ?>&projectid=<?php echo $row_Recordset_task['csa_project']; ?>"><span class="glyphicon glyphicon-check"></span> <?php echo $multilingual_re_submit_task; ?></a>
						</td>
						<?php }  ?>	
						<?php }  ?>	
						
						<!-- 添加评论-->
						<td width="12%">			
						<a href="#" onclick="addcomm();"><span class="glyphicon glyphicon-comment"></span> <?php echo $multilingual_default_addcom; ?></a>
						</td>
            
						<!-- 返回-->
						<td>
						<a class="mouse_over" href="<?php echo $tasklisturl; ?>"><span class="glyphicon glyphicon-arrow-left"></span> <?php echo $multilingual_global_action_back; ?></a>
						</td>
						<td>&nbsp;
						</td>
					</tr>
			
				</table>
			</td>
        </tr>
		
		<!-- 描述,如果有-->
		<?php if ($row_Recordset_task['csa_description'] <> "&nbsp;" && $row_Recordset_task['csa_description'] <> "") { ?>
		<tr>
          <td>&nbsp;</td>
        </tr>
		<tr>
          <td><div class="float_left"><span class="font_big18 fontbold" ><?php echo $multilingual_default_task_description; ?></span><a name="comment"></a></div>
          </td>
        </tr>
        <tr>
          <td><?php echo $row_Recordset_task['csa_description']; ?></td>
        </tr>
        <?php } ?>
		
		
		<!-- 评论,如果有-->
		<?php if($totalRows_Recordset_comment > 0){ //如果有评论?>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><div class="float_left"><span class="font_big18 fontbold" ><?php echo $multilingual_default_comment; ?></span><a name="comment"></a></div>
          </td>
        </tr>
        <tr>
          <td>
		  <table class="table table-striped table-hover glink" style="margin-bottom:3px;">
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
						  echo $multilingual_global_action_delconfirm; ?>'))self.location='comment_del.php?delID=<?php echo $row_Recordset_comment['coid']; ?>&taskID=<?php echo $row_Recordset_task['tid']; ?>';"
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
		
		
		<!--操作记录，如果有-->
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><span class="font_big18 fontbold"><?php echo $multilingual_log_title; ?></span><a name="log"></td>
        </tr>
        <tr>
          <td><table class="table table-striped table-hover glink" style="margin-bottom:3px;">
              <?php while ($row_log = mysql_fetch_assoc($TaskLog_Result)) { ?>
              <tr>
                <td ><?php echo $row_log['tk_log_time']; ?>     <!--<a href="user_view.php?recordID=<?php echo $row_Recordset_actlog['tk_log_user']; ?>">--><?php echo $row_log['tk_user_login']; ?><!--</a>-->  <?php echo $row_log['tk_log_action']; ?>
                </td>              
              </tr>
              <?php
          } 
        //$rows = mysql_num_rows($Recordset_actlog);
        //if($rows > 0) {
          //mysql_data_seek($Recordset_actlog, 0);
          //$row_Recordset_actlog = mysql_fetch_assoc($Recordset_actlog);
        //}
      ?>
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
