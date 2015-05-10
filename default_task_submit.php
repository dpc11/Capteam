<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php require_once('dao.php'); ?>
<?php
$task_dao_obj = new task_dao();

$project_id = "-1";
if (isset($_GET['projectid'])) {
  $project_id = $_GET['projectid'];
}
$stage_id = "-1";
if (isset($_GET['stageid'])) {
  $stage_id = $_GET['stageid'];
}
$task_id = "-1";
if (isset($_GET['taskid'])) {
  $task_id = $_GET['taskid'];
}

$myid = $_SESSION['MM_uid'];//当前用户

$SELtaskinfo = "SELECT * FROM tk_task WHERE tid=$task_id";
  mysql_select_db($database_tankdb,$tankdb);
  $Result1 = mysql_query($SELtaskinfo,$tankdb) or die(mysql_error());
  $taskinfo = mysql_fetch_array($Result1);

$SELuserinfo = "SELECT * FROM tk_user WHERE uid=$myid";
  mysql_select_db($database_tankdb,$tankdb);
  $Result2 = mysql_query($SELuserinfo,$tankdb) or die(mysql_error());
  $userinfo = mysql_fetch_array($Result2);

$SELstageinfo = "SELECT * FROM tk_stage WHERE stageid=$stage_id";
  mysql_select_db($database_tankdb,$tankdb);
  $Result3 = mysql_query($SELstageinfo,$tankdb) or die(mysql_error());
  $stageinfo = mysql_fetch_array($Result3);

$taskName = $taskinfo['csa_text'];
//echo $taskName;
$userName = $userinfo['tk_user_login'];
//echo $userName;
$stageFolder = $stageinfo['tk_stage_folder_id'];
//echo $stageFolder;
$docIsExist = $taskinfo['csa_document_id'];
if($docIsExist)//如果已经提交过
{
  $SELdocinfo = "SELECT * FROM tk_document WHERE docid=$docIsExist";
  mysql_select_db($database_tankdb,$tankdb);
  $Result6 = mysql_query($SELdocinfo,$tankdb) or die(mysql_error());
  $docinfo = mysql_fetch_array($Result6);
}

$doc_title = "-1";
$doc_description = "-1";
$doc_attac = "-1";

//标题
if (isset($_POST['tk_doc_title'])) {
  $doc_title= $_POST['tk_doc_title'];
}
//描述
if (isset($_POST['tk_doc_description'])) {
  $doc_description= $_POST['tk_doc_description'];
}
//附件
if (isset($_POST['csa_remark1'])) {
  $doc_attac= $_POST['csa_remark1'];
}

$fd = "0";//文件
/*
$pfiles = "-1";
if (isset($_GET['pfile'])) {
  $pfiles = $_GET['pfile'];
}
*/
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

/*if ( empty( $_POST['tk_doc_description'] ) ){
$tk_doc_description = "'',";
}else{
$tk_doc_description = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_doc_description']), "text"));
}

//附件
if ( empty( $_POST['csa_remark1'] ) ){
$tk_doc_attachment = "'',";
}else{
$tk_doc_attachment = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['csa_remark1']), "text"));
}*/

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    $today_date = date('Y-m-d');
      $now_time = date('Y-m-d H:i:s',time());
      mysql_select_db($database_tankdb, $tankdb);
  if($doc_attac)//有附件
  {
      if($docIsExist)//之前提交过
      {
          $insertSQL = "UPDATE tk_document SET tk_doc_description='$doc_description',
           tk_doc_attachment='$doc_attac' WHERE docid=$docIsExist";

          $Result4 = mysql_query($insertSQL, $tankdb) or die(mysql_error());
          $thisDid = $docIsExist;
      }
      else
      {
            $insertSQL = sprintf("INSERT INTO tk_document (tk_doc_title, tk_doc_description, tk_doc_attachment, 
            tk_doc_pid, tk_doc_parentdocid, tk_doc_type,tk_doc_create, tk_doc_lastupdate, 
            tk_doc_backup1, tk_doc_del_status)
     VALUES (%s, %s, %s, $project_id,$stageFolder,2,$myid,'$now_time',0,1)",
                       GetSQLValueString($_POST['tk_doc_title'], "text"),
                       GetSQLValueString($_POST['tk_doc_description'], "text"),
                       GetSQLValueString($_POST['csa_remark1'], "text"));

          $Result4 = mysql_query($insertSQL, $tankdb) or die(mysql_error());
          $thisDid = mysql_insert_id();
      }
  }
  else //没有附件
  {
    if($docIsExist)//之前提交过
    {
          $insertSQL = "UPDATE tk_document SET tk_doc_description='$doc_description',
           tk_doc_attachment= null WHERE docid=$docIsExist";

           $Result4 = mysql_query($insertSQL, $tankdb) or die(mysql_error());
          $thisDid = $docIsExist;
    }
    else
    {
      $insertSQL = sprintf("INSERT INTO tk_document (tk_doc_title, tk_doc_description,  
            tk_doc_pid, tk_doc_parentdocid, tk_doc_type,tk_doc_create, tk_doc_lastupdate, 
            tk_doc_backup1, tk_doc_del_status)
       VALUES (%s, %s, $project_id,$stageFolder,2,$myid,'$now_time',0,1)",
                       GetSQLValueString($_POST['tk_doc_title'], "text"),
                       GetSQLValueString($_POST['tk_doc_description'], "text"));

      $Result4 = mysql_query($insertSQL, $tankdb) or die(mysql_error());
          $thisDid = mysql_insert_id();
    }
  }

  $updateSQL = "UPDATE tk_task SET csa_document_id=$thisDid,csa_commit_time='$today_date',
                csa_status=3 WHERE tid=$task_id";

                mysql_select_db($database_tankdb, $tankdb);
          $Result5 = mysql_query($updateSQL, $tankdb) or die(mysql_error());

  date_default_timezone_set('PRC');//编辑任务的log记录
              $action='提交了任务';
              $taskid=$_POST['TID'];
              $timenow=date('Y-m-d H:i:s',time());
              $insertSQLLog=sprintf("INSERT into tk_log(tk_log_user,tk_log_action,tk_log_time,tk_log_type,tk_log_class)
                VALUES(%s,'$action','$timenow','$task_id','3')",GetSQLValueString($_SESSION['MM_uid'], "int"));
 
               mysql_select_db($database_tankdb, $tankdb);
              $Result2 = mysql_query($insertSQLLog, $tankdb) or die(mysql_error());
/**
 * 
 * __   (__`\
 * (__`\   \\`\
 *  `\\`\   \\ \
 *    `\\`\  \\ \
 *      `\\`\#\\ \#
 *        \_ ##\_ |##
 *        (___)(___)##
 *         (0)  (0)`\##
 *          |~   ~ , \##
 *          |      |  \##
 *          |     /\   \##         __..---'''''-.._.._
 *          |     | \   `\##  _.--'                _  `.
 *          Y     |  \    `##'                     \`\  \
 *         /      |   \                             | `\ \
 *        /_...___|    \                            |   `\\
 *       /        `.    |                          /      ##
 *      |          |    |                         /      ####
 *      |          |    |                        /       ####
 *      | () ()    |     \     |          |  _.-'         ##
 *      `.        .'      `._. |______..| |-'|
 *        `------'           | | | |    | || |
 *                           | | | |    | || |
 *                           | | | |    | || |
 *                           | | | |    | || |     神兽保佑，永无bug
 *                     _____ | | | |____| || |
 *                    /     `` |-`/     ` |` |
 *                    \________\__\_______\__\
 *                     """""""""   """""""'"""
 *
 */

$task_obj = $task_dao_obj->get_task_by_id($task_id);
//提交任务，给被指派的人发消息
$msg_to = $task_obj->from;
$msg_from = $task_obj->to;
$msg_type = "taskcommit";
$msg_id = $task_id;
$msg_title = $taskName;
$mail = send_message( $msg_to, $msg_from, $msg_type, $msg_id, $msg_title );
//提交任务，给抄送的人发消息
$cc_post = $task_obj->testto;
if($cc_post <> null){
    $cc_arr = json_decode($cc_post, true);
    foreach($cc_arr as $k=>$v){
        send_message( $v['uid'], $msg_from, $msg_type, $msg_id, $msg_title, 1 );
    }
}
  /*
  $newID = mysql_insert_id();
  $docID = $newID;
  $newName = $_SESSION['MM_uid'];

$insertSQL2 = sprintf("INSERT INTO tk_log (tk_log_user, tk_log_action, tk_log_type, tk_log_class, tk_log_description) VALUES (%s, %s, %s, 2, '' )",
                       GetSQLValueString($newName, "text"),
                       GetSQLValueString($multilingual_log_adddoc, "text"),
                       GetSQLValueString($docID, "text"));  
  $Result2 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());
*/
if ( $pfiles== "1") {
	  $pf = "&pfile=1";
	  } else {
	  $pf = "";
	  }
$pagetabs = "mcfile";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}
$ptab = "&pagetab=".$pagetabs;	  
//  $insertGoTo = "file_view.php?recordID=$newID&folder=$fd&projectID=$project_id".$pf.$ptab;
$insertGoTo = "default_task_edit.php?editID=$task_id";
 
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
        {
            name: 'tk_doc_title',
            mid: 'doctitle',
            type: 'limit',
            requir: true,
            min: 2,
            max: 30,
            warn: '<?php echo $multilingual_announcement_titlerequired; ?>'
        }

];

    window.onload = function () {
        J.check.regform('form1');
    }
</script>
<script charset="utf-8" src="editor/kindeditor.js"></script>
<script charset="utf-8" src="editor/lang/zh_CN.js"></script>
<script>
    var editor;
    KindEditor.ready(function (K) {
        editor = K.create('#tk_doc_description', {
            width: '100%',
            height: '500px',
            items: [
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

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>

            <!-- 左边20%的宽度的树或者说明  -->
            <td width="20%" class="input_task_right_bg" valign="top">
                <table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>
                        <td valign="top" class="gray2">
                            <h4 style="margin-top:40px"><strong><?php echo $multilingual_project_file_tiptitle; ?></strong></h4>
                            <p>
                                <?php echo $multilingual_project_file_tiptext; ?>
                            </p>

                        </td>
                    </tr>
                </table>
            </td>

            <!-- 右边80%宽度的主体内容 -->
            <td width="80%" valign="top">
                <table width="98%" border="0" cellspacing="0" cellpadding="5" align="center">
                    <tr>
                        <td>
                            <div class="col-xs-12">
                                <h3><?php echo $multilingual_project_file_submittask; ?></h3>
                            </div>

                            <!-- 提交任务标题 -->
                            <div class="form-group col-xs-12">
                                <label for="tk_doc_title">
                                    <?php echo $multilingual_project_file_title; ?><span id="doctitle"></span>
                                </label>
                                <div>
                                    <input type="text" name="tk_doc_title" id="tk_doc_title" 
                                    value="<?php echo $taskName?>——<?php echo $userName?>" 
                                    placeholder="<?php echo $multilingual_project_file_filetitle;?>" class="form-control" readonly="true"/>
                                </div>
                            </div>

                            <!-- 提交任务描述 -->
                            <div class="form-group col-xs-12">
                                <label for="tk_doc_description">
                                    <?php echo $multilingual_project_file_filetext; ?>
                                </label>
                                <div>
                                    <textarea name="tk_doc_description" id="tk_doc_description">
                                        <?php 
                                          if($doc_description!=-1)
                                          {
                                            echo $doc_description;
                                          }
                                          else
                                          {
                                            if($docIsExist)
                                              echo $docinfo['tk_doc_description'];
                                          }
                                        ?>
                                    </textarea>
                                </div>
                            </div>

                            <!-- 提交任务附件 -->
                            <div class="form-group  col-xs-12">
                                <label for="csa_remark1">
                                    <?php echo $multilingual_upload_attachment; ?>
                                </label>

                                <div class="input-group">
                                    <input type="text" name="csa_remark1" id="csa_remark1" 
                                    value="<?php 
                                      if($doc_attac!=-1)
                                        {
                                          echo $doc_attac;
                                        }
                                      else
                                          {
                                            if($docIsExist)
                                              echo $docinfo['tk_doc_attachment'];
                                          }
                                    ?>" 
                                    placeholder="<?php echo $multilingual_upload_attachment; ?>" class="form-control">
                                    <span class="input-group-btn">
        <button class="btn btn-default" type="button" onClick="openBrWindow('upload_file.php','<?php echo $multilingual_global_upload; ?>','width=450,height=235')"><?php echo $multilingual_global_upload; ?></button>
      </span>
                                </div>
                                <span class="help-block"><?php echo $multilingual_upload_tip3; ?></span>
                            </div>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="input_task_bottom_bg">
            <td></td>
            <td height="50px">
                
                <!-- 提交按钮 -->
                <button type="submit" class="btn btn-primary btn-sm submitbutton" name="cont">
                    <?php echo $multilingual_global_action_save; ?>
                </button>
                <button type="button" class="btn btn-default btn-sm" onClick="javascript:history.go(-1);">
                    <?php echo $multilingual_global_action_cancel; ?>
                </button>


                <input type="hidden" name="prod" id="prod" value="<?php echo $project_id; ?>" />
                <input type="hidden" name="tk_doc_class2" id="tk_doc_class2" value="<?php echo $p_id; ?>" />
                <input name="tk_doc_create" type="hidden" value="<?php echo " {$_SESSION[ 'MM_uid']} "; ?>" />
                <input name="tk_doc_createtime" type="hidden" value="<?php echo date(" Y-m-d H:i:s "); ?>" />
                <input type="hidden" name="tk_doc_backup1" id="tk_doc_backup1" value="<?php echo $fd; ?>" />


                <input type="hidden" name="MM_insert" value="form1" />
            </td>
        </tr>
    </table>

</form>
<?php require( 'foot.php'); ?>
</body>

</html>