<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tk_item (tk_item_key, tk_item_value, tk_item_title, tk_item_description, tk_item_type) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['key'], "text"),
                       GetSQLValueString($_POST['value'], "text"),
	                   GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($_POST['type'], "text")
  );

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());



  $insertGoTo = "setting.php?type=setting";

  header(sprintf("Location: %s", $insertGoTo));
}

?>
<!DOCTYPE html PUBLIC >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Capteam - <?php echo $multilingual_tasktype_title; ?></title>
<link href="css/tk_style.css" rel="stylesheet" type="text/css" />
<link href="css/custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery/jquery.js"></script>
</head>

<body>

<!-- admin head -->
<div class="admin_topbar" id="headerlink">
<div class="admin_logo"></div>
<div class="logininfo"><div class="float_left"><?php echo $multilingual_head_hello; ?> <?php echo "$_SESSION['MM_Displayname']"; ?>, </div> 
  &nbsp;&nbsp;<a href="index.php"><?php echo $multilingual_head_frontend; ?></a>&nbsp;&nbsp;<a href="<?php echo $logoutAction ?>"><?php echo $multilingual_head_logout; ?></a></div></div>
  
<br/>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table class="filter_table"  border="0" cellspacing="0" cellpadding="0" >
<thead>
  <tr>
    <th>Add item</th>
    </tr>
</thead>
<tbody>
    <tr>
      <td>Key:<br />        
        <input type="text" name="key" value="" size="32"  class="width-p80"/></td>
    </tr>
	<tr>
      <td>Value:<br />        
        <input type="text" name="value" value="" size="32"  class="width-p80"/></td>
    </tr>
	<tr>
      <td>Title:<br />        
        <input type="text" name="title" value="" size="32"  class="width-p80"/></td>
    </tr>
	<tr>
      <td>Description:<br />    
	   <textarea name="description" cols="50" rows="5" class="width-p80" ></textarea>
      </td>
    </tr>
	<tr>
      <td>Type:<br />        
        <input type="text" name="type" value="" size="32"  class="width-p80"/></td>
    </tr>
</tbody>
 <tfoot>
    <tr>
      <td><input type="submit" value="<?php echo $multilingual_global_action_save; ?>" 
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
<?php require('foot.php'); ?>
</body>
</html>