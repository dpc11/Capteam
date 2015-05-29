<?php
$room=$_POST['room'];

include_once("incsan.php");
sanV($room);
if (!$room) exit;

$label=$_POST['label'];
$source=$_POST['source'];
$type=$_POST['type'];

include_once('addslide-inc.php');
addSlide($room, $label, $source, $type);


?>loadstatus=1