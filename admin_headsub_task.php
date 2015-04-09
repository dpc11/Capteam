<div class="nav_normal_sub" id="headerlink_sub">
<a href="task_type_list.php"><?php echo $multilingual_tasktype_title; ?></a>
<a href="status_list.php"><?php echo $multilingual_taskstatus_title; ?></a>
</div>
<script type="text/javascript" language="javascript">
if(location.href.toLowerCase().indexOf("task_type_list.php")>-1)
	document.getElementById("headerlink_sub").getElementsByTagName("A")[0].className="nav_select_sub";
else if(location.href.toLowerCase().indexOf("status_list.php")>-1)
	document.getElementById("headerlink_sub").getElementsByTagName("A")[1].className="nav_select_sub";
</script>