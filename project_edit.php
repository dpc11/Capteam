<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_Recordset1 = "-1";
if (isset($_GET['editID'])) {
  $colname_Recordset1 = $_GET['editID'];
}

if ( empty( $_POST['project_text'] ) ){
$project_text = "project_text='',";
}else{
$project_text = sprintf("project_text=%s,", GetSQLValueString(str_replace("%","%%",$_POST['project_text']), "text"));
}

if ( empty( $_POST['project_start'] ) ){
$project_start = "project_start='0000-00-00',";
}else{
$project_start = sprintf("project_start=%s,", GetSQLValueString($_POST['project_start'], "date"));
}

if ( empty( $_POST['project_end'] ) ){
$project_end = "project_end='0000-00-00',";
}else{
$project_end = sprintf("project_end=%s,", GetSQLValueString($_POST['project_end'], "date"));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  //把当前时间作为最后一次修改时间
  $update_project_lastupdate = date("Y-m-d H:i:s",time());
  $today_date = date('Y-m-d');
  $now_time = date('Y-m-d H:i:s',time());

  if($_POST['project_end']<$today_date)
  {
          //echo("illegal");
            $dateError = -1;//结束时间小于今天
  }else if($_POST['project_end'] <$_POST['project_start'])
  {
          //echo("can't");
            $dateError = -2;//结束时间小于开始时间
  }else{
      //更新数据库
      $updateSQL = sprintf("UPDATE tk_project SET project_name=%s, $project_text $project_start $project_end  project_lastupdate = '$update_project_lastupdate' WHERE id=%s",
                           GetSQLValueString($_POST['project_name'], "text"),
                           GetSQLValueString($_POST['id'], "int"));
    echo $updateSQL;
      mysql_select_db($database_tankdb, $tankdb);
      $Result1 = mysql_query($updateSQL, $tankdb) or die(mysql_error());
      
      date_default_timezone_set('PRC');
      $editlogid=$_POST['id'];
              $timenow=date('Y-m-d H:i:s',time());
              $insertSQLLog=sprintf("INSERT into tk_log(tk_log_user,tk_log_action,tk_log_time,tk_log_type,tk_log_class)
                VALUES(%s,'编辑了项目','$timenow','$editlogid','1')",GetSQLValueString($_SESSION['MM_uid'], "int"));
 
               mysql_select_db($database_tankdb, $tankdb);
              $Result2 = mysql_query($insertSQLLog, $tankdb) or die(mysql_error());

      $updateGoTo = "project_view.php?recordID=$colname_Recordset1";
      if (isset($_SERVER['QUERY_STRING'])) {
        $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
        $updateGoTo .= $_SERVER['QUERY_STRING'];
      }
      header(sprintf("Location: %s", $updateGoTo));
  }
}


mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT * FROM tk_project WHERE id = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset2 = "SELECT * FROM tk_status_project ORDER BY task_status_pbackup1 ASC";
$Recordset2 = mysql_query($query_Recordset2, $tankdb) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$user_arr = get_all_user_select();
$user_arr_selected = get_user_select($colname_Recordset1);
//$user_arr_selected = get_user_select(GetSQLValueString($colname_Recordset1, "int"))；

$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "4" && ($row_Recordset1['project_to_user'] <> $_SESSION['MM_uid'] || $_SESSION['MM_rank'] < "2")) {   
  header("Location: ". $restrictGoTo); 
  exit;
}


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
//更新team表中的数据
//先判断原来项目成员都有谁
$arr[] = array(); //接收被删除成员的结果的数组
  mysql_select_db($database_tankdb, $tankdb);
$query_deleterecordset = "SELECT * FROM tk_team WHERE tk_team_pid=$colname_Recordset1";
$Recordset2 = mysql_query($query_deleterecordset, $tankdb) or die(mysql_error());

$totalRows_Recordset2 = mysql_num_rows($Recordset2);

  
  while($row = mysql_fetch_array($Recordset2,MYSQL_ASSOC))
{
      $arr[]=$row;
}



//删除原有的项目成员
$deleteMemSQL = sprintf("DELETE from tk_team WHERE tk_team_pid = %s", GetSQLValueString($colname_Recordset1, "int"));
mysql_select_db($database_tankdb, $tankdb);
$Result1 = mysql_query($deleteMemSQL, $tankdb) or die(mysql_error());
date_default_timezone_set('PRC');
    $action='删除了成员:'.$tk_team_uid;
              $timenow=date('Y-m-d H:i:s',time());
              $insertSQLLog=sprintf("INSERT into tk_log(tk_log_user,tk_log_action,tk_log_time,tk_log_type,tk_log_class)
                VALUES(%s,'$action','$timenow','$tk_team_pid','1')",GetSQLValueString($_SESSION['MM_uid'], "int"));
 
               mysql_select_db($database_tankdb, $tankdb);
              $Result2 = mysql_query($insertSQLLog, $tankdb) or die(mysql_error());
//往数据库中插入新成员
    //插入项目负责人
    $tk_team_pid= GetSQLValueString($colname_Recordset1, "int");//项目id
    $tk_team_uid= $_SESSION['MM_uid'];//用户id
    $tk_team_ulimit=3;//用户权限,组长是3
    $tk_team_del_status=1;//该用户在该项目中的删除状态
    $tk_team_jointeamtime=date('Y-m-d H:i:s');//该用户加入该项目的时间，PHP date() 函数会返回服
    /*开始操作数据库了，insert语句*/
    $addnewmemSQL="INSERT INTO tk_team (tk_team_pid,tk_team_uid,tk_team_ulimit,tk_team_del_status,tk_team_jointeamtime)
    VALUES ($tk_team_pid,$tk_team_uid,$tk_team_ulimit,$tk_team_del_status,'$tk_team_jointeamtime')";
    mysql_select_db($database_tankdb, $tankdb);
    $Result1 = mysql_query($addnewmemSQL, $tankdb) or die(mysql_error());

//获取选中的项目成员
$user_list= $_POST['project_to_user'];
    //往数据库team表中插入各个成员的信息
    foreach ($user_list as $a_user) {
        $tk_team_uid= $a_user;//用户id
        $tk_team_ulimit=1;//用户权限,组长是3，组员是1，副组长是2
        $tk_team_del_status=1;//该用户在该项目中的删除状态
        $tk_team_jointeamtime=date('Y-m-d H:i:s');//该用户加入该项目的时间，PHP date() 函数会返回服


        /*开始操作数据库了，insert语句*/
        $addnewmemSQL="INSERT INTO tk_team (tk_team_pid,tk_team_uid,tk_team_ulimit,tk_team_del_status,tk_team_jointeamtime)
        VALUES ($tk_team_pid,$tk_team_uid,$tk_team_ulimit,$tk_team_del_status,'$tk_team_jointeamtime')";
        mysql_select_db($database_tankdb, $tankdb);
        $Result1 = mysql_query($addnewmemSQL, $tankdb) or die(mysql_error());
    }

    $arr1[] = array(); //将要被添加的成员数组
mysql_select_db($database_tankdb, $tankdb);
$query_deleterecordset1 = "SELECT * FROM tk_team WHERE tk_team_pid=$tk_team_pid";
$Recordset3 = mysql_query($query_deleterecordset1, $tankdb) or die(mysql_error());
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

  while($row1 = mysql_fetch_array($Recordset3,MYSQL_ASSOC))
{  
      $arr1[]=$row1;
}

$ids1 = array();
$ids1 = array_reduce($arr, create_function('$v,$w', '$v[$w["tk_team_uid"]]=$w["tk_team_uid"];return $v;'));
print_r($ids1);
$ids2 = array();
$ids2 = array_reduce($arr1, create_function('$v,$w', '$v[$w["tk_team_uid"]]=$w["tk_team_uid"];return $v;'));
print_r($ids2);

$onemoretwo=array();
$onemoretwo=array_diff($ids2,$ids1);
print_r($onemoretwo);

mysql_select_db($database_tankdb, $tankdb);
foreach($onemoretwo as $a){//在循环中只要用到值
  $searchmemSQL="SELECT* FROM tk_user WHERE uid=$a";
        mysql_select_db($database_tankdb, $tankdb);
        $Result1 = mysql_query($searchmemSQL, $tankdb) or die(mysql_error());
        
        $FoundUser = mysql_num_rows($Result1);
          if ($FoundUser) {  
            $loginStrDisplayname  = mysql_result($Result1,0,'tk_display_name');
          }
date_default_timezone_set('PRC');
    $action='添加了成员:'.$a.'--'.$loginStrDisplayname;
              $timenow=date('Y-m-d H:i:s',time());
              $insertSQLLog=sprintf("INSERT into tk_log(tk_log_user,tk_log_action,tk_log_time,tk_log_type,tk_log_class)
                VALUES(%s,'$action','$timenow','$tk_team_pid','1')",GetSQLValueString($_SESSION['MM_uid'], "int"));
 
               
              $Result2 = mysql_query($insertSQLLog, $tankdb) or die(mysql_error());}


$twomoreone=array();
$twomoreone=array_diff($ids1,$ids2);
print_r($twomoreone);

mysql_select_db($database_tankdb, $tankdb);
foreach($twomoreone as $b){//在循环中只要用到值
  $searchmemSQL="SELECT* FROM tk_user WHERE uid=$b";
        mysql_select_db($database_tankdb, $tankdb);
        $Result1 = mysql_query($searchmemSQL, $tankdb) or die(mysql_error());
        
        $FoundUser = mysql_num_rows($Result1);
          if ($FoundUser) {  
            $loginStrDisplayname  = mysql_result($Result1,0,'tk_display_name');
          }
date_default_timezone_set('PRC');
    $action='删除了成员:'.$b.'--'.$loginStrDisplayname;
              $timenow=date('Y-m-d H:i:s',time());
              $insertSQLLog=sprintf("INSERT into tk_log(tk_log_user,tk_log_action,tk_log_time,tk_log_type,tk_log_class)
                VALUES(%s,'$action','$timenow','$tk_team_pid','1')",GetSQLValueString($_SESSION['MM_uid'], "int"));
 
               
              $Result2 = mysql_query($insertSQLLog, $tankdb) or die(mysql_error());}
}

?>
<?php require('head.php'); ?>
    <link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="srcipt/lhgcore.js"></script>
    <script type="text/javascript" src="srcipt/lhgcheck.js"></script>
<link rel="stylesheet" href="bootstrap/css/bootstrap-multiselect.css" type="text/css"/>
<script type="text/javascript" src="bootstrap/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="bootstrap/css/datepicker3.css" type="text/css"/>
<script type="text/javascript" src="bootstrap/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="bootstrap/js/locales/bootstrap-datepicker.zh-CN.js"></script>


	<script type="text/javascript">
	$(function() {
		$('#datepicker').datepicker({
			format: "yyyy-mm-dd"
	<?php if ($language=="cn") {echo ", language: 'zh-CN'" ;}?>
		});
		$('#datepicker2').datepicker({
			format: "yyyy-mm-dd"
	<?php if ($language=="cn") {echo ", language: 'zh-CN'" ;}?>
		});
	});
	</script>
<script type="text/javascript">
J.check.rules = [
    { name: 'project_name', mid: 'projecttitle', type: 'limit', requir: true, min: 2, max: 32, warn: '<?php echo $multilingual_projectstatus_titlerequired; ?>' },
	//{ name: 'datepicker', mid: 'datepicker_msg', type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' },
	//{ name: 'datepicker2', mid: 'datepicker2_msg', type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' }
	
];

window.onload = function()
{
    J.check.regform('form1');
}
//function option_gourl(str)
//{
//if(str == '-1')window.open('user_add.php');
//if(str == '-2')window.open('project_status.php');
//}
</script>
<script charset="utf-8" src="editor/kindeditor.js"></script>
<script charset="utf-8" src="editor/lang/zh_CN.js"></script>
<script>
        var editor;
        KindEditor.ready(function(K) {
                editor = K.create('#project_text', {
			width : '100%',
			height: '350px',
			items:[
        'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'cut', 'copy', 'paste',
        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
        'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
        'flash', 'media', 'insertfile', 'table', 'hr', 'map', 'code', 'pagebreak', 'anchor', 
        'link', 'unlink', '|', 'about'
]
});
        });
</script>
<script type="text/javascript">
  $(document).ready(function() {
					$('#select2').multiselect({

			        	enableCaseInsensitiveFiltering: true,
						maxHeight: 360,
						filterPlaceholder: '<?php echo $multilingual_user_filter; ?>'
                    });						
  });
</script>

<form action="<?php echo $editFormAction; ?>" method="post" name="myform" id="form1">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="20%" class="input_task_right_bg" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td valign="top"  class="gray2">
	 <h4 style="margin-top:40px; margin-left: 5px;" ><strong><?php echo $multilingual_project_view_nowbs; ?></strong></h4>
	 <p >
	 <?php echo $multilingual_project_add_text; ?></p>
              
              </td>
          </tr>
        </table></td>
      <td width="80%" valign="top"><table width="98%" border="0" cellspacing="0" cellpadding="5" align="center">
          <tr>
            <td><div class="col-xs-12">
                <h3><?php echo $multilingual_projectlist_edit; ?></h3>
              </div>

              <div class="form-group col-xs-12">
                <label for="project_name"><?php echo $multilingual_project_title; ?><span id="projecttitle"></span></label>
                <div>
				<input type="text" name="project_name" id="project_name" value="<?php echo $row_Recordset1['project_name']; ?>" class="form-control" placeholder="<?php echo $multilingual_project_title_tips; ?>" />
				
                </div>
              </div>
			  	  
			  <div class="form-group  col-xs-12">
                <label for="select2" ><?php echo $multilingual_project_touser; ?><span id="csa_to_user_msg"></span></label>
                <div >
                  <select name="project_to_user[]" id="select2" size="6" multiple class="form-control">
                      <?php 
                      //往数组中添加uid
                      $user_selected = array();
                      foreach ($user_arr_selected as $key_selected => $val_selected) {
                          array_push($user_selected, $val_selected["uid"]);
                      }

                      foreach($user_arr as $key => $val){ 
                              if($val["uid"] <> $_SESSION["MM_uid"]){
                                if(in_array($val["uid"], $user_selected)){ ?>
                                <option value="<?php echo $val["uid"]?>" <?php echo "selected=\"selected\"";?>><?php echo $val["uid"]."  ".$val["name"];?></option>
                       <?php         }else{  ?>
                                <option value='<?php echo $val["uid"]?>'><?php echo $val["uid"]."  ".$val["name"];?></option>
                       <?php          }
                       ?>
              
                     <?php
                     }} ?>  
          
                      </select>
                </div>
                <span class="help-block"><?php echo $multilingual_project_tips2; ?></span> </div>
				 
              <div class="form-group col-xs-12">
                <label for="project_text"><?php echo $multilingual_project_description; ?></label>
                <div>
				  <textarea name="project_text"  id="project_text"><?php echo htmlentities($row_Recordset1['project_text'], ENT_COMPAT, 'utf-8'); ?></textarea>
                </div>
              </div>
              

				<div class="form-group col-xs-12">
                <label for="datepicker"><?php echo $multilingual_project_start; ?><!--<span id="datepicker_msg"></span>-->
                      <lable style="color:#F00;font-size:14px">
                            <?php if($dateError==-2) { echo ('&nbsp&nbsp&nbsp');echo "结束时间小于开始时间";} ?>
                        </lable>
                </label>
                <div>
				            <input type="text" name="project_start" id="datepicker" value="<?php echo $row_Recordset1['project_start']; ?>"  class="form-control" />
                </div>
        </div>
			  
              <div class="form-group col-xs-12">
                <label for="datepicker2"><?php echo $multilingual_project_end; ?><!--<span id="datepicker2_msg"></span>-->
                    <lable style="color:#F00;font-size:14px">
                                <?php if($dateError==-2) {echo ('&nbsp&nbsp&nbsp');echo "结束时间小于开始时间";} else if ($dateError==-1) {echo ('&nbsp&nbsp&nbsp'); echo "结束时间小于今天";} ?>
                       </lable>
                </label>
                <div>
				<input type="text" name="project_end" value="<?php echo $row_Recordset1['project_end']; ?>" id="datepicker2"  class="form-control" />
                </div>
              </div>	  
             
				</td>
          </tr>
        </table></td>
    </tr>
    <tr class="input_task_bottom_bg">
	<td></td>
      <td  height="50px">
	  
	  <button type="submit" class="btn btn-primary btn-sm submitbutton" name="cont" ><?php echo $multilingual_global_action_save; ?></button>
          <button type="button" class="btn btn-default btn-sm" onClick="javascript:history.go(-1);"><?php echo $multilingual_global_action_cancel; ?></button>
          

        <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id" value="<?php echo $row_Recordset1['id']; ?>" /></td>
    </tr>
  </table>
</form>
<?php require('foot.php'); ?>
</body>
</html>
<?php
mysql_free_result($Recordset1);
mysql_free_result($Recordset2);
?>