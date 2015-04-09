<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php
$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "2") {   
  header("Location: ". $restrictGoTo); 
  exit;
}

$logdate = $_GET['date'];
$taskid = $_GET['taskid'];
$userid = $_GET['userid'];
$projectid = $_GET['projectid'];
$tasktype = $_GET['tasktype'];
$nowuser = $_SESSION['MM_uid'];

mysql_select_db($database_tankdb, $tankdb);
$query_log = sprintf("SELECT *, 
tk_user1.uid as uid1, 
tk_user2.tk_display_name as tk_display_name2 
FROM tk_task 
inner join tk_user as tk_user2 on tk_task.csa_to_user=tk_user2.uid 
inner join tk_user as tk_user1 on tk_task.csa_from_user=tk_user1.uid 
WHERE TID = %s", GetSQLValueString($taskid, "text"));
$log = mysql_query($query_log, $tankdb) or die(mysql_error());
$row_log = mysql_fetch_assoc($log);
$totalRows_log = mysql_num_rows($log);

$mailto = $row_log['uid1']; 
$title = $row_log['csa_text'];  
$user = $row_log['tk_display_name2'];  


$statusid = "-1";
if (isset($_POST['csa_tb_status'])) {
  $statusid = $_POST['csa_tb_status'];
}
mysql_select_db($database_tankdb, $tankdb);
$query_tkstatus1 = sprintf("SELECT * FROM tk_status WHERE id = %s", GetSQLValueString($statusid, "text"));
$tkstatus1 = mysql_query($query_tkstatus1, $tankdb) or die(mysql_error());
$row_tkstatus1 = mysql_fetch_assoc($tkstatus1);
$totalRows_tkstatus1 = mysql_num_rows($tkstatus1);

// *** Redirect if username exists
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ( empty( $_POST['csa_tb_text'] ) ){
$csa_tb_text = "'',";
}else{
$csa_tb_text = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['csa_tb_text']), "text"));
}

if ((isset($_POST["log_insert"])) && ($_POST["log_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tk_task_byday (csa_tb_text, csa_tb_year, csa_tb_status, csa_tb_manhour, csa_tb_backup1, csa_tb_backup2, csa_tb_backup3, csa_tb_backup4) VALUES ($csa_tb_text %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['csa_tb_year'], "text"),
                       GetSQLValueString($_POST['csa_tb_status'], "text"),
                       GetSQLValueString($_POST['csa_tb_manhour'], "text"),
                       GetSQLValueString($_POST['csa_tb_backup1'], "text"),
                       GetSQLValueString($_POST['csa_tb_backup2'], "text"),
                       GetSQLValueString($_POST['csa_tb_backup3'], "text"),
                       GetSQLValueString($_POST['csa_tb_backup4'], "text"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());

  $newID = $taskid;
  $newName = $_SESSION['MM_uid'];
  
  $lyear = $_POST['csa_tb_year'];
  $lgyear = str_split($lyear,4);
  $lgmonth = str_split($lgyear[1],2);
  $ldate = $lgyear[0]."-".$lgmonth[0]."-".$lgmonth[1];
  
  $logstatus = $row_tkstatus1['task_status'];
  $logtext = $_POST['csa_tb_text'];
  
  $manhour = $_POST['csa_tb_manhour'];
  $action = $multilingual_log_addlog1.$ldate.$multilingual_log_addlog2.$logstatus.$multilingual_log_costlog.$manhour.$multilingual_global_hour."&nbsp;&nbsp;".$logtext;

$insertSQL2 = sprintf("INSERT INTO tk_log (tk_log_user, tk_log_action, tk_log_type, tk_log_class, tk_log_description) VALUES (%s, %s, %s, 1, ''  )",
                       GetSQLValueString($newName, "text"),
                       GetSQLValueString($action, "text"),
                       GetSQLValueString($newID, "text"));  
$Result3 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());

  $insertGoTo = "log_finish.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  
 
$msg_to = $mailto; 
$msg_from = $nowuser;
$msg_type = "edittask";
$msg_id = $taskid;
$msg_title = $title;
$mail = send_message( $msg_to, $msg_from, $msg_type, $msg_id, $msg_title );

if($row_log['test01'] <> null){

$cc_arr = json_decode($row_log['test01'], true);

foreach($cc_arr as $k=>$v){
send_message( $v['uid'], $msg_from, $msg_type, $msg_id, $msg_title, 1 );
}

}

  header(sprintf("Location: %s", $insertGoTo));
}

  if ((isset($_POST["task_update"])) && ($_POST["task_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tk_task SET csa_remark2=%s, csa_remark3=%s, csa_last_user=%s WHERE TID=%s", 
                       GetSQLValueString($_POST['csa_tb_status'], "text"),
                       GetSQLValueString($_POST['csa_tb_time'], "text"),
                       GetSQLValueString($nowuser, "text"),                      
                       GetSQLValueString($taskid, "int"));
  mysql_select_db($database_tankdb, $tankdb);
  $Result2 = mysql_query($updateSQL, $tankdb) or die(mysql_error());
  }

mysql_select_db($database_tankdb, $tankdb);
$query_tkstatus = "SELECT * FROM tk_status WHERE task_status_backup2 <> 1 ORDER BY task_status_backup1 ASC";
$tkstatus = mysql_query($query_tkstatus, $tankdb) or die(mysql_error());
$row_tkstatus = mysql_fetch_assoc($tkstatus);
$totalRows_tkstatus = mysql_num_rows($tkstatus);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="skin/themes/base/lhgdialog.css" rel="stylesheet" type="text/css" />
	<link href="skin/themes/base/jquery-ui.min.css" rel="stylesheet" type="text/css" />
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

function submitform()
{
    document.form1.cont.value='<?php echo $multilingual_global_wait; ?>';
	document.form1.cont.disabled=true;
	document.getElementById("btn5").click();
}
	</script>
	
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>

<script type="text/javascript" src="srcipt/jquery-ui-1.10.4.min.js"></script>

<script charset="utf-8" src="editor/kindeditor.js"></script>
<script charset="utf-8" src="editor/lang/zh_CN.js"></script>
<script>
        var editor;
        KindEditor.ready(function(K) {
                editor = K.create('#csa_tb_text', {
			width : '100%',
			height: '236px',
			items:[
        'source', '|', 'undo', 'redo', '|', 'cut', 'copy', 'paste',
        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'clearhtml', 'quickformat', 'selectall', '|', 'forecolor', 'hilitecolor', 'lineheight', 'bold',
        'italic', 'underline', 'strikethrough', 'removeformat', '|',   
        'formatblock', 'fontname', 'fontsize', '|', 'insertfile',  'hr', 'pagebreak', 'anchor', 
        'link', 'unlink', '|', 'about'
]
});
        });
		
		

</script>	
<script>
  $(function() {
    var select = $( "#csa_tb_manhour" );
    var slider = $( "<div id='slider' class='pull-left' style='width:442px; margin:5px;'></div>" ).insertAfter( select ).slider({
      min: 0,
      max: 49,
      range: "min",
      value: select[ 0 ].selectedIndex + 0.5,
      slide: function( event, ui ) {
        select[ 0 ].selectedIndex = ui.value - 0.5;
      }
    });
    $( "#csa_tb_manhour" ).change(function() {
      slider.slider( "value", this.selectedIndex + 0.5 );
    });
  });
  </script>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" >
      <div class="modal-body" style="padding:5px;">
			  
			  <div class="form-group col-xs-12">
                <label for="csa_tb_status">
				<?php
$logyear = str_split($logdate,4);
$logmonth = str_split($logyear[1],2);
?>
<?php echo $logyear[0]; ?>-<?php echo $logmonth[0]; ?>-<?php echo $logmonth[1]; ?></label>
                <div  class="input-group">
				<span class="input-group-addon"><?php echo $multilingual_default_task_status; ?></span>
				<select name="csa_tb_status" id="csa_tb_status" class="form-control">
                <?php
do {  
?>
                <option value="<?php echo $row_tkstatus['id']?>"><?php echo $row_tkstatus['task_status']?></option>
                <?php
} while ($row_tkstatus = mysql_fetch_assoc($tkstatus));
  $rows = mysql_num_rows($tkstatus);
  if($rows > 0) {
      mysql_data_seek($tkstatus, 0);
	  $row_tkstatus = mysql_fetch_assoc($tkstatus);
  }
?>
</select>
                </div>

              </div>
			  
			  <div class="form-group col-xs-12">

				
				<div>

				<label for="csa_tb_manhour" class="pull-left"><?php echo $multilingual_user_view_cost; ?></label>

		<div class="pull-right">		
	  <select  name="csa_tb_manhour" id="csa_tb_manhour" >
                  <option value="0" selected="selected">0</option>
                  <option value="0.5">0.5</option>
                  <option value="1">1</option>
                  <option value="1.5">1.5</option>
                  <option value="2">2</option>
                  <option value="2.5">2.5</option>
                  <option value="3">3</option>
                  <option value="3.5">3.5</option>
                  <option value="4">4</option>
                  <option value="4.5">4.5</option>
                  <option value="5">5</option>
                  <option value="5.5">5.5</option>
                  <option value="6">6</option>
                  <option value="6.5">6.5</option>
                  <option value="7">7</option>
                  <option value="7.5">7.5</option>
                  <option value="8">8</option>
                  <option value="8.5">8.5</option>
                  <option value="9">9</option>
                  <option value="9.5">9.5</option>
                  <option value="10">10</option>
                  <option value="10.5">10.5</option>
                  <option value="11">11</option>
                  <option value="11.5">11.5</option>
                  <option value="12">12</option>
                  <option value="12.5">12.5</option>
                  <option value="13">13</option>
                  <option value="13.5">13.5</option>
                  <option value="14">14</option>
                  <option value="14.5">14.5</option>
                  <option value="15">15</option>
                  <option value="15.5">15.5</option>
                  <option value="16">16</option>
                  <option value="16.5">16.5</option>
                  <option value="17">17</option>
                  <option value="17.5">17.5</option>
                  <option value="18">18</option>
                  <option value="18.5">18.5</option>
                  <option value="19">19</option>
                  <option value="19.5">19.5</option>
                  <option value="20">20</option>
                  <option value="20.5">20.5</option>
                  <option value="21">21</option>
                  <option value="21.5">21.5</option>
                  <option value="22">22</option>
                  <option value="22.5">22.5</option>
                  <option value="23">23</option>
                  <option value="23.5">23.5</option>
                  <option value="24">24</option>
                </select>
				</div>
				

                </div>

    </div><!-- /input-group -->
				

			  
			  <div class="form-group col-xs-12" style="margin-bottom:0px;">
                <label for="csa_tb_text"><?php echo $multilingual_global_log; ?></label>
                <div>
				<textarea name="csa_tb_text"  id="csa_tb_text" ></textarea>
                </div>

              </div>
			  

			  
			  
			  <div class="clearboth"></div>


      </div>
      <div class="modal-footer"  style="margin-top:0px; padding:10px 10px 10px;">
        <button type="button" id="btn1" class="btn btn-default btn-sm" onclick="over()"><?php echo $multilingual_global_action_cancel; ?></button>
        <button name="cont" type="button" class="btn btn-primary btn-sm" data-loading-text="<?php echo $multilingual_global_wait; ?>" onClick="submitform()"><?php echo $multilingual_global_action_save; ?></button>
		
		<input type="submit"  id="btn5" value="<?php echo $multilingual_global_action_save; ?>"  style="display:none" />
		
		<input type="hidden" name="csa_tb_year" id="csa_tb_year" value="<?php echo $logdate; ?>" />
	<input type="hidden" name="csa_tb_backup1" id="csa_tb_backup1" value="<?php echo $taskid; ?>" />
	  <input type="hidden" name="csa_tb_backup2" id="csa_tb_backup2" value="<?php echo $userid; ?>" />
	  <input type="hidden" name="csa_tb_backup3" id="csa_tb_backup3" value="<?php echo $projectid; ?>" />
	  <input type="hidden" name="csa_tb_backup4" id="csa_tb_backup4" value="<?php echo $tasktype; ?>" />
	  <input type="hidden" name="csa_tb_time" id="csa_tb_time" value="<?php echo date("Y-m-d H:i:s"); ?>" />
		
		<input type="hidden" name="log_insert" value="form1" /><input type="hidden" name="task_update" value="form1" />
</div>

<script type="text/javascript">
$('button[data-loading-text]').click(function () {
    var btn = $(this).button('loading');
    setTimeout(function () {
        btn.button('reset');
    }, 3000);
});
</script>



  
</form>
</body>
</html>