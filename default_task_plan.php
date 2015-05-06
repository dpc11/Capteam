<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
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

  $updateSQL = sprintf("UPDATE tk_task SET  csa_from_user=%s, csa_to_user=%s, csa_text=%s, csa_priority=%s,  csa_plan_st=%s, csa_plan_et=%s, $plan_hour, $csa_remark1, csa_testto=%s  WHERE tid=%s",

                       GetSQLValueString($_POST['csa_from_user'], "int"),

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
  
  
/*   生成日志、消息
  $newID = $colname_Recordset_task;
  $newName = $_SESSION['MM_uid'];

$insertSQL2 = sprintf("INSERT INTO tk_log (tk_log_user, tk_log_action, tk_log_type, tk_log_class, tk_log_description) VALUES (%s, %s, %s, 1, '' )",
                       GetSQLValueString($newName, "text"),
                       GetSQLValueString($multilingual_log_edittask, "text"),
                       GetSQLValueString($newID, "text"));  
$Result2 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());
*/

$msg_to = $to_user;
$msg_from = $_POST['csa_from_user'];
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

  $updateGoTo = "default_task_edit.php?editID=$colname_Recordset_task";
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
tk_stage.tk_stage_title as staname
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
$user_arr = get_user_select($prjid);
?>
<?php require('head.php'); ?>
<link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgcheck.js"></script>
<link rel="stylesheet" href="bootstrap/css/bootstrap-multiselect.css" type="text/css"/>
<script type="text/javascript" src="bootstrap/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="bootstrap/css/datepicker3.css" type="text/css"/>
<script type="text/javascript" src="bootstrap/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="bootstrap/js/locales/bootstrap-datepicker.zh-CN.js"></script>
<script type="text/javascript">
	$(function() {
    $('#datepicker2').datepicker({
	format: "yyyy-mm-dd"
	<?php if ($language=="cn") {echo ", language: 'zh-CN'" ;}?>
    
		});
		$('#datepicker3').datepicker({
		format: "yyyy-mm-dd" 
    <?php if ($language=="cn") {echo ", language: 'zh-CN'" ;}?>
		});
		
		});

    </script>
<script type="text/javascript">
/*<!--

function openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>*/
<script charset="utf-8" src="editor/kindeditor.js"></script>
<script charset="utf-8" src="editor/lang/zh_CN.js"></script>
<script>
        var editor;
        KindEditor.ready(function(K) {
                editor = K.create('#csa_remark1', {
			width : '100%',
			height: '350px',
			items:[
        'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'cut', 'copy', 'paste',
        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
        'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
        'flash', 'media', 'insertfile', 'table', 'hr', 'map', 'code', 'pagebreak', 'anchor', 
        'link', 'unlink', '|', 'about'
]
});
        });
</script>
<script type="text/javascript">

J.check.rules = [
	{ name: 'select4', mid: 'csa_to_user_msg', requir: true, type: 'group', noselected: '', warn: '<?php echo $multilingual_default_required1; ?>' },
	{ name: 'datepicker2', mid: 'csa_plan_st_msg', requir: true, type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' },
	{ name: 'datepicker3', mid: 'csa_plan_et_msg', requir: true, type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' },
	{ name: 'csa_text', mid: 'csa_text_msg', requir: true, type: '',  warn: '<?php echo $multilingual_default_required4; ?>' },
	{name: 'plan_hour', mid: 'plan_hour_msg', type: 'rang', min: -1, warn: '<?php echo $multilingual_default_required5; ?>' }
   
];

window.onload = function()
{
    J.check.regform('myform');
}

</script>
<!-- Initialize the plugin: -->
<script type="text/javascript">
  $(document).ready(function() {
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
	
  });
</script>

<form action="<?php echo $editFormAction; ?>" method="post" name="myform" id="myform">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
    
        <!-- 左边20%的宽度的树或者说明  -->
      <td width="20%" class="input_task_right_bg" valign="top">
		  <table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
			  <tr>
				<td valign="top" >
					<dl style="margin-top:20px;">
						<dt><h4 class="gray2"><strong><?php echo $multilingual_default_taskproject; ?></strong></h4></dt>
						<dd><a href="project_view.php?recordID=<?php echo $row_Recordset_task['csa_project']; ?>" ><?php echo $row_Recordset_task['proname']; ?></a></dd>
					</dl>

					<!-- 改成阶段 -->
					<dl>
						<dt><h4 class="gray2"><strong><?php echo $multilingual_default_task_parent; ?></strong></h4></dt>
						<dd><a href="default_task_edit.php?editID=<?php echo $row_Recordset_task['csa_project_stage']; ?>" ><?php echo $row_Recordset_task['staname']; ?></a></dd>
					</dl>
					<dl class="hide">
						<dt><h4 class="gray2"><?php echo $multilingual_default_taskid; ?></h4></dt>
						<dd><?php echo $row_Recordset_task['tid']; ?></dd>
					</dl>
				</td>
			 </tr>
		   </table>
		</td>
        
        <!-- 右边80%宽度的主体内容 -->
		<td width="80%" valign="top">
		<table width="98%" border="0" cellspacing="0" cellpadding="5" align="center">
          <tr>
			<!-- 编辑任务 -->
            <td><div class="col-xs-12">
					<h3><?php echo $multilingual_taskedit_title; ?></h3>
				</div>
                
				<!-- 标题 -->
				<div class="form-group col-xs-12">
					<label for="csa_text"><?php echo $multilingual_default_task_title; ?><span  id="csa_text_msg"></span></label>
					<div>
						<input name="csa_text" id="csa_text" type="text" value="<?php echo htmlentities($row_Recordset_task['csa_text'], ENT_COMPAT, 'utf-8'); ?>" class="form-control" placeholder="<?php echo $multilingual_taskadd_title_plh;?>">
					</div>
				</div>
                
				<!-- 指派给谁 -->
				<div class="form-group  col-xs-6">
					<label for="select4" ><?php echo $multilingual_default_task_to; ?><span id="csa_to_user_msg"></span></label>
					<div >        
						<div ><!--选择其他人
							<select id="select4" name="csa_to_user" onChange="option_gourl(this.value)"  class="form-control">
					 <?php foreach($user_arr as $key => $val){  ?>
					 <option value='<?php echo $val["uid"]?>, ,<?php echo $val["name"]?>' 
		  <?php if (!(strcmp($val["uid"], $user_id))) {echo "selected=\"selected\"";} else if ($copy==1){ if(!(strcmp($val["uid"], $task_arr['to']))) {echo "selected=\"selected\"";} }?>
		  ><?php 
		   $py = substr( pinyin($val["name"]), 0, 1 );
		  echo $py."-".$val["name"]?></option>
					 <?php } ?> 
					 </select>-->
							<select id="select4" name="csa_to_user" >
								<option value="<?php echo $row_Recordset_task['csa_to_user'] ?>"><?php echo $row_Recordset_task['tk_display_name1'] ?></option>
							</select>
						</div>
					</div> 
				</div>
				
<!-- 指派人／审核人 -->
				<div class="form-group  col-xs-6">
					<label for="select2"><?php echo $multilingual_default_task_from; ?><span id="csa_from_user_msg"></span></label>
					<div>
						<input type="hidden"  name="csa_from_user"  value="<?php echo $row_Recordset_task['csa_from_user'] ?>"  />
						<input type="text"value="<?php echo $row_Recordset_task['tk_display_name2'] ?>"  class="form-control">       
					</div>
					<span class="help-block"><?php echo $multilingual_exam_tip; ?></span> 
				</div>
				
<!-- 抄送人 -->
				<div class="form-group  col-xs-12">
					<label for="user_cc"><?php echo $multilingual_default_task_cc; ?></label>
					<div>
						<select id="user_cc" name="user_cc[]" multiple>
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
				
<!-- 任务描述 -->
				<div class="form-group col-xs-12">
					<label for="csa_remark1"><?php echo $multilingual_default_task_description; ?><span  id="csa_text_msg"></span></label>
					<div>		  
						<textarea id="csa_remark1" name="csa_remark1" ><?php echo $row_Recordset_task['csa_description']; ?></textarea>
					</div>
				</div>
			  
<!-- 任务标签 -->
				<div class="form-group  col-xs-12">
					<label for="csa_tag"><?php echo $multilingual_default_tasktag; ?><span  id="csa_text_msg"></span></label>
					<div>
						<input name="csa_tag" id="csa_tag" type="text" value="<?php echo htmlentities($row_Recordset_task['csa_tag'], ENT_COMPAT, 'utf-8'); ?>"  class="form-control" placeholder="<?php echo $multilingual_default_tasktag;?>" >
					</div>
					<span class="help-block"><?php echo $multilingual_default_task_tag_tips; ?></span>
				</div>

				<div class="form-group col-xs-12">
					<label for="datepicker2"><?php echo $multilingual_default_task_planstart; ?><span id="csa_plan_st_msg"></span></label>
					<div> 
						<input type="text" name="plan_start" id="datepicker2" value="<?php echo $row_Recordset_task['csa_plan_st']; ?>" class="form-control"  />
					</div>
					<span class="help-block"><?php echo $multilingual_default_task_starttime_tips; ?></span>
				</div>
			  
				<div class="form-group col-xs-12">
					<label for="datepicker3"><?php echo $multilingual_default_task_planend; ?><span id="csa_plan_et_msg"></span></label>
					<div>
						<input type="text" name="plan_end" id="datepicker3" value="<?php echo $row_Recordset_task['csa_plan_et']; ?>"  class="form-control" />
					</div>
					<span class="help-block"><?php echo $multilingual_default_task_endtime_tips; ?></span>
				</div>
																			
				<div class="form-group col-xs-12">
					<label for="plan_hour"><?php echo $multilingual_default_task_planhour; ?><span id="plan_hour_msg"></span></label>
					<div class="input-group">
						<input type="text" name="plan_hour" id="plan_hour" value="<?php echo $row_Recordset_task['csa_plan_hour']; ?>" class="form-control"  />
						<span class="input-group-addon"><?php echo $multilingual_global_hour; ?></span>
					</div>
					<span class="help-block"><?php echo $multilingual_default_task_pv_tips; ?></span>
				</div>
			  
				<div class="form-group col-xs-12">
					<label for="csa_priority"><?php echo $multilingual_default_task_priority; ?></label>
					<div>			  
						<select name="csa_priority" id="csa_priority" class="form-control">
							  <option value="<?php $multilingual_dd_priority_p5 ?>" <?php if (!(strcmp(5, $row_Recordset_task['csa_priority']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_priority_p5; ?></option>
							  <option value="<?php $multilingual_dd_priority_p4 ?>" <?php if (!(strcmp(4, $row_Recordset_task['csa_priority']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_priority_p4; ?></option>
							  <option value="<?php $multilingual_dd_priority_p3 ?>" <?php if (!(strcmp(3, $row_Recordset_task['csa_priority']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_priority_p3; ?></option>
							  <option value="<?php $multilingual_dd_priority_p2 ?>" <?php if (!(strcmp(2, $row_Recordset_task['csa_priority']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_priority_p2; ?></option>
							  <option value="<?php $multilingual_dd_priority_p1 ?>" <?php if (!(strcmp(1, $row_Recordset_task['csa_priority']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_priority_p1; ?></option>
						</select>
					</div>
					<span class="help-block"><?php echo $multilingual_default_task_priority_tips; ?></span>
				</div>
			</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr class="input_task_bottom_bg">
        <td></td>
		<td height="50px">
            
<!-- 提交按钮 -->
			<button type="submit" class="btn btn-primary btn-sm submitbutton" name="cont" data-loading-text="<?php echo $multilingual_global_wait; ?>"><?php echo $multilingual_global_action_save; ?></button>
			<button type="button" class="btn btn-default btn-sm" onClick="javascript:history.go(-1);"><?php echo $multilingual_global_action_cancel; ?></button>
          
			<input type="hidden" name="MM_update" value="form1" />
			<input type="hidden" name="TID" value="<?php echo $row_Recordset_task['tid']; ?>" />
		</td>
	</tr>
  </table>
</form>
<?php require('foot.php'); ?>

</body>
</html>

<?php mysql_free_result($Recordset_task); ?>
