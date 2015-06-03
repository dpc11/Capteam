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

    $isset="SELECT *FROM tk_course_schedule WHERE cs_uid='$uid'";
     $ResultForIsset = mysql_query($isset, $tankdb) or die(mysql_error());
    $findnum=mysql_num_rows($ResultForIsset);
 if($day!=1){
        echo "<script type='text/javascript'>alert('您所选择的学期开始时间必须是周一，请返回上一页修改您选择的学期开始时间');history.go(-1);</script>";
    }else{
    if($findnum==1){
           

     echo "<script type='text/javascript'>alert(
         '您已经选择了学期开始时间，如果修改该时间，您课程表中的课程将会随之做出相应修改')</script>";
          
       mysql_select_db($database_tankdb, $tankdb);
       $sql="UPDATE  tk_course_schedule SET cs_firstday='$cs_uid' WHERE cs_uid='$uid'"; 
       $Result1 = mysql_query($sql, $tankdb) or die(mysql_error());
       header("location:schedule_course.php");
    } else{
       mysql_select_db($database_tankdb, $tankdb);
       $sql="INSERT INTO tk_course_schedule (cs_uid,cs_firstday) VALUES('$uid','$cs_uid')"; 
       $Result1 = mysql_query($sql, $tankdb) or die(mysql_error());
       header("location:schedule_course.php");
    }
  }

?>