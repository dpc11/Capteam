<?php 

	$version = "1.3.3b";
	$maxRows = 30;
	$tasklevel = 0;	
	$project_list_nums=0;
	mysql_select_db($database_tankdb,$tankdb);

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
	
	//根据搜索条件查询所有符合条件的项目
	function get_project_list($pagetabs,$prjtouser,$today_date,$colinputtitle_Recordset1,$orderlist,$sortlist,$startRow_Recordset1,$maxRows_Recordset1){

		$where="";
		if($pagetabs == "jprj"){
			//我参与的
			$where .= " t.tk_team_uid = $prjtouser AND p.project_del_status != -1 AND t.tk_team_ulimit != 3 ";
		}else if($pagetabs == "mprj"){
			//我负责的项目
			$where .= " p.project_to_user = $prjtouser AND p.project_del_status != -1 ";
		}else if($pagetabs == "allprj"){
			//所有项目
			$where .= " t.tk_team_uid = $prjtouser AND p.project_del_status != -1";
		}
		
		if($colinputtitle_Recordset1!=""){
			$colprt = GetSQLValueString("%%" . str_replace("%","%%",$colinputtitle_Recordset1) . "%%", "text");
			$where .= "  AND p.project_name LIKE $colprt ";			
		}
		global $tankdb;
		//修改后的sql语句					
		$query_Recordset1 = sprintf("
		SELECT
			p.id,p.project_name,p.project_text,p.project_start,p.project_end,p.project_to_user,user.tk_display_name,p.project_lastupdate,p.project_del_status,p.project_create_time 
		
		FROM 
			tk_project p, tk_team t, tk_user user 
		
		WHERE p.id=t.tk_team_pid AND user.uid=t.tk_team_uid AND 
		
			$where 
			
		GROUP BY p.id ORDER BY p.%s %s", 
		
		GetSQLValueString($sortlist, "defined", $sortlist, "NULL"),
		GetSQLValueString($orderlist, "defined", $orderlist, "NULL"));

		$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
		
		$Recordset1 = mysql_query($query_limit_Recordset1, $tankdb) or die(mysql_error());
		  
		return $Recordset1;
	}
	
	//返回上面查到的所有符合条件的的项目数量
	function get_project_list_num($pagetabs,$prjtouser,$today_date,$colinputtitle_Recordset1,$orderlist,$sortlist,$startRow_Recordset1,$maxRows_Recordset1){

		$where="";
		if($pagetabs == "jprj"){
			//我参与的
			$where .= " t.tk_team_uid = $prjtouser AND p.project_del_status != -1 AND t.tk_team_ulimit != 3";
		}else if($pagetabs == "mprj"){
			//我负责的项目
			$where .= " p.project_to_user = $prjtouser AND p.project_del_status != -1";
		}else if($pagetabs == "allprj"){
			//所有项目
			$where .= " t.tk_team_uid = $prjtouser";
		}
		
		if($colinputtitle_Recordset1!=""){
			$colprt = GetSQLValueString("%%" . str_replace("%","%%",$colinputtitle_Recordset1) . "%%", "text");
			$where .= "  AND p.project_name LIKE $colprt ";			
		}
		global $tankdb;
		//修改后的sql语句					
		$query_Recordset1 = sprintf("
		SELECT
			p.id,p.project_name,p.project_text,p.project_start,p.project_end,p.project_to_user,user.tk_display_name,p.project_lastupdate,p.project_del_status,p.project_create_time 
		
		FROM 
			tk_project p, tk_team t, tk_user user 
		
		WHERE p.id=t.tk_team_pid AND user.uid=t.tk_team_uid AND 
		
			$where 
			
		GROUP BY p.id ORDER BY p.%s %s", 
		
		GetSQLValueString($sortlist, "defined", $sortlist, "NULL"),
		GetSQLValueString($orderlist, "defined", $orderlist, "NULL"));
		
		$Recordset1 = mysql_query($query_Recordset1, $tankdb) or die(mysql_error());		
		$project_list_nums = mysql_num_rows( mysql_query($query_Recordset1));
		
		return $project_list_nums;
	}
	
	
    //根据项目id获得项目信息的数据库操作
    function get_project_by_id($project_id){
        global $tankdb;
        global $database_tankdb;
        mysql_select_db($database_tankdb, $tankdb);
        $query_project =  sprintf("SELECT * FROM tk_project WHERE id = %s",GetSQLValueString($project_id, "int"));  
        $project = mysql_query($query_project, $tankdb) or die(mysql_error());
        $row_project = mysql_fetch_assoc($project);
        
		return $row_project;
    }

    //获取某个用户负责的项目的数量
    function get_my_total_project_num($user_id){
        global $tankdb;
        global $database_tankdb;
        mysql_select_db($database_tankdb, $tankdb);
        $query_Recordset_sumtotal = sprintf("SELECT COUNT(*) as count_prj   
                                             FROM tk_project         
                                             WHERE project_to_user = %s", 
                                             GetSQLValueString($user_id, "int"));
        $Recordset_sumtotal = mysql_query($query_Recordset_sumtotal, $tankdb) or die(mysql_error());
        $row_Recordset_sumtotal = mysql_fetch_assoc($Recordset_sumtotal);
        $my_totalprj=$row_Recordset_sumtotal['count_prj'];
        return $my_totalprj;
    }

    function get_user_disName($user_id)
    {
    	global $tankdb;
        global $database_tankdb;
        mysql_select_db($database_tankdb, $tankdb);
        $selDisName = "SELECT * From tk_user WHERE uid=$user_id";
        $RS = mysql_query($selDisName, $tankdb) or die(mysql_error());
        $row_Recordset_sumtotal = mysql_fetch_assoc($RS);
        $display_name=$row_Recordset_sumtotal['tk_display_name'];
        return $display_name;
    }

?>
