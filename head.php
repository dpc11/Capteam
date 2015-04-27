<?php require_once('config/tank_config.php'); ?>
<?php

$colname_Recordset_anc = "2";

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_anc = sprintf("SELECT * FROM tk_announcement WHERE tk_anc_type = %s ORDER BY tk_anc_lastupdate DESC", GetSQLValueString($colname_Recordset_anc, "text"));
$Recordset_anc = mysql_query($query_Recordset_anc, $tankdb) or die(mysql_error());
$row_Recordset_anc = mysql_fetch_assoc($Recordset_anc);
$totalRows_Recordset_anc = mysql_num_rows($Recordset_anc);

/////////////////////////////////////////////////////////////////////////////////
//$message_count = check_message( $_SESSION['MM_uid'] );

$message_count = 0;
$totalRows_Recordset_anc=0;






$self =$_SERVER['PHP_SELF'];

$pagename = explode("/",$self);
$pagename = end($pagename);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Capteam</title>
<link href="skin/themes/base/lhgdialog.css" rel="stylesheet" type="text/css" />
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script type="text/javascript" src="srcipt/js.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgdialog.js"></script>

</head>
<body>
<?php
if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 8.0") || strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 7.0") || strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 6.0") ){
?>
<div class="alert alert-warning" >
<?php echo $multilingual_browser_check; ?>
</div>
<?php exit;} ?>

  <div class="topbar" id="headerlink" >
    <div class="logo"><a href="index.php" class="logourl" >&nbsp;</a></div>
    <div class="nav_normal2">
    
      <a href="project.php?pagetab=jprj" class="
	  <?php if($pagename == "project.php" || $pagename == "project_add.php" || $pagename == "project_view.php" || $pagename == "project_edit.php"){
	  echo "nav_select";} ?>
	  "><?php echo $multilingual_head_project; ?></a>
	  
	  <a href="index.php" class="
	  <?php if($pagename == "index.php" || $pagename == "default_task_edit.php" || $pagename == "default_task_plan.php" || $pagename == "default_task_add.php") {
	  echo "nav_select";} ?>
	  "><?php echo $multilingual_head_task; ?></a>
	
      <a href="log.php" class="
	  <?php if($pagename == "log.php" ){
	  echo "nav_select";} ?>
	  "><?php echo $multilingual_head_feed; ?></a>
	  
      <a href="file.php" class="
	  <?php if($pagename == "file.php" || $pagename == "file_add.php" || $pagename == "file_project.php" || $pagename == "file_edit.php" || $pagename == "file_view.php"){
	  echo "nav_select";} ?>
	  "><?php echo $multilingual_head_file; ?></a>
	  
<!--
      <a href="default_user.php" class="
	  <?php if($pagename == "default_user.php" || $pagename == "user_add.php" || $pagename == "user_view.php" || $pagename == "default_user_edit.php"){
	  echo "nav_select";} ?>
	  "><?php echo $multilingual_head_user; ?></a>
-->
	  
      <a href="default_announcement.php" class="
	  <?php if($pagename == "default_announcement.php" || $pagename == "announcement_add.php" || $pagename == "announcement_view.php" || $pagename == "announcement_edit.php"){
	  echo "nav_select";} ?>
	  "><?php echo $multilingual_head_announcement; ?></a>
        
      <a href="schedule_view.php" class="
	  <?php if($pagename == "schedule_view.php" || $pagename == "schedule_task.php" || $pagename == "schedule_person.php" || $pagename == "schedule_course.php"){
	  echo "nav_select";} ?>
	  "><?php echo $multilingual_head_schedule; ?></a> 
    </div>


    <div class="logininfo2">
              <div class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo "{$_SESSION['MM_Displayname']}"; ?> <span class="caret"></span></a>
                <ul class="dropdown-menu pull-right">

				  <li><a href="user_view.php?recordID=<?php echo "{$_SESSION['MM_uid']}"; ?>"><?php echo $multilingual_head_myprofile; ?></a></li>
                  <?php if($_SESSION['MM_rank'] > "1") { ?>
				  <li><a href="default_user_edit.php?UID=<?php echo "{$_SESSION['MM_uid']}"; ?>"><?php echo $multilingual_head_edituserinfo; ?></a></li>
				  <?php }  ?>

<!--
				  <?php if ($_SESSION['MM_rank'] > "4") {  ?>
                  <li><a href="setting.php?type=setting"><?php echo $multilingual_head_backend; ?></a></li>
				  <?php }  ?>
-->

                  <li><?php echo $multilingual_head_help; ?></li>
                  <li class="divider"></li>
                  <li><a href="<?php echo $logoutAction ?>"  ><?php echo $multilingual_head_logout; ?></a></li>
                </ul>
				 &nbsp;&nbsp;&nbsp;&nbsp;
				<script type="text/javascript">  
var blinkTitle = function (option) {
    var title = null;
    var newTitle = null;
    var handle = null;
    var state = false;
    var interval = null;
 
    if (option) {
        newTitle = option.newTitle ? option.newTitle : '';
        title = option.title ? option.title : document.title;
        interval = option.interval ? option.interval : 600;
    } else {
        newTitle = '<?php echo $multilingual_newmessage1;?> WSS';
        title = '<?php echo $multilingual_newmessage2;?> WSS';
        interval = 600;
    }
 
    function start() {
        var step=0, _title = document.title;
var timer = setInterval(function() {
step++;
if (step==3) {step=1};
if (step==1) {document.title='<?php echo $multilingual_newmessage1;?> WSS'};
if (step==2) {document.title='<?php echo $multilingual_newmessage2;?> WSS'};
}, 500);

return [timer, _title];
    }
    return {
        start: start
    }
}();
 
/**
 * 开始标题闪烁
 */

setInterval(function() {
	/*		
$.ajax({
		url:'message_check.php',
		success:function(resp){
			
			resp = JSON.parse(resp);
			if(resp!="0"){
				blinkTitle.start();
				$("#conmsg")[0].innerHTML=' <span class="label label-danger">' + resp + '</span>';	
			}	
		}
	})*/
}, 120000);
</script>  
                  
				 <a href="message.php" title = "<?php echo $multilingual_message; ?>" class="mouse_hover"><i class="glyphicon glyphicon-envelope icon-white"></i><span id="conmsg"><?php if($message_count > 0){ ?> <span class="label label-danger"><?php 
				 echo $message_count; ?></span>
				 <script type="text/javascript">  
				 blinkTitle.start();
				 </script>
                     <?php  } ?></span></a>
              </div><!-- /dropdown -->
			  
</div>

</div>

 <?php if ($totalRows_Recordset_anc > 0) { // Show if recordset not empty ?> 
  <div class="anc_div"  >

<div id="rollAD" style="height:18px; position:relative; overflow:hidden;">	

<div class="float_left"><strong><?php echo $multilingual_head_announcement; ?></strong>&nbsp;&nbsp;</div> 
<div class="float_left">
	<div id="rollText" style="font-size:12px; line-height:20px;  ">
			<?php do { ?>
			<a href="announcement_view.php?recordID=<?php echo $row_Recordset_anc['AID']; ?>">
			<?php echo $row_Recordset_anc['tk_anc_title']; ?> &nbsp; <?php echo $row_Recordset_anc['tk_anc_lastupdate']; ?>
			</a><br />
			<?php } while ($row_Recordset_anc = mysql_fetch_assoc($Recordset_anc)); ?>
	</div>
</div> 

</div>


<script type="text/javascript">
// <![CDATA[
var textDiv = document.getElementById("rollText");
var textList = textDiv.getElementsByTagName("a");
if(textList.length > 1){
	var textDat = textDiv.innerHTML;
	var br = textDat.toLowerCase().indexOf("<br",textDat.toLowerCase().indexOf("<br")+2);
	//var textUp2 = textDat.substr(0,br);
	textDiv.innerHTML = textDat+textDat+textDat.substr(0,br);
	textDiv.style.cssText = "position:absolute; top:0";
	var textDatH = textDiv.offsetHeight;MaxRoll();
}
var minTime,maxTime,divTop,newTop=0;
function MinRoll(){
	newTop++;
	if(newTop<=divTop+17){
		textDiv.style.top = "-" + newTop + "px";
	}else{
		clearInterval(minTime);
		maxTime = setTimeout(MaxRoll,5000);
	}
}
function MaxRoll(){
	divTop = Math.abs(parseInt(textDiv.style.top));
	if(divTop>=0 && divTop<textDatH-40){
		minTime = setInterval(MinRoll,1);
	}else{
		textDiv.style.top = 0;divTop = 0;newTop=0;MaxRoll();
	}
}
// ]]>
</script>

  </div>
  </div>
<?php } // Show if recordset not empty ?>
  
  
  
  
  
  <?php
mysql_free_result($Recordset_anc);
?>
