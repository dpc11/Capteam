<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php require_once('function/dao.php');?>
<?php 

$currentPage = $_SERVER["PHP_SELF"];
$project_dao_obj = new project_dao();
$my_totalprj = $project_dao_obj->get_my_total_project_num($_SESSION['MM_uid']);
$pagetabs = "jprj";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}
?>

<?php require('head.php'); ?>

<div class="subnav">
<div class="float_left" style="width:85%">

<!-- 切换按钮 -->
<div class="btn-group">
<a type="button" class="btn btn-default btn-sm <?php if($pagetabs == "jprj") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=jprj" >
<?php echo $multilingual_project_jprj;?>
</a>
<?php if($my_totalprj > 0) { ?>
<a type="button" class="btn btn-default btn-sm <?php if($pagetabs == "mprj") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=mprj" >
<?php echo $multilingual_project_myprj;?>
</a>
<?php } ?>	
<a type="button" class="btn btn-default btn-sm <?php if($pagetabs == "allprj") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=allprj" >
<?php echo $multilingual_project_allprj;?>
</a>
<a type="button" class="btn btn-default btn-sm <?php if($pagetabs == "closeprj") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=closeprj" >
<?php echo $multilingual_project_closeprj;?>
</a>
</div>

</div>

<?php if($_SESSION['MM_rank'] > "3") {  ?>
<div class="float_right">
<button type="button" class="btn btn-default btn-sm" name="button2" id="button2" onclick="javascript:self.location='project_add.php';">
<span class="glyphicon glyphicon-plus-sign"></span> <?php echo $multilingual_projectlist_new; ?>
</button>
</div>
<?php }  ?> 

</div>
<div class="clearboth"></div>
<div class="pagemargin">

<?php require('control_project.php'); ?>
</div>
<?php require('foot.php'); ?>

</body>
</html>