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

/*
$project_name= "-1";
if (isset($_GET['projectNAME'])) {
  $project_name = $_GET['projectNAME'];
}

$pfiles = "-1"; //判断是否是项目文档
if (isset($_GET['pfile'])) {
  $pfiles = $_GET['pfile'];
}

$fd = null; //判断是否是文件夹
if (isset($_GET['folder'])) {
  $fd = $_GET['folder'];
}
$folder_name= "-1";
if (isset($_GET['folderNAME'])) {
  $folder_name = $_GET['folderNAME'];
}

*/
$searchf = "-1"; //判断是否点击了搜索
if (isset($_GET['search'])) {
  $searchf = $_GET['search'];
}

if ($project_id <> "-1") {
  $inproject = " inner join tk_project on tk_document.tk_doc_pid=tk_project.id ";
  $inprojects = " and tk_document.tk_doc_pid=tk_project.id ";
}else{ 
	$inproject = "";
	$inprojects = "";
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
		FROM tk_document 
		inner join tk_user as tk_user1 on tk_document.tk_doc_create=tk_user1.uid  
		$inproject 
		WHERE tk_document.tk_doc_parentdocid = %s and tk_doc_title LIKE %s  and tk_doc_del_status=1 ", 
		GetSQLValueString($colname_DetailRS1, "int"),$inprolists);
	}else{
		$query_DetailRS1 = sprintf("SELECT *, 
		tk_user1.tk_display_name as tk_display_name1
		FROM tk_document 
		inner join tk_user as tk_user1 on tk_document.tk_doc_create=tk_user1.uid  
		$inproject 
		WHERE tk_document.tk_doc_parentdocid = %s and tk_doc_title LIKE %s and (tk_doc_create = %s or tk_doc_backup1=1)  and tk_doc_del_status=1 ",
		GetSQLValueString($colname_DetailRS1, "int"),$inprolists,$_SESSION['MM_uid']);
	
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
	$inproject 
	WHERE tk_document.tk_doc_parentdocid = %s  and tk_doc_del_status=1 
	$inprojects", GetSQLValueString($colname_DetailRS1, "int"));
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
} else if ($pagetabs == "mcfile"){
	//对应文件下的所有文件夹和文件  -1表示没有选中
	mysql_select_db($database_tankdb, $tankdb);
	$query_DetailRS1 = sprintf("SELECT *, 
	tk_user1.tk_display_name as tk_display_name1
	FROM tk_document 
	inner join tk_user as tk_user1 on tk_document.tk_doc_create=tk_user1.uid  
	$inproject 
	WHERE tk_document.tk_doc_parentdocid = %s  and tk_doc_del_status=1 
	$inprojects
	and (tk_doc_create = %s or tk_doc_backup1=1)", 
	GetSQLValueString($colname_DetailRS1, "int"),
	$_SESSION['MM_uid']);
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

/*
$maxRows_Recordset_file = 20;
$pageNum_Recordset_file = 0;
if (isset($_GET['pageNum_Recordset_file'])) {
  $pageNum_Recordset_file = $_GET['pageNum_Recordset_file'];
}
$startRow_Recordset_file = $pageNum_Recordset_file * $maxRows_Recordset_file;


if ($searchf == "1"){
	$inprolist = " where tk_doc_title LIKE %s";
	$inprolists = "%" . $filenames . "%";
}else if ($colname_DetailRS1=="-1" && $project_id <> "-1" && $pagetabs == "allfile") {
  $inprolist = " where tk_doc_pid = %s  AND  tk_doc_parentdocid = 0 ";
  $inprolists = $project_id;
} else if ($pagetabs == "mcfile"){
	$inprolist = " where tk_doc_create = %s AND tk_doc_backup1 = 0 ";
	$inprolists = $_SESSION['MM_uid'];
} 

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_file = sprintf("SELECT * FROM tk_document 
inner join tk_user on tk_document.tk_doc_create =tk_user.uid 
$inprolist
ORDER BY tk_doc_lastupdate DESC ", GetSQLValueString($inprolists, "text"));
$query_limit_Recordset_file = sprintf("%s LIMIT %d, %d", $query_Recordset_file, $startRow_Recordset_file, $maxRows_Recordset_file);
$Recordset_file = mysql_query($query_limit_Recordset_file, $tankdb) or die(mysql_error());
$row_Recordset_file = mysql_fetch_assoc($Recordset_file);

if (isset($_GET['totalRows_Recordset_file'])) {
  $totalRows_Recordset_file = $_GET['totalRows_Recordset_file'];
} else {
  $all_Recordset_file = mysql_query($query_Recordset_file);
  $totalRows_Recordset_file = mysql_num_rows($all_Recordset_file);
}
$totalPages_Recordset_file = ceil($totalRows_Recordset_file/$maxRows_Recordset_file)-1;

$queryString_Recordset_file = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_file") == false && 
        stristr($param, "totalRows_Recordset_file") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_file = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_file = sprintf("&totalRows_Recordset_file=%d%s", $totalRows_Recordset_file, $queryString_Recordset_file);
*/
/*
$docid = $colname_DetailRS1;
$maxRows_Recordset_actlog = 10;
$pageNum_Recordset_actlog = 0;
if (isset($_GET['pageNum_Recordset_actlog'])) {
  $pageNum_Recordset_actlog = $_GET['pageNum_Recordset_actlog'];
}
$startRow_Recordset_actlog = $pageNum_Recordset_actlog * $maxRows_Recordset_actlog;

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_actlog = sprintf("SELECT * FROM tk_log 
inner join tk_user on tk_log.tk_log_user =tk_user.uid 
								 WHERE tk_log_type = %s AND tk_log_class = 2 
								
								ORDER BY tk_log_time DESC", 
								GetSQLValueString($docid, "text")
								);
$query_limit_Recordset_actlog = sprintf("%s LIMIT %d, %d", $query_Recordset_actlog, $startRow_Recordset_actlog, $maxRows_Recordset_actlog);
$Recordset_actlog = mysql_query($query_limit_Recordset_actlog, $tankdb) or die(mysql_error());
$row_Recordset_actlog = mysql_fetch_assoc($Recordset_actlog);

if (isset($_GET['totalRows_Recordset_actlog'])) {
  $totalRows_Recordset_actlog = $_GET['totalRows_Recordset_actlog'];
} else {
  $all_Recordset_actlog = mysql_query($query_Recordset_actlog);
  $totalRows_Recordset_actlog = mysql_num_rows($all_Recordset_actlog);
}
$totalPages_Recordset_actlog = ceil($totalRows_Recordset_actlog/$maxRows_Recordset_actlog)-1;

$queryString_Recordset_actlog = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_actlog") == false && 
        stristr($param, "totalRows_Recordset_actlog") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_actlog = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_actlog = sprintf("&totalRows_Recordset_actlog=%d%s", $totalRows_Recordset_actlog, $queryString_Recordset_actlog);
*/
/*
if($pfiles==1){
$filepro=$project_id;
}else{
$filepro = "-1";
if (isset($row_DetailRS1['tk_doc_pid'])) {
  $filepro = $row_DetailRS1['tk_doc_pid'];
}
}

$filepid = "-1";
if (isset($row_DetailRS1['docid'])) {
  $filepid  = $row_DetailRS1['docid'];
}

if($filepid == "-1" && $pfiles=="1"){
$filepid = "0";
}
*/
$host_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER["QUERY_STRING"];
$host_url=strtr($host_url,"&","!");

/*
if ($projectpage == 1){ //显示项目列表
$maxRows_Recordset1 = get_item( 'maxrows_project' );
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;


$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset1") == false && 
        stristr($param, "totalRows_Recordset1") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);
}
*/

?>
<?php require('head.php'); ?>
<link href="skin/themes/base/custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script type="text/javascript" src="srcipt/js.js"></script>
<script type="text/javascript" src="srcipt/jqueryd.js"></script>
<div class="subnav">

<!--所有文件，我创建的文件-->
<div class="float_left" style="width:50%">
<div class="btn-group">
<a type="button" class="btn btn-default btn-sm <?php if($pagetabs == "allfile") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=allfile" >
<?php echo $multilingual_project_file_allfile;?>
</a>

<a type="button" class="btn btn-default btn-sm <?php if($pagetabs == "mcfile") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=mcfile" >
<?php echo $multilingual_project_file_myfile;?>
</a>

</div>
</div>

<!--新建文档、文件夹-->
<?php if ( $colname_DetailRS1 <> "-1" && $project_id <> "-1" ) { // 如果是一级页面不显示 ?>
<div class="float_right" >
<button type="button" class="btn btn-default btn-sm" name="addfolder" id="addfolder" onclick="addfolder();">
<span class="glyphicon glyphicon-folder-open"></span> <?php echo $multilingual_project_file_addfolder; ?>
</button>

<button type="button" class="btn btn-default btn-sm" name="addfile" id="addfile" onclick="window.open('file_add.php?projectid=<?php echo $project_id; ?>&pid=<?php echo $colname_DetailRS1; ?>&pagetab=<?php echo $pagetabs;?>')" >
<span class="glyphicon glyphicon-file"></span> <?php echo $multilingual_project_file_addfile; ?>
</button>
</div>
<?php } ?>

</div>
<div class="clearboth"></div>
<div class="pagemargin">

<?php require('control_file.php'); ?>
</div>
<?php require('foot.php'); ?>

</body>
</html>