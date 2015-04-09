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

$logtype = "-1";
if (isset($_GET['logtype'])) {
  $logtype = $_GET['logtype'];
}

$colname_DetailRS1 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS1 = $_GET['recordID'];
}
$colname_Recordset_log = $colname_DetailRS1;


$logvalue = GetSQLValueString($colname_Recordset_log, "int");

if($logtype == "-1" && $logvalue <> 0){
$logwhere = "csa_tb_backup3 = ".$logvalue." AND";
} else if ($logtype == "1" && $logvalue <> 0){
$logwhere = "csa_tb_backup2 = ".$logvalue." AND";
}else {
$logwhere ="";
}



$colmonth_log = date("m");
if (isset($_GET['logmonth'])) {
  $colmonth_log = $_GET['logmonth'];
}

$colyear_log = date("Y");
if (isset($_GET['logyear'])) {
  $colyear_log = $_GET['logyear'];
}

$colday_log = "";
if (isset($_GET['logday'])) {
  $colday_log = $_GET['logday'];
}

$coldate = $colyear_log.$colmonth_log.$colday_log;

//set_time_limit ( 10 );

$date = date('Y-m-d');
$filename = $multilingual_global_excelfile.$date.".csv";

// 输出Excel文件头，可把user.csv换成你要的文件名
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=$filename");
header('Cache-Control: max-age=0');

// 从数据库中获取数据，为了节省内存，不要把数据一次性读到内存，从句柄中一行一行读即可
mysql_select_db($database_tankdb, $tankdb);

$sql = sprintf("SELECT tbid,
 tk_display_name, 
 csa_tb_year,
 task_tpye,
 csa_text, 
 project_name,
 csa_tb_manhour,
 task_status,  
 csa_tb_text, 
 csa_tb_lastupdate   
 FROM tk_task_byday 
								inner join tk_project on tk_task_byday.csa_tb_backup3=tk_project.id 
								inner join tk_task_tpye on tk_task_byday.csa_tb_backup4=tk_task_tpye.id 
								inner join tk_status on tk_task_byday.csa_tb_status=tk_status.id 
								inner join tk_task on tk_task_byday.csa_tb_backup1=tk_task.TID 
								inner join tk_user on tk_task_byday.csa_tb_backup2=tk_user.uid 
WHERE $logwhere csa_tb_year LIKE %s ORDER BY csa_tb_year DESC", 
GetSQLValueString($coldate . "%", "text")
);
					
							
$stmt  = mysql_query($sql, $tankdb) or die(mysql_error());
  
// 打开PHP文件句柄，php://output 表示直接输出到浏览器
$fp = fopen('php://output', 'a');
  
// 输出Excel列名信息
$head = array("id","$multilingual_user_view_user","$multilingual_user_view_by","$multilingual_user_view_do","$multilingual_user_view_taskname","$multilingual_user_view_project2","$multilingual_user_view_cost","$multilingual_user_view_status","$multilingual_global_log","$multilingual_project_file_update");

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
        $rows[$i] = strip_tags(iconv('utf-8', 'gbk', $v));
    }
    fputcsv($fp, $rows);
}

?>