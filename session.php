<?php
if (!isset($_SESSION)) {
  session_start();
}


$MM_donotCheckaccess = "true";

$MM_restrictGoTo = "index.php";
if (!(isset($_SESSION['MM_Displayname']))) { 
$MM_restrictGoTo = "user_login.php";  
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  echo $MM_referrer;
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);

  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>