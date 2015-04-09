<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php
error_reporting(0);
$uptypes=array('image/jpg',  
'application/octet-stream',
'text/html',
'application/zip',
'application/x-zip-compressed',
'application/pdf',
'application/msword',
'application/vnd.ms-excel',
'application/vnd.ms-powerpoint',
'text/plain',
'image/jpeg',
'image/png',
'image/pjpeg',
'image/gif',
'image/bmp',
'application/x-shockwave-flash',
'image/x-png');
$max_file_size=2000000;   
$destination_folder="upload/"; 
$watermark=0;   
$watertype=1;  
$waterposition=1;   
$waterstring="newphp.site.cz"; 
$waterimg="xplore.gif";  
$imgpreview=0;  
$imgpreviewsize=1/2; 
?>
<html>
<head>
<title>WSS - <?php echo $multilingual_upload_title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">


<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function uploadtk(){
	var woper = window.opener;
	var p1 = woper.document.getElementById("csa_remark1");
	var p2 = document.getElementById("input_url");
	p1.value += p2.value + "\n\n";
	window.close();
}
</script>
</head>
<body>
<br>
<div style="width:430px; ">
<table align="center" class="fontsize-s input_task_table" >
<tr><td>
<form enctype="multipart/form-data" method="post" name="upform">



<span class="input_task_title margin-y" style="margin-top:0px;"><?php echo $multilingual_upload_title; ?></span><br>
<input name="upfile" type="file"  size="30"  class="button_sub" />&nbsp;
 <input type="submit" value="<?php echo $multilingual_upload_button; ?>" class="button_sub" 
 <?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?> 
 /><br><br>
<span class="gray"><?php echo $multilingual_upload_tip; ?></span><br>
<br>

</form>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
if (!is_uploaded_file($_FILES["upfile"]["tmp_name"]))

{
echo "<font color='red'>$multilingual_upload_error1</font>";
exit;
}

$file = $_FILES["upfile"];
if($max_file_size < $file["size"])

{
echo "<font color='red'>$multilingual_upload_error2</font>";
exit;
  }

if(!in_array($file["type"], $uptypes))

{
echo "<font color='red'>$multilingual_upload_error3</font>";
exit;
}

if(!file_exists($destination_folder))
mkdir($destination_folder);

$filename=$file["tmp_name"];
$image_size = getimagesize($filename);
$pinfo=pathinfo($file["name"]);
$ftype=$pinfo["extension"];
$destination = $destination_folder.time().".".$ftype;
if (file_exists($destination) && $overwrite != true)
{
     echo "<font color='red'>$multilingual_upload_error4</a>";
     exit;
  }

if(!move_uploaded_file ($filename, $destination))
{
   echo "<font color='red'>$multilingual_upload_error5</a>";
     exit;
  }

$pinfo=pathinfo($destination);
$fname=$pinfo["basename"];
echo " <font color=green>$multilingual_upload_done</font><br>";

if($watermark==1)
{
$iinfo=getimagesize($destination,$iinfo);
$nimage=imagecreatetruecolor($image_size[0],$image_size[1]);
$white=imagecolorallocate($nimage,255,255,255);
$black=imagecolorallocate($nimage,0,0,0);
$red=imagecolorallocate($nimage,255,0,0);
imagefill($nimage,0,0,$white);
switch ($iinfo[2])
{
case 1:
$simage =imagecreatefromgif($destination);
break;
case 2:
$simage =imagecreatefromjpeg($destination);
break;
case 3:
$simage =imagecreatefrompng($destination);
break;
case 6:
$simage =imagecreatefromwbmp($destination);
break;
default:
die("<font color='red'>$multilingual_upload_error6</a>");
exit;
}

imagecopy($nimage,$simage,0,0,0,0,$image_size[0],$image_size[1]);
imagefilledrectangle($nimage,1,$image_size[1]-15,80,$image_size[1],$white);

switch($watertype)
{
case 1: 
imagestring($nimage,2,3,$image_size[1]-15,$waterstring,$black);
break;
case 2:  
$simage1 =imagecreatefromgif("xplore.gif");
imagecopy($nimage,$simage1,0,0,0,0,85,15);
imagedestroy($simage1);
break;
}

switch ($iinfo[2])
{
case 1:
//imagegif($nimage, $destination);
imagejpeg($nimage, $destination);
break;
case 2:
imagejpeg($nimage, $destination);
break;
case 3:
imagepng($nimage, $destination);
break;
case 6:
imagewbmp($nimage, $destination);
//imagejpeg($nimage, $destination);
break;
}


imagedestroy($nimage);
imagedestroy($simage);
}

if($imgpreview==1)
{
echo "<br>$multilingual_upload_img<br>";
echo "<a href=\"".$destination."\" target='_blank'><img src=\"".$destination."\" width=".($image_size[0]*$imgpreviewsize)." height=".($image_size[1]*$imgpreviewsize);
echo " alt=\"$multilingual_upload_img\r$multilingual_upload_file".$destination."\r$multilingual_upload_time\" border='0'></a>";
}
}
?>

<textarea id="input_url"  cols="50" rows="5" style="display:none;">
<?php echo $multilingual_upload_attachment; ?><?php 
echo "<a href=\"".$destination."\" target='_blank'>";
echo "".$destination."";
echo "</a>";
?>
</textarea>



</td>
</tr>
<tr>
<td align="right">
<span class="input_task_submit">
<?php 
$file = 1;
if (isset($fname)) {
  $file = $fname;
}
if($file <> "1") { ?>
<input type="button" value="<?php echo $multilingual_global_action_ok; ?>" onClick="uploadtk();" class="button"/> 
<?php } ?>
<input type="button" value="<?php echo $multilingual_global_action_cancel; ?>" onClick="window.close();" class="button"/></span>

</td>
</tr>
</table>

</div>

</body>