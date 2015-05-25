<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "5") {   
  header("Location: ". $restrictGoTo); 
  exit;
}

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="user_error.php";
  $loginUsername = $_POST['tk_user_login'];
  $LoginRS__query = sprintf("SELECT tk_user_login FROM tk_user WHERE tk_user_login=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_tankdb, $tankdb);
  $LoginRS=mysql_query($LoginRS__query, $tankdb) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$password = "-1";
if (isset($_POST['tk_user_pass'])) {
  $password = $_POST['tk_user_pass'];
}

$tk_password = md5(crypt($password,substr($password,0,2)));

if ( empty( $_POST['tk_user_contact'] ) ){
$tk_user_contact = "'',";
}else{
$tk_user_contact = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_user_contact']), "text"));
}

if ( empty( $_POST['tk_user_email'] ) ){
$tk_user_email = "'',";
}else{
$tk_user_email = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_user_email']), "text"));
}

if ( empty( $_POST['tk_user_remark'] ) ){
$tk_user_remark = "'',";
}else{
$tk_user_remark = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_user_remark']), "text"));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tk_user (tk_user_login, tk_user_pass, tk_display_name, tk_user_rank, tk_user_remark, tk_user_contact, tk_user_email, tk_user_backup1) VALUES (%s, %s, %s, %s, $tk_user_remark $tk_user_contact $tk_user_email '')",
                       GetSQLValueString($_POST['tk_user_login'], "text"),
                       GetSQLValueString($tk_password, "text"),
                       GetSQLValueString($_POST['tk_display_name'], "text"),
                       GetSQLValueString($_POST['tk_user_rank'], "text"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());

  $insertGoTo = "default_user.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<?php require('head.php'); ?>
    <link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="srcipt/lhgcore.js"></script>
    <script type="text/javascript" src="srcipt/lhgcheck.js"></script>
<script type="text/javascript">
J.check.rules = [
    { name: 'tk_user_login', mid: 'user_login', type: 'limit|alpha', requir: true, min: 2, max: 12, warn: '<?php echo $multilingual_user_namequired; ?>|<?php echo $multilingual_user_alpha; ?>' },
	{ name: 'tk_user_pass', mid: 'user_pass', type: 'limit', requir: true, min: 2, max: 8, warn: '<?php echo $multilingual_user_namequired8; ?>' },
	{ name: 'tk_display_name', mid: 'display_name', type: 'limit', requir: true, min: 2, max: 12, warn: '<?php echo $multilingual_user_namequired; ?>' },
	{ name: 'tk_user_pass', mid: 'user_pass2', requir: true, type: 'match', to: 'tk_user_pass2', warn: '<?php echo $multilingual_user_tip_match; ?>' }
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
            <td valign="top" class="gray2">
	 <h4 style="margin-top:40px" ><strong><?php echo $multilingual_user_about; ?></strong></h4>
	 <p >
	 <?php echo $multilingual_user_abouttext; ?>
	 </p>

              
              </td>
          </tr>
        </table></td>
      <td width="75%" valign="top"><table width="98%" border="0" cellspacing="0" cellpadding="5" align="center">
          <tr>
            <td><div class="col-xs-12">
                <h3><?php echo $multilingual_user_new; ?></h3>
              </div>

              <div class="form-group col-xs-12">
                <label for="tk_user_login"><?php echo $multilingual_user_account; ?><span id="user_login" class="red">*</span></label>
                <div>
				<input type="text" name="tk_user_login" id="tk_user_login" value="" placeholder="<?php echo $multilingual_user_account;?>"  class="form-control" />
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_account; ?></span>
              </div>


			  <div class="form-group col-xs-12">
                <label for="tk_user_pass"><?php echo $multilingual_user_password; ?><span class="red" id="user_pass" >*</span></label>
                <div>
				<input type="password" name="tk_user_pass" id="tk_user_pass" value="" placeholder="<?php echo $multilingual_user_password;?>"  class="form-control" />
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_password; ?></span>
              </div>



			  <div class="form-group col-xs-12">
                <label for="tk_user_pass2"><?php echo $multilingual_user_password2; ?><span class="red" id="user_pass2" >*</span></label>
                <div>
				<input type="password" name="tk_user_pass2" id="tk_user_pass2" value="" placeholder="<?php echo $multilingual_user_password2;?>"  class="form-control"  />
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_password2; ?></span>
              </div>




			  <div class="form-group col-xs-12">
                <label for="tk_display_name"><?php echo $multilingual_user_name; ?><span id="display_name" class="red">*</span></label>
                <div>
				<input type="text" name="tk_display_name" id="tk_display_name" value="" placeholder="<?php echo $multilingual_user_name;?>"  class="form-control" />
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_name; ?></span>
              </div>




			  <div class="form-group col-xs-12">
                <label for="tk_user_contact"><?php echo $multilingual_user_contact; ?></label>
                <div>
				<input type="text" name="tk_user_contact" id="tk_user_contact" value="" placeholder="<?php echo $multilingual_user_contact;?>"  class="form-control" />
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_contact; ?></span>
              </div>




			  <div class="form-group col-xs-12">
                <label for="tk_user_email"><?php echo $multilingual_user_email; ?></label>
                <div>
				<input type="text" name="tk_user_email" id="tk_user_email" value="" placeholder="<?php echo $multilingual_user_email;?>"  class="form-control" />
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_mail; ?></span>
              </div>





			  <div class="form-group col-xs-12">
                <label for="tk_user_remark"><?php echo $multilingual_user_remark; ?></label>
                <div>
				<textarea name="tk_user_remark" id="tk_user_remark" class="form-control" rows="5" placeholder="<?php echo $multilingual_user_remark;?>"></textarea>
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_remark; ?></span>
              </div>



			  <div class="form-group col-xs-12">
                <label for="tk_user_rank"><?php echo $multilingual_user_role; ?></label>
                <div>
				<select name="tk_user_rank"  id="tk_user_rank" class="form-control">
	    <option value="0" ><?php echo $multilingual_dd_role_disabled; ?></option>
		<option value="1" ><?php echo $multilingual_dd_role_readonly; ?></option>
		<option value="2" ><?php echo $multilingual_dd_role_guest; ?></option>
        <option value="3" selected="selected"><?php echo $multilingual_dd_role_general; ?></option>
		<option value="4" ><?php echo $multilingual_dd_role_pm; ?></option>
        <option value="5" ><?php echo $multilingual_dd_role_admin; ?></option>		
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




  

           

				</td>
          </tr>
        </table></td>
    </tr>
    <tr class="input_task_bottom_bg" >
	<td></td>
      <td height="50px">
	  <button type="submit" class="btn btn-primary btn-sm submitbutton" name="cont" ><?php echo $multilingual_global_action_save; ?></button>
          <button type="button" class="btn btn-default btn-sm"  onclick="javascript:history.go(-1)" ><?php echo $multilingual_global_action_cancel; ?></button>
          




        <input type="hidden" name="MM_insert" value="form1" /></td>
    </tr>
  </table>



</form>
<?php require('foot.php'); ?>
</body>
</html>

