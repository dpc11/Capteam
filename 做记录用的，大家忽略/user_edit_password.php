<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_Recordset1 = "-1";
if (isset($_GET['UID'])) {
  $colname_Recordset1 = $_GET['UID'];
}

$password = "-1";
if (isset($_POST['tk_user_pass'])) {
  $password = $_POST['tk_user_pass'];
}

$tk_password = md5(crypt($password,substr($password,0,2)));

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE tk_user SET tk_user_pass=%s WHERE uid=%s",
                       GetSQLValueString($tk_password, "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($updateSQL, $tankdb) or die(mysql_error());

  $updateGoTo = "default_user_edit.php?UID=".$colname_Recordset1;
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));

}


mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT * FROM tk_user WHERE uid = %s", GetSQLValueString($colname_Recordset1, "text"));
$Recordset1 = mysql_query($query_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$restrictGoTo = "user_error3.php";
if ($row_Recordset1['uid'] <> $_SESSION['MM_uid'] && $_SESSION['MM_rank'] < "5") {   
  header("Location: ". $restrictGoTo); 
  exit;
}
?>
<script>
        function checkPasswords() {
            var passl = document.getElementById("tk_user_pass");
            var pass2 = document.getElementById("tk_user_pass2");
            if (passl.value != pass2.value)
                pass2.setCustomValidity("<?php echo $multilingual_user_tip_match; ?>");
            else
                pass2.setCustomValidity('');
        }

        function check() {
            document.getElementById('ok').disabled = 'disabled';
        }
    </script>

<form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">


<div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $multilingual_user_edit_password; ?></h4>
      </div>
      <div class="modal-body">

			  <div class="form-group col-xs-12">
                <label for="tk_user_pass"><?php echo $multilingual_user_newpassword; ?><span id="user_pass"></span></label>
                <div>
				
				<input type="password" name="tk_user_pass" id="tk_user_pass" value="" placeholder="<?php echo $multilingual_user_newpassword;?>"  class="form-control" Required onchange="checkPasswords()" />
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_password; ?></span>
              </div>
			  
			  <div class="form-group col-xs-12">
                <label for="tk_user_pass2"><?php echo $multilingual_user_newpassword2; ?><span id="user_pass2" ></span></label>
                <div>
				
				<input type="password" name="tk_user_pass2" id="tk_user_pass2" value="" placeholder="<?php echo $multilingual_user_newpassword2;?>"  class="form-control" Required onchange="checkPasswords()" />
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_password2; ?></span>
              </div>
			  <div class="clearboth"></div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><?php echo $multilingual_global_action_cancel; ?></button>
        <button type="submit" class="btn btn-primary btn-sm" data-loading-text="<?php echo $multilingual_global_wait; ?>"><?php echo $multilingual_global_action_save; ?></button>
		
		<input type="hidden" name="MM_update" value="form2" />
        <input type="hidden" name="ID" value="<?php echo $row_Recordset1['uid']; ?>" />
</div>
</form>

<?php
mysql_free_result($Recordset1);
?>