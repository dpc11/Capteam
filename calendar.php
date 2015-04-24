<?php
require_once('config/tank_config.php'); 
$action = $_GET['action'];
$date = $_GET['date'];
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>演示：FullCalendar应用——增删改数据操作</title>
<meta name="keywords" content="日程安排,FullCalendar,日历,JSON,jquery实例">
<meta name="description" content="在线演示FullCalendar新建、修改和删除日程事件的示例。">
<link rel="stylesheet" type="text/css" href="http://www.helloweba.com/demo/css/main.css">
<link rel="stylesheet" type="text/css" href="css/fullcalendar.css">
<link rel="stylesheet" type="text/css" href="css/fancybox.css">
<style type="text/css">
#calendar{width:960px; margin:20px auto 10px auto}
.fancy{width:450px; height:auto}
.fancy h3{height:30px; line-height:30px; border-bottom:1px solid #d3d3d3; font-size:14px}
.fancy form{padding:10px}
.fancy p{height:28px; line-height:28px; padding:4px; color:#999}
.input{height:20px; line-height:20px; padding:2px; border:1px solid #d3d3d3; width:100px}
.btn{-webkit-border-radius: 3px;-moz-border-radius:3px;padding:5px 12px; cursor:pointer}
.btn_ok{background: #360;border: 1px solid #390;color:#fff}
.btn_cancel{background:#f0f0f0;border: 1px solid #d3d3d3; color:#666 }
.btn_del{background:#f90;border: 1px solid #f80; color:#fff }
.sub_btn{height:32px; line-height:32px; padding-top:6px; border-top:1px solid #f0f0f0; text-align:right; position:relative}
.sub_btn .del{position:absolute; left:2px}
</style>
<script src='http://code.jquery.com/jquery-1.9.1.js'></script>
<script src='http://code.jquery.com/ui/1.10.3/jquery-ui.js'></script>
<script src='js/fullcalendar.min.js'></script>
<script src='js/jquery.fancybox-1.3.1.pack.js'></script>
<script type="text/javascript">
$(function() {
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		events: 'json.php',
		/*dayClick: function(date, allDay, jsEvent, view) {
			var selDate =$.fullCalendar.formatDate(date,'yyyy-MM-dd');
			$.fancybox({
				'type':'ajax',
				'href':'event.php?action=add&date='+selDate
			});
    	},*/

		eventClick: function(calEvent, jsEvent, view) {
			$.fancybox({
				'type':'ajax',
				'href':'event.php?&id='+calEvent.id
			});
    	}
	});
	
});
</script>
</head>

<<body>
<div id="header">
   <h6>任务日程（只能查询，不能进行其他操作）</a></h6>
</div>

<div id="main" style="width:1060px">
  
   <div id='calendar'></div>
</div>
<div id="footer">
    <p>任务日程显示（显示截止日期）</a></p>
</div>
<p id="stat"><script type="text/javascript" src="/js/tongji.js"></script></p>
</body>
</html>

