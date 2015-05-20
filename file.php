<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php 

$pagetabs = "allfile";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}

$project_id= "-1";
if (isset($_GET['projectID'])) {
  $project_id = $_GET['projectID'];
}
//文档id
$colname_DetailRS1 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS1 = $_GET['recordID'];
}
//搜索的文件名
$filenames = "";
if (isset($_GET['filetitle'])) {
  $filenames = $_GET['filetitle'];
}

$searchf = "-1"; //判断是否点击了搜索
if (isset($_GET['search'])) {
  $searchf = $_GET['search'];
}

$currentPage = $_SERVER["PHP_SELF"];
$maxRows_DetailRS1 = 10;//每页多少行记录
$pageNum_DetailRS1 = 0;//第几页
if (isset($_GET['pageNum_DetailRS1'])) {
  $pageNum_DetailRS1 = $_GET['pageNum_DetailRS1'];
}
$startRow_DetailRS1 = $pageNum_DetailRS1 * $maxRows_DetailRS1;

if($pagetabs=="mcfile"){
$multilingual_breadcrumb_filelist = $multilingual_project_file_myfile;
}else if ($pagetabs=="allfile")  {
$multilingual_breadcrumb_filelist = $multilingual_project_file_allfile;
}

if ($searchf == "1"){	
	$inprolists = "'%" . $filenames . "%'";
	mysql_select_db($database_tankdb, $tankdb);
	if ($pagetabs == "allfile"){
		//对应文件下的所有文件夹和文件  -1表示没有选中
		$query_DetailRS1 = sprintf("SELECT *, 
		tk_user1.tk_display_name as tk_display_name1
		FROM (select * from tk_document where FIND_IN_SET(docid, getChildLst(%s)) and docid != %s and tk_doc_del_status=1) as A
		inner join tk_user as tk_user1 on A.tk_doc_create=tk_user1.uid  
		WHERE  tk_doc_title LIKE %s   ", 
		GetSQLValueString($colname_DetailRS1, "int"),GetSQLValueString($colname_DetailRS1, "int"),$inprolists);
	}else{
		$query_DetailRS1 = sprintf("SELECT *, 
		tk_user1.tk_display_name as tk_display_name1
		FROM (select * from tk_document where FIND_IN_SET(docid, getChildLst(%s)) and docid != %s and tk_doc_del_status=1) as A
		inner join tk_user as tk_user1 on A.tk_doc_create=tk_user1.uid  
		WHERE  tk_doc_title LIKE %s  and (tk_doc_create = %s or tk_doc_backup1=1) ", 
		GetSQLValueString($colname_DetailRS1, "int"),GetSQLValueString($colname_DetailRS1, "int"),$inprolists,$_SESSION['MM_uid']);
	}
	$query_limit_DetailRS1 = sprintf("%s LIMIT %d, %d", $query_DetailRS1, $startRow_DetailRS1, $maxRows_DetailRS1);
	$DetailRS1 = mysql_query($query_limit_DetailRS1, $tankdb) or die(mysql_error());
	$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);

	if (isset($_GET['totalRows_DetailRS1'])) {
	  $totalRows_DetailRS1 = $_GET['totalRows_DetailRS1'];
	} else {
	  $all_DetailRS1 = mysql_query($query_DetailRS1);
	  $totalRows_DetailRS1 = mysql_num_rows($all_DetailRS1);
	}
	$totalPages_DetailRS1 = ceil($totalRows_DetailRS1/$maxRows_DetailRS1)-1;
}else if ($pagetabs == "allfile") {
	//对应文件下的所有文件夹和文件  -1表示没有选中
	mysql_select_db($database_tankdb, $tankdb);
	$query_DetailRS1 = sprintf("SELECT *, 
	tk_user1.tk_display_name as tk_display_name1
	FROM tk_document 
	inner join tk_user as tk_user1 on tk_document.tk_doc_create=tk_user1.uid  
 
	WHERE tk_document.tk_doc_parentdocid = %s  and tk_doc_del_status=1 
	", GetSQLValueString($colname_DetailRS1, "int"));
	$query_limit_DetailRS1 = sprintf("%s LIMIT %d, %d", $query_DetailRS1, $startRow_DetailRS1, $maxRows_DetailRS1);
	$DetailRS1 = mysql_query($query_limit_DetailRS1, $tankdb) or die(mysql_error());
	$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);
	//$inproject  $inprojects
	if (isset($_GET['totalRows_DetailRS1'])) {
	  $totalRows_DetailRS1 = $_GET['totalRows_DetailRS1'];
	} else {
	  $all_DetailRS1 = mysql_query($query_DetailRS1);
	  $totalRows_DetailRS1 = mysql_num_rows($all_DetailRS1);
	}
	$totalPages_DetailRS1 = ceil($totalRows_DetailRS1/$maxRows_DetailRS1)-1;
} else if ($pagetabs == "mcfile"){
	//对应文件下的所有文件夹和文件  -1表示没有选中
	mysql_select_db($database_tankdb, $tankdb);
	$query_DetailRS1 = sprintf("SELECT *, 
	tk_user1.tk_display_name as tk_display_name1
	FROM tk_document 
	inner join tk_user as tk_user1 on tk_document.tk_doc_create=tk_user1.uid  
	
	WHERE tk_document.tk_doc_parentdocid = %s  and tk_doc_del_status=1 
	
	and (tk_doc_create = %s or tk_doc_backup1=1)", 
	GetSQLValueString($colname_DetailRS1, "int"),
	$_SESSION['MM_uid']);
	//$inproject  $inprojects
	$query_limit_DetailRS1 = sprintf("%s LIMIT %d, %d", $query_DetailRS1, $startRow_DetailRS1, $maxRows_DetailRS1);
	$DetailRS1 = mysql_query($query_limit_DetailRS1, $tankdb) or die(mysql_error());
	$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);

	if (isset($_GET['totalRows_DetailRS1'])) {
	  $totalRows_DetailRS1 = $_GET['totalRows_DetailRS1'];
	} else {
	  $all_DetailRS1 = mysql_query($query_DetailRS1);
	  $totalRows_DetailRS1 = mysql_num_rows($all_DetailRS1);
	}
	$totalPages_DetailRS1 = ceil($totalRows_DetailRS1/$maxRows_DetailRS1)-1;
} 

$host_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER["QUERY_STRING"];
$host_url=strtr($host_url,"&","!");

$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_DetailRS1") == false && 
        stristr($param, "totalRows_DetailRS1") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset1 = sprintf("&totalRows_DetailRS1=%d%s", $totalRows_DetailRS1, $queryString_Recordset1);

?>

<?php require('head.php'); ?>

		<div class="subnav" id="subnav">
			<div class="float_left" style="width:85%">
				<!-- 切换按钮 -->
				<div class="btn-group">		
					<a type="button" class="btn btn-default btn-lg <?php if($pagetabs == "allfile") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=allfile" >
					<?php echo $multilingual_project_file_allfile;?>
					</a>

					<a type="button" class="btn btn-default btn-lg <?php if($pagetabs == "mcfile") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=mcfile" >
					<?php echo $multilingual_project_file_myfile;?>
					</a>
					
					<!--新建文档、文件夹-->
					<?php if ( $colname_DetailRS1 <> "-1" && $project_id <> "-1" ) { // 如果是一级页面不显示 ?>
					<button type="button" class="btn btn-link btn-lg" style="margin-left:30px" name="addfolder" id="addfolder" onclick="addfolder();">
					<span class="glyphicon glyphicon-folder-open"></span> <?php echo $multilingual_project_file_addfolder; ?>
					</button>

					<button type="button" class="btn btn-link btn-lg" name="addfile" id="addfile" onclick="window.open('file_add.php?projectid=<?php echo $project_id; ?>&pid=<?php echo $colname_DetailRS1; ?>&pagetab=<?php echo $pagetabs;?>')" >
					<span class="glyphicon glyphicon-file"></span> <?php echo $multilingual_project_file_addfile; ?>
					</button>
					<?php } ?>
				</div>
			</div>
			<div class="clearboth"></div>
		</div>
		<div class="clearboth"></div>
		<div class="pagemargin" id="pagemargin">
			<div class="clearboth"></div>
			<?php require('file_list.php'); ?>
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