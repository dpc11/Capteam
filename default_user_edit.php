<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "2") {   
  header("Location: ". $restrictGoTo); 
  exit;
}

$colname_Recordset1 = "-1";
if (isset($_GET['UID'])) {
  $colname_Recordset1 = $_GET['UID'];
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ( empty( $_POST['tk_user_remark'] ) ){
$tk_user_remark = "tk_user_remark='',";
}else{
$tk_user_remark = sprintf("tk_user_remark=%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_user_remark']), "text"));
}

if ( empty( $_POST['tk_user_contact'] ) ){
$tk_user_contact = "tk_user_contact='',";
}else{
$tk_user_contact = sprintf("tk_user_contact=%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_user_contact']), "text"));
}

if ( empty( $_POST['tk_user_email'] ) ){
$tk_user_email = "tk_user_email=''";
}else{
$tk_user_email = sprintf("tk_user_email=%s", GetSQLValueString(str_replace("%","%%",$_POST['tk_user_email']), "text"));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tk_user SET tk_display_name=%s, tk_user_rank=%s, $tk_user_remark $tk_user_contact $tk_user_email WHERE uid=%s",
                       
                       GetSQLValueString($_POST['tk_display_name'], "text"),
                       GetSQLValueString($_POST['tk_user_rank'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($updateSQL, $tankdb) or die(mysql_error());

  $updateGoTo = "user_view.php?recordID=$colname_Recordset1";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }

  header(sprintf("Location: %s", $updateGoTo));
}
?>
<?php require('head.php'); ?>
<link href="skin/themes/base/custom.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgcheck.js"></script>
<script type="text/javascript">
J.check.rules = [
	{ name: 'tk_display_name', mid: 'display_name', type: 'limit', requir: true, min: 2, max: 12, warn: '<?php echo $multilingual_user_namequired; ?>' }
	
];

window.onload = function()
{
    J.check.regform('form1');
} 
</script>

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="25%" class="input_task_right_bg" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td valign="top"  class="gray2">
	 <h4 style="margin-top:40px" ><strong><?php echo $multilingual_user_tiptitle; ?></strong></h4>
	 <p >
	 <?php echo $multilingual_user_tiptext; ?></p>

              
              </td>
          </tr>
        </table></td>
      <td width="75%" valign="top"><table width="98%" border="0" cellspacing="0" cellpadding="5" align="center">
          <tr>
            <td><div class="col-xs-12">
                <h3><?php echo $multilingual_user_edit_title; ?></h3>
              </div>

              <div class="form-group col-xs-12">
                <label for="tk_user_login"><?php echo $multilingual_user_account; ?></label>
                <div>
				<?php echo $row_Recordset1['tk_user_login']; ?>
                </div>
              </div>
			  
			  <div class="form-group col-xs-12">
                <label ><?php echo $multilingual_user_password; ?></label>
                <div>
				<a data-toggle="modal" href="user_edit_password.php?UID=<?php echo $colname_Recordset1; ?>" data-target="#myModal"><?php echo $multilingual_user_edit_password; ?></a>
                </div>
              </div>
			  
			  




			  <div class="form-group col-xs-12">
                <label for="tk_display_name"><?php echo $multilingual_user_name; ?><span id="display_name" class="red">*</span></label>
                <div>
				<input type="text" name="tk_display_name" id="tk_display_name" value="<?php echo $row_Recordset1['tk_display_name']; ?>" placeholder="<?php echo $multilingual_user_name;?>"  class="form-control"/>
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_name; ?></span>
              </div>




			  <div class="form-group col-xs-12">
                <label for="tk_user_contact"><?php echo $multilingual_user_contact; ?></label>
                <div>
				
				<input type="text" name="tk_user_contact" id="tk_user_contact" value="<?php echo $row_Recordset1['tk_user_contact']; ?>"  placeholder="<?php echo $multilingual_user_contact;?>"  class="form-control" />

                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_contact; ?></span>
              </div>




			  <div class="form-group col-xs-12">
                <label for="tk_user_email"><?php echo $multilingual_user_email; ?></label>
                <div>
				<input type="text" name="tk_user_email" id="tk_user_email" value="<?php echo $row_Recordset1['tk_user_email']; ?>"  placeholder="<?php echo $multilingual_user_email;?>"  class="form-control"/>
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_mail; ?></span>
              </div>





			  <div class="form-group col-xs-12">
                <label for="tk_user_remark"><?php echo $multilingual_user_remark; ?></label>
                <div>
				<textarea name="tk_user_remark" id="tk_user_remark" class="form-control" rows="5" placeholder="<?php echo $multilingual_user_remark;?>"><?php echo $row_Recordset1['tk_user_remark']; ?></textarea>
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_remark; ?></span>
              </div>


<?php if ($_SESSION['MM_rank'] > "4") { ?>
			  <div class="form-group col-xs-12">
                <label for="tk_user_rank"><?php echo $multilingual_user_role; ?></label>
                <div>
				<select name="tk_user_rank"  id="tk_user_rank" class="form-control">
            <option value="0" <?php if (!(strcmp("0", htmlentities($row_Recordset1['tk_user_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $multilingual_dd_role_disabled; ?></option>
            <option value="1" <?php if (!(strcmp("1", htmlentities($row_Recordset1['tk_user_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $multilingual_dd_role_readonly; ?></option>
            <option value="2" <?php if (!(strcmp("2", htmlentities($row_Recordset1['tk_user_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $multilingual_dd_role_guest; ?></option>
            <option value="3" <?php if (!(strcmp("3", htmlentities($row_Recordset1['tk_user_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $multilingual_dd_role_general; ?></option>
            <option value="4" <?php if (!(strcmp("4", htmlentities($row_Recordset1['tk_user_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $multilingual_dd_role_pm; ?></option>
            <option value="5" <?php if (!(strcmp("5", htmlentities($row_Recordset1['tk_user_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $multilingual_dd_role_admin; ?></option>
          </select>

                </div>
				<span class="help-block">

<table width="100%" border="1" cellspacing="0" cellpadding="5" class="rank_talbe">
        <tr>
          <td><?php echo $multilingual_user_role; ?></td>
          <td align="center"><?php echo $multilingual_rank1; ?></td>
          <td align="center"><?php echo $multilingual_rank2; ?></td>
          <td align="center"><?php echo $multilingual_rank3; ?></td>
          <td align="center"><?php echo $multilingual_rank4; ?></td>
          <td align="center"><?php echo $multilingual_rank5; ?></td>
          <td align="center"><?php echo $multilingual_rank6; ?></td>
          <td align="center"><?php echo $multilingual_rank7; ?></td>
          <td align="center"><?php echo $multilingual_rank8; ?></td>
          <td align="center"><?php echo $multilingual_rank9; ?></td>
        </tr>
        <tr>
          <td><?php echo $multilingual_dd_role_disabled; ?></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
        </tr>
        <tr>
          <td><?php echo $multilingual_dd_role_readonly; ?></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
        </tr>
        <tr>
          <td><?php echo $multilingual_dd_role_guest; ?></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
        </tr>
        <tr>
          <td><?php echo $multilingual_dd_role_general; ?></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
        </tr>
        <tr>
          <td><?php echo $multilingual_dd_role_pm; ?></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
        </tr>
        <tr>
          <td><?php echo $multilingual_dd_role_admin; ?></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
        </tr>
      </table>
</span>
              </div>
<?php } else { ?>
          <input name="tk_user_rank" type="hidden" value="<?php echo $row_Recordset1['tk_user_rank'];?>"  />
            
		   <?php } ?>



  

           

				</td>
          </tr>
        </table></td>
    </tr>
    <tr class="input_task_bottom_bg" >
	<td></td>
      <td height="50px">
	  <button type="submit" class="btn btn-primary btn-sm submitbutton" name="cont" ><?php echo $multilingual_global_action_save; ?></button>
          <button type="button" class="btn btn-default btn-sm"  onclick="javascript:history.go(-1)" ><?php echo $multilingual_global_action_cancel; ?></button>
          
<input type="hidden" name="MM_update" value="form1" />
      <input type="hidden" name="ID" value="<?php echo $row_Recordset1['uid']; ?>" /></td>
    </tr>
  </table>
  
</form>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php require('foot.php'); ?>
</body></html><?php
mysql_free_result($Recordset1);
?>
