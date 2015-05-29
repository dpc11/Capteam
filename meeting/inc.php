<?php
$adminkey="videowhisper";

include("settings.php");
include_once("incsan.php");

function append_log($text)
{
$dfile = fopen("vwlog.txt","a");
fputs($dfile,$text);
fclose($dfile);
}
?>