<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php require_once('function/file_function.php'); ?>
<?php require_once('function/stage_function.php'); ?>
<?php
$restrictGoTo = "user_error3.php";

//$_SESSION['MM_pid'] = 24;
$myid = $_SESSION['MM_uid'];
//$thisProj = $_SESSION['MM_pid'];
$thisProj = -1;
//$thisSid = -1;
if(isset($_GET['pid'])){
  $thisProj = $_GET['pid'];
}

$proInfo = get_pro_info($thisProj);
$dateError = 1;//没有错误
/*
$to_user = "-1";
if (isset($_POST['csa_to_user'])) {
$to_user_arr = explode(", ,", $_POST['csa_to_user']);
  $to_user= $to_user_arr['0'];
}*/

/*$copy = -1;
if (isset($_GET['copy']) && isset($_SESSION['copytask'])) {
  $copy = $_GET['copy'];
  $task_arr = $_SESSION['copytask'];
  $ccarr = json_decode($task_arr['cc'], true);
}*/

$title = "-1";
if (isset($_POST['tk_stage_title'])) {
  $title= $_POST['tk_stage_title'];
}

$description = "-1";
if (isset($_POST['tk_stage_desc'])) {
  $description = $_POST['tk_stage_desc'];
}

$st_time = "-1";
if (isset($_POST['stage_start'])) {
  $st_time= $_POST['stage_start'];
}

$en_time = "-1";
if (isset($_POST['stage_end'])) {
  $en_time= $_POST['stage_end'];
}

/*$project_id = "-1";
if (isset($_GET['projectID'])) {
  $project_id = $_GET['projectID'];
}*/

/*$project_url = "-1";
if (isset($_GET['formproject'])) {
  $project_url= $_GET['formproject'];
}*/

/*$user_id = "-1";
if (isset($_GET['UID'])) {
  $user_id= $_GET['UID'];
}*/

/*$user_url = "-1";
if (isset($_GET['touser'])) {
  $user_url= $_GET['touser'];
}*/

/*if ( empty( $_POST['plan_hour'] ) )
		$_POST['plan_hour'] = '0.0';*/

/*if ( empty( $_POST['tk_stage_desc'] ) ){
$tk_stage_desc = "'',";
}else{
$tk_stage_desc = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_stage_desc']), "text"));
}

if ( empty( $_POST['csa_tag'] ) ){
$csa_tag = "'',";
}else{
$csa_tag = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['csa_tag']), "text"));
}*/

//for wbs!
/*$wbs_id = "-1";
if (isset($_GET['wbsID'])) {
  $wbs_id = $_GET['wbsID'];
}

$task_id = "-1";
if (isset($_GET['taskID'])) {
  $task_id = $_GET['taskID'];
}*/

/*mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_task = sprintf("SELECT *, 
tk_project.id as proid  
FROM tk_task 
inner join tk_project on tk_task.csa_project=tk_project.id 
WHERE TID = %s", GetSQLValueString($task_id, "int"));
$Recordset_task = mysql_query($query_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);
$totalRows_Recordset_task = mysql_num_rows($Recordset_task);*/

/*if ($wbs_id == "2"){
$wbs = $task_id.">".$wbs_id;
} else {
$wbs = $row_Recordset_task['csa_remark5'].">".$row_Recordset_task['TID'].">".$wbs_id; 
}*/


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

      $today_date = date('Y-m-d');
      $now_time = date('Y-m-d H:i:s',time());

      if($en_time<$today_date)
      {
          //echo("illegal");
         $dateError = -1;//结束时间小于今天
      }else if($en_time<$st_time)
      {
          //echo("can't");
         $dateError = -2;//结束时间小于开始时间
      }else if($st_time < $proInfo['project_start'])
      {
        $dateError = -3;//开始时间小于项目的开始时间
      }else if($en_time > $proInfo['project_end'])
      {
        $dateError = -4;//结束时间大于项目的结束时间
      }
      else
      {
		  $stageNAME = GetSQLValueString($_POST['tk_stage_title'], "text");
		  
          $insertSQL = sprintf("INSERT INTO tk_stage(tk_stage_title,tk_stage_desc,tk_stage_pid,
                tk_stage_createtime,tk_stage_st,tk_stage_et,tk_stage_lastupdate) 
                   VALUES ($stageNAME,%s,$thisProj,'$today_date',%s,%s,'$now_time')",
                        GetSQLValueString($_POST['tk_stage_desc'],"text"),
                         GetSQLValueString($_POST['stage_start'],"text"), 
                        GetSQLValueString($_POST['stage_end'],"text"));
          mysql_select_db($database_tankdb,$tankdb);
          $Result1 = mysql_query($insertSQL,$tankdb) or die(mysql_error());
          $thisSid = mysql_insert_id();

		  $parentID = get_project_document_ID($thisProj);
		  $CurDate = date("Y-m-d H:i:s");
		  $tk_doc_description="'本文件夹用于存放【".str_replace("'","",$stageNAME)."】阶段的所有资料。'";
			  $insertSQLFolder = sprintf("INSERT INTO tk_document (tk_doc_title, tk_doc_description,tk_doc_pid, tk_doc_parentdocid, tk_doc_create, tk_doc_lastupdate,tk_doc_backup1, tk_doc_type) VALUES ($stageNAME, $tk_doc_description,$thisProj, $parentID, 0,'$CurDate',1,1)");

				  mysql_select_db($database_tankdb, $tankdb);
				  $Result_folder = mysql_query($insertSQLFolder, $tankdb) or die(mysql_error());

				  $folderID = mysql_insert_id();
				
				$insertSQL = sprintf("UPDATE tk_stage SET tk_stage_folder_id = $folderID WHERE stageid=$thisSid");
              mysql_select_db($database_tankdb, $tankdb);
              $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());

        //插入日志数据库
          $log_id = insert_log($thisSid,$myid);
			  
			  
         /* $selSID = sprintf("SELECT stageid FROM tk_stage WHERE tk_stage_title LIKE %s AND tk_stage_desc LIKE %s
              AND tk_stage_pid=$thisProj",
                        GetSQLValueString($_POST['tk_stage_title'],"text"),
                        GetSQLValueString($_POST['tk_stage_desc'],"text"),
                         GetSQLValueString($_POST['stage_start'],"text"), 
                        GetSQLValueString($_POST['stage_end'],"text"));*/
          /*$selSID = "SELECT stageid FROM tk_stage ORDER BY stageid DESC";
          mysql_select_db($database_tankdb,$tankdb);
          $Result2 = mysql_query($selSID,$tankdb) or die(mysql_error());
          $row = mysql_fetch_array($Result2);*/
          //$thisSid = $row['stageid'];
          //$newID = add_task( $cc_post, $_POST['csa_from_user'],  $to_user_arr['0'],  $project_id, $_POST['csa_type'], $_POST['tk_stage_title'], $_POST['csa_priority'], $_POST['csa_temp'], $_POST['stage_start'], $_POST['stage_end'], $_POST['plan_hour'], $_POST['csa_remark2'], $_POST['csa_create_user'], $_POST['csa_last_user'], $task_id, $wbs, $wbs_id, $_SESSION['MM_uid'], $csa_tag, $tk_stage_desc );


          //$last_use_arr = pushlastuse($to_user_arr["0"], $to_user_arr["1"], $myid);

          /*if ($project_url == 1){
            $insertGoTo = "project_view.php?recordID=$project_id";
          } else if ($user_url == 1){
          $insertGoTo = "user_view.php?recordID=$user_id";
          }

          else {
            $insertGoTo = " task_view.php?editID=$newID";
          }*/
          $insertGoTo = "stage_view.php?pid=$thisProj&sid=$thisSid";
          //$insertGoTo = "stage_view.php";

          if (isset($_SERVER['QUERY_STRING'])) {
            $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
            $insertGoTo .= $_SERVER['QUERY_STRING'];
           }
           echo $insertGoTo;

            /*$msg_to = $to_user_arr['0'];
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

            }*/

          header(sprintf("Location: %s", $insertGoTo));
      }     
}

//$user_arr = get_user_select();

mysql_select_db($database_tankdb, $tankdb);
//$query_Recordset_type = "SELECT * FROM tk_task_tpye ORDER BY task_tpye_backup1 ASC";
//$Recordset_type = mysql_query($query_Recordset_type, $tankdb) or die(mysql_error());
//$row_Recordset_type = mysql_fetch_assoc($Recordset_type);
//$totalRows_Recordset_type = mysql_num_rows($Recordset_type);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_project = sprintf("SELECT * FROM tk_project WHERE id = %s",
                       GetSQLValueString($project_id, "int"));  
$Recordset_project = mysql_query($query_Recordset_project, $tankdb) or die(mysql_error());
$row_Recordset_project = mysql_fetch_assoc($Recordset_project);
$totalRows_Recordset_project = mysql_num_rows($Recordset_project);

mysql_select_db($database_tankdb, $tankdb);
$query_tkstatus = "SELECT * FROM tk_status WHERE task_status_backup2 <>1 ORDER BY task_status_backup1 ASC";
$tkstatus = mysql_query($query_tkstatus, $tankdb) or die(mysql_error());
$row_tkstatus = mysql_fetch_assoc($tkstatus);
$totalRows_tkstatus = mysql_num_rows($tkstatus);


?>
<?php require('head.php'); ?>
<link type="text/css" href="css/ui/ui.all.css" rel="stylesheet" />
        <link href="css/lhgcore/lhgcheck.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/lhgcore/lhgcore.js"></script>
        <script type="text/javascript" src="js/lhgcore/lhgcheck.js"></script>
    <link rel="stylesheet" href="css/bootstrap/datepicker3.css" type="text/css"/>
    <script type="text/javascript" src="js/bootstrap/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="js/bootstrap/locales/bootstrap-datepicker.zh-CN.js"></script>
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
//	{ name: 'select4', mid: 'csa_to_user_msg', requir: true, type: 'group', noselected: '', warn: '<?php echo $multilingual_default_required1; ?>' },
//	{ name: 'select2', mid: 'csa_from_user_msg', requir: true, type: 'group', noselected: '', warn: '<?php echo $multilingual_default_required1; ?>' },
//	{ name: 'csa_type', mid: 'csa_type_msg', requir: true, type: 'group', noselected: '', warn: '<?php echo $multilingual_default_required3; ?>' },
	//{ name: 'datepicker2', mid: 'csa_plan_st_msg', requir: true, type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' },
	//{ name: 'datepicker3', mid: 'csa_plan_et_msg', requir: true, type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' },
	{ name: 'tk_stage_title', mid: 'tk_stage_title_msg', requir: true, type: '',  warn: '<?php echo $multilingual_default_required4; ?>'},
//	{ name: 'plan_hour', mid: 'plan_hour_msg', type: 'rang', min: -1, warn: '<?php echo $multilingual_default_required5; ?>' }
   
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

<script charset="utf-8" src="plug-in/editor/kindeditor.js"></script>
<script charset="utf-8" src="plug-in/editor/lang/zh_CN.js"></script>
<script>
        var editor;
        KindEditor.ready(function(K) {
                editor = K.create('#tk_stage_desc', {
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
<!--
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
-->

<form action="<?php echo $editFormAction; ?>" method="post" name="myform" id="myform">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        
        <!-- 左边20%的宽度的树或者说明  -->
      <td width="20%" class="input_task_right_bg" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td valign="top">
			<dl style="margin-top:20px; margin-left: 4px;">
                <dt><h4 class="gray2"><strong><?php echo $multilingual_default_taskproject; ?></strong></h4></dt>
  <dd><a href="project_view.php?recordID=<?php echo $row_Recordset_project['id']; ?>" ><?php echo $row_Recordset_project['project_name']; ?></a></dd>
</dl>

<?php if ($task_id <> -1) { ?>
<dl>
  <dt><h4 class="gray2"><?php echo $multilingual_default_task_parent; ?></h4></dt>
  <dd><a href=" task_view.php?editID=<?php echo $row_Recordset_task['TID']; ?>" ><?php echo $row_Recordset_task['tk_stage_title']; ?></a></dd>
</dl><?php } else { ?>

<!--
<dl>
  <dt><h4 class="gray2"><?php echo $multilingual_subtask_root; ?></h4></dt>	 
</dl>
-->
	 <?php  } ?>
	 
	 <h4 style="margin-top:40px; margin-left: 4px;" class="gray2"><strong><?php echo $multilingual_default_stage_help_title; ?></strong></h4>
	 <p class="gray2">
	 <?php echo $multilingual_default_stage_help_text; ?>
	 </p>
	 
<!--
	 <h4 style="margin-top:30px" class="gray2"><strong><?php echo $multilingual_default_task_help_title2; ?></strong></h4>
	 <p class="gray2">
	 <?php echo $multilingual_default_task_help_text2; ?>
	 </p>
-->
              
              </td>
          </tr>
        </table></td>
        
        <!-- 右边80%宽度的主体内容 -->
      <td width="80%" valign="top"><table width="98%" border="0" cellspacing="0" cellpadding="5" align="center">
          <tr>
            <td><div class="col-xs-12">
                <h3><?php echo $multilingual_stageadd_title; ?></h3>
              </div>
                
<!-- 阶段名称 -->
              <div class="form-group col-xs-12">
                <label for="tk_stage_title"><?php echo $multilingual_default_task_title; ?><span  id="tk_stage_title_msg"></span></label>
                <div>
                  <input name="tk_stage_title" id="tk_stage_title" type="text" value="<?php if($title!=-1){echo $title;}?>" class="form-control" placeholder="<?php echo $multilingual_stageadd_title_plh;?>">
                  <span class="help-block"><?php echo $multilingual_default_stage_title_tips; ?></span>
                </div>
              </div>
                
<!-- 阶段不需要类型（已删除） -->
<!--
			  <div class="form-group col-xs-12">
                <label for="csa_type" ><?php echo $multilingual_default_stage_type; ?><span id="csa_type_msg"></span></label>
                <div>
                  <select name="csa_type" id="csa_type" onChange="option_gourl(this.value)" class="form-control">
                    <option value=""><?php echo $multilingual_global_select; ?></option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_Recordset_type['id']?>" <?php if($copy==1){ if (!(strcmp($row_Recordset_type['id'], @$task_arr['type']))) {echo "selected=\"selected\"";}} ?>><?php echo $row_Recordset_type['task_tpye']?></option>
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
				<span class="help-block"><?php echo $multilingual_default_stage_type_tips; ?> 
				
				<abbr  title="<?php echo $multilingual_default_task_catips2; ?>">
				<span class="glyphicon glyphicon-question-sign"><?php echo $multilingual_default_task_ca; ?></span>
				</abbr>
				</span>
              </div>
			  
			  <div class="form-group  col-xs-6">
                <label for="select4" ><?php echo $multilingual_default_task_to; ?><span id="csa_to_user_msg"></span></label>
                <div >
                  <select id="select4" name="csa_to_user" onChange="option_gourl(this.value)"  class="form-control">
                    <option value="" ><?php echo $multilingual_global_select; ?></option>
					<?php if($_SESSION['MM_last'] <> null){ $last_arr = json_decode($_SESSION['MM_last'], true); ?>
					<optgroup label="<?php echo $multilingual_default_task_lastusers;?>">
					<?php foreach($last_arr as $key => $val){  ?>
					 <option value='<?php echo $val["uid"]?>, ,<?php echo $val["uname"]?>' ><?php echo $val["uname"]?></option>
					 <?php } ?>
					</optgroup>
					<?php } ?>
					 
					 <optgroup label="<?php echo $multilingual_default_task_users;?>">
					 <?php foreach($user_arr as $key => $val){  ?>
					 <option value='<?php echo $val["uid"]?>, ,<?php echo $val["name"]?>' 
		  <?php if (!(strcmp($val["uid"], $user_id)) && $copy==-1) {echo "selected=\"selected\"";} else if ($copy==1){ if(!(strcmp($val["uid"], $task_arr['to']))) {echo "selected=\"selected\"";} }?>
		  ><?php 
		   $py = substr( pinyin($val["name"]), 0, 1 );
		  echo $py."-".$val["name"]?></option>
					 <?php } ?>  
					 </optgroup>

                    <?php if ($_SESSION['MM_rank'] > "4") { ?>
                    <option value="-2" class="gray" >+<?php echo $multilingual_user_new; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <span class="help-block"><?php echo $multilingual_taskadd_totip; ?></span> </div>

				<div class="form-group  col-xs-6">
                <label for="select2"><?php echo $multilingual_default_task_from; ?><span id="csa_from_user_msg"></span></label>
                <div>
                  <select id="select2" name="csa_from_user" onChange="option_gourl(this.value)" >
				  <?php foreach($user_arr as $key => $val){  ?>
					 <option value="<?php echo $val["uid"]?>" 
		  <?php if (!(strcmp($val["uid"], "{$_SESSION['MM_uid']}")) && $copy==-1) {echo "selected=\"selected\"";} else if($copy==1){ if (!(strcmp($val["uid"], $task_arr['from'])) ) {echo "selected=\"selected\"";} } ?>
		  ><?php 
		   $py = substr( pinyin($val["name"]), 0, 1 );
		  echo $py."-".$val["name"]?></option>
					 <?php } ?>  

                    <?php if ($_SESSION['MM_rank'] > "4") { ?>
                    <option value="-2" class="gray" >+<?php echo $multilingual_user_new; ?></option>
                    <?php } ?>
                  </select>
                  <input name="csa_create_user" type="text"  id="csa_create_user" value="<?php echo "{$_SESSION['MM_uid']}"; ?>"  style="display:none">
                  <input name="csa_last_user" type="text"  id="csa_last_user" value="<?php echo "{$_SESSION['MM_uid']}"; ?>" style="display:none">
                </div>
                <span class="help-block"><?php echo $multilingual_exam_tip; ?></span> </div>

				<div class="form-group  col-xs-12">
                <label for="user_cc"><?php echo $multilingual_default_task_cc; ?></label>
                <div>
                  <select id="user_cc" name="user_cc[]" multiple="multiple">
				  
				  <?php foreach($user_arr as $key => $val){  ?>
					 <option value='{"uid":"<?php echo $val["uid"]?>", "uname":<?php echo json_encode($val["name"])?> }' <?php if($copy==1){if (in_2array($val["uid"], $ccarr)==1 ) {echo "selected=\"selected\"";} } ?> ><?php 
		   $py = substr( pinyin($val["name"]), 0, 1 );
		  echo $py."-".$val["name"]?></option>
					 <?php } ?> 

                  </select>
                  <input name="csa_create_user" type="text"  id="csa_create_user" value="<?php echo "{$_SESSION['MM_uid']}"; ?>"  style="display:none">
                  <input name="csa_last_user" type="text"  id="csa_last_user" value="<?php echo "{$_SESSION['MM_uid']}"; ?>" style="display:none">
                </div>
                <span class="help-block"><?php echo $multilingual_default_task_cc_tips; ?></span> </div>
-->
			  
<!-- 阶段描述 -->
              <div class="form-group col-xs-12">
                <label for="tk_stage_desc"><?php echo $multilingual_default_task_description; ?><span  id="tk_stage_title_msg"></span></label>
                <div>
                  <textarea id="tk_stage_desc" name="tk_stage_desc" ><?php if($description!=-1){echo $description;}?></textarea>
                </div>
              </div>
              
<!--
              <div class="form-group  col-xs-12">
                <label for="csa_tag"><?php echo $multilingual_default_tasktag; ?><span  id="tk_stage_title_msg"></span></label>
                <div>
                  <input name="csa_tag" id="csa_tag" type="text" value="<?php if($copy==1){echo $task_arr['tag'];}?>" class="form-control" placeholder="<?php echo $multilingual_default_tasktag;?>">
                </div>
				<span class="help-block"><?php echo $multilingual_default_task_tag_tips; ?></span>
              </div>
-->

<!-- 起始时间 -->
				<div class="form-group col-xs-12">
                <label for="datepicker2"><?php echo $multilingual_default_task_planstart; ?><!--<span id="csa_plan_st_msg"></span>-->
                    <lable style="color:#F00;font-size:14px">
                       <?php if($dateError==-2) 
                                { echo ('&nbsp&nbsp&nbsp');echo "结束时间小于开始时间";}
                             else if($dateError == -3)
                             {
                                echo ('&nbsp&nbsp&nbsp');echo "开始时间小于项目的开始时间";
                             } ?>
                    </lable>
                </label>
                <div>
                  <input type="text" name="stage_start" id="datepicker2" value="<?php if($st_time==-1){echo date("Y-m-d");} else {echo $st_time;} ?>" class="form-control"  />
                </div>
<!--				<span class="help-block"><?php echo $multilingual_default_task_starttime_tips; ?></span>-->
              </div>
			  
<!-- 结束时间 -->
              <div class="form-group col-xs-12">
                <label for="datepicker3"><?php echo $multilingual_default_task_planend; ?><!--<span id="csa_plan_et_msg"></span>-->
                    <lable style="color:#F00;font-size:14px">
                       <?php if($dateError==-2) 
                                {echo ('&nbsp&nbsp&nbsp');echo "结束时间小于开始时间";} 
                            else if ($dateError==-1) {echo ('&nbsp&nbsp&nbsp'); echo "结束时间小于今天";}
                            else if ($dateError==-4) {echo ('&nbsp&nbsp&nbsp'); echo "结束时间大于项目的结束时间";}
                                 ?>
                    </lable>
                </label>
                <div>
                  <input type="text" name="stage_end" id="datepicker3" value="<?php if($en_time==-1){echo date("Y-m-d",strtotime("+1 day"));} else {echo $en_time;} ?>" class="form-control" />
                </div>
<!--				<span class="help-block"><?php echo $multilingual_default_task_endtime_tips; ?></span>-->
              </div>

<!-- 计划工时（已删除） -->
<!--
              <div class="form-group col-xs-12">
                <label for="plan_hour"><?php echo $multilingual_default_task_planhour; ?><span id="plan_hour_msg"></span></label>
                <div class="input-group">
                  <input type="text" name="plan_hour" id="plan_hour"  value="<?php if($copy==1){echo $task_arr['hour'];}?>" size="20" class="form-control" />
				  <span class="input-group-addon"><?php echo $multilingual_global_hour; ?></span>
                </div>
				<span class="help-block"><?php echo $multilingual_default_task_pv_tips; ?></span>
              </div>
			  
              <div class="form-group col-xs-12">
                <label for="csa_priority"><?php echo $multilingual_default_task_priority; ?></label>
                <div>
                  <select name="csa_priority"  id="csa_priority" class="form-control">
                    <option value="5" <?php if($copy==1){ if (!(strcmp(5, $task_arr['priority'])) ) {echo "selected=\"selected\"";}} ?>><?php echo $multilingual_dd_priority_p5; ?></option>
                    <option value="4" <?php if($copy==1){ if (!(strcmp(4, $task_arr['priority'])) ) {echo "selected=\"selected\"";} } ?>><?php echo $multilingual_dd_priority_p4; ?></option>
                    <option value="3" <?php if ($copy==-1){echo "selected=\"selected\"";}else if($copy==1){if (!(strcmp(3, $task_arr['priority'])) && $copy==1) {echo "selected=\"selected\"";} } ?>><?php echo $multilingual_dd_priority_p3; ?></option>
                    <option value="2" <?php if($copy==1){ if (!(strcmp(2, $task_arr['priority']))) {echo "selected=\"selected\"";} } ?>><?php echo $multilingual_dd_priority_p2; ?></option>
                    <option value="1" <?php if($copy==1){ if (!(strcmp(1, $task_arr['priority']))) {echo "selected=\"selected\"";} } ?>><?php echo $multilingual_dd_priority_p1; ?></option>
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
                  <select name="csa_remark2" id="csa_remark2"  class="form-control">
                    <?php
do {  
?>
                    <option value="<?php echo $row_tkstatus['id']?>" <?php if($copy==1){if (!(strcmp($row_tkstatus['id'], $task_arr['status']))) {echo "selected=\"selected\"";} } ?>><?php echo $row_tkstatus['task_status']?></option>
                    <?php
} while ($row_tkstatus = mysql_fetch_assoc($tkstatus));
  $rows = mysql_num_rows($tkstatus);
  if($rows > 0) {
      mysql_data_seek($tkstatus, 0);
	  $row_tkstatus = mysql_fetch_assoc($tkstatus);
  }
?>
                  </select>
                </div>
				<span class="help-block"><?php echo $multilingual_default_task_start_status_tips; ?></span>
              </div>
-->
            
				</td>
          </tr>
        </table></td>
    </tr>
    <tr  class="input_task_bottom_bg" >
	<td></td>
      <td height="50px">
          
<!-- 提交按钮 -->
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
