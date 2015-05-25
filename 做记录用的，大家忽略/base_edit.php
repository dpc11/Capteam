<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php require_once('function/board_function.php'); ?>

<?php 
  $pid = "-1";
    if(isset($_GET['pid'])){
        $pid = $_GET['pid'];
    }

      if(isset($_POST['edit_board_id'])){
        $editID = $_POST['edit_board_id'];
    }
  
      if(isset($_POST['tk_edit_content'])){
        $tk_stage_desc = $_POST['tk_edit_content'];
    }
  mysql_select_db($database_tankdb, $tankdb);

    $qurnum="UPDATE tk_board SET board_content='$tk_stage_desc' WHERE board_id=$editID";
    $Result1 = mysql_query($qurnum, $tankdb) or die(mysql_error());

   header("location:board_view.php?pid=$pid");
?>