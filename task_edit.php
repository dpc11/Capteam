<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php require_once('function/user_function.php'); ?>
<?php require_once('function/task_function.php'); ?>
<?php require_once('function/message_function.php'); ?>
<?php 
$colname_Recordset_task = "-1";
if (isset($_GET['editID'])) {
  $colname_Recordset_task = $_GET['editID'];
}

$to_user = "-1";
if (isset($_POST['csa_to_user'])) {
$to_user_arr = explode(", ,", $_POST['csa_to_user']);
  $to_user= $to_user_arr['0'];
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ( empty( $_POST['csa_remark1'] ) ){
$csa_remark1 = "csa_description=''";
}else{
$csa_remark1 = sprintf("csa_description=%s", GetSQLValueString(str_replace("%","%%",$_POST['csa_remark1']), "text"));
}

if ( empty( $_POST['csa_tag'] ) ){
$test02 = "csa_tag=''";
}else{
$test02 = sprintf("csa_tag=%s", GetSQLValueString(str_replace("%","%%",$_POST['csa_tag']), "text"));
}

if ( empty( $_POST['plan_hour'] ) ){
$plan_hour = "csa_plan_hour='0.0'";
}else{
$plan_hour = sprintf("csa_plan_hour=%s", GetSQLValueString($_POST['plan_hour'], "text"));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
if($_POST['user_cc'] == null){
$cc_post= $_POST['user_cc'];
}else {
$cc_post= "[".implode(",",$_POST['user_cc'])."]";
}

  $timenow=date('Y-m-d H:i:s',time());
  $updateSQL = sprintf("UPDATE tk_task SET  csa_from_user=%s, csa_to_user=%s, csa_text=%s, csa_priority=%s,  csa_plan_st=%s, csa_plan_et=%s, $plan_hour, $csa_remark1,$test02, csa_testto=%s,csa_last_update='$timenow'  WHERE tid=%s",

                       GetSQLValueString($_SESSION['MM_uid'], "int"),

                       GetSQLValueString($to_user, "int"),
                       GetSQLValueString($_POST['csa_text'], "text"),
                       GetSQLValueString($_POST['csa_priority'], "text"),
					   GetSQLValueString($_POST['plan_start'], "text"),
					   GetSQLValueString($_POST['plan_end'], "text"),
					   GetSQLValueString($cc_post, "text"),
                       GetSQLValueString($_POST['TID'], "int"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($updateSQL, $tankdb) or die(mysql_error());
  

   date_default_timezone_set('PRC');//编辑任务的log记录
              $action='编辑了任务';
              $taskid=$_POST['TID'];
              $timenow=date('Y-m-d H:i:s',time());
              $insertSQLLog=sprintf("INSERT into tk_log(tk_log_user,tk_log_action,tk_log_time,tk_log_type,tk_log_class)
                VALUES(%s,'$action','$timenow','$taskid','3')",GetSQLValueString($_SESSION['MM_uid'], "int"));
 
               mysql_select_db($database_tankdb, $tankdb);
              $Result2 = mysql_query($insertSQLLog, $tankdb) or die(mysql_error());

$msg_to = $to_user;
$msg_from = $_SESSION['MM_uid'];
$msg_type = "edittask";
$msg_id = $_POST['TID'];
$msg_title = $_POST['csa_text'];
//给任务负责人发消息
send_message( $msg_to, $msg_from, $msg_type, $msg_id, $msg_title, 0 );
//给抄送的人发消息
if($cc_post <> null){
    $cc_arr = json_decode($cc_post, true);
    foreach($cc_arr as $k=>$v){
        send_message( $v['uid'], $msg_from, $msg_type, $msg_id, $msg_title, 1 );
    }
}

  $updateGoTo = " task_view.php?editID=$colname_Recordset_task";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));

}
mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_task = sprintf("SELECT *, 
tk_user1.tk_display_name as tk_display_name1, 
tk_user2.tk_display_name as tk_display_name2,
tk_project.project_name as proname,
tk_stage.tk_stage_title as staname,
tk_stage.tk_stage_st as stage_start,
tk_stage.tk_stage_et as stage_end 
FROM tk_task 
inner join tk_project on tk_project.id=tk_task.csa_project 
inner join tk_stage on tk_stage.stageid=tk_task.csa_project_stage 
inner join tk_user as tk_user1 on tk_task.csa_to_user=tk_user1.uid 
inner join tk_user as tk_user2 on tk_task.csa_from_user=tk_user2.uid 

WHERE tid = %s", GetSQLValueString($colname_Recordset_task, "int"));
$Recordset_task = mysql_query($query_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);
$totalRows_Recordset_task = mysql_num_rows($Recordset_task);

$ccarr = json_decode($row_Recordset_task['testto'], true);

//得到团队成员列表
$prjid=$row_Recordset_task['csa_project'];
$user_arr = get_user_select_by_project($prjid);
?>
<?php require('head.php'); ?>
<link href="css/lhgcore/lhgcheck.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/lhgcore/lhgcore.js"></script>
<script type="text/javascript" src="js/lhgcore/lhgcheck.js"></script>
<link rel="stylesheet" href="css/bootstrap/bootstrap-multiselect.css" type="text/css"/>
<script type="text/javascript" src="js/bootstrap/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="css/bootstrap/datepicker3.css" type="text/css"/>
<script type="text/javascript" src="js/bootstrap/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/bootstrap/locales/bootstrap-datepicker.zh-CN.js"></script>
<div id="pagemargin">
<div class="clearboth"></div>
<form action="<?php echo $editFormAction; ?>" method="post" name="myform" id="myform">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="20%" class="input_task_right_bg" valign="top">
	  
	  <table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
					<tr>
						<div class=" add_title col-xs-12">
							<h3 ><?php echo $multilingual_taskedit_title; ?></h3>
						</div>
						<td valign="top" class="gray2">
							<h4 style="margin-top:40px; margin-left: 5px;" ><?php echo $multilingual_default_task_help_title; ?></h4>
							<p > <?php echo $multilingual_default_task_help_text; ?></p>
							<p > <?php echo $multilingual_default_task_help_text2; ?></p>
						</td>
					</tr>
          
        </table></td>        
        <!-- 右边80%宽度的主体内容 -->
		<td width="80%"  height="100%" valign="top" align="center" valign="top">
				<table width="90%" border="0" cellspacing="0" cellpadding="5" align="center" id="add_table"class="add_table">
					<tr>
						<td>
							<table width="98%" border="0" cellspacing="0" cellpadding="5" >
							<!-- 所属项目，所属阶段 -->
        <tr>	
		<ul class="breadcrumb" style="margin-top:0px;background-color:white;font-size:22px;padding: 0px 0px;margin-bottom: 20px;">
			  <li><?php echo $multilingual_default_taskproject; ?>: <a href="project_view.php?recordID=<?php echo $row_Recordset_task['id']; ?>" ><?php echo $row_Recordset_task['project_name']; ?></a></li>
			  <li><?php echo $multilingual_default_task_parent; ?>: <a href=" stage_view.php?editID=<?php echo $row_Recordset_task['stageid']; ?>" ><?php echo $row_Recordset_task['tk_stage_title']; ?></a></li>
            </ul>
			<input style="display:none" id="stage_start" value="<?php echo $row_Recordset_task['stage_start']; ?>" />
			<input style="display:none" id="stage_end" value="<?php echo $row_Recordset_task['stage_end']; ?>" />	
        </tr>
			<tr  valign="top" >
			<td width="540px"  valign="top">
				<!-- 标题 -->
				<div class="form-group">
					<label for="csa_text"><?php echo $multilingual_default_task_title; ?><span  id="csa_text_msg"></span></label>
					<div>
						<input name="csa_text" id="csa_text" type="text" value="<?php echo htmlentities($row_Recordset_task['csa_text'], ENT_COMPAT, 'utf-8'); ?>" class="form-control" placeholder="<?php echo $multilingual_taskadd_title_plh;?>" />
					</div>
				</div>
			  </td>
			  <td width="540px" valign="top">
				<!-- 任务标签 -->
				<div class="form-group">
					<label for="csa_tag"><?php echo $multilingual_default_tasktag; ?><span  id="csa_tag_msg"></span></label>
					<div>
						<input name="csa_tag" id="csa_tag" type="text" value="<?php echo htmlentities($row_Recordset_task['csa_tag'], ENT_COMPAT, 'utf-8');  ?>"  class="form-control" placeholder="<?php echo $multilingual_default_tasktag; ?>" />
					</div>
					<span class="help-block"><?php echo $multilingual_default_task_tag_tips; ?></span>
				</div>		
			  </td>
			  </tr>
			  <tr>
			  <td width="540px" valign="top">
				<!-- 指派给谁 -->
				<div class="form-group">
					<label for="select4" ><?php echo $multilingual_default_task_to; ?><span id="csa_to_user_msg"></span></label>
					<div >        
						<div >
							<select id="select4" name="csa_to_user" >
								<option value="<?php echo $row_Recordset_task['csa_to_user'] ?>"><?php echo $row_Recordset_task['tk_display_name1'] ?></option>
							</select>
						</div>
					</div> 
                <span class="help-block"><?php echo $multilingual_taskadd_totip; ?></span>
				</div>
				</td>
				<td width="540px" valign="top">
				<!-- 抄送人 -->
				<div class="form-group">
					<label for="user_cc"><?php echo $multilingual_default_task_cc; ?></label>
					<div>
						<select id="user_cc" name="user_cc[]" multiple="multiple">
							<?php foreach($user_arr as $key => $val){  ?>
								<option value='{"uid":"<?php echo $val["uid"]?>", "uname":<?php echo json_encode($val["name"])?> }' 
								<?php if (in_2array($val["uid"], $ccarr)==1) {echo "selected=\"selected\"";}  ?>>
								<?php $py = substr( pinyin($val["name"]), 0, 1 );
								echo $py."-".$val["name"]?></option>
							<?php } ?> 
						</select>                 
					</div>
					<span class="help-block"><?php echo $multilingual_default_task_cc_tips; ?></span> 
				</div>	
			  </td>
			  </tr>
			  <tr>
			  <td width="540px" valign="top">
				<div class="form-group">
					<label for="datepicker2"><?php echo $multilingual_default_task_planstart; ?><span id="csa_plan_st_msg"></span></label>
					<div> 
						<input type="text" name="plan_start" id="datepicker2" value="<?php echo $row_Recordset_task['csa_plan_st']; ?>" class="form-control"  />
					</div>
					<span class="help-block"><?php echo $multilingual_default_task_starttime_tips; ?></span>
				</div>
			  </td>
			  <td width="540px" valign="top">
				<div class="form-group">
					<label for="datepicker3"><?php echo $multilingual_default_task_planend; ?><span id="csa_plan_et_msg"></span></label>
					<div>
						<input type="text" name="plan_end" id="datepicker3" value="<?php echo $row_Recordset_task['csa_plan_et']; ?>"  class="form-control" />
					</div>
					<span class="help-block"><?php echo $multilingual_default_task_endtime_tips; ?></span>
				</div>
			  </td>
			  </tr>
			  <tr>
			  <td  width="540px" valign="top">
				<div class="form-group">
					<label for="plan_hour"><?php echo $multilingual_default_task_planhour; ?><span id="plan_hour_msg"></span></label>
					<div class="input-group"   style="width:400px;">
						<input type="text" name="plan_hour" id="plan_hour" value="<?php echo $row_Recordset_task['csa_plan_hour']; ?>" class="form-control"  />
						<span class="input-group-addon"><?php echo $multilingual_global_hour; ?></span>
					</div>
					<span class="help-block" style="width:450px;"><?php echo $multilingual_default_task_pv_tips; ?></span>
				</div>
			  </td>
			  <td width="540px" valign="top">			  
				<div class="form-group">
					<label for="csa_priority"><?php echo $multilingual_default_task_priority; ?></label>
					<div>			  
						<select name="csa_priority" id="csa_priority" class="form-control" style="width:400px;height:45px;font-size:20px;">
							  <option value="<?php echo $multilingual_dd_priority_p5; ?>" <?php if (!(strcmp($multilingual_dd_priority_p5, $row_Recordset_task['csa_priority']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_priority_p5; ?></option>
							  <option value="<?php  echo $multilingual_dd_priority_p4; ?>" <?php if (!(strcmp($multilingual_dd_priority_p4, $row_Recordset_task['csa_priority']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_priority_p4; ?></option>
							  <option value="<?php  echo $multilingual_dd_priority_p3; ?>" <?php if (!(strcmp($multilingual_dd_priority_p3, $row_Recordset_task['csa_priority']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_priority_p3; ?></option>
							  <option value="<?php  echo $multilingual_dd_priority_p2; ?>" <?php if (!(strcmp($multilingual_dd_priority_p2, $row_Recordset_task['csa_priority']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_priority_p2; ?></option>
							  <option value="<?php  echo $multilingual_dd_priority_p1; ?>" <?php if (!(strcmp($multilingual_dd_priority_p1, $row_Recordset_task['csa_priority']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_priority_p1; ?></option>
						</select>
					</div>
					<span class="help-block"><?php echo $multilingual_default_task_priority_tips; ?></span>
				</div>
			  </td>
			  </tr>
        </table></td>
    </tr>
			  <tr >
			  <td valign="top">
				<!-- 任务描述 -->
				<div class="form-group">
					<label for="csa_remark1"><?php echo $multilingual_default_task_description; ?><span  id="csa_text_msg"></span></label>
					<div>		  
						<textarea id="csa_remark1" name="csa_remark1" ><?php echo $row_Recordset_task['csa_description']; ?></textarea>
					</div>
				</div>
			</td>
          </tr>
    <tr class="input_task_bottom_bg">
		<td height="50px">
			<!-- 提交按钮 -->
			<button type="submit" style="margin-left:800px;"  class="btn btn-primary btn-lg submitbutton" name="cont" data-loading-text="<?php echo $multilingual_global_wait; ?>"><?php echo $multilingual_global_action_save; ?></button>
			<button type="button" class="btn btn-default btn-lg" style="margin-left:20px;"  onClick="javascript:history.go(-1);"><?php echo $multilingual_global_action_cancel; ?></button>
          
			<input type="hidden" name="MM_update" value="form1" />
			<input type="hidden" name="TID" value="<?php echo $row_Recordset_task['tid']; ?>" />
		</td>
	</tr>
  </table>
  </td>
  </tr>
  </table>
</form>
</div>
</div>
<?php require('foot.php'); ?>

<script charset="utf-8" src="plug-in/editor/kindeditor.js"></script>
<script charset="utf-8" src="plug-in/editor/lang/zh_CN.js"></script>
<script type="text/javascript">

function openBrWindow(theURL,winName,features) { 
  window.open(theURL,winName,features);
}
        var editor;
        KindEditor.ready(function(K) {
                editor = K.create('#csa_remark1', {
			width : '98%',
			height: '350px',
			items:[
        'undo', 'redo', '|', 'preview', 'print', 'template', 'cut', 'copy', 'paste',
        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
        'superscript','source', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
        'flash', 'media', 'insertfile', 'table', 'hr',  'code', 'pagebreak', 
        'link', 'unlink'
]
});
        });
J.check.rules = [
	{ name: 'csa_text', mid: 'csa_text_msg', requir: true },
	{ name: 'csa_tag', mid: 'csa_tag_msg', type: 'limit',  max:20, warn: '标签最多20个英文字符' },
	{ name: 'select4', mid: 'csa_to_user_msg', requir: true, type: 'group',  min: 1, max: 1,warn: '<?php echo $multilingual_default_required1; ?>' },
	{ name: 'datepicker2', mid: 'csa_plan_st_msg', requir: true, type: 'date|cusfn',cusfunc:'checstagetaskstart()',  warn: '<?php echo $multilingual_error_date; ?>|任务开始时间早于本阶段开始时间' },
	{ name: 'datepicker2', mid: 'csa_plan_st_msg', requir: true, type: 'cusfn',cusfunc: 'checstagetaskstart2()',  warn: '任务开始时间晚于本阶段结束时间' },
	{ name: 'datepicker3', mid: 'csa_plan_et_msg', requir: true, type: 'date|cusfn',cusfunc: 'chectaskdate()',  warn: '<?php echo $multilingual_error_date; ?>|计划完成日期早于计划开始日期' },
	{ name: 'datepicker3', mid: 'csa_plan_et_msg', requir: true, type: 'cusfn',cusfunc: 'checstagetaskend1()',  warn: '任务结束时间晚于本阶段结束时间' },
	{ name: 'datepicker3', mid: 'csa_plan_et_msg', requir: true, type: 'cusfn',cusfunc: 'checstagetaskend2()',  warn: '任务结束时间早于本阶段开始时间' },
	{ name: 'datepicker3', mid: 'csa_plan_et_msg', requir: true, type: 'cusfn',cusfunc: 'checstagetaskend3()',  warn: '任务结束时间不得早于当天' },
	{name: 'plan_hour', mid: 'plan_hour_msg',requir: true,  type: 'cusfn',cusfunc: 'chectaskhour()', warn: '<?php echo $multilingual_default_required5; ?>' }
   
];
function checstagetaskend3(){
	var date1=new Date();
	var date2=Date.parse(document.getElementById("datepicker3").value.replace(/-/g,"/"));
	if(date1>date2){
		return false;
	}
	return true;
}
function checstagetaskend2(){
	var date1=Date.parse(document.getElementById("stage_start").value.replace(/-/g,"/"));
	var date2=Date.parse(document.getElementById("datepicker3").value.replace(/-/g,"/"));
	if(date1>date2){
		return false;
	}
	return true;
}
function checstagetaskend1(){
	var date1=Date.parse(document.getElementById("stage_end").value.replace(/-/g,"/"));
	var date2=Date.parse(document.getElementById("datepicker3").value.replace(/-/g,"/"));
	if(date1<date2){
		return false;
	}
	return true;
}

function checstagetaskstart(){
	var date1=Date.parse(document.getElementById("stage_start").value.replace(/-/g,"/"));
	var date2=Date.parse(document.getElementById("datepicker2").value.replace(/-/g,"/"));
	if(date1>date2){
		return false;
	}
	return true;
}

function checstagetaskstart2(){
	var date1=Date.parse(document.getElementById("stage_end").value.replace(/-/g,"/"));
	var date2=Date.parse(document.getElementById("datepicker2").value.replace(/-/g,"/"));
	if(date1<date2){
		return false;
	}
	return true;
}

function chectaskdate(){
	var date1=Date.parse(document.getElementById("datepicker3").value.replace(/-/g,"/"));
	var date2=Date.parse(document.getElementById("datepicker2").value.replace(/-/g,"/"));
	if(date1<date2){
		return false;
	}
	return true;
}

function chectaskhour(){
	if(document.getElementById("plan_hour").value<=0){
		return false;
	}
	return true;
}				
	$('button[data-loading-text]').click(function () {
		var btn = $(this).button('loading');
		setTimeout(function () {
			btn.button('reset');
		}, 2000);
	});
	
$(window).load(function()
	{    
					
    $('#datepicker2').datepicker({
	format: "yyyy-mm-dd"
	<?php if ($language=="cn") {echo ", language: 'zh-CN'" ;}?>
    
		});
		$('#datepicker3').datepicker({
		format: "yyyy-mm-dd" 
    <?php if ($language=="cn") {echo ", language: 'zh-CN'" ;}?>
		});
						
					$('#select4').multiselect({

			        	enableCaseInsensitiveFiltering: true,
						maxHeight: 360,
						filterPlaceholder: '<?php echo $multilingual_user_filter; ?>'
                    });
					
					$('#select2').multiselect({

			        	enableCaseInsensitiveFiltering: true,
						maxHeight: 360,
						filterPlaceholder: '<?php echo $multilingual_user_filter; ?>'
                    });
					
					$('#user_cc').multiselect({
					
					enableCaseInsensitiveFiltering: true,
					maxHeight: 360,
						filterPlaceholder: '<?php echo $multilingual_user_filter; ?>',
						 nonSelectedText: '<?php echo $multilingual_global_select; ?>',
						 includeSelectAllOption: true,
						 selectAllValue: '{}',
						  selectAllText: '<?php echo $multilingual_user_filter_selall; ?>',
				
			            numberDisplayed: 15
                    });
		J.check.regform('myform');	
		
		document.getElementById("foot_top").style.minHeight=document.getElementById("pagemargin").offsetHeight+document.getElementById("top_height").clientHeight-70+"px";
		$(window).resize();	
	});
$(window).resize(function()
{	
	document.getElementById("foot_top").style.height=$(window).height()+"px"; 
});
</script>

</body>
</html>

<?php mysql_free_result($Recordset_task); ?>
