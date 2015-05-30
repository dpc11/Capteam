<?php
if ($_GET["room"]) $room=$_GET["room"];
if ($_POST["room"]) $room=$_POST["room"];

$addSlide  = $_GET['addSlide'];
$slides  = $_GET['slides'];

$filename=$_FILES['vw_file']['name'];

include_once("incsan.php");
sanV($room);
if (!$room) exit;
sanV($filename);
if (strstr($filename,".php")) $filename = ""; //duplicate extension not allowed
$filename = preg_replace(array('#[\\s]+#', '#[^A-Za-z0-9\. -]+#'), array('_', ''), $filename);

if (!$filename) exit;

$destination="uploads/".$room."/";
if (!file_exists($destination)) mkdir($destination);

if ($slides)
{
	$destination .= "slides/";
	if (!file_exists($destination)) mkdir($destination);
}


if ($slides && $addSlide) include_once('addslide-inc.php');


//verify extension
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

$allowed = array('swf','jpg','jpeg','png','gif','txt','doc','docx','pdf', 'mp4', 'flv', 'avi', 'mpg', 'mpeg', 'ppt','pptx', 'pps', 'ppsx', 'doc', 'docx', 'odt', 'odf', 'rtf', 'xls', 'xlsx');

if (in_array($ext,$allowed))
{
	move_uploaded_file($_FILES['vw_file']['tmp_name'], $destination . $filename);

	if ($slides && $addSlide)
	{
		$root_url = dirname( $_SERVER['HTTPS'] ? 'https://' : 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] ) . '/';
		$source = $root_url . $destination . $filename;
		$label = basename($filename, strrchr($filename, '.'));
		$type = 'Graphic';

		if ( in_array($ext, array('ppt', 'pptx', 'pps', 'ppsx', 'txt', 'doc', 'docx', 'odt', 'odf', 'rtf', 'xls', 'xlsx')) )  importPPT($room, $label, $destination . $filename, $root_url);
		if ( in_array($ext, array('pdf')) )  importPDF($room, $label, $destination . $filename, $root_url);
		if ( in_array($ext, array('png', 'jpg', 'swf', 'jpeg')) )  addSlide($room, $label, $source, $type);
	}

	$debug = $destination . $filename;

	echo 'debug='.urlencode($debug). '&';

}else echo 'uploadFailed=badExtension&';

?>loadstatus=1
