<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php
$logdate = $_GET['date'];
$taskid = $_GET['taskid'];

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
mysql_select_db($database_tankdb, $tankdb);
$query_log =  sprintf("SELECT * FROM tk_task_byday 
inner join tk_status on tk_task_byday.csa_tb_status=tk_status.id 
WHERE csa_tb_year= %s AND csa_tb_backup1= %s",    
                       GetSQLValueString($logdate, "text"),  
                       GetSQLValueString($taskid, "int"));
$log = mysql_query($query_log, $tankdb) or die(mysql_error());
$row_log = mysql_fetch_assoc($log);
$totalRows_log = mysql_num_rows($log);

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_Recordset_comment = 10;
$pageNum_Recordset_comment = 0;
if (isset($_GET['pageNum_Recordset_comment'])) {
  $pageNum_Recordset_comment = $_GET['pageNum_Recordset_comment'];
}
$startRow_Recordset_comment = $pageNum_Recordset_comment * $maxRows_Recordset_comment;

$logid = $row_log['tbid'];

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_comment = sprintf("SELECT * FROM tk_comment 
inner join tk_user on tk_comment.tk_comm_user =tk_user.uid 
								 WHERE tk_comm_pid = %s AND tk_comm_type = 3 
								
								ORDER BY tk_comm_lastupdate DESC", 
								GetSQLValueString($logid, "text")
								);
$query_limit_Recordset_comment = sprintf("%s LIMIT %d, %d", $query_Recordset_comment, $startRow_Recordset_comment, $maxRows_Recordset_comment);
$Recordset_comment = mysql_query($query_limit_Recordset_comment, $tankdb) or die(mysql_error());
$row_Recordset_comment = mysql_fetch_assoc($Recordset_comment);

if (isset($_GET['totalRows_Recordset_comment'])) {
  $totalRows_Recordset_comment = $_GET['totalRows_Recordset_comment'];
} else {
  $all_Recordset_comment = mysql_query($query_Recordset_comment);
  $totalRows_Recordset_comment = mysql_num_rows($all_Recordset_comment);
}
$totalPages_Recordset_comment = ceil($totalRows_Recordset_comment/$maxRows_Recordset_comment)-1;

$queryString_Recordset_comment = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_comment") == false && 
        stristr($param, "totalRows_Recordset_comment") == false && 
        stristr($param, "tab") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_comment = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_comment = sprintf("&totalRows_Recordset_comment=%d%s", $totalRows_Recordset_comment, $queryString_Recordset_comment);

$host_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER["QUERY_STRING"];
$host_url=strtr($host_url,"&","!");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="skin/themes/base/lhgdialog.css" rel="stylesheet" type="text/css" />
	<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
    <script type="text/javascript" src="srcipt/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
	<script type="text/javascript" src="srcipt/lhgcore.js"></script>
	<script type="text/javascript" src="srcipt/lhgdialog.js"></script>
	<title>WSS</title>
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


function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
	</script>
    <style type="text/css">
<!--
body,html{  font-family:Arial; width:100%;   margin: 0; padding: 0; min-width:500px; !important}
-->
    </style>
</head>

<body>

  <table align="center"  class="dialog_main glink">
    <tr valign="baseline">
      <td   align="right" class="dialog_left">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td   align="left" class="dialog_left"><h4><?php
$logyear = str_split($logdate,4);
$logmonth = str_split($logyear[1],2);
?>
              <?php echo $logyear[0]; ?>-<?php echo $logmonth[0]; ?>-<?php echo $logmonth[1]; ?></h4></td>
    </tr>
    <tr valign="baseline">
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0"  class="info_task_bg" style="margin-bottom:10px;">
  
  <tr>
    <td width="12%" class="info_task_title"><?php echo $multilingual_default_task_status; ?></td>
    <td width="40%"><?php echo $row_log['task_status_display']; ?></td>
    <td width="12%" class="info_task_title"><?php echo $multilingual_user_view_cost; ?></td>
    <td><?php echo $row_log['csa_tb_manhour']; ?> <?php echo $multilingual_global_hour; ?></td>
    </tr>
	<?php if ($row_Recordset_task['test01'] <> null) {?>
	<?php } ?>
</table></td>
    </tr>
	
	<tr>
	<td>
	<table width="100%" style="line-height:40px;">
            <?php if($_SESSION['MM_rank'] > "1") { ?>
			<td width="16%">
			
			<a href="#" onClick="javascript:self.location='comment_add.php?taskid=<?php echo $logid; ?>&date=<?php echo $logdate; ?>&tid=<?php echo $taskid; ?>&type=3';"><span class="glyphicon glyphicon-comment"></span> <?php echo $multilingual_default_addcom; ?></a>
            </td>
			<?php } ?>
            
           <?php if ($_SESSION['MM_rank'] > "4" || $row_log['csa_tb_backup2'] == $_SESSION['MM_uid']) {  ?>
            <td width="13%">
			<a onclick="MM_goToURL('self','<?php echo "log_edit.php?date=".$logdate."&taskid=".$taskid; ?>');return document.MM_returnValue" class="mouse_over"><span class="glyphicon glyphicon-pencil"></span> <?php echo $multilingual_global_action_edit; ?></a>
            </td>

			<td width="13%">
			<a  class="mouse_over" onClick="javascript:self.location='log_del.php?date=<?php echo $logdate; ?>&taskid=<?php echo $taskid; ?>';"><span class="glyphicon glyphicon-remove"></span> <?php echo $multilingual_global_action_del; ?></a>
            </td>
			<?php }  ?>
			
			<td>&nbsp;
			</td>
			</tr>
			
			</table>
	
	</td>
	</tr>
	<tr>
	<td>&nbsp;
	</td>
	</tr>
    
      <?php if( $row_log['csa_tb_text'] <> null){ ?>
    <tr valign="baseline" >
      <td  valign="top"><span class="input_task_title  margin-y" style="margin-top:0px;"><?php echo $multilingual_global_log; ?></span> </td>
    </tr>
	  <tr valign="baseline" >
	  <td  valign="top" >
	  <?php echo $row_log['csa_tb_text']; ?>      </td>
    </tr>
    <tr valign="baseline" >
      <td  >&nbsp;</td>
    </tr>
    <tr valign="baseline" >
      <td  >&nbsp;</td>
    </tr>
      <?php } ?>
	  
	  
    
	 <!--remark start -->
  <tr valign="baseline">
      <td  >
	  <div>
	  <a name="comment"></a>
	  </div>	 </td>
    </tr>
  <?php if($totalRows_Recordset_comment > 0){ ?>
	<tr valign="baseline">
      <td><span class="input_task_title  margin-y" style="margin-top:0px;"><?php echo $multilingual_default_comment; ?></span></td>
    </tr>
      <tr>
          <td>
              <table  class="table table-striped table-hover glink" width="98%" >
		<?php do { ?>
		<tr valign="baseline">
      <td>
	  <div class="float_left">
	  <b>
	  <a href="user_view.php?recordID=<?php echo $row_Recordset_comment['tk_comm_user']; ?>"  target="_blank"><?php echo $row_Recordset_comment['tk_display_name']; ?></a> 
	  <?php echo $multilingual_default_by; ?>
	  <?php echo $row_Recordset_comment['tk_comm_lastupdate']; ?> 
	  <?php echo $multilingual_default_at; ?>	  </b>	  </div>
	  <div class="float_right">
	  <?php if($_SESSION['MM_rank'] > "1") { ?>
	  <?php if ($_SESSION['MM_rank'] > "4" || $row_Recordset_comment['tk_comm_user'] == $_SESSION['MM_uid']) {  ?>
	  
	  <a href="comment_edit.php?editcoID=<?php 
	  $coid =$row_Recordset_comment['coid'];
	  echo $coid; ?>&date=<?php echo $logdate; ?>&tid=<?php echo $taskid; ?>" class="mouse_hover">
	  <?php echo $multilingual_global_action_edit; ?></a>
	  
	  <?php if ($_SESSION['MM_Username'] <> $multilingual_dd_user_readonly) {  ?>
	   <a  class="mouse_hover" 
	  onclick="javascript:if(confirm( '<?php 
	  echo $multilingual_global_action_delconfirm; ?>'))self.location='comment_del.php?delID=<?php echo $row_Recordset_comment['coid']; ?>&projectID=<?php echo $logid; ?>&date=<?php echo $logdate; ?>&tid=<?php echo $taskid; ?>';"
	  ><?php echo $multilingual_global_action_del; ?></a>
	  <?php } else {  
	   echo $multilingual_global_action_del; 
	    }  ?>
	  <?php } ?><?php } ?>
	  </div>
            <?php 
	echo "<br>".$row_Recordset_comment['tk_comm_title']; 
	?>
            </td>
    </tr>

	<?php
} while ($row_Recordset_comment = mysql_fetch_assoc($Recordset_comment));
  $rows = mysql_num_rows($Recordset_comment);
  if($rows > 0) {
      mysql_data_seek($Recordset_comment, 0);
	  $row_Recordset_comment = mysql_fetch_assoc($Recordset_comment);
  }
?>
              </table>
          </td>
      </tr>
	<tr valign="baseline">
      <td   >
<table class="rowcon" border="0" align="center">
<tr>
<td>   <table border="0">
        <tr>
          <td><?php if ($pageNum_Recordset_comment > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_comment=%d%s", $currentPage, 0, $queryString_Recordset_comment); ?>#comment"><?php echo $multilingual_global_first; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_comment > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_comment=%d%s", $currentPage, max(0, $pageNum_Recordset_comment - 1), $queryString_Recordset_comment); ?>#comment"><?php echo $multilingual_global_previous; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_comment < $totalPages_Recordset_comment) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_comment=%d%s", $currentPage, min($totalPages_Recordset_comment, $pageNum_Recordset_comment + 1), $queryString_Recordset_comment); ?>#comment"><?php echo $multilingual_global_next; ?></a>
              <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_Recordset_comment < $totalPages_Recordset_comment) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_comment=%d%s", $currentPage, $totalPages_Recordset_comment, $queryString_Recordset_comment); ?>#comment"><?php echo $multilingual_global_last; ?></a>
              <?php } // Show if not last page ?></td>
        </tr>
      </table></td>
<td align="right">   <?php echo ($startRow_Recordset_comment + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_comment + $maxRows_Recordset_comment, $totalRows_Recordset_comment) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_comment ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
</table>	</td>
    </tr>
	<?php } ?>
  <tr>
    <td  >&nbsp;</td>
  </tr> 
  
  <!--remark end -->
  </table>
</div>

</form>

</body>
</html>