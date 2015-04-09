<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_admin.php'); ?>
<?php
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ( empty( $_POST['pro_status_order'] ) ){
$pro_status_order = "task_status_pbackup1='0'";
}else{
$pro_status_order = sprintf("task_status_pbackup1=%s", GetSQLValueString($_POST['pro_status_order'], "text"));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tk_status_project SET task_status=%s, task_status_display=%s, $pro_status_order WHERE psid=%s",
                       GetSQLValueString($_POST['task_status'], "text"),
                       GetSQLValueString($_POST['task_status_display'], "text"),
                       GetSQLValueString($_POST['psid'], "int"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($updateSQL, $tankdb) or die(mysql_error());

  $updateGoTo = "project_status.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_Recordset1 = "-1";
if (isset($_GET['editID'])) {
  $colname_Recordset1 = $_GET['editID'];
}
mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT * FROM tk_status_project WHERE psid = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgcheck.js"></script>
<script type="text/javascript">
<!--
J.check.rules = [
	{name: 'task_status', mid: 'task_status_msg', requir: true, type: '',  warn: '<?php echo $multilingual_default_required4; ?>'},
	{name: 'task_status_display', mid: 'task_status_display_msg', requir: true, type: '',  warn: '<?php echo $multilingual_default_required4; ?>'},
	{name: 'pro_status_order', mid: 'pro_status_order_msg', type: 'rang', min: -1, warn: '<?php echo $multilingual_default_required5; ?>' }
   
];

window.onload = function()
{
    J.check.regform('form1');
}
//-->
</script>
<title>WSS - <?php echo $multilingual_projectstatus_edit; ?></title>
<script type="text/javascript">
<!--
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->
</script>
</head>

<body>
<?php require('admin_head_sub.php'); ?>
<br />
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table class="filter_table"  border="0" cellspacing="0" cellpadding="0">
<thead>
  <tr>
    <th colspan="2"><?php echo $multilingual_projectstatus_edit; ?></th>
    </tr>
</thead>
<tbody>
    <tr >
      <td><?php echo $multilingual_projectstatus_name; ?>:<br />  <input type="text" name="task_status" id="task_status" value="<?php echo htmlentities($row_Recordset1['task_status'], ENT_COMPAT, 'utf-8'); ?>" size="32" class="width-p80"  /><span id="task_status_msg"></span></td>
	  <td><?php echo $multilingual_default_order; ?>:<br />        
        <input type="text" name="pro_status_order" id="pro_status_order" value="<?php echo htmlentities($row_Recordset1['task_status_pbackup1'], ENT_COMPAT, 'utf-8'); ?>" size="32" /><span id="pro_status_order_msg"></span></td>
    </tr>
    <tr>
      <td colspan="2"><?php echo $multilingual_projectstatus_style; ?>:<span id="task_status_display_msg"></span><br /><textarea name="task_status_display" id="task_status_display" cols="50" rows="5" class="width-p80" ><?php echo htmlentities($row_Recordset1['task_status_display'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
    </tr>
</tbody>
<tfoot>
    <tr>
      <td colspan="2"><input type="submit" value="<?php echo $multilingual_global_action_save; ?>" 
	   <?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?> 
	  />
        <label>
          <input name="button" type="submit" id="button" onclick="MM_goToURL('self','project_status.php');return document.MM_returnValue" value="<?php echo $multilingual_global_action_cancel; ?>" />
      </label></td>
    </tr>
</tfoot>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="psid" value="<?php echo $row_Recordset1['psid']; ?>" />
</form>
<p>&nbsp;</p>
<?php require('foot.php'); ?>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>