<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "4") {   
  header("Location: ". $restrictGoTo); 
  exit;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ( empty( $_POST['project_code'] ) ){
$project_code = "'',";
}else{
$project_code = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['project_code']), "text"));
}

if ( empty( $_POST['project_text'] ) ){
$project_text = "'',";
}else{
$project_text = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['project_text']), "text"));
}

if ( empty( $_POST['project_from_contact'] ) ){
$project_from_contact = "'',";
}else{
$project_from_contact = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['project_from_contact']), "text"));
}

if ( empty( $_POST['project_start'] ) )
		$_POST['project_start'] = '0000-00-00';

if ( empty( $_POST['project_end'] ) )
		$_POST['project_end'] = '0000-00-00';

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tk_project (project_name, project_code, project_text, project_start, project_end, project_to_user, project_status, project_from, project_from_user, project_to_dept, project_remark, project_from_contact) VALUES (%s, $project_code $project_text %s, %s, %s, %s, '', '', '', '', '')",
                       GetSQLValueString($_POST['project_name'], "text"),
                       GetSQLValueString($_POST['project_start'], "date"),
                       GetSQLValueString($_POST['project_end'], "date"),
                       GetSQLValueString($_POST['project_to_user'], "text"),
                       GetSQLValueString($_POST['project_status'], "text"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());
  $newID = mysql_insert_id();
  $insertGoTo = "project_view.php?recordID=$newID";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$user_arr = get_user_select();

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset3 = "SELECT * FROM tk_status_project ORDER BY task_status_pbackup1 ASC";
$Recordset3 = mysql_query($query_Recordset3, $tankdb) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

?>
<?php require('head.php'); ?>
	<link type="text/css" href="skin/themes/base/ui.all.css" rel="stylesheet" />
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
		$('#datepicker').datepicker({
			format: "yyyy-mm-dd"
	<?php if ($language=="cn") {echo ", language: 'zh-CN'" ;}?>
		});
		$('#datepicker2').datepicker({
			format: "yyyy-mm-dd"
	<?php if ($language=="cn") {echo ", language: 'zh-CN'" ;}?>
		});
	});
	</script>
<script type="text/javascript">
J.check.rules = [
    { name: 'project_name', mid: 'projecttitle', type: 'limit', requir: true, min: 2, max: 32, warn: '<?php echo $multilingual_projectstatus_titlerequired; ?>' },
	{ name: 'datepicker', mid: 'datepicker_msg', type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' },
	{ name: 'datepicker2', mid: 'datepicker2_msg', type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' }
	
];

window.onload = function()
{
    J.check.regform('form1');
}

function option_gourl(str)
{
if(str == '-1')window.open('user_add.php');
if(str == '-2')window.open('project_status.php');
}
</script>
<script charset="utf-8" src="editor/kindeditor.js"></script>
<script charset="utf-8" src="editor/lang/zh_CN.js"></script>
<script>
        var editor;
        KindEditor.ready(function(K) {
                editor = K.create('#project_text', {
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
<!-- Initialize the plugin: -->
<script type="text/javascript">
  $(document).ready(function() {

					$('#select2').multiselect({

			        	enableCaseInsensitiveFiltering: true,
						maxHeight: 360,
						filterPlaceholder: '<?php echo $multilingual_user_filter; ?>'
                    });
					

	
  });
</script>





<form action="<?php echo $editFormAction; ?>" method="post" name="myform" id="form1">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="25%" class="input_task_right_bg" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td valign="top" class="gray2">
	 <h4 style="margin-top:40px" ><strong><?php echo $multilingual_project_view_nowbs; ?></strong></h4>
	 <p >
	 <?php echo $multilingual_project_add_text; ?></p>
              
              </td>
          </tr>
        </table></td>
      <td width="75%" valign="top"><table width="98%" border="0" cellspacing="0" cellpadding="5" align="center">
          <tr>
            <td><div class="col-xs-12">
                <h3><?php echo $multilingual_projectlist_new; ?></h3>
              </div>
              <div class="form-group col-xs-12">
                <label for="project_name"><?php echo $multilingual_project_title; ?><span id="projecttitle"></span></label>
                <div>
				<input type="text" name="project_name" id="project_name" value="" class="form-control" placeholder="<?php echo $multilingual_project_title_tips; ?>" />
                </div>
              </div>
			  
			  
			  <div class="form-group  col-xs-12">
                <label for="select2" ><?php echo $multilingual_project_touser; ?><span id="csa_to_user_msg"></span></label>
                <div >
                  <select id="select2" name="project_to_user" onChange="option_gourl(this.value)"  class="form-control">
					<?php foreach($user_arr as $key => $val){  ?>
					 <option value='<?php echo $val["uid"]?>' 
		  ><?php 
		   $py = substr( pinyin($val["name"]), 0, 1 );
		  echo $py."-".$val["name"]?></option>
					 <?php } ?>  
				

                    <?php if ($_SESSION['MM_rank'] > "4") { ?>
                    <option value="-1" class="gray" >+<?php echo $multilingual_user_new; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <span class="help-block"><?php echo $multilingual_project_tips2; ?></span> </div>
				
				
				
				
			  
              <div class="form-group col-xs-12">
                <label for="project_text"><?php echo $multilingual_project_description; ?></label>
                <div>
                  <textarea name="project_text" id="project_text"></textarea>
                </div>
              </div>

              <div class="form-group  col-xs-12 hide">
                <label for="project_code"><?php echo $multilingual_project_code; ?></label>
                <div>
                  <input name="project_code" id="project_code" type="text" value="" class="form-control" placeholder="<?php echo $multilingual_project_code;?>">
                </div>
				<span class="help-block"><?php echo $multilingual_project_code; ?></span>
              </div>

				 <div class="form-group col-xs-12">
                <label for="project_status"><?php echo $multilingual_project_status; ?></label>
                <div>
				<select name="project_status" id="project_status" onChange="option_gourl(this.value)" class="form-control">
        <?php
do {  
?>
        <option value="<?php echo $row_Recordset3['psid']?>"><?php echo $row_Recordset3['task_status']?></option>
        <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
<?php if ($_SESSION['MM_rank'] > "4") { ?>
<option value="-2" class="gray" >+<?php echo $multilingual_projectstatus_new; ?></option>
<?php } ?>
      </select>


                </div>
				<span class="help-block"><?php echo $multilingual_project_tips; ?></span>
              </div>





				<div class="form-group col-xs-12">
                <label for="datepicker"><?php echo $multilingual_project_start; ?><span id="datepicker_msg"></span></label>
                <div>
                  <input type="text" name="project_start" id="datepicker" value="<?php echo date('Y-m-d'); ?>" class="form-control"  />
                </div>
              </div>
			  
              <div class="form-group col-xs-12">
                <label for="datepicker2"><?php echo $multilingual_project_end; ?><span id="datepicker2_msg"></span></label>
                <div>
                  <input type="text" name="project_end" id="datepicker2" value="<?php echo date("Y-m-d",strtotime("+7 day")); ?>" class="form-control" />
                </div>
              </div>
			  
             
				</td>
          </tr>
        </table></td>
    </tr>
    <tr class="input_task_bottom_bg" >
	<td></td>
      <td height="50px">
	  
	  <button type="submit" class="btn btn-primary btn-sm submitbutton" name="cont" ><?php echo $multilingual_global_action_save; ?></button>
          <button type="button" class="btn btn-default btn-sm" onClick="javascript:history.go(-1);"><?php echo $multilingual_global_action_cancel; ?></button>
          

        <input type="hidden" name="MM_insert" value="form1" /></td>
    </tr>
  </table>

</form>
<?php require('foot.php'); ?>
</body>
</html>
<?php
mysql_free_result($Recordset3);
?>