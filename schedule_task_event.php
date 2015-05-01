<?php
require_once('config/tank_config.php'); 

$id = (int)$_GET['id'];


?>
    
<link rel="stylesheet" type="text/css" href="calendar/css/jquery-ui.css">
<script type="text/javascript" src="calendar/js/jquery.form.min.js"></script>

<!-- 显示详细任务日程 -->
<div class="fancy">
    <h3><?php echo $multilingual_schedule_task; ?></h3>
    <div class="form-group col-xs-12">
        <label style="font-size: 1.3em">
            <?php
            $ViewCalendarTaskSQL1="select * from tk_task,tk_project,tk_stage where tk_stage.stageid=tk_task.csa_project_stage
            and tk_project.id=tk_task.csa_project and tid='$id' ";

            mysql_select_db($database_tankdb, $tankdb);
            $ResultTaskCalendar = mysql_query($ViewCalendarTaskSQL1, $tankdb) or die(mysql_error());

            while($row = mysql_fetch_assoc($ResultTaskCalendar))
        {
            $pagename='default_task_edit.php';
            $tid=$id;
            $url=$pagename.'?pagetab=alltask&editID='.(int)$_GET['id'];
           
            $task=$row['csa_text'];
            $taskdes=$row['csa_description'];
            echo "<div class='b'><a href='$url'>$task</a></div>";}
            ?>
        </label>
        <textarea name="event" id="event" class="form-control" rows="4" cols="20" readonly><?php 
           $ViewCalendarTaskSQL2="select * from tk_task,tk_project,tk_stage where tk_stage.stageid=tk_task.csa_project_stage
            and tk_project.id=tk_task.csa_project and tid='$id' ";

            mysql_select_db($database_tankdb, $tankdb);
            $ResultTaskCalendar1 = mysql_query($ViewCalendarTaskSQL2, $tankdb) or die(mysql_error());

            while($row = mysql_fetch_assoc($ResultTaskCalendar1))
        {
            
        echo $row['csa_description'];} ?></textarea>
    </div>
    <div class="form-group col-xs-12">
        <label>
            <?php echo $multilingual_project_end; ?>
        </label>
        <input type="text" name="project_start" id="datepicker" value=<?php 
           $ViewCalendarTaskSQL2="select * from tk_task,tk_project,tk_stage where tk_stage.stageid=tk_task.csa_project_stage
            and tk_project.id=tk_task.csa_project and tid='$id' ";

            mysql_select_db($database_tankdb, $tankdb);
            $ResultTaskCalendar1 = mysql_query($ViewCalendarTaskSQL2, $tankdb) or die(mysql_error());

            while($row = mysql_fetch_assoc($ResultTaskCalendar1))
        {
            
        echo $row['csa_plan_et'];} ?> class="form-control" readonly/>
    </div>
    <div class="form-group col-xs-12" style="margin-bottom: 5px;">
        <label>
            <?php echo $multilingual_default_task_project; ?>：&nbsp;
        </label>
        <label style="font-size: 1.2em">
            <?php 
           $ViewCalendarTaskSQL2="select * from tk_task,tk_project,tk_stage where tk_stage.stageid=tk_task.csa_project_stage
            and tk_project.id=tk_task.csa_project and tid='$id' ";

            mysql_select_db($database_tankdb, $tankdb);
            $ResultTaskCalendar1 = mysql_query($ViewCalendarTaskSQL2, $tankdb) or die(mysql_error());

            while($row = mysql_fetch_assoc($ResultTaskCalendar1))
        {http://localhost/Capteam/project_view.php?recordID=56&pagetab=allprj
            $pagename='project_view.php';
            $pid=$row['id'];
            $url=$pagename.'?pagetab=alltask&recordID='.$pid;
            $pname=$row['project_name'];
            echo "<div class='b'><a href='$url'>$pname</a></div>";
       } ?>
        </label>
    </div>
    <div class="form-group col-xs-12">
        <label>
            <?php echo $multilingual_default_task_stage; ?>：&nbsp;
        </label>
        <label style="font-size: 1.2em">
            <?php 
           $ViewCalendarTaskSQL2="select * from tk_task,tk_project,tk_stage where tk_stage.stageid=tk_task.csa_project_stage
            and tk_project.id=tk_task.csa_project and tid='$id' ";

            mysql_select_db($database_tankdb, $tankdb);
            $ResultTaskCalendar1 = mysql_query($ViewCalendarTaskSQL2, $tankdb) or die(mysql_error());

            while($row = mysql_fetch_assoc($ResultTaskCalendar1))
        {//http://localhost/Capteam/project_view.php?recordID=56&pagetab=allprj
            $pagename='stage_view.php';
            $stageid=$row['stageid'];
            $pid=$row['id'];
            //stage_view.php?sid=16&pid=56
            $url=$pagename.'?sid='.$stageid.'&pid='.$pid;
            $tk_stage_title=$row['tk_stage_title'];
            echo "<div class='b'><a href='$url'>$tk_stage_title</a></div>";
       } ?>
        </label>
    </div>
    <div class="col-xs-12">
        <button type="button" class="btn btn-primary btn-sm" value="取消" onClick="$.fancybox.close()" style="width: 70px; float: right;"><?php echo $multilingual_global_action_close; ?></button>
    </div>
    </div>
</div>
