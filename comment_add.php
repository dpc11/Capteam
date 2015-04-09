<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php
$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "2") {   
  header("Location: ". $restrictGoTo); 
  exit;
}

$taskid = $_GET['taskid'];
$nowuserid = $_SESSION['MM_uid'];
$nowuser = $_SESSION['MM_Displayname'];

$pid = "-1";
if (isset($_GET['projectid'])) {
  $pid = $_GET['projectid'];
}

$date = "-1";
if (isset($_GET['date'])) {
  $date = $_GET['date'];
}

$tid = "-1";
if (isset($_GET['tid'])) { //是否是log评论
  $tid = $_GET['tid'];
}

$ctype = "-1";
if (isset($_GET['type'])) {
  $ctype = $_GET['type'];
}

if ($tid == "-1"){
$taskmid = $taskid;
} else { //如果tid有值，认为是log评论
$taskmid = $tid;
}

mysql_select_db($database_tankdb, $tankdb);
$query_log = sprintf("SELECT csa_to_user, csa_text  
FROM tk_task 
WHERE TID= %s ",GetSQLValueString($taskmid, "text"));
$log = mysql_query($query_log, $tankdb) or die(mysql_error());
$row_log = mysql_fetch_assoc($log);

$title = $row_log['csa_text'];  


// *** Redirect if username exists
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ( empty( $_POST['tk_comm_title'] ) ){
$tk_comm_title = "'',";
}else{
$tk_comm_title = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_comm_title']), "text"));
}

if ((isset($_POST["com_insert"])) && ($_POST["com_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tk_comment (tk_comm_title, tk_comm_user, tk_comm_pid, tk_comm_type, tk_comm_text) VALUES ($tk_comm_title %s, %s, %s, '')",
                       GetSQLValueString($nowuserid, "text"),
                       GetSQLValueString($taskid, "text"),
                       GetSQLValueString($ctype, "text"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());


if ($ctype == 3) { //如果是log备注
  $updateSQL = sprintf("UPDATE tk_task_byday SET csa_tb_comment=csa_tb_comment+1 WHERE tbid=%s", GetSQLValueString($taskid, "int"));
  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($updateSQL, $tankdb) or die(mysql_error());
}

if ($tid <> "-1"){

  $lyear = $date;
  $lgyear = str_split($lyear,4);
  $lgmonth = str_split($lgyear[1],2);
  $ldate = $lgyear[0]."-".$lgmonth[0]."-".$lgmonth[1];

$marklogtext = $_POST['tk_comm_title'];

$action = $multilingual_log_marklog1.$ldate.$multilingual_log_marklog2.$marklogtext;

$insertSQL2 = sprintf("INSERT INTO tk_log (tk_log_user, tk_log_action, tk_log_type, tk_log_class, tk_log_description) VALUES (%s, %s, %s, 1, ''  )",
                       GetSQLValueString($nowuserid, "text"),
                       GetSQLValueString($action, "text"),
                       GetSQLValueString($taskmid, "text"));  
$Result3 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());

}

if ($tid == "-1"){
	$comm_type = "taskcomm";
	$comm_title = $title;
  } else {
	  $comm_type = "logcomm";
	  $comm_title = $title."(".$ldate.$multilingual_log_marklog2.")";
  }


if($pid <> 1){
$msg_to = $row_log['csa_to_user']; 
$msg_from = $nowuserid;
$msg_type = $comm_type;
$msg_id = $taskmid;
$msg_title = $comm_title;
$mail = send_message( $msg_to, $msg_from, $msg_type, $msg_id, $msg_title );
}
    
    
if ($date == "-1"){
  $insertGoTo = "log_finish.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
} else {
 $insertGoTo = "log_view.php?date=".$date."&taskid=".$tid."#comment";
}



  header(sprintf("Location: %s", $insertGoTo));
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
function submitform()
{
    document.form1.cont.value='<?php echo $multilingual_global_wait; ?>';
	document.form1.cont.disabled=true;
	document.getElementById("btn5").click();
}
<!--
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
//-->
</script>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" >
 <div class="modal-body">

			  <div class="form-group col-xs-12">
      
                <div>
				 <textarea name="tk_comm_title" id="tk_comm_title"></textarea>
                </div>
				
              </div>
			  <div class="clearboth"></div>
      </div>
      <div class="modal-footer">

	  <?php if ($date == "-1"){ ?>
        <button type="button" class="btn btn-default btn-sm"  id="btn1" onclick="over()"><?php echo $multilingual_global_action_cancel; ?></button>
		<?php } else { ?>
		<button type="button" class="btn btn-default btn-sm" id="btn12" onclick="MM_goToURL('self','<?php echo "log_view.php?date=".$date."&taskid=".$tid; ?>');return document.MM_returnValue"><?php echo $multilingual_global_action_cancel; ?></button>
		<?php } ?>
        <button type="submit" class="btn btn-primary btn-sm" data-loading-text="<?php echo $multilingual_global_wait; ?>" name="cont" onClick="submitform()"><?php echo $multilingual_global_action_save; ?></button>
		
		<input type="submit"  id="btn5" value="<?php echo $multilingual_global_action_save; ?>"  style="display:none" />
		<input type="hidden" name="com_insert" value="form1" />
	
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