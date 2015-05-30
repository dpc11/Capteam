<?php
$room=$_POST['room'];

include_once("incsan.php");
sanV($room);
if (!$room) exit;

$room=$_POST['room'];
$stream=$_POST['stream'];
$recording=$_POST['recording'];
$rectime=$_POST['rectime'];

include_once('addslide-inc.php');
addData($room, $stream, "stream=$stream&duration=$rectime", 'Stream');


?>loadstatus=1