<?php require_once( 'config/tank_config.php'); ?>
<?php require_once( 'session_unset.php'); ?>
<?php require_once( 'session.php'); ?>
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


<?php require( 'head.php'); ?>
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgdialog.js"></script>
<div class="subnav">
    <div class="float_left" style="width:85%">

        <!-- 切换按钮 -->
        <div class="btn-group">
            <a type="button" class="btn btn-default btn-sm active" href="schedule_view.php">
                <?php echo $multilingual_schedule_view;?>
            </a>    
            <a type="button" class="btn btn-default btn-sm" href="schedule_task.php">
                <?php echo $multilingual_schedule_task;?>
            </a>
            <a type="button" class="btn btn-default btn-sm" href="schedule_person.php">
                <?php echo $multilingual_schedule_person;?>
            </a>
            <a type="button" class="btn btn-default btn-sm" href="schedule_course.php">
                <?php echo $multilingual_schedule_course;?>
            </a>
        </div>
    </div>

<!-- 新增日程按钮（应该不需要） -->
<!--
<?php if($_SESSION[ 'MM_rank']> "3") { ?>
<div class="float_right">
    <button type="button" class="btn btn-default btn-sm" name="button2" id="button2" onclick="javascript:self.location='project_add.php';">
        <span class="glyphicon glyphicon-plus-sign"></span>
        <?php echo $multilingual_projectlist_new; ?>
    </button>
</div>
<?php } ?>
-->

</div>
<div class="clearboth"></div>
<div class="pagemargin">
    
<div class="calendar-head">
    <!-- 月份切换部分 -->
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr height="45px">
            <td class="glink">
                <span class="ui-icon month_pre fleft"></span><span class="fleft"><a href="default_task_calendar.php?date=<?php echo $OUT['PRE_MONTH']?>&taskid=<?php echo $taskid; ?>&userid=<?php echo $userid; ?>&projectid=<?php echo $projectid; ?>&tasktype=<?php echo $tasktype; ?>"><?php echo $multilingual_calendar_premonth ?></a></span>
            </td>
            <td align="center" class="month_title">
                <?php echo $OUT[ 'YEAR_MONTH']?>
            </td>
            <td align="right" class="glink">
                <span class="ui-icon month_next fright"></span><span class="fright"><a href="default_task_calendar.php?date=<?php echo $OUT['NEXT_MONTH']?>&taskid=<?php echo $taskid; ?>&userid=<?php echo $userid; ?>&projectid=<?php echo $projectid; ?>&tasktype=<?php echo $tasktype; ?>"><?php echo $multilingual_calendar_nextmonth ?></a></span>
            </td>
        </tr>
    </table>
</div>
    <br />
    
    <!-- 日期 -->
    <table border="0" cellspacing="0" cellpadding="0" class="week_title">
        <tr>
            <td>
                <?php echo $LANG_WEEK[ 'Sun']?>
            </td>
            <td>
                <?php echo $LANG_WEEK[ 'Mon']?>
            </td>
            <td>
                <?php echo $LANG_WEEK[ 'Tue']?>
            </td>
            <td>
                <?php echo $LANG_WEEK[ 'Wed']?>
            </td>
            <td>
                <?php echo $LANG_WEEK[ 'Thu']?>
            </td>
            <td>
                <?php echo $LANG_WEEK[ 'Fri']?>
            </td>
            <td>
                <?php echo $LANG_WEEK[ 'Sat']?>
            </td>
            </td>
        </tr>
    </table>

    <!-- 本月日程部分 -->
    <table cellpadding="0" cellspacing="1" class="calendar_main">
        <?php echo $out_list;?>
    </table>
</div>
<?php require( 'foot.php'); ?>

</body>
</html>