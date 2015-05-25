<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php
$restrictGoTo = "user_error3.php";

$project_id = "-1";
if (isset($_GET['projectID'])) {
  $project_id = $_GET['projectID'];
}

$p_id = "-1";
if (isset($_GET['pid'])) {
  $p_id = $_GET['pid'];
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_Recordset1 = "-1";
if (isset($_GET['editID'])) {
  $colname_Recordset1 = $_GET['editID'];
}

$pagetabs = "mcfile";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}
$ptab = "&pagetab=".$pagetabs;	  

if ( empty( $_POST['tk_doc_description'] ) ){
$tk_doc_description = "tk_doc_description='',";
}else{
$tk_doc_description = sprintf("tk_doc_description=%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_doc_description']), "text"));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tk_document SET tk_doc_title=%s, $tk_doc_description  tk_doc_lastupdate=%s WHERE docid=%s and tk_doc_del_status=1",
                       GetSQLValueString($_POST['tk_doc_title'], "text"),
					   GetSQLValueString($_POST['tk_doc_lastupdate'], "text"),
                       GetSQLValueString($_POST['docid'], "int"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($updateSQL, $tankdb) or die(mysql_error());

  $newID = $colname_Recordset1;
  $newName = $_SESSION['MM_uid'];
/*
$insertSQL2 = sprintf("INSERT INTO tk_log (tk_log_user, tk_log_action, tk_log_type, tk_log_class, tk_log_description) VALUES (%s, %s, %s, 2, '')",
                       GetSQLValueString($newName, "text"),
                       GetSQLValueString($multilingual_log_editdoc, "text"),
                       GetSQLValueString($newID, "text"));  
$Result2 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());
*/



  $updateGoTo = "log_finish.php";

  
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT * FROM tk_document WHERE docid = %s  and tk_doc_del_status=1 ", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!DOCTYPE html PUBLIC>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS - <?php echo $multilingual_project_file_editfolder; ?></title>
<link href="css/lhgcore/lhgdialog.css" rel="stylesheet" type="text/css" />
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
<script type="text/javascript" src="js/lhgcore/jquery.js"></script>
<script src="js/bootstrap/bootstrap.js"></script>

<script charset="utf-8" src="plug-in/editor/kindeditor.js"></script>
<script charset="utf-8" src="plug-in/editor/lang/zh_CN.js"></script>
<script>
        var editor;
        KindEditor.ready(function(K) {
                editor = K.create('#tk_doc_description', {
			width : '100%',
			height: '246px',
			items:[
        'source', '|', 'undo', 'redo', '|',  'cut', 'copy', 'paste',
        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
        'superscript', 'clearhtml', '|', 'fullscreen', 
        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
        'flash', 'media', 'insertfile', 'table', 'hr', 'map', 'code', 'pagebreak', 'anchor', 
        'link', 'unlink', '|', 'about'
]
});
        });
</script>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
<div class="modal-body"  style="padding:12px;">


<div class="form-group col-xs-12">
                <label for="tk_doc_title"><?php echo $multilingual_project_file_foldertitle; ?><span id="doctitle"></span></label>
                <div>
				<input type="text" name="tk_doc_title" id="tk_doc_title" value="<?php echo $row_Recordset1['tk_doc_title']; ?>" placeholder="<?php echo $multilingual_project_file_foldertitle; ?>"  class="form-control" />
                </div>
              </div>


			 <div class="form-group col-xs-12">
                <label for="tk_doc_description"><?php echo $multilingual_project_file_description; ?></label>
                <div>
				<textarea name="tk_doc_description" id="tk_doc_description" ><?php echo $row_Recordset1['tk_doc_description']; ?></textarea>
                </div>
              </div>
			 
			 
			  <div class="clearboth"></div>
      </div>
      <div class="modal-footer" style="margin-top:0px; padding:10px 10px 10px;">


        <button type="button" class="btn btn-default btn-sm"  id="btn1" onclick="over()"><?php echo $multilingual_global_action_cancel; ?></button>

        <button type="submit" class="btn btn-primary btn-sm" data-loading-text="<?php echo $multilingual_global_wait; ?>" id="b02"><?php echo $multilingual_global_action_save; ?></button>
		
		<input type="hidden" name="docid" id="docid" value="<?php echo $row_Recordset1['docid']; ?>" /> 
		<input type="hidden" name="tk_doc_lastupdate" id="tk_doc_lastupdate" value="<?php echo date("Y-m-d H:i:s"); ?>" />
		
		<input type="hidden" name="MM_update" value="form1" />
	
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