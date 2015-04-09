<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
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

if ( empty( $_POST['task_tpye_order'] ) ){
$task_tpye_order = "'0',";
}else{
$task_tpye_order = sprintf("%s,", GetSQLValueString($_POST['task_tpye_order'], "text"));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tk_task_tpye (task_tpye, task_tpye_backup1, tk_task_typerank) VALUES (%s, $task_tpye_order '')",
                       GetSQLValueString($_POST['task_tpye'], "text"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());

  $insertGoTo = "task_type_list.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = "SELECT * FROM tk_task_tpye ORDER BY task_tpye_backup1 ASC";
$Recordset1 = mysql_query($query_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS - <?php echo $multilingual_tasktype_title; ?></title>
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/custom.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgcheck.js"></script>
<script type="text/javascript">
<!--
J.check.rules = [
	{name: 'task_tpye', mid: 'csa_text_msg', requir: true, type: '',  warn: '<?php echo $multilingual_default_required4; ?>'},
	{name: 'task_tpye_order', mid: 'task_tpye_order_msg', type: 'rang', min: -1, warn: '<?php echo $multilingual_default_required5; ?>' }
   
];

window.onload = function()
{
    J.check.regform('form1');
}
//-->
</script>
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script type="text/javascript" src="srcipt/js.js"></script>
</head>

<body>
<?php require('admin_head.php'); ?>
<table border="0" cellspacing="5" cellpadding="12" width="100%">
  <tr>
    <td width="200px" class="set_menu_bg" valign="top"><?php require('setting_menu.php'); ?></td>
	<td >
	<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table class="filter_table"  border="0" cellspacing="0" cellpadding="0" >
<thead>
  <tr>
    <th colspan="2" ><?php echo $multilingual_tasktype_new; ?></th>
    </tr>
</thead>
<tbody>
    <tr>
      <td><?php echo $multilingual_tasktype_name; ?>:<br />        
        <input type="text" name="task_tpye" id="task_tpye" value="" size="32"  class="width-p80"/><span id="csa_text_msg"></span></td>
		<td><?php echo $multilingual_default_order; ?>:<br />        
        <input type="text" name="task_tpye_order" id="task_tpye_order" value="" size="32" /><span id="task_tpye_order_msg"></td>
    </tr>
</tbody>
 <tfoot>
    <tr>
      <td  colspan="2" ><input type="submit" value="<?php echo $multilingual_global_action_save; ?>" 
	   <?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?> 
	  /></td>
    </tr>
     </tfoot>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
<div class="taskdiv">
<table border="0" cellspacing="0" cellpadding="0" class="maintable">
<thead class="toptable">
    <tr>
        <th><?php echo $multilingual_tasktype_name; ?></th>
		<th><?php echo $multilingual_default_order; ?></th>
        <th colspan="2"><?php echo $multilingual_global_action; ?></th>
      </tr>
</thead>
<?php do { ?>
    <tr>
      <td width="50%"><?php echo $row_Recordset1['task_tpye']; ?></td>
	  <td width="20%"><?php echo $row_Recordset1['task_tpye_backup1']; ?></td>
      <td width="15%"><a href="task_type_edit.php?editID=<?php echo $row_Recordset1['id']; ?>"><?php echo $multilingual_global_action_edit; ?></a></td>
      <td width="15%">
	  <?php if ($totalRows_Recordset1 > 1  && $_SESSION['MM_Username'] <> $multilingual_dd_user_readonly) { // Show if recordset not empty ?>
	  <a href="#" 
	  onclick="javascript:if(confirm( '<?php 
	  echo $multilingual_global_action_delconfirm6; ?>'))self.location='task_type_del.php?delID=<?php echo $row_Recordset1['id']; ?>';"
	  ><?php echo $multilingual_global_action_del; ?></a>
	  <?php } else { // Show if recordset not empty ?> 

<?php echo $multilingual_global_action_del; ?>

<?php } // Show if recordset not empty ?>
	  </td>
    </tr>
<?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
</table>
</div>

	
	</td>
</tr>
</table>	
<?php require('foot.php'); ?>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>