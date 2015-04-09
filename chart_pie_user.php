<?php
include_once ('config/tank_config.php');  
include_once ('chart/php-ofc-library/open-flash-chart.php'); 

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

$title = new title(  ); 
$title->set_style("font-size:12px; font-weight:bold;"); 
$pie = new pie(); 
$pie->set_alpha(0.6); 
$pie->set_start_angle( 32 ); 
$pie->add_animation( new pie_fade() ); 
$pie->set_tooltip( '#val# '.$multilingual_project_hour  ); 
$pie->set_colours( array('#99C754','#54C7C5','#999999','#996699','#009900','#77C600','#ff7400', 
'#FF0000','#4096ee','#c79810') ); 

$projectid = $_GET['recordID'];

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_sumtotal = sprintf("SELECT 
							sum(csa_tb_manhour) as sum_hour 
							FROM tk_task_byday 								
							
							WHERE csa_tb_backup2 = %s", GetSQLValueString($projectid, "text"));
$Recordset_sumtotal = mysql_query($query_Recordset_sumtotal, $tankdb) or die(mysql_error());
$row_Recordset_sumtotal = mysql_fetch_assoc($Recordset_sumtotal);
$t=$row_Recordset_sumtotal['sum_hour']; 

$sql=sprintf("SELECT * , sum(csa_tb_manhour) as summ1 FROM tk_task_byday 								
							inner join tk_task_tpye on tk_task_byday.csa_tb_backup4=tk_task_tpye.id								
								WHERE csa_tb_backup2 = %s GROUP BY csa_tb_backup4 ORDER BY summ1 DESC", GetSQLValueString($projectid, "text")); 

$query = mysql_query($sql, $tankdb) or die(mysql_error());

while($row=mysql_fetch_assoc($query)){ 
    $total=$row['summ1']; 
    if(!empty($t)){ 
        $v=round($total/$t,2)*100; 
    }else{ 
        $v=0; 
    } 
    $dis[]=array("name"=>$row['task_tpye'],"total"=>$row['summ1'],"v"=>$v); 
} 
$len_dis=count($dis); 
for($i=0;$i<$len_dis;$i++){ 
    $dis_value[]=new pie_value(intval($dis[$i]['total']),$dis[$i]['name'].":".$dis[$i]['total'].$multilingual_project_hour."(".$dis[$i]['v']."%)"); 
} 
$pie->set_values($dis_value); 
 
$chart = new open_flash_chart(); 
$chart->set_title( $title ); 
$chart->add_element( $pie ); 
$chart->x_axis = null; 
$chart->bg_colour = ( '#FFFFFF' ); 
echo $chart->toPrettyString(); 

?>
<?php echo $projectid; ?>