<?php 
$type = $_SERVER["QUERY_STRING"];
$self =$_SERVER['PHP_SELF'];
$pagename = end(explode("/",$self));
?>

<span class="<?php if($type == "type=setting"){echo "set_menu_onfocus";} else {echo "set_menu_nofocus";}?>"><a href="setting.php?type=setting"><?php echo $multilingual_set_baseset; ?></a></span>


<span class="<?php if($type == "type=setting_mail"){echo "set_menu_onfocus";} else {echo "set_menu_nofocus";}?>"><a href="setting.php?type=setting_mail"><?php echo $multilingual_set_mailset; ?></a></span>


<hr class="set_menu_hr" size="1">

<span class="<?php if($pagename == "task_type_list.php"){echo "set_menu_onfocus";} else {echo "set_menu_nofocus";}?>"><a href="task_type_list.php"><?php echo $multilingual_tasktype_title; ?></a></span>
<span class="<?php if($pagename == "status_list.php"){echo "set_menu_onfocus";} else {echo "set_menu_nofocus";}?>"><a href="status_list.php"><?php echo $multilingual_taskstatus_title; ?></a></span>

<hr class="set_menu_hr" size="1">

<span class="<?php if($pagename == "project_status.php"){echo "set_menu_onfocus";} else {echo "set_menu_nofocus";}?>"><a href="project_status.php"><?php echo $multilingual_projectstatus_title; ?></a></span>
<span class="<?php if($pagename == "project_member.php"){echo "set_menu_onfocus";} else {echo "set_menu_nofocus";}?>"><a href="project_member.php"><?php echo $multilingual_projectmem_title; ?></a></span>

<hr class="set_menu_hr" size="1">

<span class="<?php if($pagename == "team_list.php"){echo "set_menu_onfocus";} else {echo "set_menu_nofocus";}?>"><a href="team_list.php"><?php echo $multilingual_dept_title; ?></a></span>

<hr class="set_menu_hr" size="1">

<span class="<?php if($pagename == "update.php"){echo "set_menu_onfocus";} else {echo "set_menu_nofocus";}?>"><a href="update.php" ><?php echo $multilingual_version_title; ?></a></span>