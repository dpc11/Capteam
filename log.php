<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php 
$pagetabs = "alllog";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}

$currentPage = $_SERVER["PHP_SELF"];


?>
<?php require('head.php'); ?>
<!--
<div class="subnav">
<ul class="nav nav-tabs">
  <li class="<?php if($pagetabs == "mlog") {
	  echo "active";} ?>">
    <a href="<?php echo $pagename; ?>"><?php echo $multilingual_log_mylog;?></a>
  </li>
  <li class="
	  <?php if($pagetabs == "alllog") {
	  echo "active";} ?>
	  "><a href="<?php echo $pagename; ?>?logtouser=0&pagetab=alllog"><?php echo $multilingual_log_newlog;?></a></li>
</ul>
<div class="clearboth"></div>
</div>
-->
<div class="pagemargin">

<?php require('control_log.php'); ?>
</div>
<?php require('foot.php'); ?>

</body>
</html>