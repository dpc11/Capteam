<?php
function addSlide($room, $label, $source, $type)
{
	addData($room, $label, "src=$source", $type);
}

function addData($room, $label, $data, $type)
{
	$filename="uploads/$room/slides/slideshow.xml";
	if (file_exists($filename)) $txt = implode(file($filename));
	if (!$txt) $txt="<SLIDES>\r</SLIDES>";

	$txt = str_ireplace("</SLIDES>"," <SLIDE index=\"00\" label=\"$label\" type=\"$type\" data=\"$data\" />\r</SLIDES>",$txt);

	//assign good order numbers
	preg_match_all("|<SLIDE (.*) />|U",  $txt, $out, PREG_SET_ORDER);
	$k=1;
	for ($i=0;$i<count($out);$i++)
	{
		$repl=preg_replace('/index="(\d+)"/','index="'.sprintf("%02d",$k++).'"',$out[$i][0]);
		$txt=str_replace($out[$i][0],$repl,$txt);
	}

	// save file
	$fp=fopen($filename,"w");
	if ($fp)
	{
		fwrite($fp, $txt);
		fclose($fp);
	}
}

/*
PPT, PDF conversion requires:
1. Apache_OpenOffice
2. unoconv
3. ImageMagick
*/

function importPPT($room, $label, $filename, $root_url)
{
	$root_path =  realpath(dirname( __FILE__ )) . '/';
	$filepath = $root_path . $filename;

	$folder =  "uploads/$room/slides/";
	$outpath = $root_path . $folder;

	$newFolder = $outpath . $label . '/';


	if (!file_exists($newFolder)) mkdir($newFolder);

	$debug = $outpath;

	/*
	Paths:
	[~]# which unoconv
	/usr/bin/unoconv
	[~]# which convert
	/usr/bin/convert
	*/

	//convert to pdf
	$cmd = '/usr/bin/unoconv -f pdf \'' . $filepath . '\' -o \'' . $outpath . $label . '.pdf\'';
	exec($cmd, $output, $returnvalue);

	//$debug = $cmd;

	//convert to png
	$cmd = '/usr/bin/convert \'' . $outpath . $label . '.pdf\' \'' . $newFolder . '%03d.png\'';
	exec($cmd, $output, $returnvalue);

	$files = scandir($newFolder);
	foreach ($files as $file)
	{
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		$no = basename($file, strrchr($file, '.'));
		if ($ext == 'png') addSlide($room, $label . ' #' . $no, $root_url . $folder . $label .'/'. $file, 'Graphic');
	}

	echo 'importDebug=' . $debug . '&';

}

function importPDF($room, $label, $filename, $root_url)
{
	$root_path =  realpath(dirname( __FILE__ )) . '/';
	$filepath = $root_path . $filename;

	$folder =  "uploads/$room/slides/";
	$outpath = $root_path . $folder;

	$newFolder = $outpath . $label . '/';

	if (!file_exists($newFolder)) mkdir($newFolder);

	//convert to png
	$cmd = '/usr/bin/convert \'' . $filepath . '\' \'' . $newFolder . '%03d.png\'';
	exec($cmd, $output, $returnvalue);

	$files = scandir($newFolder);
	foreach ($files as $file)
	{
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		$no = basename($file, strrchr($file, '.'));
		if ($ext == 'png') addSlide($room, $label . ' #' . $no, $root_url . $folder . $label .'/'. $file, 'Graphic');
	}


	echo 'importDebug=' . $debug . '&';

}
?>