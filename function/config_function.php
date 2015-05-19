<?php 
$version = "1.3.3b";
$maxRows = 30;
$tasklevel = 0;
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

//get item
function get_item( $item ) {
	
	global $tankdb;
	$sql_item = "SELECT tk_item_value FROM tk_item WHERE tk_item_key = '$item'";

	$Recordset_item = mysql_query($sql_item, $tankdb) or die(mysql_error());
	$row_Recordset_item = mysql_fetch_assoc($Recordset_item);
	
	return $row_Recordset_item['tk_item_value'];
}


//check message
function check_message( $userid ) {
	global $tankdb;
	global $database_tankdb;

	$user_message_id = $_SESSION['MM_msg'];
	$count_message_SQL = sprintf("SELECT 
								COUNT(meid) as count_msg   
								FROM tk_message  							
					WHERE tk_mess_status = 1 AND tk_mess_touser = '$userid'"
									);//选择未读的消息
	//WHERE tk_mess_status = 1 AND tk_mess_touser = '$userid'"
	//WHERE meid > '$user_message_id' AND tk_mess_touser = '$userid'"
	$count_message_RS = mysql_query($count_message_SQL, $tankdb) or die(mysql_error());
	$row_count_message = mysql_fetch_assoc($count_message_RS);
	//$_SESSION['MM_msg_con'] = $row_count_message['count_msg'];
	return $row_count_message['count_msg'];
}

//strsToArray
function strsToArray($strs) { 
$result = array(); 
$array = array(); 
$strs = str_replace('，', ',', $strs); 
$strs = str_replace("n", ',', $strs); 
$strs = str_replace("rn", ',', $strs); 
$strs = str_replace(' ', ',', $strs); 
$array = explode(',', $strs); 
foreach ($array as $key => $value) { 
if ('' != ($value = trim($value))) { 
$result[] = $value; 
} 
} 
return $result; 
} 

//搜索二维数组
function in_2array($strs, $arr){
   $exist = 0;
   foreach($arr as $value){
     if(in_array($strs, $value)){
        $exist = 1;
        break;    //循环判断字符串是否存在于一位数组，存在则跳出  返回结果
     }
   }
   return $exist;
}
//二维数组去重
function array_unique_fb($array2D) { 
foreach ($array2D as $k=>$v) 
{ 
$v = join(",",$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串 
$temp[$k] = $v; 
} 
$temp = array_unique($temp); //去掉重复的字符串,也就是重复的一维数组 
foreach ($temp as $k => $v) 
{ 
$array=explode(",",$v); //再将拆开的数组重新组装 
$temp2[$k]["uid"] =$array[0]; 
$temp2[$k]["uname"] =$array[1]; 
} 
return $temp2; 
} 

//get pinyin
function getFirstLetter($str){     
	$fchar = ord($str{0});  
	if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($str{0});  
	@$s1 = iconv("UTF-8","gb2312", $str);  
	$s2 = iconv("gb2312","UTF-8", $s1);  
	if($s2 == $str){$s = $s1;}
	else{$s = $str;}  
	$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;  
	if($asc>=-20319 and $asc<=-20284)return "A";
if($asc>=-20283 and $asc<=-19776)return "B";
if($asc>=-19775 and $asc<=-19219)return "C";
if($asc>=-19218 and $asc<=-18711)return "D";
if($asc>=-18710 and $asc<=-18527)return "E";
if($asc>=-18526 and $asc<=-18240)return "F";
if($asc>=-18239 and $asc<=-17923)return "G";
if($asc>=-17922 and $asc<=-17418)return "H";
if($asc>=-17417 and $asc<=-16475)return "J";
if($asc>=-16474 and $asc<=-16213)return "K";
if($asc>=-16212 and $asc<=-15641)return "L";
if($asc>=-15640 and $asc<=-15166)return "M";
if($asc>=-15165 and $asc<=-14923)return "N";
if($asc>=-14922 and $asc<=-14915)return "O";
if($asc>=-14914 and $asc<=-14631)return "P";
if($asc>=-14630 and $asc<=-14150)return "Q";
if($asc>=-14149 and $asc<=-14091)return "R";
if($asc>=-14090 and $asc<=-13319)return "S";
if($asc>=-13318 and $asc<=-12839)return "T";
if($asc>=-12838 and $asc<=-12557)return "W";
if($asc>=-12556 and $asc<=-11848)return "X";
if($asc>=-11847 and $asc<=-11056)return "Y";
if($asc>=-11055 and $asc<=-10247)return "Z";
	return null;  
}  
 
function pinyin($zh){  
     $ret = "";  
     $s1 = iconv("UTF-8","gb2312", $zh);  
     $s2 = iconv("gb2312","UTF-8", $s1);  
     if($s2 == $zh){$zh = $s1;}  
     for($i = 0; $i < strlen($zh); $i++){  
         $s1 = substr($zh,$i,1);  
         $p = ord($s1);  
         if($p > 160){  
             $s2 = substr($zh,$i++,2);  
             $ret .= getFirstLetter($s2);  
         }else{  
             $ret .= $s1;  
         }  
     }  
     return $ret;  
}    

//get tree
function get_tree( $projectid ) {
global $tankdb;
global $database_tankdb;
/*开始操作数据库了，select语句*/
$viewstageSQL1="SELECT * from tk_stage WHERE tk_stage_delestatus=1 AND tk_stage_pid=$projectid";
//对数据库进行查询
mysql_select_db($database_tankdb, $tankdb);
$Result_stage = mysql_query($viewstageSQL1, $tankdb) or die(mysql_error());
$FoundTask = mysql_num_rows($Result_stage);
    
if (!$FoundTask) {
 return 0;    
}
//对查到的数据进行遍历
while($row_stage = mysql_fetch_array($Result_stage))
  {
    $pid = 0;//因为是阶段，所以父节点是0节点
    $today_date = date('Y-m-d');//今天的日期，用于计算项目状态
    //计算项目的状态
      if($today_date < $row_stage['tk_stage_st']){
        //表示项目还没有开始
        $str = "<div style='background-color: #FF6666; width:100%; text-align:center;'>阶段未开始</div>";
        $stage_statues = "阶段未开始";
      }elseif ($today_date > $row_stage['tk_stage_et']) {
        //表示项目已结结束
        $str = "<div style='background-color: #B3B3B3; width:100%; text-align:center;'>阶段已结束</div>";
        $stage_statues = "阶段已结束";
      }else{
        //表示项目正在进行中
        $str = "<div style='background-color: #6ABD78; width:100%; text-align:center;'>阶段进行中</div>";
        $stage_statues = "阶段进行中";
      }
  $str =  explode('background-color:', $str);
  $str =  explode('width:', $str[1]);
  $nodename = "<span style ='color:".$str[0]."'>■</span>"." [阶段]".$row_stage['tk_stage_title'];
  $nodetitle = $stage_statues;  
  $stage_id = $row_stage['stageid'];
  //插入
  $result[] = array('id'=>(100000+$row_stage['stageid']),'pid'=>$pid,'name'=>$nodename,'title'=>$nodetitle,);

  //这里应该加上项目的操作
 /*开始操作数据库了，select语句,返回该项目中所有阶段的id号和题目*/
  $viewtaskSQL1="SELECT * from tk_task,tk_status WHERE tk_task.csa_status = tk_status.id AND tk_task.csa_del_status=1 AND tk_task.csa_project_stage=$stage_id";
  //对数据库进行查询
  mysql_select_db($database_tankdb, $tankdb);
  $Result_task = mysql_query($viewtaskSQL1, $tankdb) or die(mysql_error());
  while($row_task = mysql_fetch_array($Result_task)){
      $pid = $stage_id+100000;//父节点是改阶段的menu id
      $str = $row_task['task_status_display'];
      $str =  explode('background-color:', $str);
      $str =  explode('width:', $str[1]);

      $nodename = "<span style ='color:".$str[0]."'>■</span>"." [任务]".$row_task['csa_text'];
      $nodetitle = $row_task['task_status'];
      $result[] = array('id'=>$row_task['tid'],'pid'=>$pid,'name'=>$nodename,'title'=>$nodetitle,);
  }
}

$str=json_encode($result);
return $str;
}
?>