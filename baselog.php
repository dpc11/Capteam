﻿<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php require_once('function/board_function.php'); ?>

<?php 
    $pid = "-1";
    if(isset($_GET['pid'])){
        $pid = $_GET['pid'];
    }
  
      if(isset($_GET['tk_stage_desc'])){
        $tk_stage_desc = $_GET['tk_stage_desc'];
    }
  mysql_select_db($database_tankdb, $tankdb);

    $uid=$_SESSION['MM_uid'];//获得用户id
    $qurnum="SELECT * FROM tk_board";
    $Result1 = mysql_query($qurnum, $tankdb) or die(mysql_error());

    $bnumpre=mysql_num_rows($Result1);
    $bnumnow=$bnumpre+1;
  $insertSQL="INSERT INTO tk_board (board_seq,board_pid,board_from,board_content,board_time)VALUES('$bnumnow','$pid','$uid','$tk_stage_desc',NOW())";

  $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());
  header("location:base.php?pid=$pid");
?>