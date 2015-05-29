<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php require_once('function/board_function.php'); ?>

<?php 
    $uid = "-1";
    if(isset($_GET['uid'])){
        $uid = $_GET['uid'];
    }
    $cs_uid=$_POST['date'];
    mysql_select_db($database_tankdb, $tankdb);

    $uid=$_SESSION['MM_uid'];//获得用户id

    $day=date("w",strtotime($cs_uid));
    if($day!=1){
        echo "<script type='text/javascript'>alert('您所选择的学期开始时间必须是周一，请返回上一页修改您选择的学期开始时间');history.go(-1);</script>";
    }else{
       mysql_select_db($database_tankdb, $tankdb);
       $sql="INSERT INTO tk_course_schedule (cs_uid,cs_firstday) VALUES('$uid','$cs_uid')"; 
       $Result1 = mysql_query($sql, $tankdb) or die(mysql_error());
       header("location:schedule_course.php");
    }
   // echo $uid;
    //echo "STRING";
   // echo $cs_uid;

    //$qurnum="SELECT * FROM tk_board";
   // $Result1 = mysql_query($qurnum, $tankdb) or die(mysql_error());

   // $bnumpre=mysql_num_rows($Result1);
   // $bnumnow=$bnumpre+1;
   
      //  $insertSQL="INSERT INTO tk_board (board_seq,board_type,board_pid,board_from,board_content,board_time)VALUES('$bnumnow',2,-1,'$uid','$tk_stage_desc',NOW())";
    

  //$Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());
  

?>