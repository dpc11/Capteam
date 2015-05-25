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
$backnums=-1;
if (isset($_GET['backnums'])) {
  $backnums = $_GET['backnums'];
}
mysql_select_db($database_tankdb, $tankdb);
$query_DetailRS1 = sprintf("SELECT * FROM tk_announcement inner join tk_user on tk_announcement.tk_anc_create=tk_user.uid  WHERE tk_announcement.aid = %s ", GetSQLValueString($colname_DetailRS1, "int"));
$DetailRS1 = mysql_query($query_DetailRS1, $tankdb) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);


/*
$docid = $colname_DetailRS1;
$maxRows_Recordset_actlog = 10;
$pageNum_Recordset_actlog = 0;
if (isset($_GET['pageNum_Recordset_actlog'])) {
  $pageNum_Recordset_actlog = $_GET['pageNum_Recordset_actlog'];
}
$startRow_Recordset_actlog = $pageNum_Recordset_actlog * $maxRows_Recordset_actlog;

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_actlog = sprintf("SELECT * FROM tk_log 
inner join tk_user on tk_log.tk_log_user =tk_user.uid 
								 WHERE tk_log_type = %s AND tk_log_class = 2 
								
								ORDER BY tk_log_time DESC", 
								GetSQLValueString($docid, "text")
								);
$query_limit_Recordset_actlog = sprintf("%s LIMIT %d, %d", $query_Recordset_actlog, $startRow_Recordset_actlog, $maxRows_Recordset_actlog);
$Recordset_actlog = mysql_query($query_limit_Recordset_actlog, $tankdb) or die(mysql_error());
$row_Recordset_actlog = mysql_fetch_assoc($Recordset_actlog);

if (isset($_GET['totalRows_Recordset_actlog'])) {
  $totalRows_Recordset_actlog = $_GET['totalRows_Recordset_actlog'];
} else {
  $all_Recordset_actlog = mysql_query($query_Recordset_actlog);
  $totalRows_Recordset_actlog = mysql_num_rows($all_Recordset_actlog);
}
$totalPages_Recordset_actlog = ceil($totalRows_Recordset_actlog/$maxRows_Recordset_actlog)-1;

$queryString_Recordset_actlog = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_actlog") == false && 
        stristr($param, "totalRows_Recordset_actlog") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_actlog = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_actlog = sprintf("&totalRows_Recordset_actlog=%d%s", $totalRows_Recordset_actlog, $queryString_Recordset_actlog);

*/
?>

<?php require('head.php'); ?>
<table width="100%" height="100%" id="totalpage">
  
  <tr>
    <td class="file_text_bg" height="100%">
	<div class="file_text_div" id="filecontain" style="background:white;height:100%">
	<div  id="tablecontain" style="background:white;">
	<table width="100%" align="center" style="border-bottom: 3px #D1D1D1 double;border-width:6px;margin-bottom:6px;" >
        <tr>
		<td>
	<h2><b><?php echo $row_DetailRS1['tk_anc_title']; ?></b></h2>	</td>
	</tr>
        <tr>
          <td>
		  <table width="100%" align="center">
        <tr>
		  <td width="10%">
		  <span class="glyphicon glyphicon-pencil"></span> <a href="announcement_edit.php?editAID=<?php echo $row_DetailRS1['aid']; ?>"><?php echo $multilingual_global_action_edit; ?></a>

		  </td>
		  
		  <td width="10%">
		  <span class="glyphicon glyphicon-remove"></span> <a  class="mouse_hover" onclick="javascript:if(confirm( '<?php 
	  echo $multilingual_global_action_delconfirm; ?>'))self.location='announcement_del.php?delAID=<?php echo $row_DetailRS1['aid']; ?>';"><?php echo $multilingual_global_action_del; ?></a>

		  </td>
		  <td width="10%">
		  <span class="glyphicon glyphicon-arrow-left"></span> <a onclick="javascript:history.go(<?php echo $backnums; ?>)" class="mouse_hover"><?php echo $multilingual_global_action_back; ?></a>
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
  <div id="logcontain">
  <tr >
          <td class="file_text_bg" >
  <?php if($totalRows_Recordset_actlog > 0){ //显示操作记录，如果有 ?>
		  <table style="width:940px;" align="center">
		  <tr>
		  <td>
		  <br />&nbsp;&nbsp;<span class="font_big18 fontbold"><?php echo $multilingual_log_title; ?></span><a name="task">
		  </td>
		  </tr>
		  </table>
		  </td>
        </tr>
  <tr>
    <td class="file_text_bg">
	<table class="table table-hover glink" style="width:940px;" align="center">
	<?php do { ?>
        <tr>
          <td><?php echo $row_Recordset_actlog['tk_log_time']; ?> <a href="user_view.php?recordID=<?php echo $row_Recordset_actlog['tk_log_user']; ?>"><?php echo $row_Recordset_actlog['tk_display_name']; ?></a> <?php echo $row_Recordset_actlog['tk_log_action']; ?>
          <td>
        </tr>
        <?php
} while ($row_Recordset_actlog = mysql_fetch_assoc($Recordset_actlog));
  $rows = mysql_num_rows($Recordset_actlog);
  if($rows > 0) {
      mysql_data_seek($Recordset_actlog, 0);
	  $row_Recordset_actlog = mysql_fetch_assoc($Recordset_actlog);
  }
?>
	</table>
	<table class="rowcon" border="0"  style="width:940px;"  align="center">
        <tr>
          <td><table border="0">
              <tr>
                <td><?php if ($pageNum_Recordset_actlog > 0) { // Show if not first page ?>
                    <a href="<?php printf("%s?pageNum_Recordset_actlog=%d%s", $currentPage, 0, $queryString_Recordset_actlog); ?>#task"><?php echo $multilingual_global_first; ?></a>
                    <?php } // Show if not first page ?></td>
                <td><?php if ($pageNum_Recordset_actlog > 0) { // Show if not first page ?>
                    <a href="<?php printf("%s?pageNum_Recordset_actlog=%d%s", $currentPage, max(0, $pageNum_Recordset_actlog - 1), $queryString_Recordset_actlog); ?>#task"><?php echo $multilingual_global_previous; ?></a>
                    <?php } // Show if not first page ?></td>
                <td><?php if ($pageNum_Recordset_actlog < $totalPages_Recordset_actlog) { // Show if not last page ?>
                    <a href="<?php printf("%s?pageNum_Recordset_actlog=%d%s", $currentPage, min($totalPages_Recordset_actlog, $pageNum_Recordset_actlog + 1), $queryString_Recordset_actlog); ?>#task"><?php echo $multilingual_global_next; ?></a>
                    <?php } // Show if not last page ?></td>
                <td><?php if ($pageNum_Recordset_actlog < $totalPages_Recordset_actlog) { // Show if not last page ?>
                    <a href="<?php printf("%s?pageNum_Recordset_actlog=%d%s", $currentPage, $totalPages_Recordset_actlog, $queryString_Recordset_actlog); ?>#task"><?php echo $multilingual_global_last; ?></a>
                    <?php } // Show if not last page ?></td>
              </tr>
            </table></td>
          <td align="right"><?php echo ($startRow_Recordset_actlog + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_actlog + $maxRows_Recordset_actlog, $totalRows_Recordset_actlog) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_actlog ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
        </tr>
      </table>
	</td>
  </tr>
  <?php } ?>
  </div>
</table>
</div>
<?php require('foot.php'); ?>
<script>
$(window).load(function()
	{		
	
		$("#totalpage").css("height",$(window).height()-60-64+"px");
		$("#totalpage").css("min-height",document.getElementById("tablecontain").clientHeight+document.getElementById("logcontain").clientHeight+60+"px");
		$("#filecontain").css("min-height",document.getElementById("tablecontain").clientHeight+110+"px");
		$("#filecontain").css("height",document.getElementById("totalpage").clientHeight-110-
				document.getElementById("logcontain").clientHeight+"px");
		$("#foot_top").css("min-height",document.getElementById("totalpage").clientHeight+60+60+"px");
	});
	$(window).resize(function()
	{	
		
		$("#totalpage").css("height",$(window).height()-60-64+"px");
		$("#filecontain").css("height",$(window).height()-60-64-document.getElementById("logcontain").clientHeight-110+"px");

		$("#foot_top").css("min-height",document.getElementById("totalpage").clientHeight+60+60+"px");
	});
</script>
</body>
</html><?php
mysql_free_result($DetailRS1);
?>