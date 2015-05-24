<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_DetailRS1 = 25;
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
$query_DetailRS1 = sprintf("SELECT * FROM tk_user WHERE uid = %s", GetSQLValueString($colname_DetailRS1, "text"));
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

$maxRows_Recordset_prj = 15;
$pageNum_Recordset_prj = 0;
if (isset($_GET['pageNum_Recordset_prj'])) {
  $pageNum_Recordset_prj = $_GET['pageNum_Recordset_prj'];
}
$startRow_Recordset_prj = $pageNum_Recordset_prj * $maxRows_Recordset_prj;

$colname_Recordset_prj = $row_DetailRS1['uid'];

/*mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_prj = sprintf("SELECT * FROM tk_project inner join tk_status_project on tk_project.project_status=tk_status_project.psid WHERE project_to_user = %s ORDER BY project_lastupdate DESC", GetSQLValueString($colname_Recordset_prj, "text"));
$query_limit_Recordset_prj =  sprintf("%s LIMIT %d, %d", $query_Recordset_prj, $startRow_Recordset_prj, $maxRows_Recordset_prj);
$Recordset_prj = mysql_query($query_limit_Recordset_prj, $tankdb) or die(mysql_error());
$row_Recordset_prj = mysql_fetch_assoc($Recordset_prj);*/

if (isset($_GET['totalRows_Recordset_prj'])) {
  $totalRows_Recordset_prj = $_GET['totalRows_Recordset_prj'];
} else {
  $all_Recordset_prj = mysql_query($query_Recordset_prj);
  $totalRows_Recordset_prj = mysql_num_rows($all_Recordset_prj);
}
$totalPages_Recordset_prj = ceil($totalRows_Recordset_prj/$maxRows_Recordset_prj)-1;
$queryString_Recordset_prj = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_prj") == false && 
        stristr($param, "totalRows_Recordset_prj") == false && 
        stristr($param, "tab") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_prj = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_prj = sprintf("&totalRows_Recordset_prj=%d%s", $totalRows_Recordset_prj, $queryString_Recordset_prj);

$maxRows_Recordset_log = 15;
$pageNum_Recordset_log = 0;
if (isset($_GET['pageNum_Recordset_log'])) {
  $pageNum_Recordset_log = $_GET['pageNum_Recordset_log'];
}
$startRow_Recordset_log = $pageNum_Recordset_log * $maxRows_Recordset_log;

$colname_Recordset_log = $row_DetailRS1['uid'];

$colmonth_log = date("m");
$_SESSION['ser_logmonth'] = $colmonth_log;
if (isset($_GET['logmonth'])) {
  $colmonth_log = $_GET['logmonth'];
  $_SESSION['ser_logmonth'] = $colmonth_log;
}

$colyear_log = date("Y");
$_SESSION['ser_logyear'] = $colyear_log;
if (isset($_GET['logyear'])) {
  $colyear_log = $_GET['logyear'];
  $_SESSION['ser_logyear'] = $colyear_log;
}

$colday_log = "";
$_SESSION['ser_logday'] = $colday_log;
if (isset($_GET['logday'])) {
  $colday_log = $_GET['logday'];
  $_SESSION['ser_logday'] = $colday_log;
}

$coldate = $colyear_log.$colmonth_log.$colday_log;

?>
<?php require('head.php'); ?>
<script type="text/javascript" src="plug-in/chart/js/swfobject.js"></script> 
<script type="text/javascript"> 
var flashvars = {"data-file":"chart_pie_user.php?recordID=<?php echo $colname_Recordset_task; ?>"};  
var params = {menu: "false",scale: "noScale",wmode:"opaque"};  
swfobject.embedSWF("plug-in/chart/open-flash-chart.swf", "chart", "600px", "230px", 
 "9.0.0","expressInstall.swf", flashvars,params);  
 
 function   searchtask() 
      {document.form1.action= "user_view.php?#task "; 
        document.form1.submit(); 
        return   true; 
      
      } 

function   exportexcel() 
      {document.form1.action= "excel_log.php "; 
        document.form1.submit(); 
        return   false; 
      
      } 
	 
<?php 
$tab = "-1";
if (isset($_GET['tab'])) {
  $tab = $_GET['tab'];
}
if($tab==2){
echo "
<script language='javascript'>
function tabs2()
{
var len = 3;
for (var i = 1; i <= len; i++)
{
document.getElementById('tab_a' + i).style.display = (i == 2) ? 'block' : 'none';
document.getElementById('tab_' + i).className = (i == 2) ? 'onhover' : 'none';
}
}
</script>
";
}
?>
<?php 
$tab = "-1";
if (isset($_GET['tab'])) {
  $tab = $_GET['tab'];
}
if($tab==3){
echo "
<script language='javascript'>
function tabs3()
{
var len = 3;
for (var i = 1; i <= len; i++)
{
document.getElementById('tab_a' + i).style.display = (i == 3) ? 'block' : 'none';
document.getElementById('tab_' + i).className = (i == 3) ? 'onhover' : 'none';
}
}
</script>
";
}
?>

function tabs(n)
{
var len = 3;
for (var i = 1; i <= len; i++)
{
document.getElementById('tab_a' + i).style.display = (i == n) ? 'block' : 'none';
document.getElementById('tab_' + i).className = (i == n) ? 'onhover' : 'none';
}
}
</script>
<body <?php if($tab==2){ echo "onload='tabs2();'";} elseif($tab==3){ echo "onload='tabs3();'";} ?>>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="25%" class="input_task_right_bg" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td valign="top"  class="gray2">
	 <h4 style="margin-top:40px"><strong><?php echo "查看个人信息"; ?></strong></h4>


<?php ?>
          
              </td>
          </tr>
        </table></td>
      <td width="75%" valign="top"><table width="98%" border="0" cellspacing="0" cellpadding="5" align="center">
          <tr>
            <td>
			  
			  

             <table width="98%" border="0" cellspacing="0" cellpadding="5" >
			 <tr>
			 <td>
			 <h3><?php echo $row_DetailRS1['tk_display_name']; ?></h3>
			 </td>
			 
			 </tr>
			 <tr>
			 
			 
			 <td>
			 <table width="100%" border="0" cellspacing="0" cellpadding="5"  class="info_task_bg">

	 <?php if($row_DetailRS1['tk_user_login'] <> null && $row_DetailRS1['tk_user_login'] <> " ") { ?>
  <tr>
  
    <td class="info_task_title"><?php echo $multilingual_user_account; ?></td>
    <td><a href="mailto:<?php echo $row_DetailRS1['tk_user_login']; ?>"><?php echo $row_DetailRS1['tk_user_login']; ?></a></td>
    <td class="info_task_title">&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  
   <?php } ?>

	 <?php if($row_DetailRS1['tk_user_email'] <> null && $row_DetailRS1['tk_user_email'] <> " ") { ?>
  <tr>
  
    <td class="info_task_title"><?php echo $multilingual_user_email; ?></td>
    <td><a href="mailto:<?php echo $row_DetailRS1['tk_user_email']; ?>"><?php echo $row_DetailRS1['tk_user_email']; ?></a></td>
    <td class="info_task_title">&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
	
	 <?php } ?>
	 
	 
	 <?php if($row_DetailRS1['tk_user_contact'] <> null && $row_DetailRS1['tk_user_contact'] <> " ") { ?>
  <tr>
    <td class="info_task_title"><?php echo $multilingual_user_contact; ?></td>
    <td><?php echo $row_DetailRS1['tk_user_contact']; ?></td>
    <td class="info_task_title">&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
	 <?php } ?>
</table>
			 </td>
			 </tr>
			 

		  <tr>
		  <td>
		  <table width="100%" style="line-height:40px;">
		  <tr>
              
		  <?php if ($_SESSION['MM_uid'] == $row_DetailRS1['uid']) { ?>
		  <td width="10%">
		  <a href="default_user_edit.php?UID=<?php echo $row_DetailRS1['uid']; ?>"><span class="glyphicon glyphicon-pencil"></span> <?php echo $multilingual_global_action_edit; ?></a>
		  </td>
		  <?php }  ?> 
              
<!-- 不能删除用户 -->
<!--
		  <?php if ($_SESSION['MM_rank'] > "4" && $row_Recordset_countuser['count_user'] > "1") {  ?>
		  <td width="10%">
		  <a onClick="javascript:if(confirm( '<?php 
	 if($totalRows_Recordset_task == "0" && $totalRows_Recordset_prj == "0"){  
	  echo $multilingual_global_action_delconfirm;
	  } else { echo $multilingual_global_action_delconfirm4;} ?>'))self.location='user_del.php?UID=<?php echo $row_DetailRS1['uid']; ?>';" value="<?php echo $multilingual_global_action_del; ?>" class="mouse_hover"><span class="glyphicon glyphicon-remove"></span> <?php echo $multilingual_global_action_del; ?></a>
		  </td>
		  <?php }  ?> 
-->
		  
		  <td>
		   <a class="mouse_over" onClick="javascript:history.go(-1)">
			 <span class="glyphicon glyphicon-arrow-left"></span> <?php echo $multilingual_global_action_back; ?>			 </a>
		  </td>
		  
		  <td>&nbsp;
		  
		  </td>
		  </tr>
		  </table>

		  </td>
		  </tr>
          
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table>

				</td>
          </tr>
        </table></td>
    </tr>
   
  </table>

<?php require('foot.php'); ?>
</body>
</html><?php
mysql_free_result($DetailRS1);
mysql_free_result($Recordset_task);
?>