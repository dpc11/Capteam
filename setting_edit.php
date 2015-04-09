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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tk_item SET tk_item_key=%s, tk_item_value=%s, tk_item_title=%s, tk_item_description=%s, tk_item_type=%s WHERE item_id=%s",
                       GetSQLValueString($_POST['key'], "text"),
                       GetSQLValueString($_POST['value'], "text"),
	                   GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($_POST['type'], "text"),
                       GetSQLValueString($_POST['item_id'], "text"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($updateSQL, $tankdb) or die(mysql_error());

$itemtype = "-1";
if (isset($_GET['type'])) {
  $itemtype = $_GET['type'];
}

  $updateGoTo = "setting.php?type=$itemtype";

  header(sprintf("Location: %s", $updateGoTo));
}

$colname_Recordset1 = "-1";
if (isset($_GET['editID'])) {
  $colname_Recordset1 = $_GET['editID'];
}
mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT * FROM tk_item WHERE item_id = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS - <?php echo $multilingual_announcement_edit_title; ?></title>
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgcheck.js"></script>
<script type="text/javascript">
<!--
J.check.rules = [
	{name: 'value', mid: 'csa_text_msg', requir: true, type: '',  warn: '<?php echo $multilingual_default_required4; ?>'}
   
];

window.onload = function()
{
    J.check.regform('form1');
}
//-->
</script>
</head>

<body>
<?php require('admin_head.php'); ?>
<br />

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table class="filter_table"  border="0" cellspacing="0" cellpadding="0">
<thead>
  <tr>
    <th><?php echo $multilingual_set_setup.$row_Recordset1['tk_item_title']; ?></th>
  </tr>
</thead>
<tbody>
<?php if( $_SESSION['MM_Username'] <> "csa") {?>


    <tr style="display:none">
      <td>Key:<br />        
        <input type="text" name="key" value="<?php echo $row_Recordset1['tk_item_key']; ?>" size="32"  class="width-p80"/></td>
    </tr>
	<tr>
      <td>     
        <input type="text" name="value" id="value" value="<?php echo $row_Recordset1['tk_item_value']; ?>" size="32"  class="width-p80"/><span id="csa_text_msg"></span><br/>
		<span class="gray"><?php echo $row_Recordset1['tk_item_description']; ?></span>
		</td>
    </tr>
	<tr style="display:none">
      <td>Title:<br />        
        <input type="text" name="title" value="<?php echo $row_Recordset1['tk_item_title']; ?>" size="32"  class="width-p80"/></td>
    </tr>
	<tr style="display:none">
      <td>Description:<br /> 
	  
	  <textarea name="description" cols="50" rows="5" class="width-p80" ><?php echo $row_Recordset1['tk_item_description']; ?></textarea>
      </td>
    </tr>
	<tr>
      <td style="display:none">Type:<br />        
        <input type="text" name="type" value="<?php echo $row_Recordset1['tk_item_type']; ?>" size="32"  class="width-p80"/></td>
    </tr>
<?php } else {?>
    <tr>
      <td>Key:<br />        
        <input type="text" name="key" value="<?php echo $row_Recordset1['tk_item_key']; ?>" size="32"  class="width-p80"/></td>
    </tr>
	<tr>
      <td>Value:<br />        
        <input type="text" name="value" value="<?php echo $row_Recordset1['tk_item_value']; ?>" size="32"  class="width-p80"/></td>
    </tr>
	<tr>
      <td>Title:<br />        
        <input type="text" name="title" value="<?php echo $row_Recordset1['tk_item_title']; ?>" size="32"  class="width-p80"/></td>
    </tr>
	<tr>
      <td>Description:<br /> 
	  
	  <textarea name="description" cols="50" rows="5" class="width-p80" ><?php echo $row_Recordset1['tk_item_description']; ?></textarea>
      </td>
    </tr>
	<tr>
      <td>Type:<br />        
        <input type="text" name="type" value="<?php echo $row_Recordset1['tk_item_type']; ?>" size="32"  class="width-p80"/></td>
    </tr>
<?php } ?>
</tbody>
    <tfoot>
    <tr>
      <td nowrap="nowrap"><input type="submit" value="<?php echo $multilingual_global_action_save; ?>" 
	   <?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?> 
	  />
      <input name="button" type="button" id="button" onclick="javascript:history.go(-1)" value="<?php echo $multilingual_global_action_cancel; ?>" /></td>
     
    </tr>
	</tfoot> 
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="item_id" value="<?php echo $row_Recordset1['item_id']; ?>" />
</form>
<p>&nbsp;</p>
<?php require('foot.php'); ?>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>