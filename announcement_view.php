<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php 
$maxRows_DetailRS1 = 10;
$pageNum_DetailRS1 = 0;
if (isset($_GET['pageNum_DetailRS1'])) {
  $pageNum_DetailRS1 = $_GET['pageNum_DetailRS1'];
}
$startRow_DetailRS1 = $pageNum_DetailRS1 * $maxRows_DetailRS1;

$colname_DetailRS1 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS1 = $_GET['recordID'];
}
mysql_select_db($database_tankdb, $tankdb);
$query_DetailRS1 = sprintf("SELECT * FROM tk_announcement inner join tk_user on tk_announcement.tk_anc_create=tk_user.uid  WHERE tk_announcement.AID = %s ORDER BY tk_anc_lastupdate DESC", GetSQLValueString($colname_DetailRS1, "int"));
$query_limit_DetailRS1 = sprintf("%s LIMIT %d, %d", $query_DetailRS1, $startRow_DetailRS1, $maxRows_DetailRS1);
$DetailRS1 = mysql_query($query_limit_DetailRS1, $tankdb) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);

if (isset($_GET['totalRows_DetailRS1'])) {
  $totalRows_DetailRS1 = $_GET['totalRows_DetailRS1'];
} else {
  $all_DetailRS1 = mysql_query($query_DetailRS1);
  $totalRows_DetailRS1 = mysql_num_rows($all_DetailRS1);
}
$totalPages_DetailRS1 = ceil($totalRows_DetailRS1/$maxRows_DetailRS1)-1;
?>

<?php require('head.php'); ?>
<table width="100%">
  <tr>
    <td class="file_text_bg">
	<div class="file_text_div">
	<table width="100%" align="center">
        <tr>
		<td>
	<h2><b><?php echo $row_DetailRS1['tk_anc_title']; ?></b></h2>	</td>
	</tr>
        <tr>
          <td>
		  <table width="100%" align="center">
        <tr>
		<?php if ($_SESSION['MM_rank'] > "4") {  ?>
		  <td width="10%">
		  <span class="glyphicon glyphicon-pencil"></span> <a href="announcement_edit.php?editAID=<?php echo $row_DetailRS1['AID']; ?>"><?php echo $multilingual_global_action_edit; ?></a>

		  </td>
		  
		  <td width="10%">
		  <span class="glyphicon glyphicon-remove"></span> <a  class="mouse_hover" onclick="javascript:if(confirm( '<?php 
	  echo $multilingual_global_action_delconfirm; ?>'))self.location='announcement_del.php?delAID=<?php echo $row_DetailRS1['AID']; ?>';"><?php echo $multilingual_global_action_del; ?></a>

		  </td>
		  <?php } ?>
		  <td width="10%">
		  <span class="glyphicon glyphicon-arrow-left"></span> <a onclick="javascript:history.go(-1)" class="mouse_hover"><?php echo $multilingual_global_action_back; ?></a>
		  </td>
		  <td>&nbsp;
		  </td>
        </tr>
		<tr>
		<td>&nbsp;
		</td>
		</tr>
      </table>
		  
		  </td>
        </tr>
	</table>
	
	 <?php if($row_DetailRS1['tk_anc_text'] <> "&nbsp;"  && $row_DetailRS1['tk_anc_text'] <> "") { ?>
  <?php 
	echo $row_DetailRS1['tk_anc_text']; 
	?>
  <?php } ?>
	</div>
	</td>
  </tr>
  
</table>
<?php require('foot.php'); ?>
</body>
</html><?php
mysql_free_result($DetailRS1);
?>