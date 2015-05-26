<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php require_once('function/config_function.php'); ?>
<?php require_once('function/project_function.php'); ?>
<?php 

$currentPage = $_SERVER["PHP_SELF"];
$pagetabs = "allprj";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}  
?>
<?php require('head.php'); ?>

		<div class="subnav" id="subnav">
			<div class="float_left" style="width:85%">
				<!-- 切换按钮 -->
				<div class="btn-group">		
					<a type="button" class="btn btn-default btn-lg <?php if($pagetabs == "allprj") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=allprj" >
					<?php echo $multilingual_project_allprj;?>
					</a>
					<a type="button" class="btn btn-default btn-lg <?php if($pagetabs == "jprj") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=jprj" >
					<?php echo $multilingual_project_jprj;?>
					</a>
					<a type="button" class="btn btn-default btn-lg <?php if($pagetabs == "mprj") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=mprj" >
					<?php echo $multilingual_project_myprj;?>
					</a>			
					<a type="button" class="btn btn-link btn-lg" style="margin-left:10px;" name="button2" id="button2" onclick="javascript:self.location='project_add.php';">
					<span class="glyphicon glyphicon-plus-sign"></span> <?php echo $multilingual_projectlist_new; ?>
					</a>
				</div>
			</div>
			<div class="clearboth"></div>
		</div>
		<div class="clearboth"></div>
		<div class="pagemargin" id="pagemargin">
			<div class="clearboth"></div>
			<?php require('project_list.php'); ?>
		</div>
	</div>
	
	<?php require('foot.php'); ?>
	
	<script>

	$(window).load(function()
	{
		$(window).resize();	
	});
	$(window).resize(function()
	{	
		$("#tbody_br").css("width",$("#tasktab").width()-551+"px");
		$("#headerlink").css("width",$("#tasktab").width()/0.9+"px");
		$("#foot_div").css("width",$("#tasktab").width()/0.9+"px");
		$("#foot_top").css("min-height",document.getElementById("pagemargin").clientHeight+document.getElementById("subnav").clientHeight+66+60+70+"px"); 
	});
	</script>
</body>
</html>