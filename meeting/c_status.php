<?php
/*
POST Variables:
u=Username
s=Session, usually same as username
r=Room
ct=session time (in milliseconds)
lt=last session time received from this script in (milliseconds)
*/

$room=$_POST[r];
$session=$_POST[s];
$username=$_POST[u];

$currentTime=$_POST[ct];
$lastTime=$_POST[lt];

$maximumSessionTime=3600000; //900000ms=15 minutes

$disconnect=""; //anything else than "" will disconnect with that message
?>timeTotal=<?=$maximumSessionTime?>&timeUsed=<?=$currentTime?>&lastTime=<?=$currentTime?>&disconnect=<?=$disconnect?>&loadstatus=1