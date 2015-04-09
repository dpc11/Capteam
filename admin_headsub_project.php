<div class="nav_normal_sub" id="headerlink_sub">
<a href="project_status.php"><?php echo $multilingual_projectstatus_title; ?></a>
<a href="project_sub_list.php"><?php echo $multilingual_projectsub_title; ?></a>
</div>
<script type="text/javascript" language="javascript">
if(location.href.toLowerCase().indexOf("project_status.php")>-1)
	document.getElementById("headerlink_sub").getElementsByTagName("A")[0].className="nav_select_sub";
else if(location.href.toLowerCase().indexOf("project_sub_list.php")>-1)
	document.getElementById("headerlink_sub").getElementsByTagName("A")[1].className="nav_select_sub";
</script>