<?php require_once('config/tank_config.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="skin/themes/base/lhgdialog.css" rel="stylesheet" type="text/css" />
<title>log</title>
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
		function getid()
		{
			return P.document.getElementById('textfield1').value;
		}
	</script>
	
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
	
<script charset="utf-8" src="editor/kindeditor.js"></script>
<script charset="utf-8" src="editor/lang/zh_CN.js"></script>
<!--
<script type="text/javascript">

var api = frameElement.api, W = api.opener;
window.onload = function()
{
	
	alert (W.document.getElementById('textfield1').value);
    document.getElementById('textfield1').value = W.document.getElementById('textfield1').value;
    document.getElementById('textfield3').value = W.document.getElementById('textfield3').value;
    document.getElementById('textfield5').value = W.document.getElementById('textfield5').value;
	
};

</script>-->	
</head>     
<body >
	<input name="textfield1" id="textfield1" value=function getid(); />
	<input name="textfield1" id="textfield1" value="111111111111111" />
	<form action="register_real.php" method="post" name="myform" id="form1">
	<input type="hidden" name="textfield1" id="textfield1" value="111111111111111" />
	<input type="hidden" name="textfield3" id="textfield3" value="11111111111111" />
	<input type="hidden" name="textfield5" id="textfield5" value="11111111111111" />
	</form>
</body>
</html> 
