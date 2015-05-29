<?php
//                            _ooOoo_  
//                           o8888888o  
//                           88" . "88  
//                           (| -_- |)  
//                            O\ = /O  
//                        ____/`---'\____  
//                      .   ' \\| |// `.  
//                       / \\||| : |||// \  
//                     / _||||| -:- |||||- \  
//                       | | \\\ - /// | |  
//                     | \_| ''\---/'' | |  
//                      \ .-\__ `-` ___/-. /  
//                   ___`. .' /--.--\ `. . __  
//                ."" '< `.___\_<|>_/___.' >'"".  
//               | | : `- \`.;`\ _ /`;.`/ - ` : | |  
//                 \ \ `-. \_ __\ /__ _/ .-` / /  
//         ======`-.____`-.___\_____/___.-`____.-'======  
//                            `=---='  
//  
//         .............................................  
//                  佛祖保佑             永无BUG 
require_once( 'config/tank_config.php'); ?>
<?php require_once( 'session_unset.php'); ?>
<?php require_once( 'session.php'); ?>
<?php require_once( 'function/schedule_function.php'); ?>

<?php 
//获得个人日程数据
$userid = $_SESSION['MM_uid'];
$data = get_course_events($userid);



mysql_select_db($database_tankdb, $tankdb);
       $sql="SELECT * FROM tk_course_schedule WHERE cs_uid=$userid"; 
       $Result1 = mysql_query($sql, $tankdb) or die(mysql_error());
        
      $row=mysql_num_rows($Result1);
      //echo $row;
     // echo "string";
      $firstday=mysql_result($Result1,0,'cs_firstday');
      $csid=mysql_result($Result1,0,'cs_id');
      //echo $csid;
?>

<?php require( 'head.php'); ?>
<link rel="stylesheet" type="text/css" href="plug-in/calendar/css/fullcalendar.css">
<link rel="stylesheet" type="text/css" href="plug-in/calendar/css/fancybox.css">
<style type="text/css">
    .fc-day
    {
        cursor: pointer; 
    }
    .fc-widget-content
    {
        cursor: pointer; 
    }
</style>
<script src='js/jquery/jquery-1.9.1.js'></script>
<script src='js/jquery/jquery-ui-1.10.4.min.js'></script>
<script src='plug-in/calendar/js/fullcalendar.js'></script>
<script src='plug-in/calendar/js/jquery.fancybox-1.3.1.pack.js'></script>
<link rel="stylesheet" type="text/css" href="plug-in/calendar/css/jquery-ui.css">
<link rel="stylesheet" href="css/bootstrap/datepicker3.css" type="text/css"/>
<script type="text/javascript" src="js/bootstrap/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/bootstrap/locales/bootstrap-datepicker.zh-CN.js"></script>
<script type="text/javascript">
$(function() {
	$('#calendar').fullCalendar({
		header: {
			left: 'prev today next',
			center: 'title',
			right: 'agendaWeek'
		},
        defaultView: 'agendaWeek',
        minTime: 7,
        maxTime: 23,
        slotMinutes:30,
        allDaySlot: false,
        events: <?php echo json_encode($data); ?>,

        // 在个人日程中新增日程
		dayClick: function(date, allDay, jsEvent, view) {
			var selDate =$.fullCalendar.formatDate(date,'yyyy-MM-dd');
			$.fancybox({
				'type':'ajax',
				'href':'schedule_course_event.php?action=add&uid='+<?php echo $userid; ?>+'&date='+selDate+'&csid='+<?php echo $csid; ?>
			});
    	},
    
        // 修改个人日程中的日程
        eventClick: function(calEvent, jsEvent, view) {
			$.fancybox({
				'type':'ajax',
				'href':'schedule_course_event.php?action=edit&uid='+<?php echo $userid; ?>+'&id='+calEvent.id
			});
    	}
	});
	
});
</script>
<script type="text/javascript">
function start() {
  //var o = document.getElementById("dd");
  //o.style.display = (o.style.display=="")?"none":"";
   window.location.href="schedule_course_first.php";
 }
 </script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>

        <!-- 左边20%的宽度的树或者说明  -->
        <td width="20%" class="input_task_right_bg" valign="top">
            <table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                    <td valign="top" class="gray2">
                        <h4 style="margin-top:40px; margin-left: 5px;"><strong><?php echo $multilingual_schedule_person; ?></strong></h4>
                        <p>
                            <?php echo $multilingual_schedule_person_tip; ?>
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
                        <a type="button" class="btn btn-default btn-sm" href="schedule_view.php">
                            <?php echo $multilingual_schedule_view;?>
                        </a>
                        <a type="button" class="btn btn-default btn-sm" href="schedule_task.php">
                            <?php echo $multilingual_schedule_task;?>
                        </a>
                        <a type="button" class="btn btn-default btn-sm " href="schedule_person.php">
                            <?php echo $multilingual_schedule_person;?>
                        </a>
                        <a type="button" class="btn btn-default btn-sm active" href="schedule_course.php">
                            <?php echo $multilingual_schedule_course;?>
                        </a>
                    </div>
                </div>
            </div>
            <div class="clearboth"></div>

            <span id="sel_end" style="float:right;margin-bottom: 20px;">
               
                <?php if($row==0){?>
                     <button type="button" id="textfield_label"   style="color:red;font-size:17px;font-weight:bold;float:left;border-color:red;border-right-width: 1px;margin-right: 
                5px;border-bottom-width: 1.5;margin-bottom: 0px;height: 34.85714292526245px;" data-toggle="modal" data-target="#myModal" >学期开始时间</button>
                    <?php }else{?>

                <button type="button" id="textfield_label"   style="font-size:17px;font-weight:bold;float:left;border-color:rgb(204, 204, 204);border-right-width: 1px;margin-right: 
                5px;border-bottom-width: 1.5;margin-bottom: 0px;height: 34.85714292526245px;" data-toggle="modal" data-target="#myModal" >学期开始时间</button>
                <?php }?>
            <div class="col-xs-6" style="padding-left: 0;">
                <input type="text" name="startdate" id="datepicker" value="<?php echo $firstday; ?>" readonly="readonly" style="" class="form-control" />
            </div>
            </span>

            <div class="pagemargin">

                <!-- 所有日程表主体部分 -->
                <div>
                    <?php if($row==1){?>
                    <div id='calendar'>
                    <?php }?>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>
<?php require( 'foot.php'); ?>

</body>
<form action="<?php echo "course_add.php?uid=".$userid ?>"  method="post" name="form1" id="form1">
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">  
  <div class="modal-dialog">  
    <div class="modal-content">  
      <div class="modal-header">  
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>  
        <h4 class="modal-title">选择学期开始时间</h4>  
      </div>  
      <div class="modal-body">  
        
         <input name='date' type='date' id='date'  class='date' />

      </div>  
      <div class="modal-footer">  
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
        <button type="submit" class="btn btn-primary">Save changes</button>  
      </div>  
    </div><!-- /.modal-content -->  
  </div><!-- /.modal-dialog -->  
</div><!-- /.modal -->  
</form>

</html>