<?php
require_once('config/tank_config.php');
$version = "1.3.3b";

mysql_select_db($database_tankdb,$tankdb);

  global $tankdb;
   $cur_b = -1;

  $cur_b = $_POST["cur_board"];
  
 //获得点击便签的内容
  $selCurID = "SELECT * FROM tk_board WHERE board_id=$cur_b";
  $RS1 = mysql_query($selCurID, $tankdb) or die(mysql_error());
  $CurInfo=mysql_fetch_assoc($RS1);
  $cur_content = $CurInfo['board_content'];

  echo $cur_content;
?>