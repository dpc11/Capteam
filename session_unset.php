<?php

if (!isset($_SESSION)) {
  session_start();
}

$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  $_SESSION['MM_Displayname'] = NULL;
  $_SESSION['MM_uid'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  $_SESSION['urlthis'] = NULL;
  $_SESSION['MM_last'] = NULL;
  $_SESSION['urlback'] = NULL;
  unset($_SESSION['MM_Displayname']);
  unset($_SESSION['MM_uid']);
  unset($_SESSION['PrevUrl']);
  unset($_SESSION['urlthis']);
  unset($_SESSION['MM_last']); 
  unset($_SESSION['urlback']);
	
  $logoutGoTo = "user_login.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>