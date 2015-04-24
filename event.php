<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<div class="fancy">
    <h3>这是您的任务日程</h3>
   
</div>
<script type="text/javascript" src="js/jquery.form.min.js"></script>
<script type="text/javascript">
</script>
<?php
require_once('config/tank_config.php'); 

$id = (int)$_GET['id'];

$ViewCalendarTaskSQL1="select * from tk_task,tk_project,tk_stage where tk_stage.stageid=tk_task.csa_project_stage
and tk_project.id=tk_task.csa_project and tid='$id' ";

mysql_select_db($database_tankdb, $tankdb);
echo "<br>";
$ResultTaskCalendar = mysql_query($ViewCalendarTaskSQL1, $tankdb) or die(mysql_error());

while($row = mysql_fetch_array($ResultTaskCalendar))
  {
  echo "     ";
  echo "您今天（截止日期）的要完成的任务是： ";
  echo $row['csa_text'] ;
  echo "<br />";
  echo "<br />";
  echo "所属项目：";
   echo $row['project_name'] ;
  echo "<br />";
  echo "<br />";
  echo "所属阶段：";
   echo $row['tk_stage_title'] ;
  }
   
?>

