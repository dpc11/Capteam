<?php
$maxRows_Recordset_log = 15;
$pageNum_Recordset_log = 0;
if (isset($_GET['pageNum_Recordset_log'])) {
  $pageNum_Recordset_log = $_GET['pageNum_Recordset_log'];
}
$startRow_Recordset_log = $pageNum_Recordset_log * $maxRows_Recordset_log;

$logtouser =   "0";
if (isset($_GET['logtouser'])) {
  $logtouser = $_GET['logtouser'];
}

$logproject = "0";
if (isset($_GET['logproject'])) {
  $logproject = $_GET['logproject'];
}


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

$coltouser = GetSQLValueString($logtouser, "int");
$colproject = GetSQLValueString($logproject, "int");
$coldate = GetSQLValueString($coldate . "%", "text");

$where = "";
			$where=' WHERE';

			if($logtouser <> '0')
			{
				$where.= " csa_tb_backup2 = $coltouser";
			}
			
			if($logproject <> '0')
			{
				$where.= " AND csa_tb_backup3 = $colproject";
			}
			
			//if($coldate <> '0')
			//{
			//	$where.= " csa_tb_year LIKE $coldate";
			//}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_log = "SELECT * FROM tk_task_byday 
								inner join tk_project on tk_task_byday.csa_tb_backup3=tk_project.id 
								inner join tk_task_tpye on tk_task_byday.csa_tb_backup4=tk_task_tpye.id 
								inner join tk_status on tk_task_byday.csa_tb_status=tk_status.id 
								inner join tk_task on tk_task_byday.csa_tb_backup1=tk_task.TID 
								inner join tk_user on tk_task_byday.csa_tb_backup2=tk_user.uid 

ORDER BY csa_tb_lastupdate DESC" ;
$query_limit_Recordset_log = sprintf("%s LIMIT %d, %d", $query_Recordset_log, $startRow_Recordset_log, $maxRows_Recordset_log);
$Recordset_log = mysql_query($query_limit_Recordset_log, $tankdb) or die(mysql_error());
$row_Recordset_log = mysql_fetch_assoc($Recordset_log);

if (isset($_GET['totalRows_Recordset_log'])) {
  $totalRows_Recordset_log = $_GET['totalRows_Recordset_log'];
} else {
  $all_Recordset_log = mysql_query($query_Recordset_log);
  $totalRows_Recordset_log = mysql_num_rows($all_Recordset_log);
}
$totalPages_Recordset_log = ceil($totalRows_Recordset_log/$maxRows_Recordset_log)-1;
$queryString_Recordset_log = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_log") == false && 
        stristr($param, "totalRows_Recordset_log") == false && 
        stristr($param, "tab") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_log = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_log = sprintf("&totalRows_Recordset_log=%d%s", $totalRows_Recordset_log, $queryString_Recordset_log);
?>


<script type="text/JavaScript">
function GP_popupConfirmMsg(msg) { //v1.0
  document.MM_returnValue = confirm(msg);
}

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}

function   searchtask() 
      {document.form1.action= "<?php echo $pagename; ?>"; 
        document.form1.submit(); 
        return   true; 
      
  } 

function   exportexcel() 
      {document.form1.action= "excel_log.php "; 
        document.form1.submit(); 
        return   false; 
      
      } 
</script>


<table width="100%" cellpadding="5">
  <tr style="display:none;">
  <td><a name="task"></a>
  <div class="search_div">
<form id="form1" name="form1" method="get" class="saerch_form">
  
  <input name="recordID" id="recordID" value="<?php echo $logtouser; ?>" style="display:none" />
  <input name="logtype" id="logtype" value="1" style="display:none" />
	<select name="logyear" id="logyear">

        <option value="2009" <?php 
		if (isset($_SESSION['ser_logyear'])) {	
		if (!(strcmp(2009, "{$_SESSION['ser_logyear']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(2009, date("Y")))) {echo "selected=\"selected\"";} ?>>2009</option>
        <option value="2010" <?php if (isset($_SESSION['ser_logyear'])) {	
		if (!(strcmp(2010, "{$_SESSION['ser_logyear']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(2010, date("Y")))) {echo "selected=\"selected\"";} ?>>2010</option>
        <option value="2011" <?php if (isset($_SESSION['ser_logyear'])) {	
		if (!(strcmp(2011, "{$_SESSION['ser_logyear']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(2011, date("Y")))) {echo "selected=\"selected\"";} ?>>2011</option>
        <option value="2012" <?php if (isset($_SESSION['ser_logyear'])) {	
		if (!(strcmp(2012, "{$_SESSION['ser_logyear']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(2012, date("Y")))) {echo "selected=\"selected\"";} ?>>2012</option>
        <option value="2013" <?php if (isset($_SESSION['ser_logyear'])) {	
		if (!(strcmp(2013, "{$_SESSION['ser_logyear']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(2013, date("Y")))) {echo "selected=\"selected\"";} ?>>2013</option>
        <option value="2014" <?php if (isset($_SESSION['ser_logyear'])) {	
		if (!(strcmp(2014, "{$_SESSION['ser_logyear']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(2014, date("Y")))) {echo "selected=\"selected\"";} ?>>2014</option>
        <option value="2015" <?php if (isset($_SESSION['ser_logyear'])) {	
		if (!(strcmp(2015, "{$_SESSION['ser_logyear']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(2015, date("Y")))) {echo "selected=\"selected\"";} ?>>2015</option>
        <option value="2016" <?php if (isset($_SESSION['ser_logyear'])) {	
		if (!(strcmp(2016, "{$_SESSION['ser_logyear']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(2016, date("Y")))) {echo "selected=\"selected\"";} ?>>2016</option>
        <option value="2017" <?php if (isset($_SESSION['ser_logyear'])) {	
		if (!(strcmp(2017, "{$_SESSION['ser_logyear']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(2017, date("Y")))) {echo "selected=\"selected\"";} ?>>2017</option>
        <option value="2018" <?php if (isset($_SESSION['ser_logyear'])) {	
		if (!(strcmp(2018, "{$_SESSION['ser_logyear']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(2018, date("Y")))) {echo "selected=\"selected\"";} ?>>2018</option>
        <option value="2019" <?php if (isset($_SESSION['ser_logyear'])) {	
		if (!(strcmp(2019, "{$_SESSION['ser_logyear']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(2019, date("Y")))) {echo "selected=\"selected\"";} ?>>2019</option>
        <option value="2020" <?php if (isset($_SESSION['ser_logyear'])) {	
		if (!(strcmp(2020, "{$_SESSION['ser_logyear']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(2020, date("Y")))) {echo "selected=\"selected\"";} ?>>2020</option>
      </select> / 
	  
	  
	  <select  name="logmonth" id="logmonth">
      <option value=""><?php echo $multilingual_taskf_month; ?></option>
      <option value="01" <?php 
	  if (isset($_SESSION['ser_logmonth'])) {	
		if (!(strcmp("01", "{$_SESSION['ser_logmonth']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(1, date("n")))) {echo "selected=\"selected\"";} ?>>01</option>
      <option value="02" <?php if (isset($_SESSION['ser_logmonth'])) {	
		if (!(strcmp("02", "{$_SESSION['ser_logmonth']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(2, date("n")))) {echo "selected=\"selected\"";} ?>>02</option>
      <option value="03" <?php if (isset($_SESSION['ser_logmonth'])) {	
		if (!(strcmp("03", "{$_SESSION['ser_logmonth']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(3, date("n")))) {echo "selected=\"selected\"";} ?>>03</option>
      <option value="04" <?php if (isset($_SESSION['ser_logmonth'])) {	
		if (!(strcmp("04", "{$_SESSION['ser_logmonth']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(4, date("n")))) {echo "selected=\"selected\"";} ?>>04</option>
      <option value="05" <?php if (isset($_SESSION['ser_logmonth'])) {	
		if (!(strcmp("05", "{$_SESSION['ser_logmonth']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(5, date("n")))) {echo "selected=\"selected\"";} ?>>05</option>
      <option value="06" <?php if (isset($_SESSION['ser_logmonth'])) {	
		if (!(strcmp("06", "{$_SESSION['ser_logmonth']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(6, date("n")))) {echo "selected=\"selected\"";} ?>>06</option>
      <option value="07" <?php if (isset($_SESSION['ser_logmonth'])) {	
		if (!(strcmp("07", "{$_SESSION['ser_logmonth']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(7, date("n")))) {echo "selected=\"selected\"";} ?>>07</option>
      <option value="08" <?php if (isset($_SESSION['ser_logmonth'])) {	
		if (!(strcmp("08", "{$_SESSION['ser_logmonth']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(8, date("n")))) {echo "selected=\"selected\"";} ?>>08</option>
      <option value="09" <?php if (isset($_SESSION['ser_logmonth'])) {	
		if (!(strcmp("09", "{$_SESSION['ser_logmonth']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(9, date("n")))) {echo "selected=\"selected\"";} ?>>09</option>
      <option value="10" <?php if (isset($_SESSION['ser_logmonth'])) {	
		if (!(strcmp("10", "{$_SESSION['ser_logmonth']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(10, date("n")))) {echo "selected=\"selected\"";} ?>>10</option>
      <option value="11" <?php if (isset($_SESSION['ser_logmonth'])) {	
		if (!(strcmp("11", "{$_SESSION['ser_logmonth']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(11, date("n")))) {echo "selected=\"selected\"";} ?>>11</option>
      <option value="12" <?php if (isset($_SESSION['ser_logmonth'])) {	
		if (!(strcmp("12", "{$_SESSION['ser_logmonth']}"))) {
			echo "selected=\"selected\"";
			}
		}
else if (!(strcmp(12, date("n")))) {echo "selected=\"selected\"";} ?>>12</option>
    </select>
	
 / <select name="logday" id="logday">
      <option value="" selected="selected"><?php echo $multilingual_taskf_day; ?></option>
      <option value="01" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("01", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?> >01</option>
      <option value="02" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("02", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>02</option>
      <option value="03" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("03", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>03</option>
      <option value="04" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("04", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>04</option>
      <option value="05" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("05", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>05</option>
      <option value="06" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("06", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>06</option>
      <option value="07" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("07", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>07</option>
      <option value="08" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("08", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>08</option>
      <option value="09" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("09", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>09</option>
      <option value="10" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("10", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>10</option>
      <option value="11" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("11", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>11</option>
      <option value="12" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("12", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>12</option>
      <option value="13" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("13", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>13</option>
      <option value="14" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("14", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>14</option>
      <option value="15" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("15", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>15</option>
      <option value="16" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("16", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>16</option>
      <option value="17" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("17", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>17</option>
      <option value="18" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("18", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>18</option>
      <option value="19" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("19", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>19</option>
      <option value="20" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("20", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>20</option>
      <option value="21" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("21", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>21</option>
      <option value="22" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("22", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>22</option>
      <option value="23" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("23", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>23</option>
      <option value="24" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("24", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>24</option>
      <option value="25" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("25", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>25</option>
      <option value="26" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("26", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>26</option>
      <option value="27" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("27", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>27</option>
      <option value="28" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("28", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>28</option>
      <option value="29" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("29", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>29</option>
      <option value="30" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("30", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>30</option>
      <option value="31" <?php if (isset($_SESSION['ser_logday'])) {	
		if (!(strcmp("31", "{$_SESSION['ser_logday']}"))) {
			echo "selected=\"selected\"";
			}
		}?>>31</option>
    </select>	
	
	<?php if($pagetabs == "alllog"){ ?>
	<input name="logtouser" id="logtouser" value="0" style="display:none" />
	<?php } ?>
	<input name="pagetab" id="pagetab" value="<?php echo $pagetabs;?>" style="display:none" />
	<input type="button" value="<?php echo $multilingual_global_action_ok; ?>" class="button" onclick= "return   searchtask(); " />	
	<input type="button" name="export" id="export" value="<?php echo $multilingual_global_excel; ?>"  class="button" onclick= "return   exportexcel(); " />
 </form> </td>
  </tr>
  <tr>
    <td>
	<?php if ($totalRows_Recordset_log > 0) { ?>
    <div >


    <table class="table table-striped table-hover" width="98%" >
<thead>
<tr>
<th>
<br />
<span class="font_big18 fontbold breakwordsfloat_left">
<?php echo $multilingual_head_feed; ?></span></th>
<th>
<?php echo $multilingual_user_view_cost; ?></th>
<th>
<?php echo $multilingual_user_view_status; ?></th>
<th>
<?php echo $multilingual_user_view_project2; ?></th>
<th width="180px">
<?php echo $multilingual_project_file_update; ?></th>
<th></th>
</tr>
</thead>
<tbody>
  <?php do { ?>
<tr>
      <td class="glink">
<span class="glyphicon glyphicon-user"></span> <?php echo $row_Recordset_log['tk_display_name']; ?> <?php echo $multilingual_user_view_by; ?> 
	   
<?php 
$logdate = $row_Recordset_log['csa_tb_year'];
$logyear = str_split($logdate,4);
$logmonth = str_split($logyear[1],2);
echo $logyear[0]; ?>-<?php echo $logmonth[0]; ?>-<?php echo $logmonth[1]; ?>	



	  <?php echo $multilingual_user_view_do; ?>  
	  <?php echo $row_Recordset_log['task_tpye']; ?> - 
	  <a href="default_task_edit.php?editID=<?php echo $row_Recordset_log['TID']; ?>" >
	  <?php echo $row_Recordset_log['csa_text']; ?></a>

	  <?php if($row_Recordset_log['csa_tb_text']<>null){ echo "<br/><span class='gray'>".$row_Recordset_log['csa_tb_text']."</span>"; }?>  </td>

<td class="glink" width="80px">
 <?php echo $row_Recordset_log['csa_tb_manhour']; ?> <?php echo $multilingual_user_view_hour; ?></td>

<td class="glink" width="120px">
 <?php echo $row_Recordset_log['task_status_display']; ?></td>

<td class="glink" width="160px" >
 <a href="project_view.php?recordID=<?php echo $row_Recordset_log['csa_project']; ?>">
  <?php echo $row_Recordset_log['project_name']; ?></a></td>


  <td class="glink" width="120px" >
<?php echo $row_Recordset_log['csa_tb_lastupdate']; ?>  </td>
  <td class="glink" width="60px" >
<script>	  
function addcomment<?php echo $row_Recordset_log['tbid']; ?>()
{
    J.dialog.get({ id: 'test', title: '<?php echo $multilingual_default_task_section5; ?>', page: 'log_view.php?date=<?php echo $row_Recordset_log['csa_tb_year']; ?>&taskid=<?php echo $row_Recordset_log['csa_tb_backup1']; ?>' });
}
</script>
  <a class="mouse_hover" onclick="addcomment<?php echo $row_Recordset_log['tbid']; ?>()"><?php echo $multilingual_log_comment; ?><?php 
  if ($row_Recordset_log['csa_tb_comment'] > 0) {
  echo "(".$row_Recordset_log['csa_tb_comment'].")"; 
  }?></a>  </td>
</tr>
     <?php
} while ($row_Recordset_log = mysql_fetch_assoc($Recordset_log));
  $rows = mysql_num_rows($Recordset_log);
  if($rows > 0) {
      mysql_data_seek($Recordset_log, 0);
	  $row_Recordset_log = mysql_fetch_assoc($Recordset_log);
  }
?>
</tbody>
</table>
</div>
<table class="rowcon" border="0" align="center">
<tr>
<td>   <table border="0">
        <tr>
          <td><?php if ($pageNum_Recordset_log > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_log=%d%s", $currentPage, 0, $queryString_Recordset_log); ?>&pagetab=<?php echo $pagetabs;?>#task"><?php echo $multilingual_global_first; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_log > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_log=%d%s", $currentPage, max(0, $pageNum_Recordset_log - 1), $queryString_Recordset_log); ?>&pagetab=<?php echo $pagetabs;?>#task"><?php echo $multilingual_global_previous; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_log < $totalPages_Recordset_log) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_log=%d%s", $currentPage, min($totalPages_Recordset_log, $pageNum_Recordset_log + 1), $queryString_Recordset_log); ?>&pagetab=<?php echo $pagetabs;?>#task"><?php echo $multilingual_global_next; ?></a>
              <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_Recordset_log < $totalPages_Recordset_log) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_log=%d%s", $currentPage, $totalPages_Recordset_log, $queryString_Recordset_log); ?>&pagetab=<?php echo $pagetabs;?>#task"><?php echo $multilingual_global_last; ?></a>
              <?php } // Show if not last page ?></td>
        </tr>
      </table></td>
<td align="right">   <?php echo ($startRow_Recordset_log + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_log + $maxRows_Recordset_log, $totalRows_Recordset_log) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_log ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
</table> 

<?php } else { ?>
<div class="alert alert-warning" style="margin:6px;">
  <?php echo $multilingual_user_view_nolog; ?></div>
<?php }  ?> </td>
</tr>
</table>  
