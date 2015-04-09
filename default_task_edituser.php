<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php
$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "2") {   
  header("Location: ". $restrictGoTo); 
  exit;
}


$to_user = "-1";
if (isset($_POST['csa_to_user'])) {
$to_user_arr = explode(", ,", $_POST['csa_to_user']);
  $to_user= $to_user_arr['0'];
}

$taskid = $_GET['taskid'];
$nowuser = $_SESSION['MM_uid'];


mysql_select_db($database_tankdb, $tankdb);
$query_touser = "SELECT * FROM tk_user WHERE uid = '$to_user'";
$touser = mysql_query($query_touser, $tankdb) or die(mysql_error());
$row_touser = mysql_fetch_assoc($touser);
$totalRows_touser = mysql_num_rows($touser);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_task = sprintf("SELECT *, 
tk_user1.tk_user_email as tk_user_email1, 
tk_user1.tk_display_name as tk_display_name1 
FROM tk_task 
inner join tk_user as tk_user1 on tk_task.csa_from_user=tk_user1.uid 
WHERE TID = %s", GetSQLValueString($taskid, "int"));
$Recordset_task = mysql_query($query_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);
$totalRows_Recordset_task = mysql_num_rows($Recordset_task);

$mailto = $row_touser['tk_user_email']; 
$mailto2 = $row_Recordset_task['tk_user_email1']; 
$title = $row_Recordset_task['csa_text'];
$user = $row_Recordset_task['tk_display_name1'];  


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

  if ((isset($_POST["task_update"])) && ($_POST["task_update"] == "form2")) {
  $updatetask = sprintf("UPDATE tk_task SET csa_to_user=%s, csa_last_user=%s WHERE TID=%s", 
                       GetSQLValueString($to_user, "text"),
                       GetSQLValueString($nowuser, "text"),                      
                       GetSQLValueString($taskid, "int"));
  mysql_select_db($database_tankdb, $tankdb);
  $Result2 = mysql_query($updatetask, $tankdb) or die(mysql_error());
  
 $last_use_arr = pushlastuse($to_user_arr["0"], $to_user_arr["1"], $_SESSION['MM_uid']);
 
  $newID = $taskid;
  $to_user_display = $row_touser['tk_display_name']; 
  $newName = $_SESSION['MM_uid'];
  $action = $multilingual_log_edittaskuser."&nbsp;".$to_user_display;

$insertSQL2 = sprintf("INSERT INTO tk_log (tk_log_user, tk_log_action, tk_log_type, tk_log_class, tk_log_description) VALUES (%s, %s, %s, 1, ''  )",
                       GetSQLValueString($newName, "text"),
                       GetSQLValueString($action, "text"),
                       GetSQLValueString($newID, "text"));  
$Result3 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());

 
    $updateGoTo = "default_task_edit.php?editID=".$taskid;
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
 

$msg_to = $to_user; 
$msg_from = $nowuser;
$msg_type = "edituser";
$msg_id = $taskid;
$msg_title = $title;
$mail = send_message( $msg_to, $msg_from, $msg_type, $msg_id, $msg_title );

if($row_Recordset_task['test01'] <> null){

$cc_arr = json_decode($row_Recordset_task['test01'], true);

foreach($cc_arr as $k=>$v){
send_message( $v['uid'], $msg_from, $msg_type, $msg_id, $msg_title, 1 );
}

}
  
  header(sprintf("Location: %s", $updateGoTo));
  }

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_task = sprintf("SELECT * FROM tk_task WHERE TID = %s", GetSQLValueString($taskid, "int"));
$Recordset_task = mysql_query($query_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);
$totalRows_Recordset_task = mysql_num_rows($Recordset_task);

$user_arr = get_user_select();
?>
<link rel="stylesheet" href="bootstrap/css/bootstrap-multiselect.css" type="text/css"/>
<script type="text/javascript" src="bootstrap/js/bootstrap-multiselect.js"></script>
<!-- Initialize the plugin: -->
<script type="text/javascript">
  $(document).ready(function() {
    $('#select4').multiselect({

			        	enableCaseInsensitiveFiltering: true,
						maxHeight: 360,
						filterPlaceholder: '<?php echo $multilingual_user_filter; ?>'
                    });
	
  });
</script>
<form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
<div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $multilingual_tasklog_changeuser; ?></h4>
      </div>
      <div class="modal-body">

			  <div class="form-group col-xs-12">
                <label for="select4"><?php echo $multilingual_tasklog_changeto; ?></label>
                <div>
				
				<select id="select4" name="csa_to_user" onChange="option_gourl(this.value)"  class="form-control">
					<?php if($_SESSION['MM_last'] <> null){ $last_arr = json_decode($_SESSION['MM_last'], true); ?>
					<optgroup label="<?php echo $multilingual_default_task_lastusers;?>">
					<?php foreach($last_arr as $key => $val){  ?>
					 <option value='<?php echo $val["uid"]?>, ,<?php echo $val["uname"]?>' ><?php echo $val["uname"]?></option>
					 <?php } ?>
					</optgroup>
					<?php } ?>
					 
					 <optgroup label="<?php echo $multilingual_default_task_users;?>">
					 <?php foreach($user_arr as $key => $val){  ?>
					 <option value='<?php echo $val["uid"]?>, ,<?php echo $val["name"]?>' 
		  <?php if (!(strcmp($val["uid"], $row_Recordset_task['csa_to_user']))) {echo "selected=\"selected\"";} ?>
		  ><?php 
		   $py = substr( pinyin($val["name"]), 0, 1 );
		  echo $py."-".$val["name"]?></option>
					 <?php } ?>  
					 </optgroup>
                  </select>

                </div>
				<span class="help-block"><?php echo $multilingual_tasklog_changeuser_tips; ?></span>
              </div>
			  <div class="clearboth"></div>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><?php echo $multilingual_global_action_cancel; ?></button>
        <button type="submit" class="btn btn-primary btn-sm" data-loading-text="<?php echo $multilingual_global_wait; ?>"><?php echo $multilingual_global_action_save; ?></button>
		<input type="hidden" name="task_update" value="form2" />
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
