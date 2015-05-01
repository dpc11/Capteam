<?php require_once( 'config/tank_config.php'); ?>
<?php require_once( 'session_unset.php'); ?>
<?php require_once( 'session.php'); ?>

<?php require( 'head.php'); ?>
<link rel="stylesheet" type="text/css" href="calendar/css/fullcalendar.css">
<link rel="stylesheet" type="text/css" href="calendar/css/fancybox.css">
<script src='srcipt/jquery-1.9.1.js'></script>
<script src='srcipt/jquery-ui-1.10.4.min.js'></script>
<script src='calendar/js/fullcalendar.js'></script>
<script src='calendar/js/jquery.fancybox-1.3.1.pack.js'></script>
<script type="text/javascript">
$(function() {
	$('#calendar').fullCalendar({
		header: {
			left: 'prev today next',
			center: 'title',
			right: 'month,agendaWeek'
		},
		events: 'calendar_json.php',

//        // 查看所有日程中的详细日程
//		eventClick: function(calEvent, jsEvent, view) {
//			$.fancybox({
//				'type':'ajax',
//				'href':'schedule_view_event.php?&id='+calEvent.id
//			});
//    	},
	});
	
});
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>

        <!-- 左边20%的宽度的树或者说明  -->
        <td width="20%" class="input_task_right_bg" valign="top">
            <table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                    <td valign="top" class="gray2">
                        <h4 style="margin-top:40px; margin-left: 5px;"><strong><?php echo $multilingual_schedule_view; ?></strong></h4>
                        <p>
                            <?php echo $multilingual_schedule_view_tip; ?>
                        </p>

                    </td>
                </tr>
            </table>
        </td>

        <!-- 右边80%宽度的主体内容 -->
        <td width="80%" valign="top">
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
            </div>
            <div class="clearboth"></div>
            <div class="pagemargin">

                <!-- 所有日程表主体部分 -->
                <div>
                    <div id='calendar'>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>
<?php require( 'foot.php'); ?>

</body>
</html>