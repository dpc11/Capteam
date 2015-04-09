<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php 
$pagetabs = "allfile";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}

$projectlist = "-1";
if (isset($_GET['pl'])) {
  $projectlist = $_GET['pl'];
}

$project_id= "-1";
if (isset($_GET['projectID'])) {
  $project_id = $_GET['projectID'];
}

$project_name= "-1";
if (isset($_GET['projectNAME'])) {
  $project_name = $_GET['projectNAME'];
}

$folder_name= "-1";
if (isset($_GET['folderNAME'])) {
  $folder_name = $_GET['folderNAME'];
}

$pfiles = "-1"; //判断是否是项目文档
if (isset($_GET['pfile'])) {
  $pfiles = $_GET['pfile'];
}

$searchf = "-1"; //判断是否点击了搜索
if (isset($_GET['search'])) {
  $searchf = $_GET['search'];
}

if ($project_id <> "-1") {
  $inproject = " inner join tk_project on tk_document.tk_doc_class1=tk_project.id ";
} else { $inproject = " ";}

$filenames = "";
if (isset($_GET['filetitle'])) {
  $filenames = $_GET['filetitle'];
}

$fd = null; //判断是否是文件夹
if (isset($_GET['folder'])) {
  $fd = $_GET['folder'];
}

$projectpage = "-1"; //判断是否是项目列表
if (isset($_GET['projectpage'])) {
  $projectpage = $_GET['projectpage'];
}

$colname_DetailRS1 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS1 = $_GET['recordID'];
}

$currentPage = $_SERVER["PHP_SELF"];
$maxRows_DetailRS1 = 10;
$pageNum_DetailRS1 = 0;
if (isset($_GET['pageNum_DetailRS1'])) {
  $pageNum_DetailRS1 = $_GET['pageNum_DetailRS1'];
}
$startRow_DetailRS1 = $pageNum_DetailRS1 * $maxRows_DetailRS1;

if($pagetabs=="mcfile"){
$multilingual_breadcrumb_filelist = $multilingual_project_file_myfile;
}else if ($pagetabs=="mefile") {
$multilingual_breadcrumb_filelist = $multilingual_project_file_myeditfile;
}else if ($pagetabs=="allfile")  {
$multilingual_breadcrumb_filelist = $multilingual_project_file_allfile;
}

mysql_select_db($database_tankdb, $tankdb);
$query_DetailRS1 = sprintf("SELECT *, 
tk_user1.tk_display_name as tk_display_name1, 
tk_user2.tk_display_name as tk_display_name2 FROM tk_document 
inner join tk_user as tk_user1 on tk_document.tk_doc_create=tk_user1.uid  
inner join tk_user as tk_user2 on tk_document.tk_doc_edit=tk_user2.uid 
$inproject 
WHERE tk_document.docid = %s", GetSQLValueString($colname_DetailRS1, "int"));
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

mysql_select_db($database_tankdb, $tankdb);
$query_projectname = sprintf("SELECT * FROM tk_project 
inner join tk_user on tk_project.project_to_user=tk_user.uid 
WHERE tk_project.id = %s", GetSQLValueString($project_id, "int"));
$projectname = mysql_query($query_projectname, $tankdb) or die(mysql_error());
$row_projectname = mysql_fetch_assoc($projectname);

$fileid = $row_DetailRS1['tk_doc_class2'];
mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_pfilename = sprintf("SELECT * FROM tk_document WHERE docid = %s", GetSQLValueString($fileid, "int"));
$Recordset_pfilename = mysql_query($query_Recordset_pfilename, $tankdb) or die(mysql_error());
$row_Recordset_pfilename = mysql_fetch_assoc($Recordset_pfilename);
$totalRows_Recordset_pfilename = mysql_num_rows($Recordset_pfilename);

$maxRows_Recordset_file = 20;
$pageNum_Recordset_file = 0;
if (isset($_GET['pageNum_Recordset_file'])) {
  $pageNum_Recordset_file = $_GET['pageNum_Recordset_file'];
}
$startRow_Recordset_file = $pageNum_Recordset_file * $maxRows_Recordset_file;


if ($searchf == "1"){
$inprolist = "tk_doc_title LIKE %s AND tk_doc_backup1 <> 1";
$inprolists = "%" . $filenames . "%";
}else if ($colname_DetailRS1=="-1" && $project_id <> "-1" && $pagetabs == "allfile") {
  $inprolist = " tk_doc_class1 = %s  AND  tk_doc_class2 = 0 ";
  $inprolists = $project_id;
  
} else if ($pagetabs == "mcfile"){
$inprolist = " tk_doc_create = %s AND tk_doc_backup1 = 0 ";
$inprolists = $_SESSION['MM_uid'];
} 
 else if ($pagetabs == "mefile"){
$inprolist = " tk_log.tk_log_user = %s AND tk_log.tk_log_class = 2 AND tk_doc_backup1 = 0 ";
$inprolists = $_SESSION['MM_uid'];
} else { 
  $inprolist = " tk_doc_class2 = %s  ";
  $inprolists = $colname_DetailRS1;
} 
if($pagetabs == "mefile" ){
$where1 = "inner join tk_log on tk_document.docid=tk_log.tk_log_type";
$where2 = "GROUP BY tk_document.docid";
}else{
$where1 = "";
$where2 = "";
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_file = sprintf("SELECT * FROM tk_document 
inner join tk_user on tk_document.tk_doc_edit =tk_user.uid 
$where1 
WHERE $inprolist
								
								$where2 ORDER BY tk_doc_backup1 DESC, tk_doc_edittime DESC", 
								GetSQLValueString($inprolists, "text")
								);
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


if($pfiles==1){
$filepro=$project_id;
}else{
$filepro = "-1";
if (isset($row_DetailRS1['tk_doc_class1'])) {
  $filepro = $row_DetailRS1['tk_doc_class1'];
}
}

$filepid = "-1";
if (isset($row_DetailRS1['docid'])) {
  $filepid  = $row_DetailRS1['docid'];
}

if($filepid == "-1" && $pfiles=="1"){
$filepid = "0";
}

$host_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER["QUERY_STRING"];
$host_url=strtr($host_url,"&","!");

if ($projectpage == 1){ //显示项目列表
$maxRows_Recordset1 = get_item( 'maxrows_project' );
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;


$colinputtitle_Recordset1 = "";
if (isset($_GET['inputtitle'])) {
  $colinputtitle_Recordset1 = $_GET['inputtitle'];
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT * FROM tk_project 
							
							inner join tk_user on tk_project.project_to_user=tk_user.uid
							WHERE project_name LIKE %s ORDER BY tk_project.project_lastupdate DESC",  
GetSQLValueString("%" . $colinputtitle_Recordset1 . "%", "text"));
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysql_query($query_Recordset1);
  $totalRows_Recordset1 = mysql_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;

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
?>
<?php require('head.php'); ?>
<link href="skin/themes/base/custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script type="text/javascript" src="srcipt/js.js"></script>
<script type="text/javascript" src="srcipt/jqueryd.js"></script>
<div class="subnav">
<div class="float_left" style="width:50%">
<div class="btn-group">
<a type="button" class="btn btn-default btn-sm <?php if($pagetabs == "allfile") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=allfile" >
<?php echo $multilingual_project_file_allfile;?>
</a>

<a type="button" class="btn btn-default btn-sm <?php if($pagetabs == "mcfile") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=mcfile" >
<?php echo $multilingual_project_file_myfile;?>
</a>

<a type="button" class="btn btn-default btn-sm <?php if($pagetabs == "mefile") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=mefile" >
<?php echo $multilingual_project_file_myeditfile;?>
</a>


</div>
</div>

<?php if($_SESSION['MM_rank'] > "1" && $projectpage <> 1) { ?> 
 <?php if($colname_DetailRS1 == "-1" || $fd == "1") { ?> 
  <?php if($pagetabs=="allfile"){ ?>
<div class="float_right" >
<button type="button" class="btn btn-default btn-sm" name="addfolder" id="addfolder" onclick="addfolder();">
<span class="glyphicon glyphicon-folder-open"></span> <?php echo $multilingual_project_file_addfolder; ?>
</button>

<button type="button" class="btn btn-default btn-sm" name="addfile" id="addfile" onclick="window.open('file_add.php?projectid=<?php echo $filepro; ?>&pid=<?php echo $filepid; ?>
<?php if ( $pfiles== "1") {echo "&pfile=1"; }?>&pagetab=<?php echo $pagetabs;?>')" >
<span class="glyphicon glyphicon-file"></span> <?php echo $multilingual_project_file_addfile; ?>
</button>
</div>
<?php } else {?>  
 <div class="float_right" >
 <button type="button" class="btn btn-default btn-sm" name="addfile1" id="addfile1" onclick="window.open('file_add.php?projectid=<?php echo $filepro; ?>&pid=<?php echo $filepid; ?>
<?php if ( $pfiles== "1") {echo "&pfile=1";}?>&pagetab=<?php echo $pagetabs;?>')">
<span class="glyphicon glyphicon-file"></span> <?php echo $multilingual_project_file_addfile; ?>
</button>
</div>
<?php } ?>
<?php } ?>
<?php } ?>	  



</div>
<div class="clearboth"></div>
<div class="pagemargin">

<?php require('control_file.php'); ?>
</div>
<?php require('foot.php'); ?>

</body>
</html>