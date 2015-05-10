<?php
require_once('config/tank_config.php');
$version = "1.3.3b";

mysql_select_db($database_tankdb,$tankdb);

  global $tankdb;
   $cur_seq = -1;
  $drop_seq = -1;

  $cur_b = $_POST["cur_board"];
  $drop_b = $_POST["drop_board"];
  if (preg_match('/parent(\d+)/',$cur_b,$reg)) 
  	$cur_seq=$reg[1];
  if(preg_match('/parent(\d+)/',$drop_b,$reg))
    $drop_seq=$reg[1];
 //获得点击移动块的id号
  $selCurID = "SELECT * FROM tk_board WHERE board_seq=$cur_seq";
  $RS1 = mysql_query($selCurID, $tankdb) or die(mysql_error());
  $CurInfo=mysql_fetch_assoc($RS1);
  $cur_id = $CurInfo['board_id'];

  //获得目标块的id号
  $selDropID = "SELECT * FROM tk_board WHERE board_seq=$drop_seq";
  $RS2 = mysql_query($selDropID, $tankdb) or die(mysql_error());
  $DropInfo=mysql_fetch_assoc($RS2);
  $drop_id = $DropInfo['board_id'];

  //更新移动块的顺序编号
  $updateCur = "UPDATE tk_board SET board_seq = $drop_seq WHERE board_id=$cur_id";
  $RS3 = mysql_query($updateCur, $tankdb) or die(mysql_error());

  //更新目标块的顺序编号
  $updateCur = "UPDATE tk_board SET board_seq = $cur_seq WHERE board_id=$drop_id";
  $RS4 = mysql_query($updateCur, $tankdb) or die(mysql_error());

  //$BoardInfoRS = mysql_query($selBoardInfo, $tankdb) or die(mysql_error());

?>