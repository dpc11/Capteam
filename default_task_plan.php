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
$csa_remark1 = "csa_remark1='',";
}else{
$csa_remark1 = sprintf("csa_remark1=%s,", GetSQLValueString(str_replace("%","%%",$_POST['csa_remark1']), "text"));
}

if ( empty( $_POST['csa_tag'] ) ){
$test02 = "test02=''";
}else{
$test02 = sprintf("test02=%s", GetSQLValueString(str_replace("%","%%",$_POST['csa_tag']), "text"));
}

if ( empty( $_POST['plan_hour'] ) ){
$plan_hour = "csa_plan_hour='0.0',";
}else{
$plan_hour = sprintf("csa_plan_hour=%s,", GetSQLValueString($_POST['plan_hour'], "text"));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
if($_POST['user_cc'] == null){
$cc_post= $_POST['user_cc'];
}else {
$cc_post= "[".implode(",",$_POST['user_cc'])."]";
}

  $updateSQL = sprintf("UPDATE tk_task SET  csa_from_user=%s, csa_to_user=%s, csa_type=%s, csa_text=%s, csa_priority=%s, csa_temp=%s, csa_plan_st=%s, csa_plan_et=%s, $plan_hour $csa_remark1 csa_remark2=%s, test01=%s, csa_last_user=%s, $test02 WHERE TID=%s",

                       GetSQLValueString($_POST['csa_from_user'], "text"),

                       GetSQLValueString($to_user, "text"),
                       GetSQLValueString($_POST['csa_type'], "text"),
                       GetSQLValueString($_POST['csa_text'], "text"),
                       GetSQLValueString($_POST['csa_priority'], "text"),
                       GetSQLValueString($_POST['csa_temp'], "text"),
					   GetSQLValueString($_POST['plan_start'], "text"),
					   GetSQLValueString($_POST['plan_end'], "text"),
                       GetSQLValueString($_POST['csa_remark2'], "text"),
					   GetSQLValueString($cc_post, "text"),
                       GetSQLValueString($_POST['csa_last_user'], "text"),
                       GetSQLValueString($_POST['TID'], "int"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($updateSQL, $tankdb) or die(mysql_error());

  $newID = $colname_Recordset_task;
  $newName = $_SESSION['MM_uid'];

$insertSQL2 = sprintf("INSERT INTO tk_log (tk_log_user, tk_log_action, tk_log_type, tk_log_class, tk_log_description) VALUES (%s, %s, %s, 1, '' )",
                       GetSQLValueString($newName, "text"),
                       GetSQLValueString($multilingual_log_edittask, "text"),
                       GetSQLValueString($newID, "text"));  
$Result2 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());
$last_use_arr = pushlastuse($to_user_arr["0"], $to_user_arr["1"], $_SESSION['MM_uid']);

$msg_to = $to_user;
$msg_from = $_POST['csa_from_user'];
$msg_type = "edittask";
$msg_id = $_POST['TID'];
$msg_title = $_POST['csa_text'];

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
$query_Recordset_task = sprintf("SELECT *, tk_user1.tk_display_name as tk_display_name1 
FROM tk_task 
inner join tk_project on tk_task.csa_project=tk_project.id 
inner join tk_user as tk_user1 on tk_task.csa_to_user=tk_user1.uid 
WHERE TID = %s", GetSQLValueString($colname_Recordset_task, "int"));
$Recordset_task = mysql_query($query_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);
$totalRows_Recordset_task = mysql_num_rows($Recordset_task);

$ccarr = json_decode($row_Recordset_task['test01'], true);


mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_ptask = sprintf("SELECT *  
FROM tk_task 
WHERE TID = %s", GetSQLValueString($row_Recordset_task['csa_remark4'], "int"));
$Recordset_ptask = mysql_query($query_Recordset_ptask, $tankdb) or die(mysql_error());
$row_Recordset_ptask = mysql_fetch_assoc($Recordset_ptask);
$totalRows_Recordset_ptask = mysql_num_rows($Recordset_ptask);

$user_arr = get_user_select();

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_type = "SELECT * FROM tk_task_tpye ORDER BY task_tpye_backup1 ASC";
$Recordset_type = mysql_query($query_Recordset_type, $tankdb) or die(mysql_error());
$row_Recordset_type = mysql_fetch_assoc($Recordset_type);
$totalRows_Recordset_type = mysql_num_rows($Recordset_type);

mysql_select_db($database_tankdb, $tankdb);
$query_tkstatus = "SELECT * FROM tk_status WHERE task_status_backup2 <> 1 ORDER BY task_status_backup1 ASC";
$tkstatus = mysql_query($query_tkstatus, $tankdb) or die(mysql_error());
$row_tkstatus = mysql_fetch_assoc($tkstatus);
$totalRows_tkstatus = mysql_num_rows($tkstatus);

$prjid=$row_Recordset_task['csa_project'];
mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_project = "SELECT * FROM tk_project WHERE id = $prjid";
$Recordset_project = mysql_query($query_Recordset_project, $tankdb) or die(mysql_error());
$row_Recordset_project = mysql_fetch_assoc($Recordset_project);
$totalRows_Recordset_project = mysql_num_rows($Recordset_project);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_countlog = sprintf("SELECT COUNT(*) as count_log FROM tk_task_byday WHERE csa_tb_backup1=%s", GetSQLValueString($colname_Recordset_task, "int"));
$Recordset_countlog = mysql_query($query_Recordset_countlog, $tankdb) or die(mysql_error());
$row_Recordset_countlog = mysql_fetch_assoc($Recordset_countlog);

$restrictGoTo = "user_error3.php";
if (($_SESSION['MM_rank'] < "5" && $row_Recordset_task['csa_create_user'] <> $_SESSION['MM_uid']) || $_SESSION['MM_rank'] < "2") {   
  header("Location: ". $restrictGoTo); 
  exit;
}
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
<!--
function openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
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
</script>
<script type="text/javascript">
<!--
J.check.rules = [
	{ name: 'select4', mid: 'csa_to_user_msg', requir: true, type: 'group', noselected: '', warn: '<?php echo $multilingual_default_required1; ?>' },
	{ name: 'select2', mid: 'csa_from_user_msg', requir: true, type: 'group', noselected: '', warn: '<?php echo $multilingual_default_required1; ?>' },
	{ name: 'csa_type', mid: 'csa_type_msg', requir: true, type: 'group', noselected: '', warn: '<?php echo $multilingual_default_required3; ?>' },
	{ name: 'datepicker2', mid: 'csa_plan_st_msg', requir: true, type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' },
	{ name: 'datepicker3', mid: 'csa_plan_et_msg', requir: true, type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' },
	{ name: 'csa_text', mid: 'csa_text_msg', requir: true, type: '',  warn: '<?php echo $multilingual_default_required4; ?>' },
	{name: 'plan_hour', mid: 'plan_hour_msg', type: 'rang', min: -1, warn: '<?php echo $multilingual_default_required5; ?>' }
   
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

<?php if ($row_Recordset_task['csa_remark4'] <> -1) { ?>
<dl>
  <dt><h4 class="gray2"><?php echo $multilingual_default_task_parent; ?></h4></dt>
  <dd><a href="default_task_edit.php?editID=<?php echo $row_Recordset_ptask['TID']; ?>" ><?php echo $row_Recordset_ptask['csa_text']; ?></a></dd>
</dl><?php } else { ?>

<dl>
  <dt><h4 class="gray2"><?php echo $multilingual_subtask_root; ?></h4></dt>	 
</dl>

	 <?php  } ?>
	 
	 <dl class="hide">
  <dt><h4 class="gray2"><?php echo $multilingual_default_taskid; ?></h4></dt>
  <dd><?php echo $row_Recordset_task['TID']; ?></dd>
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
            <td><div class="col-xs-12">
                <h3><?php echo $multilingual_taskedit_title; ?></h3>
              </div>
              <div class="form-group col-xs-12">
                <label for="csa_text"><?php echo $multilingual_default_task_title; ?><span  id="csa_text_msg"></span></label>
                <div>
                   
				  <input name="csa_text" id="csa_text" type="text" value="<?php echo htmlentities($row_Recordset_task['csa_text'], ENT_COMPAT, 'utf-8'); ?>" class="form-control" placeholder="<?php echo $multilingual_taskadd_title_plh;?>">
                </div>
              </div>
			  
			  <div class="form-group col-xs-12">
                <label for="csa_type" ><?php echo $multilingual_default_task_type; ?><span id="csa_type_msg"></span></label>
                <div>
				<?php 
	if ($row_Recordset_countlog['count_log'] <> "0") { ?>
        <select name="dis_csa_type" id="dis_csa_type" disabled="disabled" class="form-control">
          <?php
do {  
?>
          <option value="<?php echo $row_Recordset_type['id']?>" <?php if (!(strcmp($row_Recordset_type['id'], $row_Recordset_task['csa_type']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset_type['task_tpye']?></option>
          <?php
} while ($row_Recordset_type = mysql_fetch_assoc($Recordset_type));
  $rows = mysql_num_rows($Recordset_type);
  if($rows > 0) {
      mysql_data_seek($Recordset_type, 0);
	  $row_Recordset_type = mysql_fetch_assoc($Recordset_type);
  }
?>
        </select>
      
        <?php } ?>
        <select class="form-control" name="csa_type" id="csa_type"    onchange="option_gourl(this.value)" 
		 <?php 
	if ($row_Recordset_countlog['count_log'] <> 0) { 
	echo "style='display:none;'";
	 } ?> 
		>
          <?php
do {  
?>
          <option value="<?php echo $row_Recordset_type['id']?>"<?php if (!(strcmp($row_Recordset_type['id'], $row_Recordset_task['csa_type']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset_type['task_tpye']?></option>
          <?php
} while ($row_Recordset_type = mysql_fetch_assoc($Recordset_type));
  $rows = mysql_num_rows($Recordset_type);
  if($rows > 0) {
      mysql_data_seek($Recordset_type, 0);
	  $row_Recordset_type = mysql_fetch_assoc($Recordset_type);
  }
?>
          <?php if ($_SESSION['MM_rank'] > "4") { ?>
          <option value="-1" class="gray" >+<?php echo $multilingual_tasktype_new; ?></option>
          <?php } ?>
        </select>
                </div>
				<span class="help-block">
				<?php 
	if ($row_Recordset_countlog['count_log'] == 0) { ?>
				<?php echo $multilingual_default_task_type_tips; ?> <abbr  title="<?php echo $multilingual_default_task_catips2; ?>"><span class="glyphicon glyphicon-question-sign"><?php echo $multilingual_default_task_ca; ?></span></abbr>

				<?php } else { echo $multilingual_tasktype_lock2; } ?>
				
				</span>
              </div>
			  
			  <div class="form-group  col-xs-6">
                <label for="select4" ><?php echo $multilingual_default_task_to; ?><span id="csa_to_user_msg"></span></label>
                <div >
				<?php 
	if ($row_Recordset_countlog['count_log'] <> "0") { ?>
        <input type="text" value="<?php echo $row_Recordset_task['tk_display_name1'] ?>" disabled="disabled"  class="form-control">
        <div class="hide">
		<select id="select4" name="csa_to_user" >
          <option value="<?php echo $row_Recordset_task['csa_to_user'] ?>"><?php echo $row_Recordset_task['csa_to_user'] ?></option>
        </select>
		</div>

        <?php } else { ?>
        <select id="select4" name="csa_to_user" onChange="option_gourl(this.value)" >
		
		<?php if($_SESSION['MM_last'] <> null){ $last_arr = json_decode($_SESSION['MM_last'], true); ?>
					<optgroup label="<?php echo $multilingual_default_task_lastusers;?>">
					<?php foreach($last_arr as $key => $val){  ?>
					 <option value='<?php echo $val["uid"]?>, ,<?php echo $val["uname"]?>' ><?php echo $val["uname"]?></option>
					 <?php } ?>
					</optgroup>
					<?php } ?>
					 
					 <optgroup label="<?php echo $multilingual_default_task_users;?>">
		
		
		<?php foreach($user_arr as $key => $val){  ?>
					 <option value="<?php echo $val["uid"]?>, ,<?php echo $val["name"]?>" 
		  <?php if (!(strcmp($val["uid"], $row_Recordset_task['csa_to_user']))) {echo "selected=\"selected\"";} ?>
		  ><?php 
		   $py = substr( pinyin($val["name"]), 0, 1 );
		  echo $py."-".$val["name"]?></option>
		<?php } ?>  
		</optgroup>

          <?php if ($_SESSION['MM_rank'] > "4") { ?>
          <option value="-2" class="gray" >+<?php echo $multilingual_user_new; ?></option>
          <?php } ?>
        </select>
        <?php } ?>

                </div>
                <span class="help-block"><?php if ($row_Recordset_countlog['count_log'] <> "0"){echo $multilingual_tasktype_lock2; }else {echo $multilingual_taskadd_totip;} ?></span> </div>
				
				<div class="form-group  col-xs-6">
                <label for="select2"><?php echo $multilingual_default_task_from; ?><span id="csa_from_user_msg"></span></label>
                <div>
				<select  id="select2" name="csa_from_user" onChange="option_gourl(this.value)">
				<?php foreach($user_arr as $key => $val){  ?>
					 <option value="<?php echo $val["uid"]?>" 
		  <?php if (!(strcmp($val["uid"], $row_Recordset_task['csa_from_user']))) {echo "selected=\"selected\"";} ?>
		  ><?php 
		   $py = substr( pinyin($val["name"]), 0, 1 );
		  echo $py."-".$val["name"]?></option>
		<?php } ?>  
				
          <?php if ($_SESSION['MM_rank'] > "4") { ?>
          <option value="-2" class="gray" >+<?php echo $multilingual_user_new; ?></option>
          <?php } ?>
        </select>
				

                  <input name="csa_last_user" type="text"  id="csa_last_user" value="<?php echo "{$_SESSION['MM_uid']}"; ?>" style="display:none">
                </div>
                <span class="help-block"><?php echo $multilingual_exam_tip; ?></span> </div>
				
				<div class="form-group  col-xs-12">
                <label for="user_cc"><?php echo $multilingual_default_task_cc; ?></label>
                <div>
                  <select id="user_cc" name="user_cc[]" multiple="multiple">
				   <?php foreach($user_arr as $key => $val){  ?>
					 <option value='{"uid":"<?php echo $val["uid"]?>", "uname":<?php echo json_encode($val["name"])?> }' 
					 <?php if (in_2array($val["uid"], $ccarr)==1) {echo "selected=\"selected\"";}  ?>
					 ><?php 
		   $py = substr( pinyin($val["name"]), 0, 1 );
		  echo $py."-".$val["name"]?></option>
					 <?php } ?> 

                  </select>
                 
              
				  <input name="csa_last_user" type="text"  id="csa_last_user" value="<?php echo "{$_SESSION['MM_uid']}"; ?>"  style="display:none;">
                </div>
                <span class="help-block"><?php echo $multilingual_default_task_cc_tips; ?></span> </div>
			  
              <div class="form-group col-xs-12">
                <label for="csa_remark1"><?php echo $multilingual_default_task_description; ?><span  id="csa_text_msg"></span></label>
                <div>		  
				  <textarea id="csa_remark1" name="csa_remark1" ><?php echo $row_Recordset_task['csa_remark1']; ?></textarea>
                </div>
              </div>
              <div class="form-group  col-xs-12">
                <label for="csa_tag"><?php echo $multilingual_default_tasktag; ?><span  id="csa_text_msg"></span></label>
                <div>
				  <input name="csa_tag" id="csa_tag" type="text" value="<?php echo htmlentities($row_Recordset_task['test02'], ENT_COMPAT, 'utf-8'); ?>"  class="form-control" placeholder="<?php echo $multilingual_default_tasktag;?>" >
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
          <option value="5" <?php if (!(strcmp(5, $row_Recordset_task['csa_priority']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_priority_p5; ?></option>
          <option value="4" <?php if (!(strcmp(4, $row_Recordset_task['csa_priority']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_priority_p4; ?></option>
          <option value="3" <?php if (!(strcmp(3, $row_Recordset_task['csa_priority']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_priority_p3; ?></option>
          <option value="2" <?php if (!(strcmp(2, $row_Recordset_task['csa_priority']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_priority_p2; ?></option>
          <option value="1" <?php if (!(strcmp(1, $row_Recordset_task['csa_priority']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_priority_p1; ?></option>
        </select>
		
		<select name="csa_temp" class="hide">
          <option value="5" <?php if (!(strcmp(5, $row_Recordset_task['csa_temp']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_level_l5; ?></option>
          <option value="4" <?php if (!(strcmp(4, $row_Recordset_task['csa_temp']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_level_l4; ?></option>
          <option value="3" <?php if (!(strcmp(3, $row_Recordset_task['csa_temp']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_level_l3; ?></option>
          <option value="2" <?php if (!(strcmp(2, $row_Recordset_task['csa_temp']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_level_l2; ?></option>
          <option value="1" <?php if (!(strcmp(1, $row_Recordset_task['csa_temp']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_dd_level_l1; ?></option>
        </select>
                </div>
				<span class="help-block"><?php echo $multilingual_default_task_priority_tips; ?></span>
              </div>
			  
              <div class="form-group col-xs-12 hidden">
                <label for="csa_temp"><?php echo $multilingual_default_tasklevel; ?></label>
                <div>
                  <select name="csa_temp" id="csa_temp" class="form-control">
                    <option value="5"><?php echo $multilingual_dd_level_l5; ?></option>
                    <option value="4"><?php echo $multilingual_dd_level_l4; ?></option>
                    <option value="3" SELECTED=“SELECTED”><?php echo $multilingual_dd_level_l3; ?></option>
                    <option value="2"><?php echo $multilingual_dd_level_l2; ?></option>
                    <option value="1"><?php echo $multilingual_dd_level_l1; ?></option>
                  </select>
                </div>
              </div>
			  
              <div class="form-group col-xs-12">
                <label for="csa_remark2"><?php echo $multilingual_default_task_start_status; ?></label>
                <div>
				<select name="csa_remark2" id="csa_remark2" class="form-control">
          <?php do {  ?>
          <option value="<?php echo $row_tkstatus['id'] ?>" <?php if (!(strcmp($row_tkstatus['id'], $row_Recordset_task['csa_remark2']))) {echo "selected=\"selected\"";} ?>><?php echo $row_tkstatus['task_status']?></option>
          <?php } while ($row_tkstatus = mysql_fetch_assoc($tkstatus));  $rows = mysql_num_rows($tkstatus);  if($rows > 0) {      mysql_data_seek($tkstatus, 0);	  $row_tkstatus = mysql_fetch_assoc($tkstatus);  } ?>
        </select>
				
               
                </div>
				<span class="help-block"><?php echo $multilingual_default_task_start_status_tips; ?></span>
              </div>
				</td>
          </tr>
        </table></td>
    </tr>
    <tr class="input_task_bottom_bg">
	<td></td>
      <td height="50px">
	  <button type="submit" class="btn btn-primary btn-sm submitbutton" name="cont" data-loading-text="<?php echo $multilingual_global_wait; ?>"><?php echo $multilingual_global_action_save; ?></button>
          <button type="button" class="btn btn-default btn-sm" onClick="javascript:history.go(-1);"><?php echo $multilingual_global_action_cancel; ?></button>
          
          <input type="submit"  id="btn5" value="<?php echo $multilingual_global_action_save; ?>"  style="display:none" />

        <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="TID" value="<?php echo $row_Recordset_task['TID']; ?>" /></td>
    </tr>
  </table>
</form>
<?php require('foot.php'); ?>
</body>
</html>
<?php
mysql_free_result($Recordset_task);
mysql_free_result($Recordset_type);
mysql_free_result($Recordset_project);
?>
