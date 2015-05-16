<?php require_once('config/tank_config.php'); ?>
<?php  
	header("Content-type: text/html;charset=GBK");//输出编码,避免中文乱码  
	$pid=$_GET['pid'];  
      
	mysql_query("set names 'GBK'");  
	mysql_select_db($database_lr, $lr);  
	$sql="select * from tk_stage where tk_stage_pid='$pid'";  
	$result=mysql_query($sql);  
		
	echo '<option value="" selected="selected">所有阶段</option>';
	while($rows=mysql_fetch_array($result)){  
		echo "<option value=".$rows['stageid'].">";  
		echo $rows['tk_stage_title'];  
		echo "</option>n";  
	}  
?> 