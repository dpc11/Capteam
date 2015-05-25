<?php
require_once('config/tank_config.php');
$version = "1.3.3b";

mysql_select_db($database_tankdb,$tankdb);

  global $tankdb;
   $cur_seq = -1;
   $act_seq = -1;

  $cur_b = $_POST["cur_board"];
  $act_b = $_POST["act_board"];
  $tag = $_POST["tag"];
  if (preg_match('/parent(\d+)/',$cur_b,$reg)) 
  	$cur_seq=$reg[1];
  if(preg_match('/parent(\d+)/',$act_b,$reg))
    $act_seq=$reg[1];
 //获得点击移动块的id号
  $selCurID = "SELECT * FROM tk_board WHERE board_seq=$cur_seq";
  $RS1 = mysql_query($selCurID, $tankdb) or die(mysql_error());
  $CurInfo=mysql_fetch_assoc($RS1);
  $cur_id = $CurInfo['board_id'];

  //获得目标块的id号
  $selDropID = "SELECT * FROM tk_board WHERE board_seq=$act_seq";
  $RS2 = mysql_query($selDropID, $tankdb) or die(mysql_error());
  $DropInfo=mysql_fetch_assoc($RS2);
  $drop_id = $DropInfo['board_id'];

  if($tag == 0)
  {
    $updateAdd = "UPDATE tk_board SET board_seq = board_seq+1 WHERE board_seq > $act_seq AND board_seq < $cur_seq";
    $RS3 = mysql_query($updateAdd, $tankdb) or die(mysql_error());

    $updateCur = "UPDATE tk_board SET board_seq = $act_seq+1 WHERE board_id = $cur_id";
    $RS4 = mysql_query($updateCur, $tankdb) or die(mysql_error());
  }
  else
  {
    $updateAdd = "UPDATE tk_board SET board_seq = board_seq+1 WHERE board_seq >= $act_seq AND board_seq < $cur_seq";
    $RS3 = mysql_query($updateAdd, $tankdb) or die(mysql_error());

    $updateCur = "UPDATE tk_board SET board_seq = $act_seq WHERE board_id = $cur_id";
    $RS4 = mysql_query($updateCur, $tankdb) or die(mysql_error());
  }

  //$BoardInfoRS = mysql_query($selBoardInfo, $tankdb) or die(mysql_error());

?>