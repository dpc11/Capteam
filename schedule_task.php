<?php require_once( 'config/tank_config.php'); ?>
<?php require_once( 'session_unset.php'); ?>
<?php require_once( 'session.php'); ?>

<?php require( 'head.php'); ?>
<link rel="stylesheet" type="text/css" href="calendar/css/fullcalendar.css">
<link rel="stylesheet" type="text/css" href="calendar/css/fancybox.css">
<script src='http://code.jquery.com/jquery-1.9.1.js'></script>
<script src='http://code.jquery.com/ui/1.10.3/jquery-ui.js'></script>
<script src='calendar/js/fullcalendar.min.js'></script>
<script src='calendar/js/jquery.fancybox-1.3.1.pack.js'></script>
<script type="text/javascript">
$(function() {
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		events: 'calendar_json.php',

		eventClick: function(calEvent, jsEvent, view) {
			$.fancybox({
				'type':'ajax',
                
                // 查看任务日程的详细日程

				'href':'schedule_task_event.php?&id='+calEvent.id
			});
    	}
	});
	
});
</script>
<div class="subnav">
    <div class="float_left" style="width:85%">

        <!-- 切换按钮 -->
        <div class="btn-group">
            <a type="button" class="btn btn-default btn-sm" href="schedule_view.php">
                <?php echo $multilingual_schedule_view;?>
            </a>    
            <a type="button" class="btn btn-default btn-sm active" href="schedule_task.php">
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
</div>
<div class="clearboth"></div>
<div class="pagemargin">
    
    <!-- 任务日程表主体部分 -->
    <div>
        <div id='calendar'>
        </div>
    </div>
<?php require( 'foot.php'); ?>

</body>
</html>