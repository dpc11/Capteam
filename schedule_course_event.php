<?php require_once('config/tank_config.php'); ?>
<?php
$action = $_GET['action'];
$id = (int)$_GET['id'];
$uid = (int)$_GET['uid'];
$csid=$_GET['csid'];


switch($action){
	case 'add':
		addform($uid,$csid);
		break;
	case 'edit':
		editform($id);
		break;
}

// 新增日程部分
function addform($uid,$csid){
$date = $_GET['date'];
?>

<link rel="stylesheet" type="text/css" href="plug-in/calendar/css/jquery-ui.css">
<link rel="stylesheet" href="css/bootstrap/datepicker3.css" type="text/css"/>
<script type="text/javascript" src="js/bootstrap/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/bootstrap/locales/bootstrap-datepicker.zh-CN.js"></script>
<div class="fancy">
    <h3>新增课程日程</h3>  
    <form id="add_form" action="<?php $url='schedule_course_opt.php?action=add&csid='.$csid;echo $url; ?>" method="post">
        <input type="hidden" name="uid" value=<?php echo $uid; ?> />
        <div class="form-group col-xs-12">
            <label for="datepicker">
                课程名称
            </label>
            <textarea name="event" id="event" class="form-control" rows="1" cols="20"></textarea>
        </div>
        <div class="form-group col-xs-12">
            <label for="datepicker">
                上课地点
            </label>
            <textarea name="event2" id="event2" class="form-control" rows="1" cols="20"></textarea>
        </div>
        
        <div class="form-group col-xs-12">
            <div style="display: block">    
                <label for="datepicker">
                    起始时间
                </label>
            </div>
           <!-- <div class="col-xs-6" style="padding-left: 0;">
                <input type="text" name="startdate" id="datepicker" value="<?php echo date('Y-m-d'); ?>" class="form-control" />
            </div>-->
            <span id="sel_start">
                  <label class="beauty-label" id="textfield_label"   style="font-size:17px;font-weight:bold;float:left;">第</label>
                 <div class="col-xs-3"
  style="padding-left: 5px;  border-left-width: 5px; padding-right: 5px;"  >
                
                <select name="s_week" class="form-control">
                    <option value="01"selected>1</option>
                    <option value="02">2</option>
                    <option value="03">3</option>
                    <option value="04">4</option>
                    <option value="05">5</option>
                    <option value="06">6</option>
                    <option value="07">7</option>
                    <option value="08">8</option>
                    <option value="09">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
        
                </select>
                </div>
                <label class="beauty-label" id="textfield_label"   style="font-size:17px;font-weight:bold;float:left;">周</label>
                <label class="beauty-label" id="textfield_label"   style="font-size:17px;float:left;border-left-width: 15px;margin-left: 15px;margin-right: 15px;">至</label>
                <label class="beauty-label" id="textfield_label"   style="font-size:17px;font-weight:bold;float:left;">第</label>
                <div class="col-xs-3"
  style="padding-left: 5px;  border-left-width: 5px; padding-right: 5px;"  >
                
                <select name="e_week" class="form-control">
                    <option value="01"selected>1</option>
                    <option value="02">2</option>
                    <option value="03">3</option>
                    <option value="04">4</option>
                    <option value="05">5</option>
                    <option value="06">6</option>
                    <option value="07">7</option>
                    <option value="08">8</option>
                    <option value="09">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
        
                </select>
                </div>
                <label class="beauty-label" id="textfield_label"   style="font-size:17px;font-weight:bold;float:left;">周</label>
                
              
            </span>
        </div>
        <div class="form-group col-xs-12">   
            <div style="display: block">    
                <label for="datepicker2">
                    上课时间
                </label>
            </div>
           
            <span id="sel_end">
                <label class="beauty-label" id="textfield_label"   style="font-size:17px;font-weight:bold;float:left;">周</label>
                 <div class="col-xs-3"
  style="padding-left: 5px;  border-left-width: 5px; padding-right: 5px;"  >
                
                <select name="weekday" class="form-control">
                    <option value="01"selected>1</option>
                    <option value="02">2</option>
                    <option value="03">3</option>
                    <option value="04">4</option>
                    <option value="05">5</option>
                    <option value="06">6</option>
                    <option value="07">7</option>
                    
        
                </select>
                </div>
            </span>
        </div>
        <div class="form-group col-xs-12">   
            
           
            

               <span id="sel_end1">
                  <div class="col-xs-3"style="
    padding-left: 0px;
    padding-right: 0px;
    width:99px;
">
                <select name="s_hour" class="form-control"style="float:left;width:100%;">
                    <option value="00">00</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08" selected>08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                </select>
                </div>
                <div class="col-xs-3">
                <select name="s_minute" class="form-control">
                    <option value="00" selected>00</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                </select>
                </div>
                 <label class="beauty-label" id="textfield_label"   style="font-size:17px;float:left;">至</label>
                <div class="col-xs-3">
                <select name="e_hour" class="form-control">
                    <option value="00">00</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08" selected>08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                </select>
                </div>
                <div class="col-xs-3"style="
    padding-left: 0px;
">
                <select name="e_minute" class="form-control"style="
    width: 101.85714292526245px;
">
                    <option value="00" selected>00</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                </select>
                </div>
            </span>
        </div>
       
                    
        <div class="sub_btn col-xs-12">
            <button type="submit" class="btn btn-primary btn-sm submitbutton" style="margin-left: 0;">保存</button>
            <button type="button" class="btn btn-default btn-sm" value="取消" onClick="$.fancybox.close()">取消</button>
        </div>
    </form>
</div>
<?php }

// 编辑日程部分
function editform($id){
   // echo $id;
	$query = mysql_query("select * from tk_course where course_id='$id'");
	$row = mysql_fetch_array($query);
	if($row){
		//$id = $row['id'];
		$time = $row['course_name'];
		$course_place = $row['course_place'];
        $startweek = $row['course_startweek'];//转换为date类型
		$endweek = $row['course_endweek'];//转换为date类型


        $course_day=$row['course_day'];

        $starttime1 = $row['course_starttime'];
        $starttime = strtotime($starttime1);//转换为date类型
		$start_h = date("H",$starttime);
		$start_m = date("i",$starttime);

		
		$endtime1 = $row['course_endtime'];
        $endtime = strtotime($endtime1);//转换为date类型
        $end_d = date("Y-m-d",$endtime);
        $end_h = date("H",$endtime);
        $end_m = date("i",$endtime);
        
       // $allday = $row['is_allday'];
		
	}
?>

<link rel="stylesheet" type="text/css" href="plug-in/calendar/css/jquery-ui.css">
<link rel="stylesheet" href="css/bootstrap/datepicker3.css" type="text/css"/>
<script type="text/javascript" src="js/bootstrap/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/bootstrap/locales/bootstrap-datepicker.zh-CN.js"></script>

<input type="hidden" name="id" id="eventid" value="<?php echo $id;?>" />
<div class="fancy">
	<h3>编辑日程 </h3>
    <form id="add_form" action="schedule_course_opt.php?action=edit" method="post">
       <input type="hidden" name="uid" value=<?php echo $uid; ?> />
        <div class="form-group col-xs-12">
            <label for="datepicker">
                课程名称
            </label>
            <textarea name="event" id="event" class="form-control" rows="1" cols="20"><?php echo $time; ?></textarea>
        </div>
        <div class="form-group col-xs-12">
            <label for="datepicker">
                上课地点
            </label>
            <textarea name="event2" id="event2" class="form-control" rows="1" cols="20"><?php echo $course_place; ?></textarea>
        </div>
        
        <div class="form-group col-xs-12">
            <div style="display: block">    
                <label for="datepicker">
                    起始时间
                </label>
            </div>
           <!-- <div class="col-xs-6" style="padding-left: 0;">
                <input type="text" name="startdate" id="datepicker" value="<?php echo date('Y-m-d'); ?>" class="form-control" />
            </div>-->
            <span id="sel_start">
                  <label class="beauty-label" id="textfield_label"   style="font-size:17px;font-weight:bold;float:left;">第</label>
                 <div class="col-xs-3"
  style="padding-left: 5px;  border-left-width: 5px; padding-right: 5px;"  >
                
                <select name="s_week" class="form-control" >
                    <option value="<?php echo $startweek;?>" selected><?php echo $startweek;?></option>
                    <option value="01">1</option>
                    <option value="02">2</option>
                    <option value="03">3</option>
                    <option value="04">4</option>
                    <option value="05">5</option>
                    <option value="06">6</option>
                    <option value="07">7</option>
                    <option value="08">8</option>
                    <option value="09">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
        
                </select>
                </div>
                <label class="beauty-label" id="textfield_label"   style="font-size:17px;font-weight:bold;float:left;">周</label>
                <label class="beauty-label" id="textfield_label"   style="font-size:17px;float:left;border-left-width: 15px;margin-left: 15px;margin-right: 15px;">至</label>
                <label class="beauty-label" id="textfield_label"   style="font-size:17px;font-weight:bold;float:left;">第</label>
                <div class="col-xs-3"
  style="padding-left: 5px;  border-left-width: 5px; padding-right: 5px;"  >
                
                <select name="e_week" class="form-control">
                     <option value="<?php echo $endweek;?>" selected><?php echo $endweek;?></option>
                    <option value="01">1</option>
                    <option value="02">2</option>
                    <option value="03">3</option>
                    <option value="04">4</option>
                    <option value="05">5</option>
                    <option value="06">6</option>
                    <option value="07">7</option>
                    <option value="08">8</option>
                    <option value="09">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
        
                </select>
                </div>
                <label class="beauty-label" id="textfield_label"   style="font-size:17px;font-weight:bold;float:left;">周</label>
                
              
            </span>
        </div>
        <div class="form-group col-xs-12">   
            <div style="display: block">    
                <label for="datepicker2">
                    上课时间
                </label>
            </div>
           
            <span id="sel_end">
                <label class="beauty-label" id="textfield_label"   style="font-size:17px;font-weight:bold;float:left;">周</label>
                 <div class="col-xs-3"
  style="padding-left: 5px;  border-left-width: 5px; padding-right: 5px;"  >
                
                <select name="weekday" class="form-control">
                    <option value="<?php echo $course_day;?>" selected><?php echo $course_day;?></option>
                    <option value="01">1</option>
                    <option value="02">2</option>
                    <option value="03">3</option>
                    <option value="04">4</option>
                    <option value="05">5</option>
                    <option value="06">6</option>
                    <option value="07">7</option>
                    
        
                </select>
                </div>
            </span>
        </div>
        <div class="form-group col-xs-12">   
            
           
            

               <span id="sel_end1">
                  <div class="col-xs-3"style="
    padding-left: 0px;
    padding-right: 0px;
    width:99px;
">
                <select name="s_hour" class="form-control"style="float:left;width:100%;">
                    <option value="<?php echo $start_h;?>" selected><?php echo $start_h;?></option>
                    <option value="00">00</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08" >08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                </select>
                </div>
                <div class="col-xs-3">
                <select name="s_minute" class="form-control">
                    <option value="<?php echo $start_m;?>" selected><?php echo $start_m;?></option>
                    <option value="00">00</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                </select>
                </div>
                 <label class="beauty-label" id="textfield_label"   style="font-size:17px;float:left;">至</label>
                <div class="col-xs-3">
                <select name="e_hour" class="form-control">
                    <option value="<?php echo $end_h;?>" selected><?php echo $end_h;?></option>
                    <option value="00">00</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08" >08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                </select>
                </div>
                <div class="col-xs-3"style="
    padding-left: 0px;
">
                <select name="e_minute" class="form-control"style="
    width: 101.85714292526245px;
">             
                    <option value="<?php echo $end_m;?>" selected><?php echo $end_m;?></option>
                    <option value="00" selected>00</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                </select>
                </div>
            </span>
        </div>
       
                    
        <div class="sub_btn col-xs-12">
            <button type="submit" class="btn btn-primary btn-sm submitbutton" style="margin-left: 0;">保存</button>
            <button type="button" class="btn btn-default btn-sm" value="取消" onClick="$.fancybox.close()">取消</button>
        </div>
    </form>
</div>
<?php }?>

<script type="text/javascript" src="plug-in/calendar/js/jquery.form.min.js"></script>
<script type="text/javascript">
$(function(){
    
    $('#datepicker').datepicker({
			format: "yyyy-mm-dd"
	<?php if ($language=="cn") {echo ", language: 'zh-CN'" ;}?>
		});
    $('#datepicker2').datepicker({
			format: "yyyy-mm-dd"
	<?php if ($language=="cn") {echo ", language: 'zh-CN'" ;}?>
		});
    	
	$("#isallday").click(function(){
		if($("#sel_start").css("display")=="none"){
			$("#sel_start,#sel_end").show();
		}else{
			$("#sel_start,#sel_end").hide();
		}
	});
    
	//提交表单
	$('#add_form').ajaxForm({
		beforeSubmit: showRequest, //表单验证
        success: showResponse //成功返回
    }); 
	
	//删除事件
	$("#del_event").click(function(){
		if(confirm("您确定要删除吗？")){
			var eventid = $("#eventid").val();
			$.post("schedule_person_opt.php?action=del",{id:eventid},function(msg){
				if(msg==1){//删除成功
					$.fancybox.close();
                    location.reload();
					// $('#calendar').fullCalendar('refetchEvents'); //重新获取所有事件数据
				}else{
					alert(msg);	
				}
			});
		}
	});
});

function showRequest(){
	var events = $("#event").val();
	if(events==''){
		alert("请输入日程内容！");
		$("#event").focus();
		return false;
	}
}

function showResponse(responseText, statusText, xhr, $form){
	if(statusText=="success"){	
		if(responseText==1){//1表示成功
			$.fancybox.close();
            location.reload();
			// $('#calendar').fullCalendar('refetchEvents'); //重新获取所有事件数据
		}else{
			alert(responseText);
		}
	}else{
		alert(statusText);
	}
}
</script>