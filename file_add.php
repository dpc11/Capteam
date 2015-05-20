<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php require_once('function/file_log_function.php'); ?>
<?php
$restrictGoTo = "user_error3.php";

$project_id = "-1";
if (isset($_GET['projectid'])) {
  $project_id = $_GET['projectid'];
}

$p_id = "-1";
if (isset($_GET['pid'])) {
  $p_id = $_GET['pid'];
}
$pagetabs = "mcfile";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}

/*
$fd = "0";
if (isset($_GET['folder'])) {
  $fd = $_GET['folder'];
}

$pfiles = "-1";
if (isset($_GET['pfile'])) {
  $pfiles = $_GET['pfile'];
}
*/
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ( empty( $_POST['tk_doc_description'] ) ){
$tk_doc_description = "'',";
}else{
$tk_doc_description = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_doc_description']), "text"));
}

if ( empty( $_POST['csa_remark1'] ) ){
$csa_remark1 = "'',";
}else{
$csa_remark1 = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['csa_remark1']), "text"));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tk_document (tk_doc_title, tk_doc_description, tk_doc_attachment, tk_doc_pid, tk_doc_parentdocid, tk_doc_create, tk_doc_lastupdate,  tk_doc_backup1, tk_doc_type) VALUES (%s, $tk_doc_description $csa_remark1 %s, %s, %s, %s, 0, 1)",
                       GetSQLValueString($_POST['tk_doc_title'], "text"),
                       GetSQLValueString($_POST['tk_doc_class1'], "text"),
                       GetSQLValueString($_POST['tk_doc_class2'], "text"),
                       GetSQLValueString($_POST['tk_doc_create'], "text"),
                       GetSQLValueString($_POST['tk_doc_createtime'], "text"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());

  $docID = mysql_insert_id();
  $newName = $_SESSION['MM_uid'];

  //插入log数据库
  $log_id = insert_log_file($project_id,$newName,$p_id,$docID);
/*
$insertSQL2 = sprintf("INSERT INTO tk_log (tk_log_user, tk_log_action, tk_log_type, tk_log_class, tk_log_description) VALUES (%s, %s, %s, 2, '' )",
                       GetSQLValueString($newName, "text"),
                       GetSQLValueString($multilingual_log_adddoc, "text"),
                       GetSQLValueString($docID, "text"));  
  $Result2 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());
*/


$ptab = "&pagetab=".$pagetabs;	  
  $insertGoTo = "file_view.php?recordID=$docID&projectID=$project_id".$ptab;

  
  if (isset($_SERVER['QUERY_STRING'])) {
   // $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
  //  $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>


<?php require('head.php'); ?>
<link href="css/lhgcore/lhgcheck.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/lhgcore/lhgcore.js"></script>
<script type="text/javascript" src="js/lhgcore/lhgcheck.js"></script>
<script type="text/javascript">
J.check.rules = [
    { name: 'tk_doc_title', mid: 'doctitle', type: 'limit', requir: true, min: 2, max: 30, warn: '<?php echo $multilingual_announcement_titlerequired; ?>' }
	
];
	$(window).load(function()
	{
		J.check.regform('form1');
		
		$("#foot_top").css("min-height",document.getElementById("top_height").clientHeight+document.getElementById("file_table").clientHeight-45+"px");
	});
	$(window).resize(function()
	{	
		$("#foot_top").css("min-height",document.getElementById("top_height").clientHeight+document.getElementById("file_table").clientHeight+60+"px"); 
	});
</script>
<script charset="utf-8" src="plug-in/editor/kindeditor.js"></script>
<script charset="utf-8" src="plug-in/editor/lang/zh_CN.js"></script>
<script>
        var editor;
        KindEditor.ready(function(K) {
                editor = K.create('#tk_doc_description', {
			width : '1150px',
			height: '500px',
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
</script>

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="file_table">
    <tr>
	<!-- 左边20%的宽度的树或者说明  -->
			<td width="20%" height="100%" class="input_task_right_bg"  valign="top">
				<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
					<tr>
						<div class=" add_title col-xs-12">
							<h3 ><?php echo $multilingual_project_file_addfile; ?></h3>
						</div>
						<td valign="top" class="gray2">
							<h4 style="margin-top:40px; margin-left: 5px;" ><?php echo $multilingual_project_file_tiptitle; ?></h4>
							<p > <?php echo $multilingual_project_file_tiptext; ?></p>
						</td>
					</tr>
				</table>
			</td>
			<td width="80%"  height="100%" valign="top" align="center">
			<table width="90%" border="0" cellspacing="0" cellpadding="5" align="center" id="add_table"class="add_table">
          <tr>
            <td>
              <div class="form-group col-xs-12">
                <label for="tk_doc_title"><?php echo $multilingual_project_file_title; ?><span id="doctitle"></span></label>
                <div>
				<input type="text" name="tk_doc_title" id="tk_doc_title" value="" placeholder="<?php echo $multilingual_project_file_filetitle;?>"  class="form-control" />
				
                </div>
              </div>

				
			  
              <div class="form-group col-xs-12">
                <label for="tk_doc_description"><?php echo $multilingual_project_file_filetext; ?></label>
                <div>
				<textarea name="tk_doc_description" id="tk_doc_description" ></textarea>
                </div>
              </div>

              <div class="form-group col-xs-12">
			  
                <label for="csa_remark1" style="float:left" ><?php echo $multilingual_upload_attachment; ?></label>
				<span class="help-block" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $multilingual_upload_tip3; ?></span>
				<div class="input-group col-xs-12">
					  <input type="text" name="csa_remark1" id="csa_remark1" value=""placeholder="<?php echo $multilingual_upload_attachment; ?>" class="form-control" style="width:1000px;height:50px;">
						<button class="btn btn-default btn-lg"  style="float:left;margin-left:30px;" type="button" onClick="openBrWindow('upload_file.php','<?php echo $multilingual_global_upload; ?>','width=450,height=235')"><?php echo $multilingual_global_upload; ?></button>
				</div>
              </div>

				</td>
          </tr>
		  <tr >
						<td align="left" >
							<table width="250px" border="0" cellspacing="0" cellpadding="5" style="margin-left:650px;margin-top:20px;">
							<!-- 提交按钮 -->
								<tr >
									<td >
										<button type="submit" class="btn btn-primary btn-sm" name="cont" style="width:100px"><?php echo $multilingual_global_action_save; ?></button>
									</td>
									<td  width="20%" align="center" >
										<button type="button" class="btn btn-default btn-sm" style="width:100px" onClick="window.close();"><?php echo $multilingual_global_action_cancel; ?></button>
										<input type="hidden" name="MM_insert" value="form1" />
										
										<input type="hidden" name="tk_doc_class1" id="tk_doc_class1" value="<?php echo $project_id; ?>" />
										<input type="hidden" name="tk_doc_class2" id="tk_doc_class2" value="<?php echo $p_id; ?>" />
										<input name="tk_doc_create" type="hidden" value="<?php echo "{$_SESSION['MM_uid']}"; ?>"  />
										<input name="tk_doc_createtime" type="hidden" value="<?php echo date("Y-m-d H:i:s"); ?>"  />
										<input type="hidden" name="MM_insert" value="form1" />
									</td>
								</tr>
							</table>
						</td>
					</tr>					
				</table>
			</td>
		</tr>
  </table>

</form>
</div>
<?php require('foot.php'); ?>
</body>
</html>
