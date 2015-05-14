<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php


    if ((isset($_GET['delID'])) && ($_GET['delID'] != "")) {

      $del_id = $_GET['delID'];
      $p_id = $_GET['pid'];
     
      $deleteBoardSQL = "UPDATE tk_board SET board_del_status=-1 WHERE board_id=$del_id";
      mysql_select_db($database_tankdb, $tankdb);
      $Result = mysql_query($deleteBoardSQL, $tankdb) or die(mysql_error());

      $deleteGoTo = "base.php?pid=$p_id";
      if (isset($_SERVER['QUERY_STRING'])) {
        $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
        $deleteGoTo .= $_SERVER['QUERY_STRING'];
      }
      header(sprintf("Location: %s", $deleteGoTo));
    }
?>
<!DOCTYPE html PUBLIC >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>

<body>
</body>
</html>