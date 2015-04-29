<link rel="stylesheet" type="text/css" href="calendar/css/jquery-ui.css">
<script type="text/javascript" src="calendar/js/jquery.form.min.js"></script>

<!-- 显示详细任务日程 -->
<div class="fancy">
    <h3>任务日程</h3>

<?php
require_once('config/tank_config.php'); 

$id = (int)$_GET['id'];

$ViewCalendarTaskSQL1="select * from tk_task,tk_project,tk_stage where tk_stage.stageid=tk_task.csa_project_stage
and tk_project.id=tk_task.csa_project and tid='$id' ";

mysql_select_db($database_tankdb, $tankdb);
$ResultTaskCalendar = mysql_query($ViewCalendarTaskSQL1, $tankdb) or die(mysql_error());

while($row = mysql_fetch_array($ResultTaskCalendar))
  {
  echo "您今天（截止日期）要完成的任务是： ";
  //echo $row['csa_text'] ;
  $task=$row['csa_text'];

  $pagename='default_task_edit.php';
  $tid=$id;
  $url=$pagename.'?pagetab=alltask&editID='.(int)$_GET['id'];
  //$current_url=default_task_edit.php?pagetab=alltask&editID=28'>;
  //echo "<div class='b'><a href="<?php echo $pagename; ?
  echo "<div class='b'><a href='$url'>$task</a></div>";


  echo "<br />";
  echo "<br />";
  echo "所属项目：";
  echo $row['project_name'] ;
  echo "<br />";
  echo "所属阶段：";
  echo $row['tk_stage_title'] ;
  }
?>
    
</div>
