<?php require_once('config/tank_config.php'); ?>
<?php

$self =$_SERVER['PHP_SELF'];
$pagename = explode("/",$self);
$pagename = end($pagename);

if($pagename=="index.php") {
  require('task_update.php');
}
$colname_Recordset_anc = "2";

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_anc = sprintf("SELECT * FROM tk_announcement WHERE tk_anc_type = %s ORDER BY tk_anc_lastupdate DESC", GetSQLValueString($colname_Recordset_anc, "text"));
$Recordset_anc = mysql_query($query_Recordset_anc, $tankdb) or die(mysql_error());
$row_Recordset_anc = mysql_fetch_assoc($Recordset_anc);
$totalRows_Recordset_anc = mysql_num_rows($Recordset_anc);

$message_count = check_message( $_SESSION['MM_uid'] );

$totalRows_Recordset_anc=0;


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Capteam</title>
<link href="css/lhgcore/lhgdialog.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap/bootstrap.css" rel="stylesheet" media="screen">
<link href="css/tk_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery/jquery.js"></script>
<script type="text/javascript" src="js/bootstrap/bootstrap.js"></script>
<script type="text/javascript" src="js/lhgcore/lhgcore.js"></script>
<script type="text/javascript" src="js/lhgcore/lhgdialog.js"></script>

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
									newTitle = '<?php echo $multilingual_newmessage1;?> Capteam';
									title = '<?php echo $multilingual_newmessage2;?> Capteam';
									interval = 600;
								}
			 
								function start() {
									var step=0, _title = document.title;
									var timer = setInterval(function() {
									step++;
									if (step==3) {step=1};
									if (step==1) {document.title='<?php echo $multilingual_newmessage1;?> Capteam'};
									if (step==2) {document.title='<?php echo $multilingual_newmessage2;?> Capteam'};
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
										
								$.ajax({
									url:'message_check.php',
									success:function(resp){
											
										resp = JSON.parse(resp);
										if(resp!="0"){
											blinkTitle.start();
											$("#conmsg")[0].innerHTML=' <span class="label label-danger">' + resp + '</span>';	
										}	
									}
								});
							}, 120000);
						</script>  
</head>
<body id="docbody" style="min-width:1510px;">
		<div class="clearboth"></div>
		<div id="foot_top" class="foot_top">
			<div class="clearboth"></div>
			<div id="top_height" >
				<div class="clearboth"></div>
				<?php
					if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 8.0") || strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 7.0") || strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 6.0") ){
					?>
					<div class="alert alert-warning" >
					<?php echo $multilingual_browser_check; ?>
					</div>
					<?php exit;
					} ?>

				<div class="topbar" id="headerlink" name="headerlink">
					<div class="logo"><a href="index.php" class="logourl" >&nbsp;</a></div>
					<div class="nav_normal2">
			
						<a href="project.php" class="
						  <?php if($pagename == "project.php" || $pagename == "project_add.php" || $pagename == "project_view.php" || $pagename == "project_edit.php"){
						  echo "nav_select";} ?>
						  "><?php echo $multilingual_head_project; ?></a>
					  
						<a href="index.php" class="
						<?php if($pagename == "index.php" || $pagename == " task_view.php" || $pagename == "default_task_plan.php" || $pagename == "default_task_add.php") { echo "nav_select";} ?> "><?php echo $multilingual_head_task; ?></a>
			
						<a href="log.php" class="
						<?php if($pagename == "log.php" ){ echo "nav_select";} ?>"><?php echo $multilingual_head_feed; ?></a>
					  
						<a href="file.php" 
						class="<?php if($pagename == "file.php" || $pagename == "file_add.php" || $pagename == "file_project.php" || $pagename == "file_edit.php" || $pagename == "file_view.php"){ 
						echo "nav_select";} ?> "><?php echo $multilingual_head_file; ?></a>
					  
						<a href="announcement.php" 
						class="<?php if($pagename == "announcement.php" || $pagename == "announcement_add.php" || $pagename == "announcement_view.php" || $pagename == "announcement_edit.php"){
						echo "nav_select";} ?> "><?php echo $multilingual_head_announcement; ?></a>
						
						<a href="schedule_view.php" 
						class="<?php if($pagename == "schedule_view.php" || $pagename == "schedule_task.php" || $pagename == "schedule_person.php" || $pagename == "schedule_course.php"){
						echo "nav_select";} ?>"><?php echo $multilingual_head_schedule; ?></a> 

						<a href="board_view.php" 
						class="<?php if($pagename == "board_view.php")
									{	
									    if(isset($_GET['pid'])){
									        ;
									    }else
									    {
									    	echo "nav_select";
									    }
									} ?>"><?php echo $multilingual_head_board; ?></a> 

					</div>


					<div class="logininfo2">
						<div class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $_SESSION['MM_Displayname']; ?> <span class="caret"></span></a>
							<ul class="dropdown-menu pull-right">
								<li><a href="user_view.php?recordID=<?php echo $_SESSION['MM_uid']; ?>"><?php echo $multilingual_head_myprofile; ?></a></li>
								<li><a href="default_user_edit.php?UID=<?php echo $_SESSION['MM_uid']; ?>"><?php echo $multilingual_head_edituserinfo; ?></a></li>
								<li><a href="setting.php?type=setting"><?php echo $multilingual_head_backend; ?></a></li>
								<li><?php echo $multilingual_head_help; ?></li>
								<li class="divider"></li>
								<li><a href="<?php echo $logoutAction ?>"  ><?php echo $multilingual_head_logout; ?></a></li>
							</ul>
							<a href="message.php" title = "<?php echo $multilingual_message; ?>" class="mouse_hover">
								<i class="glyphicon glyphicon-envelope icon-white" ></i>
								<span id="conmsg"><?php if($message_count > 0){ ?> 
									<span class="label label-danger" style="position:relative;z-index:3;margin-left:-25px;font-size:10px;"><?php echo $message_count; ?></span>
									<script type="text/javascript">  
										blinkTitle.start();
									</script>
								<?php  } ?></span>
							</a>
						</div>
					</div>

				</div>

				<?php if ($totalRows_Recordset_anc > 0) { // Show if recordset not empty ?> 
				<div class="anc_div"  id="anc_div" >

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

				</div>
			<?php } ?>
			</div>
			<?php
			mysql_free_result($Recordset_anc);
			?>
