<?php
require_once('config/tank_config.php'); 

$id = (int)$_GET['id'];

$ViewCalendarTaskSQL1="select * from tk_task,tk_project,tk_stage where tk_stage.stageid=tk_task.csa_project_stage
and tk_project.id=tk_task.csa_project and tid='$id' ";

mysql_select_db($database_tankdb, $tankdb);
$ResultTaskCalendar = mysql_query($ViewCalendarTaskSQL1, $tankdb) or die(mysql_error());

while($row = mysql_fetch_array($ResultTaskCalendar))
  {
//  echo "您今天（截止日期）要完成的任务是： ";
//  //echo $row['csa_text'] ;
//  $task=$row['csa_text'];
//
//  $pagename='default_task_edit.php';
//  $tid=$id;
//  $url=$pagename.'?pagetab=alltask&editID='.(int)$_GET['id'];
//
//  echo "<div class='b'><a href='$url'>$task</a></div>";
//
//
//  echo "<br />";
//  echo "<br />";
//  echo "所属项目：";
//  echo $row['project_name'] ;
//  echo "<br />";
//  echo "所属阶段：";
//  echo $row['tk_stage_title'] ;
  }
?>
    
<link rel="stylesheet" type="text/css" href="plug-in/calendar/css/jquery-ui.css">
<script type="text/javascript" src="plug-in/calendar/js/jquery.form.min.js"></script>

<!-- 显示详细任务日程 -->
<div class="fancy">
    <h3><?php echo $multilingual_schedule_task; ?></h3>
    <div class="form-group col-xs-12">
        <label style="font-size: 1.3em">
            （任务名称及链接）
        </label>
        <textarea name="event" id="event" class="form-control" rows="4" cols="20" readonly>（任务描述）</textarea>
    </div>
    <div class="form-group col-xs-12">
        <label>
            <?php echo $multilingual_project_end; ?>
        </label>
        <input type="text" name="project_start" id="datepicker" value="<?php echo date('Y-m-d'); ?>" class="form-control" readonly/>
    </div>
    <div class="form-group col-xs-12" style="margin-bottom: 5px;">
        <label>
            <?php echo $multilingual_default_task_project; ?>：&nbsp;
        </label>
        <label style="font-size: 1.2em">
            （项目及链接）
        </label>
    </div>
    <div class="form-group col-xs-12">
        <label>
            <?php echo $multilingual_default_task_stage; ?>：&nbsp;
        </label>
        <label style="font-size: 1.2em">
            （阶段及链接）
        </label>
    </div>
    <div class="col-xs-12">
        <button type="button" class="btn btn-primary btn-sm" value="取消" onClick="$.fancybox.close()" style="width: 70px; float: right;"><?php echo $multilingual_global_action_close; ?></button>
    </div>
    </div>
</div>
