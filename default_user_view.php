<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php
$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "2") {   
  header("Location: ". $restrictGoTo); 
  exit;
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$date = "-1";
if (isset($_GET['date'])) {
  $date = $_GET['date'];
}

$tid = "-1";
if (isset($_GET['tid'])) {
  $tid = $_GET['tid'];
}

$nowuser = $_SESSION['MM_uid'];

$commentid = "-1";
if (isset($_GET['editcoID'])) {
  $commentid = $_GET['editcoID'];
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ( empty( $_POST['tk_comm_title'] ) ){
$tk_comm_title = "tk_comm_title='',";
}else{
$tk_comm_title = sprintf("tk_comm_title=%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_comm_title']), "text"));
}

if ((isset($_POST["comment_update"])) && ($_POST["comment_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tk_comment SET $tk_comm_title tk_comm_user=%s WHERE coid= %s",
                       GetSQLValueString($nowuser, "text"),
                       GetSQLValueString($commentid, "int"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($updateSQL, $tankdb) or die(mysql_error());

if ($date == "-1"){
  $updateGoTo = "log_finish.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  } else {
 $updateGoTo = "log_view.php?date=".$date."&taskid=".$tid."#comment";
}
  header(sprintf("Location: %s", $updateGoTo));
}


mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_comment = sprintf("SELECT * FROM tk_comment WHERE coid = %s", GetSQLValueString($commentid, "int"));
$Recordset_comment = mysql_query($query_Recordset_comment, $tankdb) or die(mysql_error());
$row_Recordset_comment = mysql_fetch_assoc($Recordset_comment);
$totalRows_Recordset_comment = mysql_num_rows($Recordset_comment);

$comuser = $row_Recordset_comment['tk_comm_user'];

$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "5" && $comuser <> $_SESSION['MM_uid']) {   
  header("Location: ". $restrictGoTo); 
  exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="skin/themes/base/lhgdialog.css" rel="stylesheet" type="text/css" />
	<title>log</title>
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

<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>

<script charset="utf-8" src="editor/kindeditor.js"></script>
<script charset="utf-8" src="editor/lang/zh_CN.js"></script>
<script>
        var editor;
        KindEditor.ready(function(K) {
                editor = K.create('#tk_comm_title', {
			width : '100%',
			height: '295px',
			items:[
        'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'cut', 'copy', 'paste',
        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
        'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'forecolor', 'hilitecolor', 'lineheight', 'bold',
        'italic', 'underline', 'strikethrough', 'removeformat', '|',   
        'formatblock', 'fontname', 'fontsize', '|','image',
        'flash', 'media', 'insertfile', 'table', 'hr', 'pagebreak', 'anchor', 
        'link', 'unlink', '|', 'about'
]
});
        });
		
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
</script>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" >

      <div class="modal-body">

			  <div class="form-group col-xs-12">
      
                <div>
				 <textarea name="tk_comm_title" id="tk_comm_title" ><?php echo htmlentities($row_Recordset_comment['tk_comm_title'], ENT_COMPAT, 'utf-8'); ?></textarea>
                </div>
				
              </div>
			  <div class="clearboth"></div>
      </div>
      <div class="modal-footer">
	  <?php if ($date == "-1"){ ?>
        <button type="button" class="btn btn-default btn-sm" onclick="over()"><?php echo $multilingual_global_action_cancel; ?></button>
		<?php } else { ?>
		<button type="button" class="btn btn-default btn-sm" id="btn12" onclick="MM_goToURL('self','<?php echo "log_view.php?date=".$date."&taskid=".$tid; ?>');return document.MM_returnValue"><?php echo $multilingual_global_action_cancel; ?></button>
		<?php } ?>
        <button type="submit" class="btn btn-primary btn-sm" data-loading-text="<?php echo $multilingual_global_wait; ?>"><?php echo $multilingual_global_action_save; ?></button>
	
</div>

<script type="text/javascript">
$('button[data-loading-text]').click(function () {
    var btn = $(this).button('loading');
    setTimeout(function () {
        btn.button('reset');
    }, 3000);
});
</script>


  <input type="hidden" name="comment_update" value="form1" />
</form>
</body>
</html>