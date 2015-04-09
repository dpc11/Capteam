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

$item_type = "%";
if (isset($_GET['type'])) {
  $item_type = $_GET['type'];
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT * FROM tk_item WHERE tk_item_type = %s ", 
								GetSQLValueString($item_type, "text")
								);
$Recordset1 = mysql_query($query_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS -<?php echo $multilingual_set_title; ?></title>
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script type="text/javascript" src="srcipt/js.js"></script>
</head>
<body>
<?php require('admin_head.php'); ?>
<table border="0" cellspacing="5" cellpadding="12" width="100%">
  <tr>
    <td width="200px" class="set_menu_bg" valign="top"><?php require('setting_menu.php'); ?></td>
	<td  valign="top">
<table border="0" cellspacing="10" cellpadding="0" width="100%" class="glink">
<thead>
    <tr>
        <th></th><th></th><th></th>
        <th align="right"><?php if( $_SESSION['MM_Username'] == "csa") {?><a href="setting_add.php">Add item</a><?php } ?></th>
      </tr>
</thead>
<?php do { ?>
    <tr>
      <td width="15%" align="right"><?php echo $row_Recordset1['tk_item_title']; ?>:
	  </td>
	  <td width="35%"><b><?php echo $row_Recordset1['tk_item_value']; ?></b>
	  </td>
      <td width="15%"><a href="setting_edit.php?editID=<?php echo $row_Recordset1['item_id']; ?>&type=<?php echo $item_type; ?>"><?php echo $multilingual_set_setup; ?></a></td>
      <td width="35%">
	  <?php if( $_SESSION['MM_Username'] == "csa") {?>
	  <?php if ($totalRows_Recordset1 > 1  && $_SESSION['MM_Username'] <> $multilingual_dd_user_readonly) { // Show if recordset not empty ?>
	  <a href="#" 
	  onclick="javascript:if(confirm( '<?php 
	  echo $multilingual_global_action_delconfirm; ?>'))self.location='setting_del.php?delID=<?php echo $row_Recordset1['item_id']; ?>&type=<?php echo $item_type; ?>';"
	  ><?php echo $multilingual_global_action_del; ?></a>
	  <?php } else { // Show if recordset not empty ?> 

<?php echo $multilingual_global_action_del; ?>

<?php } // Show if recordset not empty ?>
<?php } ?>
	  </td>
    </tr>

    <tr>
      <td width="35%" align="right">
	  </td>
	  <td width="35%">
	  <span class="gray"><?php echo $row_Recordset1['tk_item_description']; ?></span>
	  
	  </td>
      <td width="15%"></td>
      <td width="15%">
	 </td>
    </tr>

	<tr>
	<td>&nbsp;</td>
	</tr>
<?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
</table>

<p>&nbsp;</p>
	</td>
  </tr>
</table>
<?php require('foot.php'); ?>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
