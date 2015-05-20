<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php require_once('function/config_function.php'); ?>
<?php
//error_reporting(0);
$uptypes=array('image/jpg',  
'application/octet-stream',
'application/vnd.openxmlformats',
'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
'application/vnd.openxmlformats-officedocument.presentationml.presentation',
'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
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
<!DOCTYPE html PUBLIC >
<html>
<head>
<title>Capteam - 附件上传</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="css/lhgcore/lhgdialog.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap/bootstrap.css" rel="stylesheet" media="screen">
<link href="css/tk_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery/jquery.js"></script>
<script type="text/javascript" src="js/bootstrap/bootstrap.js"></script>
<script type="text/javascript" src="js/lhgcore/lhgcore.js"></script>
<script type="text/javascript" src="js/lhgcore/lhgdialog.js"></script>
<script type="text/javascript">
	var P = window.parent, D = P.loadinndlg();   
	function closreload(url)
	{
		if(!url)
			P.reload();    
	}
	function over()
	{
		P.cancel();
	}
	function uploadtk(){
		var woper = window.opener;
		var p1 = woper.document.getElementById("csa_remark1");
		var p2 = document.getElementById("input_url");
		p1.value = p2.value;
		window.close();
	}
	function displayfile(){
		$("#input_url").val($("#upfile_").val());  
	}
</script>
</head>
<body>
<div class="clearboth"></div>
<form enctype="multipart/form-data" method="post" name="upform">
<div class="clearboth"></div>
	<div style="width:500px;height:400px; ">

	<div style="margin:10px 150px 25px 200px">
	<label ><?php echo $multilingual_upload_title; ?></label>
	</div>
	<div class="form-group" style="width:100%;margin-left:50px;margin-right:50px;">
		<div class="clearboth"></div>
		<div style="width:100px;float:left;height:45px;color:rgb(9, 9, 9);font-size:16px;">	
		 <button  class="btn btn-default btn-lg" style="height:45px;color:rgb(9, 9, 9);font-size:16px;" type="button"  onclick="$('#upfile_').click();">选择文件</button>
		 </div>
		 
		<div style="float:left;width:300px;height:45px;font-size:16px;margin-right:10px;">
		<input id="input_url" style="height:45px;font-size:16px;" type="text" class="form-control" placeholder="请选择文件" value="<?php echo $destination_title;?>" />
		</div>	
		<input  type="file"  id="upfile_"style="display:none;" onchange="displayfile();" />
	</div>
			<span class="gray glink" style="width:400px;margin-left:50px;margin-top:30px;"><?php 
			$filesizes=$max_file_size/1000000;
			echo $multilingual_upload_tip.$filesizes."MB. " ?></span>
	<button  class="btn btn-default btn-lg"  style="margin-top:30px;"type="submit" value="上传" style="flaot:right;" ></button>
	</div>
</form>
<div>
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
if ($_FILES["upfile"]["tmp_name"]=="")
{
echo "<font color='red'>请选择文件！</font>";
exit;
}
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

//Modification 1: no php file is allowed
if (strtolower(end(explode('.', $file["name"])))=='php')
{
echo "<font color='red'>$multilingual_upload_error3</font>";
exit;
}
//Modification 1 end

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
$names = $_FILES['upfile']['name'];
$named = iconv('utf-8','gbk',$names);
$ftype=$pinfo["extension"];
$destination = $destination_folder.md5(time())."_".$named;
$destination_title = $destination_folder.md5(time())."_".$names;
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

<?php 
$file = 1;
if (isset($fname)) {
  $file = $fname;
}
if($file <> "1") { ?>
<input type="button btn-defalut btn-lg" value="<?php echo $multilingual_global_action_ok; ?>" onClick="uploadtk();" class="button"/> 
<?php } ?>
<input type="button btn-defalut btn-lg" value="<?php echo $multilingual_global_action_cancel; ?>" onClick="window.close();" class="button"/>

</div>

</body>
</html>