<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php require_once('function/announcement_function.php'); ?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ( empty( $_POST['tk_anc_text'] ) ){
$tk_anc_text = "'',";
}else{
$tk_anc_text = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_anc_text']), "text"));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tk_announcement (tk_anc_title, tk_anc_text, tk_anc_type, tk_anc_create,tk_pid) VALUES (%s, $tk_anc_text %s, %s , %s)",
                       GetSQLValueString($_POST['tk_anc_title'], "text"),
					   GetSQLValueString($_POST['tk_anc_type'], "text"),
                       GetSQLValueString($_POST['tk_anc_create'], "text"),
                       GetSQLValueString($_POST['tk_pid'], "text"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());
  $newID = mysql_insert_id();
  $insertGoTo = "announcement_view.php?recordID=$newID";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  $insertGoTo=$insertGoTo."&backnums=-2";
  header(sprintf("Location: %s", $insertGoTo));
  exit;
}

$teamleader_lists=get_teamleader_lists($_SESSION['MM_uid']);
$row_teamleader_lists = mysql_fetch_assoc($teamleader_lists);

?>
<?php require('head.php'); ?>
    <link href="css/lhgcore/lhgcheck.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="js/lhgcore/lhgcore.js"></script>
    <script type="text/javascript" src="js/lhgcore/lhgcheck.js"></script>
    <script type="text/javascript" src="js/bootstrap/bootstrap-select.js"></script>
    <link href="css/bootstrap/bootstrap-select.css" rel="stylesheet" type="text/css" />

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">

<table width="100%"  border="0" cellspacing="0" cellpadding="0"  id="form1_table">
    <tr>
		<td width="20%" class="input_task_right_bg"  valign="top" id="tips">
			<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
				<div class=" add_title col-xs-12">
					<h3 ><?php echo $multilingual_announcement_new_title; ?></h3>
				</div>
				<td valign="top" >
					 <h4 style="margin-top:40px" class="gray2"><strong><?php echo $multilingual_announcement_status; ?></strong></h4>
					 <p class="gray2">
					 <?php echo $multilingual_announcement_tip; ?>
					 </p>
              </td>
			</tr>
        </table>
	</td>
		<td width="80%" valign="top" align="center">
			<table width="80%" border="0" cellspacing="0" cellpadding="5" align="center" id="add_table"class="add_table">
				<tr>
					<td >
						<table width="98%" border="0" cellspacing="0" cellpadding="5" align="center">
							<tr align="left" >
								<td width="420px">
							<div class="form-group" style="width: 400px;">
								<label for="tk_anc_title"><?php echo $multilingual_announcement_title; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="anntitle"></span></label>
								<div>
									<input type="text" name="tk_anc_title" id="tk_anc_title" value="" class="form-control" placeholder="<?php echo $multilingual_announcement_title; ?>" />
								</div>
							</div>
								</td >
								<td width="420px">
							<div class="form-group "  style="width: 400px;">
								<label for="tk_anc_type">所属团队&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<span id="anneproject"></span></label>
								<div>
									<select name="tk_pid" id="tk_pid" class="form-control selectpicker">
									<?php do {  ?> 
									  <option value="<?php echo $row_teamleader_lists['tk_team_pid']; ?>"><?php echo $row_teamleader_lists['project_name']; ?></option>
									  <?php } while ($row_teamleader_lists = mysql_fetch_assoc($teamleader_lists)); ?>
									</select>
								</div>
							</div>
								</td >
								<td >
								<div class="form-group" style="width: 100px;" >
								<label for="tk_anc_type">公告状态</label>
								<div >
									<select name="tk_anc_type" id="tk_anc_type" class="form-control selectpicker">
									  <option value="2"><?php echo $multilingual_dd_announcement_settop; ?></option>
									  <option value="1" selected="selected"><?php echo $multilingual_dd_announcement_online; ?></option>
									  <option value="-1" ><?php echo $multilingual_dd_announcement_offline; ?></option>
									</select>
								</div>
							</div>
								</td >
								</tr>
						</table>
					</td>
				</tr>
					<tr >
					<td>
						<div class="form-group" style="margin-top:10px;width:90%;" id="textdiv">
							<label for="tk_anc_text"><?php echo $multilingual_announcement_text; ?></label>
							<div>
							<textarea name="tk_anc_text" id="tk_anc_text"></textarea>
							</div>
						</div>
					</td>
				</tr>
				<tr class="input_task_bottom_bg" >
					<td align="left" >
							<table width="250px" border="0" cellspacing="0" cellpadding="5" style="margin-left:600px;margin-top:20px;">
							<!-- 提交按钮 -->
								<tr >
									<td >
										<input style="display:none" name="MM_insert" id="MM_insert" value="form1" />
										<input style="display:none" id="tk_anc_create"   name="tk_anc_create" value="<?php echo $_SESSION['MM_uid']; ?>" />
										<button type="submit" class="btn btn-primary btn-lg" name="cont"  data-loading-text="提交中……" style="width:120px"><?php echo $multilingual_global_action_save; ?></button>
									</td>
									<td  width="20%" align="center" >
										<button type="button" class="btn btn-default btn-lg" style="width:120px" onClick="javascript:history.go(-1);"><?php echo $multilingual_global_action_cancel; ?></button>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
			</tr>					
		</table>
</form>
<div style="height:1px; margin-top:-1px;clear: both;overflow:hidden;"></div>
</div>
<?php require('foot.php'); ?>

<script charset="utf-8" src="plug-in/editor/kindeditor.js"></script>
<script charset="utf-8" src="plug-in/editor/lang/zh_CN.js"></script>
<script>

		$('button[data-loading-text]').click(function () {
			var btn = $(this).button('loading');
			setTimeout(function () {
				btn.button('reset');	
			}, 3000);
		});

		J.check.rules = [
			{ name: 'tk_anc_title', mid: 'anntitle', type: 'limit', requir: true, min: 2, max: 30, warn: '<?php echo $multilingual_announcement_titlerequired; ?>' }
			
		];

		var h = 350+'px';
		if($(window).height()-480>0){
			h = $(window).height()-480+'px';
		}
        var editor;
        KindEditor.ready(function(K) {
                editor = K.create('#tk_anc_text', {
				width : '1100px',
				height: h,
				items:[
					'undo', 'redo', '|', 'preview', 'print', 'template', 'cut', 'copy', 'paste',
					'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
					'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
					'superscript','source', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
					'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
					'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
					'flash', 'media', 'insertfile', 'table', 'hr',  'code', 'pagebreak', 
					'link', 'unlink'
			]
			});
        });

	$(window).load(function()
	{
        
		J.check.regform('form1');
		$('.selectpicker').selectpicker();
		
		if((document.getElementById("textdiv").clientHeight+document.getElementById("top_height").clientHeight+252+60)>$(window).height()){
			document.getElementById("foot_top").style.height=document.getElementById("textdiv").clientHeight+document.getElementById("top_height").clientHeight+252+"px";
		}else{
			document.getElementById("foot_top").style.height=$(window).height()+"px";
		}
		document.getElementById("tips").style.height=document.getElementById("foot_top").clientHeight-126+"px";
		//document.getElementById("form1_table").style.height=document.getElementById("foot_top").clientHeight-126+"px";
		//$(window).resize();

    });
	$(window).resize(function()
	{	
		document.getElementById("tips").style.height=document.getElementById("foot_top").clientHeight-126+"px";
		//document.getElementById("tips").style.height=document.getElementById("foot_top").clientHeight-186+"px";
		//document.getElementById("form1_table").style.height=document.getElementById("foot_top").clientHeight-126+"px";
		
	});
</script>
</body>
</html>