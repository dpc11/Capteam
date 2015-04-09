<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php
//ini_set('display_errors',0);
 
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
$colname_Recordset1 = $_SESSION['MM_uid'];
if (isset($_GET['select4'])) {
  $colname_Recordset1 = $_GET['select4'];
}

$colfromuser_Recordset1 = "%";
if (isset($_GET['select2'])) {
  $colfromuser_Recordset1 = $_GET['select2'];
}

$colmonth_Recordset1 = date("m");
if (isset($_GET['textfield'])) {
  $colmonth_Recordset1 = $_GET['textfield'];
}

$colyear_Recordset1 = date("Y");
if (isset($_GET['select_year'])) {
  $colyear_Recordset1 = $_GET['select_year'];
}

if ($colyear_Recordset1 == "--"){
$startday = "1975-09-23";
$endday = "3000-13-31";
} else if ($colmonth_Recordset1 == "--"){
$startday = $colyear_Recordset1."-01-01";
$endday = $colyear_Recordset1."-12-31";
} else {
$startday = $colyear_Recordset1."-".$colmonth_Recordset1."-01";
$endday = $colyear_Recordset1."-".$colmonth_Recordset1."-31";
}

$colprt_Recordset1 = "";
if (isset($_GET['select_prt'])) {
  $colprt_Recordset1 = $_GET['select_prt'];
}

$coltemp_Recordset1 = "";
if (isset($_GET['select_temp'])) {
  $coltemp_Recordset1 = $_GET['select_temp'];
}

$colstatus_Recordset1 = "";
if (isset($_GET['select_st'])&& $_GET['select_st'] <>1) {
  $colstatus_Recordset1 = $_GET['select_st'];
}else{ $colstatus_Recordset1 = "";}

$colstatusf_Recordset1 = "+";
if (isset($_GET['select_st'])&& $_GET['select_st'] ==1 ) {
  $colstatusf_Recordset1 = $multilingual_dd_status_stfinish;
}

$coltype_Recordset1 = "";
if (isset($_GET['select_type'])) {
  $coltype_Recordset1 = $_GET['select_type'];
}


$colproject_Recordset1 = "";
if (isset($_GET['select_project'])) {
  $colproject_Recordset1 = $_GET['select_project'];
}


$colinputid_Recordset1 = "";
if (isset($_GET['inputid'])) {
  $colinputid_Recordset1 = $_GET['inputid'];
}

$colinputtitle_Recordset1 = "";
if (isset($_GET['inputtitle'])) {
  $colinputtitle_Recordset1 = $_GET['inputtitle'];
}

$colcreate_Recordset1 = "%";
if (isset($_GET['create_by'])) {
  $colcreate_Recordset1 = $_GET['create_by'];
}

if($colyear_Recordset1 == "--"){
$YEAR = "0000";
} else {
$YEAR = $colyear_Recordset1;
}
if($colmonth_Recordset1 == "--"){
$MONTH = "00";
} else {
$MONTH = $colmonth_Recordset1;
} 

$colprt = "%" . $colprt_Recordset1 . "%";
$coltemp = "%" . $coltemp_Recordset1 . "%";
$colstatus ="%" . $colstatus_Recordset1 . "%";
$colstatusf ="%" . $colstatusf_Recordset1 . "%";
$coltype ="%" . $coltype_Recordset1 . "%";
$colproject ="%" . $colproject_Recordset1 . "%";
$colinputid ="%" . $colinputid_Recordset1 . "%";
$colinputtitle ="%" . $colinputtitle_Recordset1 . "%";

//set_time_limit ( 10 );

$date = date('Y-m-d');
$filename = $multilingual_global_excelfile.$date.".csv";

// 输出Excel文件头，可把user.csv换成你要的文件名
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=$filename");
header('Cache-Control: max-age=0');

// 从数据库中获取数据，为了节省内存，不要把数据一次性读到内存，从句柄中一行一行读即可
mysql_select_db($database_tankdb, $tankdb);
$sql = sprintf("SELECT TID, 
							
							tk_project.project_name as project_name_prt, 
							task_tpye, 
							csa_text, 
							tk_user1.tk_display_name as tk_display_name1, 
							tk_user2.tk_display_name as tk_display_name2, 
							task_status,
							csa_priority,
							csa_temp,
							csa_plan_st,
							csa_plan_et,
							csa_remark1 
							
							FROM tk_task  
							inner join tk_task_tpye on tk_task.csa_type=tk_task_tpye.id
							inner join tk_project on tk_task.csa_project=tk_project.id
							
							inner join tk_user as tk_user1 on tk_task.csa_to_user=tk_user1.uid 
							inner join tk_user as tk_user2 on tk_task.csa_from_user=tk_user2.uid 
							
							inner join tk_status on tk_task.csa_remark2=tk_status.id
							
							WHERE tk_task.csa_to_user LIKE %s 
							AND tk_task.csa_from_user LIKE %s 
							AND tk_task.csa_priority LIKE %s 
							AND tk_task.csa_temp LIKE %s 

							AND tk_status.task_status LIKE %s 
							AND tk_status.task_status NOT LIKE %s 

							AND tk_task.csa_type LIKE %s 
							AND tk_task.csa_project LIKE %s 
							AND tk_task.TID LIKE %s 
							AND tk_task.csa_text LIKE %s
							AND tk_task.csa_create_user LIKE %s

							AND (tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st >=%s
 							AND tk_task.csa_plan_et <=%s)
														
							ORDER BY csa_project ASC", 
							
							GetSQLValueString($colname_Recordset1 , "text"), 
							GetSQLValueString($colfromuser_Recordset1 , "text"),  
							GetSQLValueString("%" . $colprt_Recordset1 . "%", "text"), 
							GetSQLValueString("%" . $coltemp_Recordset1 . "%", "text"), 
							GetSQLValueString("%" . $colstatus_Recordset1 . "%", "text"),  
							GetSQLValueString("%" . $colstatusf_Recordset1 . "%", "text"),  
							GetSQLValueString("%" . $coltype_Recordset1 . "%", "text"),
							GetSQLValueString("%" . $colproject_Recordset1 . "%", "text"),
							GetSQLValueString("%" . $colinputid_Recordset1 . "%", "text"),
							GetSQLValueString("%" . $colinputtitle_Recordset1 . "%", "text"),
							GetSQLValueString($colcreate_Recordset1 , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text")
							);
						
							
$stmt  = mysql_query($sql, $tankdb) or die(mysql_error());
  
// 打开PHP文件句柄，php://output 表示直接输出到浏览器
$fp = fopen('php://output', 'a');
  
// 输出Excel列名信息
$head = array("id","$multilingual_default_task_project","$multilingual_default_task_type","$multilingual_default_task_title","$multilingual_default_task_to","$multilingual_default_task_from","$multilingual_default_task_status","$multilingual_default_task_priority","$multilingual_default_tasklevel","$multilingual_default_task_planstart","$multilingual_default_task_planend","$multilingual_default_task_description");

foreach ($head as $i => $v) {
    // CSV的Excel支持GBK编码，一定要转换，否则乱码
    $head[$i] = iconv('utf-8', 'gbk', $v);
}
  
// 将数据通过fputcsv写到文件句柄
fputcsv($fp, $head);
  
// 计数器
$cnt = 0;
// 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
$limit = 100000;



// 逐行取出数据，不浪费内存

while($row=mysql_fetch_assoc($stmt)){ 
  
    $cnt ++;
   if ($limit == $cnt) { //刷新一下输出buffer，防止由于数据过多造成问题
        ob_flush();
        flush();
        $cnt = 0;
    }
    foreach ($row as $i => $v) {
        $rows[$i] =  strip_tags(iconv('utf-8', 'gbk', $v));
    }
    fputcsv($fp, $rows);
}

?>