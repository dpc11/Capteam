<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php require_once('function/config_function.php'); ?>
<?php
//error_reporting(0);
$destination="";
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

$filename_upload="";
if(isset($_POST['input_url'])){
	$filename_upload=$_POST['input_url'];
}
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
</style>
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
	function uploadtk(destination,names){
		window.parent.parent.document.getElementById("csa_remark1").value=destination;
		window.parent.parent.document.getElementById("names").value=names;
		P.cancel();
	}

</script>
</head>
<body style="overflow:hidden">
<form  method="post" enctype="multipart/form-data" name="upform" action="file_upload.php" >
<div style="height:400px; width:500px;margin-top:40px;margin-left:40px">
	<div style="margin:10px 150px 25px 200px">
		<label ><?php echo $multilingual_upload_title; ?></label>
	</div>
	<div class="form-group" style="width:100%;padding-left:50px;padding-right:50px;overflow:hidden">
		<input  type="file"  id="upfile_" name="upfile_"style="display:block;border: 1px solid #999;"  />
	</div>
	<div style="width:100%;padding-left:50px;padding-top:15px;">
		<span class="gray glink" >
		<?php  $filesizes=$max_file_size/1000000;
		echo $multilingual_upload_tip.$filesizes."MB. "  ?></span>
	</div>
<div style="float:left;width:100%;hieht:60px;padding-left:50px;">
<?php
$OK=true;
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if ($_FILES['upfile_']['tmp_name']=="")
	{
		echo "<font color='red'>请选择文件！</font>";
		$OK=false;
	}
	if ($OK&&!is_uploaded_file($_FILES["upfile_"]["tmp_name"]))
	{
		echo "<font color='red'>$multilingual_upload_error1</font>";
		$OK=false;
	}
	$file = $_FILES["upfile_"];
	if($OK&&$max_file_size < $file["size"])
	{
		echo "<font color='red'>$multilingual_upload_error2</font>";
		$OK=false;
	}
	//Modification 1: no php file is allowed
	if ($OK&&strtolower(end(explode('.', $file["name"])))=='php')
	{
		echo "<font color='red'>$multilingual_upload_error3</font>";
		$OK=false;
	}
	//Modification 1 end
	if($OK&&!in_array($file["type"], $uptypes))
	{
		echo "<font color='red'>$multilingual_upload_error3</font>";
		$OK=false;
	}
	if($OK&&!file_exists($destination_folder))
		mkdir($destination_folder);

	if($OK)
	{
		$filename=$file["tmp_name"];
		$image_size = getimagesize($filename);
		$pinfo=pathinfo($file["name"]);
		$names = $_FILES['upfile_']['name'];
		$named = iconv('utf-8','gbk',$names);
		$ftype=$pinfo["extension"];
		$destination = $destination_folder.md5(time())."_".$named;
		$destination_title = $destination_folder.md5(time())."_".$names;
		if ($OK&&file_exists($destination) && $overwrite != true)
		{
			echo "<font color='red'>$multilingual_upload_error4</a>";
			$OK=false;
		}

		if($OK&&!move_uploaded_file ($filename, $destination))
		{
			echo "<font color='red'>$multilingual_upload_error5</a>";
			$OK=false;
		}
		if($OK){
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
						$OK=false;
				}
				if($OK){
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
		}
	}
}
?>
</div>
	
<div style="width:100%;padding-top:20px;padding-right:40px;overflow:hidden;">
<button type="button" class="btn btn-default btn-lg" style="margin-left:10px;float:right;width:60px;height:45px;color:rgb(9, 9, 9);font-size:17px;" onClick="over();" ><?php echo $multilingual_global_action_cancel; ?></button>
<button  class="btn btn-default btn-lg" id="upfile" style="margin-left:10px;float:right;width:60px;height:45px;color:rgb(9, 9, 9);font-size:17px;" type="submit" style="flaot:right;" >上传</button>
<?php 
$file = 1;
if (isset($fname)) {
  $file = $fname;
}
if($OK&&$file <> "1") {  ?>
<button  type="button" class="btn btn-default btn-lg" 
style="padding-left:10px;float:right;width:60px;height:45px;color:rgb(9, 9, 9);font-size:17px;"onClick="uploadtk('<?php echo $destination_title; ?>','<?php  echo $names;  ?>');" ><?php echo $multilingual_global_action_ok; ?></button> 
<?php }
 ?>
</div>


</div>
</form>
</body>
</html>