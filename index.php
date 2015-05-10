<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php 
$url_this = $_SERVER["QUERY_STRING"] ;

$current_url = current(explode("&sort",$url_this));

$pagetabs = "alltask";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}

$currentPage = $_SERVER["PHP_SELF"];

$taskpage=2;

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_sumtotal = sprintf("SELECT 
							COUNT(*) as count_task   
							FROM tk_task						
							WHERE csa_from_user = %s AND csa_status =3 AND csa_del_status=1", 
								GetSQLValueString($_SESSION['MM_uid'], "int")
								);
$Recordset_sumtotal = mysql_query($query_Recordset_sumtotal, $tankdb) or die(mysql_error());
$row_Recordset_sumtotal = mysql_fetch_assoc($Recordset_sumtotal);
$exam_totaltask=$row_Recordset_sumtotal['count_task'];
?>

<?php require('head.php'); ?>

<script type="text/javascript">
$(function(){
	var $hov_t = $('.toptable tr th')
	$hov_t.addClass('trhover_1')	   
	$hov_t.hover(function(){
		$(this).addClass('thhover')							
	},function(){
		$(this).removeClass('thhover')
		})		   
})

$(function(){
	$('.maintable tr:even').addClass('even')
	var $j = $('.maintable tr');
	$j.hover(function(){
		$(this).addClass('trhover')								  
	},function(){
		$(this).removeClass('trhover')
		});
})
</script>

<div class="subnav">
<div class="float_left" style="width:85%">
<div class="btn-group">
<!--我参与的任务 -->
  <a type="button" class="btn btn-default btn-sm <?php if($pagetabs == "mtask") { echo "active";} ?>" href="<?php echo $pagename; ?>?select=&select_project=&select_year=<?php echo date("Y");?>&textfield=<?php echo date("m");?>&select3=-1&select4=<?php echo "{$_SESSION['MM_uid']}"; ?>&select_prt=&select_temp=&inputtitle=&select1=-1&select2=%&create_by=%&select_type=&inputid=&inputtag=&pagetab=mtask">
  <?php echo $multilingual_user_mytask;?>
  </a>
<!--我创建的任务 -->
  <a type="button" class="btn btn-default btn-sm <?php if($pagetabs == "ctask") { echo "active";} ?>" href="<?php echo $pagename; ?>?select=&select_project=&select_year=<?php echo date("Y");?>&textfield=<?php echo date("m");?>&select3=-1&select4=%&select_prt=&select_temp=&inputtitle=&select1=-1&select2=%&create_by=<?php echo "{$_SESSION['MM_uid']}"; ?>&select_type=&inputid=&inputtag=&pagetab=ctask"><?php echo $multilingual_default_createme;?></a>
 
<!--抄送给我的任务 --> 
  <a type="button" class="btn btn-default btn-sm <?php if($pagetabs == "cctome") { echo "active";} ?>" href="<?php echo $pagename; ?>?select=&select_project=&select_year=<?php echo date("Y");?>&textfield=<?php echo date("m");?>&select3=-1&select4=%&select_prt=&select_temp=&inputtitle=&select1=-1&select2=%&select_type=&inputid=&inputtag=&pagetab=cctome"><?php echo $multilingual_default_task_cctome_title;?></a>

 <!--待我审核的任务 --> 
  <?php if($exam_totaltask>0) { ?>
  <a type="button" class="btn btn-default btn-sm <?php if($pagetabs == "etask") { echo "active";} ?>" href="<?php echo $pagename; ?>?select=&select_project=&select_year=--&textfield=--&select3=-1&select4=%&select_prt=&select_temp=&select_exam=3&inputtitle=&select1=-1&select2=<?php echo "{$_SESSION['MM_uid']}"; ?>&select_type=&inputid=&inputtag=&pagetab=etask"><?php echo $multilingual_exam_wait."(".$exam_totaltask.")"; ?></a>
  <?php } ?>

 <!--所有任务 --> 
  <a type="button" class="btn btn-default btn-sm <?php if($pagetabs == "alltask") { echo "active";} ?>" href="<?php echo $pagename; ?>?select=&select_project=&select_year=<?php echo date("Y");?>&textfield=<?php echo date("m");?>&select3=-1&select4=%&select_prt=&select_temp=&inputtitle=&select1=-1&select2=%&create_by=%&select_type=&inputid=&inputtag=&pagetab=alltask"><?php echo $multilingual_default_alltask;?></a>
</div><!--btn-group -->
</div><!--float_left -->

<!--新建任务d按钮
<?php if($_SESSION['MM_rank'] > "2" ) { ?>
<div class="float_right">
		<button type="button" class="btn btn-default btn-sm" name="button2" id="button2" onclick="addtask();">
		<span class="glyphicon glyphicon-plus-sign"></span> <?php echo $multilingual_default_newtask; ?>
		</button>
</div>
<?php }  ?>  --> 
<div class="clearboth"></div>
</div><!--subnav -->
<div class="pagemargin">
<?php require('control_task.php'); ?>
</div>
<?php require('foot.php'); ?>

</body>
</html>