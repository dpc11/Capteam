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

$currentPage = $_SERVER["PHP_SELF"];

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_sumtotal = sprintf("SELECT 
							COUNT(*) as count_prj   
							FROM tk_project 	
							WHERE project_to_user = %s", 
								GetSQLValueString($_SESSION['MM_uid'], "int")
								);
$Recordset_sumtotal = mysql_query($query_Recordset_sumtotal, $tankdb) or die(mysql_error());
$row_Recordset_sumtotal = mysql_fetch_assoc($Recordset_sumtotal);
$my_totalprj=$row_Recordset_sumtotal['count_prj'];

$pagetabs = "jprj";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}
?>

<?php require('head.php'); ?>

<div class="subnav">
<div class="float_left" style="width:85%">
<div class="btn-group">
<a type="button" class="btn btn-default btn-sm <?php if($pagetabs == "jprj") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=jprj" >
<?php echo $multilingual_project_jprj;?>
</a>
<?php if($my_totalprj > 0) { ?>
<a type="button" class="btn btn-default btn-sm <?php if($pagetabs == "mprj") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=mprj" >
<?php echo $multilingual_project_myprj;?>
</a>
<?php } ?>	
<a type="button" class="btn btn-default btn-sm <?php if($pagetabs == "allprj") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=allprj" >
<?php echo $multilingual_project_allprj;?>
</a>
<a type="button" class="btn btn-default btn-sm <?php if($pagetabs == "closeprj") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=closeprj" >
<?php echo $multilingual_project_closeprj;?>
</a>
</div>

</div>

<?php if($_SESSION['MM_rank'] > "3") {  ?>
<div class="float_right">
<button type="button" class="btn btn-default btn-sm" name="button2" id="button2" onclick="javascript:self.location='project_add.php';">
<span class="glyphicon glyphicon-plus-sign"></span> <?php echo $multilingual_projectlist_new; ?>
</button>
</div>
<?php }  ?> 

</div>
<div class="clearboth"></div>
<div class="pagemargin">

<?php require('control_project.php'); ?>
</div>
<?php require('foot.php'); ?>

</body>
</html>