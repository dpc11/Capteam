<?php require_once('config/tank_config.php'); 

$getjson = file_get_contents('php://input');
$dataarr =json_decode($getjson, true);
$token=$dataarr['token'];

$tab=$dataarr['tab'];

$uid = check_token($token);
if($uid <> 3){
    
    mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_sumtotal = sprintf("SELECT 
							COUNT(*) as count_prj   
							FROM tk_project 	
							WHERE project_to_user = %s", 
								GetSQLValueString($uid, "int")
								);
$Recordset_sumtotal = mysql_query($query_Recordset_sumtotal, $tankdb) or die(mysql_error());
$row_Recordset_sumtotal = mysql_fetch_assoc($Recordset_sumtotal);
$my_totalprj=$row_Recordset_sumtotal['count_prj'];

    $get_function = project_list( $uid, "project_lastupdate", "DESC", "0", $tab );

    $rearr = array(
'summprj'=>$my_totalprj, 	
'list'=>$get_function 	
);

$redata = json_encode($rearr);
echo $redata;
} else {
echo 3;
}
?>
