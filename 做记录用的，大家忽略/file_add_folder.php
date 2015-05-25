<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
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
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ( empty( $_POST['tk_doc_description'] ) ){
$tk_doc_description = "'',";
}else{
$tk_doc_description = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_doc_description']), "text"));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tk_document (tk_doc_title, tk_doc_description, tk_doc_pid, tk_doc_parentdocid, tk_doc_create, tk_doc_lastupdate,tk_doc_backup1, tk_doc_type) VALUES (%s, $tk_doc_description  %s, %s, %s, %s, 1, 1)",
                       GetSQLValueString($_POST['tk_doc_title'], "text"),
                       GetSQLValueString($_POST['tk_doc_pid'], "text"),
                       GetSQLValueString($_POST['tk_doc_parentdocid'], "text"),
                       GetSQLValueString($_POST['tk_doc_create'], "text"),
                       GetSQLValueString($_POST['tk_doc_createtime'], "text"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());

  $docID = mysql_insert_id();
  $newName = $_SESSION['MM_uid'];
/*
$insertSQL2 = sprintf("INSERT INTO tk_log (tk_log_user, tk_log_action, tk_log_type, tk_log_class, tk_log_description) VALUES (%s, %s, %s, 2, '' )",
                       GetSQLValueString($newName, "text"),
                       GetSQLValueString($multilingual_log_adddoc, "text"),
                       GetSQLValueString($docID, "text"));  
  $Result2 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());
*/

$ptab = "&pagetab=".$pagetabs;	  
 $insertGoTo = "log_finish.php";
//$insertGoTo = "file.php?recordID=$p_id&projectID=$project_id".$ptab;

  
  if (isset($_SERVER['QUERY_STRING'])) {
   // $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
  //  $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Capteam - <?php echo $multilingual_project_file_addfolder; ?></title>
<link href="css/tk_style.css" rel="stylesheet" type="text/css" />
<link href="css/lhgcore/lhgcheck.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="js/lhgcore/lhgcore.js"></script>
    <script type="text/javascript" src="js/lhgcore/lhgcheck.js"></script>
<script type="text/javascript">
var P = window.parent, D = P.loadinndlg();   
function closreload(url)
{
    if(!url)
	    P.reload();    
}
function over()
{
    P.cancel();
}
	</script>
<script type="text/javascript">
J.check.rules = [
    { name: 'tk_doc_title', mid: 'doctitle', type: 'limit', requir: true, min: 1, max: 30, warn: '<?php echo $multilingual_announcement_titlerequired; ?>' }
	
];

window.onload = function()
{
    J.check.regform('form1');
}
</script>

<link href="css/bootstrap/bootstrap.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="js/jquery/jquery.js"></script>
<script src="js/bootstrap/bootstrap.js"></script>

<script charset="utf-8" src="plug-in/editor/kindeditor.js"></script>
<script charset="utf-8" src="plug-in/editor/lang/zh_CN.js"></script>
<script>
        var editor;
        KindEditor.ready(function(K) {
                editor = K.create('#tk_doc_description', {
			width : '100%',
			height: '336px',
			items:[
        'undo', 'redo', '|',  'cut', 'copy', 'paste',
        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
        'superscript', 'clearhtml','source', '|', 'fullscreen', 
        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
        'table', 'hr', 'code', 'pagebreak', 'link', 'unlink'
]
});
        });
</script>

</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
<div class="modal-body"  style="padding:12px;">


<div class="form-group">
                <label for="tk_doc_title" class="project_label"><?php echo $multilingual_project_file_foldertitle; ?><span id="doctitle"></span></label>
                <div>
				<input type="text" name="tk_doc_title" id="tk_doc_title" value="" style="height:45px" placeholder="<?php echo $multilingual_project_file_foldertitle; ?>"  class="form-control" />
                </div>
              </div>


			 <div class="form-group ">
                <label for="tk_doc_description" class="project_label" ><?php echo $multilingual_project_file_description; ?></label>
                <div>
				<textarea name="tk_doc_description" id="tk_doc_description" >

</textarea>
                </div>
              </div>
			 
			 
			  <div class="clearboth"></div>
      </div>
      <div class="modal-footer" style="margin-top:0px; padding:10px 10px 10px;">


        <button type="submit" class="btn btn-primary btn-lg"  style="margin-top:6px;margin-right:20px;" data-loading-text="<?php echo $multilingual_global_wait; ?>" ><?php echo $multilingual_global_action_save; ?></button>
		
        <button type="button" class="btn btn-default btn-lg"  style="margin-top:6px;margin-right:60px;" id="btn1" onclick="over()"><?php echo $multilingual_global_action_cancel; ?></button>

		<input type="hidden" name="tk_doc_pid" id="tk_doc_pid" value="<?php echo $project_id; ?>"  /> 
		
		<input type="hidden" name="tk_doc_parentdocid" id="tk_doc_parentdocid" value="<?php echo $p_id; ?>"  />
		
		<input name="tk_doc_create" type="hidden" value="<?php echo "{$_SESSION['MM_uid']}"; ?>" />
		
		<input name="tk_doc_createtime" type="hidden" value="<?php echo date("Y-m-d H:i:s"); ?>" />
		
		<input name="tk_doc_edit" type="hidden" value="<?php echo "{$_SESSION['MM_uid']}"; ?>"  />
		
		<input type="hidden" name="MM_insert" value="form1" />
	
</div>

<script type="text/javascript">
$('button[data-loading-text]').click(function () {
    var btn = $(this).button('loading');
    setTimeout(function () {
        btn.button('reset');
    }, 3000);
});
</script>
 
</form>
</body>
</html>