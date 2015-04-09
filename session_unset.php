<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['MM_Displayname'] = NULL;
  $_SESSION['MM_uid'] = NULL;
  $_SESSION['MM_rank'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  $_SESSION['urlthis'] = NULL;
  $_SESSION['MM_msg'] = NULL;
  $_SESSION['mail'] = NULL;
  $_SESSION['MM_last'] = NULL;
  $_SESSION['copytask'] = NULL;
  //$_SESSION['urlback'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['MM_Displayname']);
  unset($_SESSION['MM_uid']);
  unset($_SESSION['MM_rank']);
  unset($_SESSION['PrevUrl']);
  unset($_SESSION['urlthis']);
  unset($_SESSION['MM_msg']); 
  unset($_SESSION['mail']); 
  unset($_SESSION['MM_last']); 
  unset($_SESSION['copytask']); 
  //unset($_SESSION['urlback']);
	
  $logoutGoTo = "user_login.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>