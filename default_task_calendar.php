<?php require_once('config/tank_config.php'); ?>
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

$taskid = $_GET['taskid'];
$userid = $_GET['userid'];
$projectid = $_GET['projectid'];
$tasktype = $_GET['tasktype'];
$nowuser = $_SESSION['MM_uid'];

mysql_select_db($database_tankdb, $tankdb);
$sql=sprintf("SELECT * FROM tk_task_byday 
inner join tk_status on tk_task_byday.csa_tb_status=tk_status.id 
WHERE csa_tb_backup1= %s", GetSQLValueString($taskid, "int"));
$rec=mysql_query($sql);
$strs=null;
while($row=mysql_fetch_array($rec)){
$rowstatus = str_replace("'",   "\'",   $row['task_status_display']);

$strtext =   $row['csa_tb_text'];
$strtext =  stripslashes($strtext);
$strtext = str_replace("\n",   "",   $strtext);  
$strtext = str_replace("\r",   "",   $strtext);  
$strtext = str_replace("  ",   "&nbsp;",   $strtext); 
$strtext = str_replace("'",   " ",   $strtext); 

$strtexttip =   htmlspecialchars($row['csa_tb_text']);
$strtexttip =  stripslashes($strtexttip);
$strtexttip = str_replace("\n",   " ",   $strtexttip);  
$strtexttip = str_replace("\r",   " ",   $strtexttip);  
$strtexttip = str_replace("'",   " ",   $strtexttip); 

$strs.="var "."d".$row[1]."="."'"."$rowstatus"."<b>$multilingual_calendar_cost: ".$row['csa_tb_manhour']."$multilingual_global_hour</b><br/>"."<div  class=\'log_text \'>$strtext</div>"."'; ";
}

$LANG_WEEK = array (
	'Mon' => $multilingual_global_mon,
	'Tue' => $multilingual_global_tues,
	'Wed' => $multilingual_global_wed,
	'Thu' => $multilingual_global_thur,
	'Fri' => $multilingual_global_fir,
	'Sat' => $multilingual_global_sat,
	'Sun' => $multilingual_global_sun );


if(empty($_GET['date'])){
	$today = time();
	$nowdate = date("Ymd", $today);
}else{
	$nowdate = $_GET['date'];
	$today = mktime(0,0,0,
		substr($nowdate,4,2), substr($nowdate,6,2), substr($nowdate,0,4));
}

$pre_day = date("Ymd", $today - 24*60*60);
$next_day = date("Ymd", $today + 24*60*60);

$pre_month = date("Ymd",mktime(0,0,0,
		substr($nowdate,4,2)-1, substr($nowdate,6,2), substr($nowdate,0,4)));

$next_month = date("Ymd",mktime(0,0,0,
		substr($nowdate,4,2)+1, substr($nowdate,6,2), substr($nowdate,0,4)));
		
$OUT['today'] = date("Y-m-d", $today);
$OUT['weekday'] = $LANG_WEEK[date("D", $today)];

$todaybegin = mktime(0, 0, 0, 
		substr($nowdate,4,2), substr($nowdate,6,2), substr($nowdate,0,4));
$todayend = mktime(23, 59, 59, 
		substr($nowdate,4,2), substr($nowdate,6,2), substr($nowdate,0,4));

$weekday_index = array (
	'Sun' => 0,
	'Mon' => 1,
	'Tue' => 2,
	'Wed' => 3,
	'Thu' => 4,
	'Fri' => 5,
	'Sat' => 6);


$weekday = $weekday_index[date("D", $today)];	
$day = date("j", $today);

$fstDay = $weekday_index[date("D",$today- ($day-1)*24*60*60)];

$monthTotalDay = date("t", $today);
$out_list = "<TR>\n";
for($i=0; $i<$fstDay; $i++)
{
	$out_list .= "\t<TD width=20>&nbsp;</TD>\n";
}

for($i=1; $i<=$monthTotalDay; $i++)
{
	$fstDay++;
	$outstylestr = "onmouseover=\"javascript:this.className=\'day_hover_style\';\" onmouseout=\"javascript:this.className=\'onday_style\';\"";
	$outstyle = str_replace('\"',   '"',   $outstylestr); 
	$outstyle = str_replace("\'",   "'",   $outstylestr); 
	$outstylestra = "onmouseover=\"javascript:this.className=\'day_hover_style\';\" onmouseout=\"javascript:this.className=\'day_style\';\"";
	$outstylea = str_replace('\"',   '"',   $outstylestra); 
	$outstylea = str_replace("\'",   "'",   $outstylestra); 
	$i_day = date("Ymd", mktime(0,0,0,
		substr($nowdate,4,2), $i, substr($nowdate,0,4)));
	if($fstDay % 7==1) $out_list .= "<TR>\n";
	if($day==$i) $out_list .= "\t<TD class='onday_style' $outstyle valign='top'>
<script type='text/javascript'>
function op$i_day()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_add.php?date=$i_day&taskid=$taskid&userid=$userid&projectid=$projectid&tasktype=$tasktype' });
}



function vi$i_day()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_view.php?date=$i_day&taskid=$taskid' });
}
</script>
<script type='text/javascript'>
if (typeof(d$i_day)=='undefined' && '$nowuser' == '$userid' )
{
document.write('<div onclick=\'op$i_day();\' title=\'$multilingual_calendar_addlog\' class=\'day_mouse\'><div class=\'day_no\'>$i</div><div  class=\'day_main\'></div></div>')
}

else if (typeof(d$i_day)=='undefined')
{
document.write('<div><div class=\'day_no\'>$i</div><div class=\'day_main\'></div></div>')
}
else
{
document.write('<div onclick=\'vi$i_day();\' title=\'$multilingual_calendar_view\' class=\'day_mouse\'><div class=\'day_no\'>$i</div><div class=\'day_main\'>')
document.write(d$i_day)
document.write('</div></div>')
}
</script>
	</TD>\n";
	else $out_list .= "\t<TD class='day_style' $outstylea  valign='top'>
<script type='text/javascript'>
function op$i_day()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_add.php?date=$i_day&taskid=$taskid&userid=$userid&projectid=$projectid&tasktype=$tasktype' });
}



function vi$i_day()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_view.php?date=$i_day&taskid=$taskid' });
}
</script>
<script type='text/javascript'>
if (typeof(d$i_day)=='undefined' && '$nowuser' == '$userid' )
{
document.write('<div onclick=\'op$i_day();\' title=\'$multilingual_calendar_addlog\' class=\'day_mouse\'><div class=\'day_no\'>$i</div><div  class=\'day_main\'></div></div>')
}
else if (typeof(d$i_day)=='undefined')
{
document.write('<div><div class=\'day_no\'>$i</div><div class=\'day_main\'></div></div>')
}
else
{
document.write('<div onclick=\'vi$i_day();\' title=\'$multilingual_calendar_view\' class=\'day_mouse\'><div class=\'day_no\'>$i</div><div class=\'day_main\'>')
document.write(d$i_day)
document.write('</div></div>')
}
</script>
	</TD>\n";
	if($fstDay % 7==0) $out_list .= "</TR>\n";
}

for($i=0; $i<7-$fstDay % 7; $i++)
{
	$out_list .= "<TD width=20>&nbsp;</TD>\n";
}
$out_list .= "</TR>\n";

$OUT['YEAR_MONTH'] = date("Y-m", $today);

$OUT['PRE_DAY']    = $pre_day;
$OUT['NEXT_DAY']   = $next_day;
$OUT['PRE_MONTH']  = $pre_month;
$OUT['NEXT_MONTH'] = $next_month;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS</title>
<link href="skin/themes/base/lhgdialog.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgdialog.js"></script>
<script type="text/javascript">
<?php echo $strs;?> 
</script>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr height="45px">
                <td class="glink">
				<span class="ui-icon month_pre fleft"></span><span class="fleft"><a href="default_task_calendar.php?date=<?php echo $OUT['PRE_MONTH']?>&taskid=<?php echo $taskid; ?>&userid=<?php echo $userid; ?>&projectid=<?php echo $projectid; ?>&tasktype=<?php echo $tasktype; ?>"><?php echo $multilingual_calendar_premonth ?></a></span>
				</td>
                <td align="center" class="month_title"><?php echo $OUT['YEAR_MONTH']?></td>
                <td align="right" class="glink">
				<span class="ui-icon month_next fright"></span><span class="fright"><a href="default_task_calendar.php?date=<?php echo $OUT['NEXT_MONTH']?>&taskid=<?php echo $taskid; ?>&userid=<?php echo $userid; ?>&projectid=<?php echo $projectid; ?>&tasktype=<?php echo $tasktype; ?>"><?php echo $multilingual_calendar_nextmonth ?></a></span>
				</td>
              </tr>
          </table><br />
          <table border="0" cellspacing="0" cellpadding="0"  class="week_title">
          <tr>
          <td><?php echo $LANG_WEEK['Sun']?></td>
          <td><?php echo $LANG_WEEK['Mon']?></td>
          <td><?php echo $LANG_WEEK['Tue']?></td>
          <td><?php echo $LANG_WEEK['Wed']?></td>
          <td><?php echo $LANG_WEEK['Thu']?></td>
          <td><?php echo $LANG_WEEK['Fri']?></td>
          <td><?php echo $LANG_WEEK['Sat']?></td>
		  </td>
		  </tr>
		  </table> 
      <table cellpadding="0" cellspacing="1" class="calendar_main" >
        <?php echo $out_list;?> 
	</table>
<br />
</body>
</html>