<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php require_once('function/log_function.php'); ?>
<?php require_once('function/user_function.php'); ?>
<?php require_once('function/project_function.php'); ?>
<?php 

$url_this = $_SERVER["QUERY_STRING"] ;

$current_url = current(explode("&sort",$url_this));

$phpself =$_SERVER['PHP_SELF'];
$temp = explode("/",$phpself);
$pagenames = end($temp);

$pagetabs = "alllog";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}

$currentPage = $_SERVER["PHP_SELF"];
?>

<?php require('head.php'); ?>

<div class="pagemargin" id="pagemargin">
<?php require('log_list.php'); ?>
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
		$("#foot_top").css("min-height",document.getElementById("pagemargin").clientHeight+66+60+70+"px"); 
	});
	</script>
</body>
</html>