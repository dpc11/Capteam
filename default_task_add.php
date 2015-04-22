<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php


$myid = $_SESSION['MM_uid'];

$to_user = "-1";
if (isset($_POST['csa_to_user'])) {
$to_user_arr = explode(", ,", $_POST['csa_to_user']);
echo $to_user_arr;
  $to_user= $to_user_arr['0'];
  echo $to_user;
}

$title = "-1";
if (isset($_POST['csa_text'])) {
  $title= $_POST['csa_text'];
}

$project_id = "-1";
if (isset($_GET['projectID'])) {
  $project_id = $_GET['projectID'];
}

$stage_id = "-1";
if (isset($_GET['stageID'])) {
  $stage_id = $_GET['stageID'];
}

/*
$project_url = "-1";
if (isset($_GET['formproject'])) {
  $project_url= $_GET['formproject'];
}

$stage_url = "-1";
if (isset($_GET['formpstage'])) {
  $stage_url= $_GET['formpstage'];
}

$user_id = "-1";
if (isset($_GET['UID'])) {
  $user_id= $_GET['UID'];
}
$user_url = "-1";
if (isset($_GET['touser'])) {
  $user_url= $_GET['touser'];
}
*/

if ( empty( $_POST['plan_hour'] ) )
		$_POST['plan_hour'] = '0.0';

if ( empty( $_POST['csa_remark1'] ) ){
$csa_description = "''";
}else{
$csa_description = sprintf("%s", GetSQLValueString(str_replace("%","%%",$_POST['csa_remark1']), "text"));
}

if ( empty( $_POST['csa_tag'] ) ){
$csa_tag = "''";
}else{
$csa_tag = sprintf("%s", GetSQLValueString(str_replace("%","%%",$_POST['csa_tag']), "text"));
}
/*
//for wbs!
$wbs_id = "-1";
if (isset($_GET['wbsID'])) {
  $wbs_id = $_GET['wbsID'];
}
*/
mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_task = sprintf("SELECT *, 
tk_project.id as proid  
FROM tk_task 
inner join tk_project on tk_task.csa_project=tk_project.id 
WHERE tid = %s", GetSQLValueString($task_id, "int"));
$Recordset_task = mysql_query($query_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);
$totalRows_Recordset_task = mysql_num_rows($Recordset_task);


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
if($_POST['user_cc'] == null){
$cc_post= $_POST['user_cc'];
}else {
$cc_post= "[".implode(",",$_POST['user_cc'])."]";
}

$newID = add_task( $cc_post, $_POST['csa_from_user'],  $to_user_arr['0'],  $project_id, $stage_id, $_POST['csa_text'], $_POST['csa_priority'],  $_POST['plan_start'], $_POST['plan_end'], $_POST['plan_hour'],  $_SESSION['MM_uid'], $csa_tag, $csa_description );

/*
if ($project_url == 1){
$insertGoTo = "project_view.php?recordID=$project_id";
} else if ($user_url == 1){
$insertGoTo = "user_view.php?recordID=$user_id";
}

else {
  $insertGoTo = "default_task_edit.php?editID=$newID";
}
*/
$insertGoTo = "default_task_edit.php?editID=$newID";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
/*
$msg_to = $to_user_arr['0'];
$msg_from = $_POST['csa_create_user'];
$msg_type = "newtask";
$msg_id = $newID;
$msg_title = $title;
$mail = send_message( $msg_to, $msg_from, $msg_type, $msg_id, $msg_title );

if($_POST['user_cc'] <> null){

$cc_arr = json_decode($cc_post, true);

foreach($cc_arr as $k=>$v){
send_message( $v['uid'], $msg_from, $msg_type, $msg_id, $msg_title, 1 );
}

}
*/
  header(sprintf("Location: %s", $insertGoTo));
}

$user_arr = get_user_select($project_id);

?>
<?php require('head.php'); ?>
<link type="text/css" href="skin/themes/base/ui.all.css" rel="stylesheet" />
<link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
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
<!--
J.check.rules = [
	{ name: 'select4', mid: 'csa_to_user_msg', requir: true, type: 'group', noselected: '', warn: '<?php echo $multilingual_default_required1; ?>' },
	{ name: 'select2', mid: 'csa_from_user_msg', requir: true, type: 'group', noselected: '', warn: '<?php echo $multilingual_default_required1; ?>' },
	{ name: 'datepicker2', mid: 'csa_plan_st_msg', requir: true, type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' },
	{ name: 'datepicker3', mid: 'csa_plan_et_msg', requir: true, type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' },
	{ name: 'csa_text', mid: 'csa_text_msg', requir: true, type: '',  warn: '<?php echo $multilingual_default_required4; ?>'},
	{ name: 'plan_hour', mid: 'plan_hour_msg', type: 'rang', min: -1, warn: '<?php echo $multilingual_default_required5; ?>' }
   
];

window.onload = function()
{
    J.check.regform('myform');
}

function option_gourl(str)
{
if(str == '-1')window.open('task_type_list.php');
if(str == '-2')window.open('user_add.php');
if(str == '-3')window.open('project_add.php');
}
//-->
</script>

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
		
function submitform()
{
    document.myform.cont.value='<?php echo $multilingual_global_wait; ?>';
	document.myform.cont.disabled=true;
	document.getElementById("btn5").click();
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
      <td width="25%" class="input_task_right_bg" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td valign="top" >
			<dl style="margin-top:20px;">
  <dt><h4 class="gray2"><?php echo $multilingual_default_taskproject; ?></h4></dt>
  <dd><a href="project_view.php?recordID=<?php echo $row_Recordset_project['id']; ?>" ><?php echo $row_Recordset_project['project_name']; ?></a></dd>
</dl>
<dl>
  <dt><h4 class="gray2"><?php echo $multilingual_taskadd_title; ?></h4></dt>	 
</dl>
 
	 <h4 style="margin-top:40px" class="gray2"><strong><?php echo $multilingual_default_task_help_title; ?></strong></h4>
	 <p class="gray2">
	 <?php echo $multilingual_default_task_help_text; ?>
	 </p>
	 
	 <h4 style="margin-top:30px" class="gray2"><strong><?php echo $multilingual_default_task_help_title2; ?></strong></h4>
	 <p class="gray2">
	 <?php echo $multilingual_default_task_help_text2; ?>
	 </p>
              
              </td>
          </tr>
        </table></td>
      <td width="75%" valign="top"><table width="98%" border="0" cellspacing="0" cellpadding="5" align="center">
          <tr>
		  <!--新建任务-->	
            <td><div class="col-xs-12">
                <h3><?php echo $multilingual_taskadd_title; ?></h3>
              </div>
              <div class="form-group col-xs-12">
                <label for="csa_text"><?php echo $multilingual_default_task_title; ?><span  id="csa_text_msg"></span></label>
                <div>
                  <input name="csa_text" id="csa_text" type="text" value="" class="form-control" placeholder="<?php echo $multilingual_taskadd_title_plh;?>">
                </div>
              </div>
			  	<!--指派给-->		  
			  <div class="form-group  col-xs-6">
                <label for="select4" ><?php echo $multilingual_default_task_to; ?><span id="csa_to_user_msg"></span></label>
                <div >
                  <select id="select4" name="csa_to_user" onChange="option_gourl(this.value)"  class="form-control">
					 <?php foreach($user_arr as $key => $val){  ?>
					 <option value='<?php echo $val["uid"]?>> <!--, ,<?php echo $val["name"]?>' -->
					 <?php 
		   $py = substr( pinyin($val["name"]), 0, 1 );
		  echo $py."-".$val["name"]?></option>
					 <?php } ?>  
                  </select>
                </div>
                <span class="help-block"><?php echo $multilingual_taskadd_totip; ?></span> </div>
				<!--来自-->	
				<div class="form-group  col-xs-6">
                <label for="select2"><?php echo $multilingual_default_task_from; ?><span id="csa_from_user_msg"></span></label>
                <div>
                  <input name="csa_from_user" type="text"  id="csa_from_user" value="<?php echo "{$_SESSION['MM_uid']}"; ?>" >
                </div>
                <span class="help-block"><?php echo $multilingual_exam_tip; ?></span> </div>			
				<!--抄送-->	
				<div class="form-group  col-xs-12">
                <label for="user_cc"><?php echo $multilingual_default_task_cc; ?></label>
                <div>
                  <select id="user_cc" name="user_cc[]" multiple="multiple">
				  
				  <?php foreach($user_arr as $key => $val){  ?>
					 <option value='{"uid":"<?php echo $val["uid"]?>", "uname":<?php echo json_encode($val["name"])?> }' ><?php 
		   $py = substr( pinyin($val["name"]), 0, 1 );
		  echo $py."-".$val["name"]?></option>
					 <?php } ?> 
                  </select>
                </div>
                <span class="help-block"><?php echo $multilingual_default_task_cc_tips; ?></span> </div>
			  
			  <!--描述-->
              <div class="form-group col-xs-12">
                <label for="csa_remark1"><?php echo $multilingual_default_task_description; ?><span  id="csa_text_msg"></span></label>
                <div>
                  <textarea id="csa_remark1" name="csa_remark1" ></textarea>
                </div>
              </div>
			  <!--标签-->
              <div class="form-group  col-xs-12">
                <label for="csa_tag"><?php echo $multilingual_default_tasktag; ?><span  id="csa_text_msg"></span></label>
                <div>
                  <input name="csa_tag" id="csa_tag" type="text" value="" class="form-control" placeholder="<?php echo $multilingual_default_tasktag;?>">
                </div>
				<span class="help-block"><?php echo $multilingual_default_task_tag_tips; ?></span>
              </div>				
				<!--计划开始时间-->
				<div class="form-group col-xs-12">
                <label for="datepicker2"><?php echo $multilingual_default_task_planstart; ?><span id="csa_plan_st_msg"></span></label>
                <div>
                  <input type="text" name="plan_start" id="datepicker2" value="<?php echo date('Y-m-d'); ?>" class="form-control"  />
                </div>
				<span class="help-block"><?php echo $multilingual_default_task_starttime_tips; ?></span>
              </div>
			  <!--计划结束时间-->
              <div class="form-group col-xs-12">
                <label for="datepicker3"><?php echo $multilingual_default_task_planend; ?><span id="csa_plan_et_msg"></span></label>
                <div>
                  <input type="text" name="plan_end" id="datepicker3" value="<?php echo date("Y-m-d",strtotime("+1 day")); ?>" class="form-control" />
                </div>
				<span class="help-block"><?php echo $multilingual_default_task_endtime_tips; ?></span>
              </div>
			  <!--工作量-->
              <div class="form-group col-xs-12">
                <label for="plan_hour"><?php echo $multilingual_default_task_planhour; ?><span id="plan_hour_msg"></span></label>
                <div class="input-group">
                  <input type="text" name="plan_hour" id="plan_hour"  value="" size="20" class="form-control" />
				  <span class="input-group-addon"><?php echo $multilingual_global_hour; ?></span>
                </div>
				<span class="help-block"><?php echo $multilingual_default_task_pv_tips; ?></span>
              </div>
			  <!--优先级-->
              <div class="form-group col-xs-12">
                <label for="csa_priority"><?php echo $multilingual_default_task_priority; ?></label>
                <div>
                  <select name="csa_priority"  id="csa_priority" class="form-control">
                    <option value="<?php echo $multilingual_dd_priority_p5; ?>" ><?php echo $multilingual_dd_priority_p5; ?></option>
                    <option value="<?php echo $multilingual_dd_priority_p4; ?>" ><?php echo $multilingual_dd_priority_p4; ?></option>
                    <option value="<?php echo $multilingual_dd_priority_p3; ?>" ><?php echo $multilingual_dd_priority_p3; ?></option>
                    <option value="<?php echo $multilingual_dd_priority_p2; ?>" ><?php echo $multilingual_dd_priority_p2; ?></option>
                    <option value="<?php echo $multilingual_dd_priority_p1; ?>" ><?php echo $multilingual_dd_priority_p1; ?></option>
                  </select>
                </div>
				<span class="help-block"><?php echo $multilingual_default_task_priority_tips; ?></span>
              </div>
				</td>
          </tr>
        </table></td>
    </tr>
    <tr  class="input_task_bottom_bg" >
	<td></td>
      <td height="50px">
          <button type="submit" class="btn btn-primary btn-sm submitbutton" data-loading-text="<?php echo $multilingual_global_wait; ?>"><?php echo $multilingual_global_action_save; ?></button>
		  
		  <button type="button" class="btn btn-default btn-sm" onClick="javascript:history.go(-1);"><?php echo $multilingual_global_action_cancel; ?></button>
          <input type="submit"  id="btn5" value="<?php echo $multilingual_global_action_save; ?>"  style="display:none" />
      
        <input type="hidden" name="MM_insert" value="form1" /></td>
    </tr>
  </table>
</form>
<script type="text/javascript">
$('button[data-loading-text]').click(function () {
    var btn = $(this).button('loading');
    setTimeout(function () {
        btn.button('reset');
    }, 2000);
});
</script>
<?php require('foot.php'); ?>
</body>
</html>
<?php
mysql_free_result($Recordset_project);
mysql_free_result($Recordset_type);
?>
