<?php
if (!isset($_SESSION)) {
  session_start();
}

$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "5" || $_SESSION['MM_UserGroup'] == $multilingual_dd_role_disabled) {   
  header("Location: ". $restrictGoTo); 
  exit;
}

?>