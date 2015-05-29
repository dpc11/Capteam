<?php require_once('config/tank_config.php'); ?>
<?php
$action = $_GET['action'];
$id = (int)$_GET['id'];
$uid = (int)$_GET['uid'];

switch($action){
    case 'add':
        addform($uid);
        break;
    case 'edit':
        editform($id);
        break;
}

// 新增日程部分

$date = $_GET['date'];
?>

<link rel="stylesheet" type="text/css" href="plug-in/calendar/css/jquery-ui.css">
<link rel="stylesheet" href="css/bootstrap/datepicker3.css" type="text/css"/>
<script type="text/javascript" src="js/bootstrap/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/bootstrap/locales/bootstrap-datepicker.zh-CN.js"></script>
<div class="fancy">
    <h3>新增日程</h3>
    <form id="add_form" action="schedule_person_opt.php?action=add" method="post">
        <input type="hidden" name="uid" value=<?php echo $uid; ?> />
        <div class="form-group col-xs-12">
            <label for="datepicker">
                日程内容
            </label>
            <textarea name="event" id="event" class="form-control" rows="4" cols="20"></textarea>
        </div>
        <div class="col-xs-12">
            <input type="checkbox" id="isallday" name="isallday"/><label for="isallday">&nbsp;全天日程（精确到天）</label>
        </div>
        <div class="form-group col-xs-12">
            <div style="display: block">    
                <label for="datepicker">
                    开始时间
                </label>
            </div>
            <div class="col-xs-6" style="padding-left: 0;">
                <input type="text" name="startdate" id="datepicker" value="<?php echo date('Y-m-d'); ?>" class="form-control" />
            </div>
            <span id="sel_start">
                <div class="col-xs-3">
                <select name="s_hour" class="form-control">
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
            </span>
        </div>
        <div class="form-group col-xs-12">   
            <div style="display: block">    
                <label for="datepicker2">
                    结束时间
                </label>
            </div>
            <div class="col-xs-6" style="padding-left: 0;">
                <input type="text" name="enddate" id="datepicker2" value="<?php echo date('Y-m-d'); ?>" class="form-control" />
            </div>
            <span id="sel_end">
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
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12" selected>12</option>
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
                <select name="e_minute" class="form-control">
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
<?php

// 编辑日程部分
function editform($id){
    $query = mysql_query("select * from tk_schedule where id='$id'");
    $row = mysql_fetch_array($query);
    if($row){
        $id = $row['id'];
        $title = $row['name'];
        $starttime1 = $row['start_time'];
        $starttime = strtotime($starttime1);//转换为date类型
        $start_d = date("Y-m-d",$starttime);
        $start_h = date("H",$starttime);
        $start_m = date("i",$starttime);

        
        $endtime1 = $row['end_time'];
        $endtime = strtotime($endtime1);//转换为date类型
        $end_d = date("Y-m-d",$endtime);
        $end_h = date("H",$endtime);
        $end_m = date("i",$endtime);
        
        $allday = $row['is_allday'];
        if($allday==1){
            $display = "style='display:none'";
            $allday_chk = "checked";
        }else{
            $display = "style=''";
            $allday_chk = '';
        }
    }
?>

<link rel="stylesheet" type="text/css" href="plug-in/calendar/css/jquery-ui.css">
<link rel="stylesheet" href="css/bootstrap/datepicker3.css" type="text/css"/>
<script type="text/javascript" src="js/bootstrap/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/bootstrap/locales/bootstrap-datepicker.zh-CN.js"></script>

<input type="hidden" name="id" id="eventid" value="<?php echo $id;?>" />
<div class="fancy">
    <h3>编辑日程</h3>
    <form id="add_form" action="schedule_person_opt.php?action=edit" method="post">
        <input type="hidden" name="id" value=<?php echo $id; ?> />
        <input type="hidden" name="uid" value=<?php echo $uid; ?> />
        <div class="form-group col-xs-12">
            <label for="datepicker">
                日程内容
            </label>
            <textarea name="event" id="event" class="form-control" rows="4" cols="20"><?php echo $title; ?></textarea>
        </div>
        <div class="col-xs-12">
            <input type="checkbox" id="isallday" name="isallday" <?php echo $allday_chk;?>/><label for="isallday">&nbsp;全天日程（精确到天）</label>
        </div>
        <div class="form-group col-xs-12">
            <div style="display: block">    
                <label for="datepicker">
                    开始时间
                </label>
            </div>
            <div class="col-xs-6" style="padding-left: 0;">
                <input type="text" name="startdate" id="datepicker" value="<?php echo $start_d; ?>" class="form-control" />
            </div>
           
        </div>
     
    </form>
</div>
<?php }?>


