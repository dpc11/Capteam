<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$maxRows_DetailRS1 = 10;
$pageNum_DetailRS1 = 0;
$score_error = 0;
if (isset($_GET['pageNum_DetailRS1'])) {
  $pageNum_DetailRS1 = $_GET['pageNum_DetailRS1'];
}
$startRow_DetailRS1 = $pageNum_DetailRS1 * $maxRows_DetailRS1;
$currentPage = $_SERVER["PHP_SELF"];

$pagetabs = "mcfile";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}

/*$colname_DetailRS1 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS1 = $_GET['recordID'];
}*/

$task_id= "-1";
if (isset($_GET['taskid'])) {
  $task_id = $_GET['taskid'];
}

/*if ($project_id <> "-1") {
  $inproject = " inner join tk_project on tk_document.tk_doc_class1=tk_project.id ";
} else { $inproject = " ";}*/

$filenames = "";
if (isset($_GET['filetitle'])) {
  $filenames = $_GET['filetitle'];
}

$pfiles = "-1"; //判断是否是项目文档
if (isset($_GET['pfile'])) {
  $pfiles = $_GET['pfile'];
}

$check_result="-1";
$check_opinion="-1";
$check_score="-1";
//结果
if (isset($_POST['csa_to_user'])) {
  $check_result= $_POST['csa_to_user'];
}

//意见
if (isset($_POST['examtext'])) {
  $check_opinion= $_POST['examtext'];
}
echo $check_opinion;
//分数
if (isset($_POST['examscore'])) {
  $check_score= $_POST['examscore'];
}


mysql_select_db($database_tankdb, $tankdb);
/*$query_DetailRS1 = sprintf("SELECT *, 
tk_user1.tk_display_name as tk_display_name1, 
FROM tk_document 
inner join tk_user as tk_user1 on tk_document.tk_doc_create=tk_user1.uid   
$inproject 
WHERE tk_document.docid = %s", GetSQLValueString($colname_DetailRS1, "int"));
$query_limit_DetailRS1 = sprintf("%s LIMIT %d, %d", $query_DetailRS1, $startRow_DetailRS1, $maxRows_DetailRS1);
$DetailRS1 = mysql_query($query_limit_DetailRS1, $tankdb) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);*/
$SELdocument="SELECT * FROM tk_document,tk_task 
WHERE tid=$task_id AND csa_document_id=docid";
$DetailRS1 = mysql_query($SELdocument, $tankdb) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);

if (isset($_GET['totalRows_DetailRS1'])) {
  $totalRows_DetailRS1 = $_GET['totalRows_DetailRS1'];
} else {
  $all_DetailRS1 = mysql_query($query_DetailRS1);
  $totalRows_DetailRS1 = mysql_num_rows($all_DetailRS1);
}
$totalPages_DetailRS1 = ceil($totalRows_DetailRS1/$maxRows_DetailRS1)-1;


$docid = $row_DetailRS1['csa_document_id'];
/*$maxRows_Recordset_actlog = 10;
$pageNum_Recordset_actlog = 0;
if (isset($_GET['pageNum_Recordset_actlog'])) {
  $pageNum_Recordset_actlog = $_GET['pageNum_Recordset_actlog'];
}
$startRow_Recordset_actlog = $pageNum_Recordset_actlog * $maxRows_Recordset_actlog;

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_actlog = sprintf("SELECT * FROM tk_log 
inner join tk_user on tk_log.tk_log_user =tk_user.uid 
								 WHERE tk_log_type = %s AND tk_log_class = 2 
								
								ORDER BY tk_log_time DESC", 
								GetSQLValueString($docid, "text")
								);
$query_limit_Recordset_actlog = sprintf("%s LIMIT %d, %d", $query_Recordset_actlog, $startRow_Recordset_actlog, $maxRows_Recordset_actlog);
$Recordset_actlog = mysql_query($query_limit_Recordset_actlog, $tankdb) or die(mysql_error());
$row_Recordset_actlog = mysql_fetch_assoc($Recordset_actlog);

if (isset($_GET['totalRows_Recordset_actlog'])) {
  $totalRows_Recordset_actlog = $_GET['totalRows_Recordset_actlog'];
} else {
  $all_Recordset_actlog = mysql_query($query_Recordset_actlog);
  $totalRows_Recordset_actlog = mysql_num_rows($all_Recordset_actlog);
}
$totalPages_Recordset_actlog = ceil($totalRows_Recordset_actlog/$maxRows_Recordset_actlog)-1;

$queryString_Recordset_actlog = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_actlog") == false && 
        stristr($param, "totalRows_Recordset_actlog") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_actlog = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_actlog = sprintf("&totalRows_Recordset_actlog=%d%s", $totalRows_Recordset_actlog, $queryString_Recordset_actlog);*/

      $editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  if($check_result==1 &&($check_score<0 || $check_score>100 || !$check_score) )
    $score_error = 1;
  else
  {
      $today_date = date('Y-m-d');
      $now_time = date('Y-m-d H:i:s',time());
      $minus = strtotime($today_date) - strtotime($row_DetailRS1['csa_plan_et']);

      if($minus <= 0)//未截止
        $date_minus = 0;
      else //截止了
        $date_minus = $minus;

      if($check_result == '-1')//驳回
      {
          $task_status = 5;
          //$leader_score = 0;
          //$final_score=0;
          $updateSQL = "UPDATE tk_task SET csa_check_time='$now_time',csa_check_context='$check_opinion',
            csa_status=$task_status WHERE tid=$task_id";

            mysql_select_db($database_tankdb, $tankdb);
          $Result4 = mysql_query($updateSQL, $tankdb) or die(mysql_error());

          date_default_timezone_set('PRC');//编辑任务的log记录
              $action='审核了任务，任务被驳回';
              $taskid=$_POST['TID'];
              $timenow=date('Y-m-d H:i:s',time());
              $insertSQLLog=sprintf("INSERT into tk_log(tk_log_user,tk_log_action,tk_log_time,tk_log_type,tk_log_class)
                VALUES(%s,'$action','$timenow','$task_id','3')",GetSQLValueString($_SESSION['MM_uid'], "int"));
 
               mysql_select_db($database_tankdb, $tankdb);
              $Result2 = mysql_query($insertSQLLog, $tankdb) or die(mysql_error());
      }
      else if($check_result == '1')//验收
      {
          $task_status = 4;
          $leader_score = $check_score;     
          $final_score = $check_score-$date_minus;
          if($final_score < 0)//最低零分
          {
            $final_score = 0;
          }
          $updateSQL = "UPDATE tk_task SET csa_leader_grade=$leader_score,
        csa_final_grade=$final_score,csa_check_time='$now_time',csa_check_context='$check_opinion',
        csa_status=$task_status WHERE tid=$task_id";
          
          mysql_select_db($database_tankdb, $tankdb);
           $Result4 = mysql_query($updateSQL, $tankdb) or die(mysql_error());
      

          date_default_timezone_set('PRC');//编辑任务的log记录
              $action='审核了任务，任务被验收';
              $taskid=$_POST['TID'];
              $timenow=date('Y-m-d H:i:s',time());
              $insertSQLLog=sprintf("INSERT into tk_log(tk_log_user,tk_log_action,tk_log_time,tk_log_type,tk_log_class)
                VALUES(%s,'$action','$timenow','$task_id','3')",GetSQLValueString($_SESSION['MM_uid'], "int"));
 
               mysql_select_db($database_tankdb, $tankdb);
              $Result2 = mysql_query($insertSQLLog, $tankdb) or die(mysql_error());
          $project_id = $row_DetailRS1['tk_doc_pid'];
          $task_to_user = $row_DetailRS1['csa_to_user'];
        
              $user_team_score = 0;

          $CountSum = "SELECT SUM(csa_plan_hour) as sum_hour FROM tk_task 
               WHERE csa_status=4 AND csa_to_user=$task_to_user AND csa_project=$project_id";
          mysql_select_db($database_tankdb, $tankdb);
           $SUMResult = mysql_query($CountSum, $tankdb) or die(mysql_error());
           $row2 = mysql_fetch_array($SUMResult);
           $user_SumScore=$row2['sum_hour'];//求出该用户已有的工时总和

           $everyScore = "SELECT csa_plan_hour,csa_final_grade FROM tk_task
               WHERE csa_status=4 AND csa_to_user=$task_to_user AND csa_project=$project_id";
           mysql_select_db($database_tankdb, $tankdb);
           $everyResult = mysql_query($everyScore, $tankdb) or die(mysql_error());
           while ($row3 = mysql_fetch_array($everyResult))
           {
               $user_team_score += $row3['csa_plan_hour']/$user_SumScore*$row3['csa_final_grade'];
           }

           $updateTeam = "UPDATE tk_team SET tk_team_score=$user_team_score
               WHERE tk_team_pid=$project_id AND tk_team_uid=$task_to_user";
           mysql_select_db($database_tankdb, $tankdb);
           $updateResult = mysql_query($updateTeam, $tankdb) or die(mysql_error()); 
      }
      $insertGoTo = "default_task_edit.php?editID=$task_id";
       
        header(sprintf("Location: %s", $insertGoTo));
    }
  }
?>


<?php require('head.php'); ?>
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgdialog.js"></script>
<script type="text/javascript" src="srcipt/lhgcheck.js"></script>
<script type="text/javascript">
    
    // 通过审核结果选项控制是否显示任务分数框
    function showScore() {
        var select = document.getElementById("select4");
        if (select.options[select.selectedIndex].value == 1) {
            document.getElementById("score").className = "form-group col-xs-12";
        } else {
            document.getElementById("score").className = "form-group col-xs-12 hide";
        }
        
    };
</script>
<script charset="utf-8" src="editor/kindeditor.js"></script>
<script charset="utf-8" src="editor/lang/zh_CN.js"></script>

<form action="<?php echo $editFormAction; ?>" method="post" name="myform" id="myform">
<table width="100%">
  <tr>
    <td class="file_text_bg">
        <br />
        <h2 align="center"><?php echo $multilingual_project_file_checktask; ?></h2>
        
<!-- 任务文档详细信息 -->
	<div class="file_text_div">
	<table width="100%" align="center">
    <tr>
    <td>
	<h3><?php echo $row_DetailRS1['tk_doc_title']; ?></h3></td>
	</tr>
        <tr>
          <td>
		  <table width="100%" align="center">
        <tr>
		<?php if ($row_DetailRS1['tk_doc_attachment'] <> "" && $row_DetailRS1['tk_doc_attachment'] <> " ") { //显示附件下载地址，如果有 ?>
            
<!-- 任务附件 -->
          <td width="12%">
		  <a href="<?php echo $row_DetailRS1['tk_doc_attachment']; ?>" class="icon_atc"><?php echo $multilingual_project_file_download; ?></a>
		  </td>
		  <?php } ?>

<!-- 任务导出Word -->
		  <td width="13%">
		  <a href="word.php?fileid=<?php echo $colname_DetailRS1; ?>" class="icon_word"><?php echo $multilingual_project_file_word; ?></a> 
		  </td>
            
<!-- 编辑任务文档（这里应该不需要） -->
<!--
		  <?php if($_SESSION['MM_rank'] > "1") { ?>
		  <td width="10%">
		  <span class="glyphicon glyphicon-pencil"></span> <a href="file_edit.php?editID=<?php echo $row_DetailRS1['docid']; ?>&projectID=<?php 
	  if ( $pfiles== "1" || $colname_DetailRS1 == "-1") { 
	  echo $project_id;
	  } else {
	  echo "-1";
	  } ?>&pid=<?php echo $row_DetailRS1['tk_doc_class2']; ?>&folder=0<?php if ( $pfiles== "1") {
	  echo "&pfile=1";
	  }?>&pagetab=<?php echo $pagetabs;?>"><?php echo $multilingual_global_action_edit; ?></a>

		  </td>
		  <?php } ?>
-->
            
		  <td width="10%">
		  <span class="glyphicon glyphicon-remove-circle"></span> <a onClick="window.opener.location.reload(); window.close();" class="mouse_hover"><?php echo $multilingual_global_action_close; ?></a>
		  </td>
		  <td>&nbsp;
		  </td>
        </tr>
		<tr>
		<td>&nbsp;
		</td>
		</tr>
      </table>
		  
		  </td>
        </tr>
	</table>
    
<!-- 任务文档描述 -->
	<?php if($row_DetailRS1['tk_doc_description'] <> null) { ?>
	<?php echo $row_DetailRS1['tk_doc_description']; 
	?>
	<?php } ?>
	</div>
	</td>
  </tr>
  
<!-- 任务文档操作记录 暂时还没有-->
  <!--<?php if($totalRows_Recordset_actlog > 0){ //显示操作记录，如果有 ?>
  <tr>
          <td class="file_text_bg">
		  <table style="width:940px;" align="center">
		  <tr>
		  <td>
		  <br />&nbsp;&nbsp;<span class="font_big18 fontbold"><?php echo $multilingual_log_title; ?></span><a name="task">
		  </td>
		  </tr>
		  </table>
		  </td>
        </tr>
  <tr>
    <td class="file_text_bg">
	<table class="table table-hover glink" style="width:940px;" align="center">
	<?php do { ?>
        <tr>
          <td><?php echo $row_Recordset_actlog['tk_log_time']; ?> <a href="user_view.php?recordID=<?php echo $row_Recordset_actlog['tk_log_user']; ?>"><?php echo $row_Recordset_actlog['tk_display_name']; ?></a> <?php echo $row_Recordset_actlog['tk_log_action']; ?>
          <td>
        </tr>
        <?php
} while ($row_Recordset_actlog = mysql_fetch_assoc($Recordset_actlog));
  $rows = mysql_num_rows($Recordset_actlog);
  if($rows > 0) {
      mysql_data_seek($Recordset_actlog, 0);
	  $row_Recordset_actlog = mysql_fetch_assoc($Recordset_actlog);
  }
?>
	</table>
	<table class="rowcon" border="0"  style="width:940px;"  align="center">
        <tr>
          <td><table border="0">
              <tr>
                <td><?php if ($pageNum_Recordset_actlog > 0) { // Show if not first page ?>
                    <a href="<?php printf("%s?pageNum_Recordset_actlog=%d%s", $currentPage, 0, $queryString_Recordset_actlog); ?>#task"><?php echo $multilingual_global_first; ?></a>
                    <?php } // Show if not first page ?></td>
                <td><?php if ($pageNum_Recordset_actlog > 0) { // Show if not first page ?>
                    <a href="<?php printf("%s?pageNum_Recordset_actlog=%d%s", $currentPage, max(0, $pageNum_Recordset_actlog - 1), $queryString_Recordset_actlog); ?>#task"><?php echo $multilingual_global_previous; ?></a>
                    <?php } // Show if not first page ?></td>
                <td><?php if ($pageNum_Recordset_actlog < $totalPages_Recordset_actlog) { // Show if not last page ?>
                    <a href="<?php printf("%s?pageNum_Recordset_actlog=%d%s", $currentPage, min($totalPages_Recordset_actlog, $pageNum_Recordset_actlog + 1), $queryString_Recordset_actlog); ?>#task"><?php echo $multilingual_global_next; ?></a>
                    <?php } // Show if not last page ?></td>
                <td><?php if ($pageNum_Recordset_actlog < $totalPages_Recordset_actlog) { // Show if not last page ?>
                    <a href="<?php printf("%s?pageNum_Recordset_actlog=%d%s", $currentPage, $totalPages_Recordset_actlog, $queryString_Recordset_actlog); ?>#task"><?php echo $multilingual_global_last; ?></a>
                    <?php } // Show if not last page ?></td>
              </tr>
            </table></td>
          <td align="right"><?php echo ($startRow_Recordset_actlog + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_actlog + $maxRows_Recordset_actlog, $totalRows_Recordset_actlog) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_actlog ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
        </tr>
      </table>
	</td>
  </tr>
  <?php } ?>-->
    
    <!-- 审核意见部分 -->
    <tr>
    <td class="file_text_bg">
    <div class="file_check_div">
        <div class="form-group col-xs-12">
            <label for="tk_user_pass"><?php echo $multilingual_exam_select; ?></label>
            <div>
                <select id="select4" onchange="showScore();" name="csa_to_user" class="form-control">
                <option value="1" selected><?php echo $multilingual_exam_pass; ?></option>
                <option value="-1" ><?php echo $multilingual_exam_deny; ?></option>
                </select>
            </div>      
        </div>
			  
        <div class="form-group col-xs-12">
            <label for="examtext"><?php echo $multilingual_exam_text; ?></label>
            <div>
				      <textarea name="examtext" id="examtext" class="form-control" rows="5"><?php if($check_opinion!= -1)  echo $check_opinion;?></textarea>
            </div>
				<span class="help-block"><?php echo $multilingual_exam_tip2; ?></span>
        </div>
        
        <!-- 打分 -->
        <div id="score" class="form-group col-xs-12">
            <label for="examtext"><?php echo $multilingual_exam_score; ?>
              <lable style="color:#F00;font-size:14px">
                    <?php if($score_error==1) 
                      {echo ('&nbsp&nbsp&nbsp');
                      echo "请在[1,100]范围内打分";} 
                    ?>
              </lable>
            </label>
                <div>
				<input name="examscore" id="examscore" class="form-control" />
                </div>
				<span class="help-block"><?php echo $multilingual_exam_tip3; ?></span>
        </div>
        <div class="clearboth"></div>
    </div>
        </td>
    </tr>
    
    <tr  class="input_task_bottom_bg" align="center"> 
    <td height="50px">
            
<!-- 提交按钮 -->
          <!-- 提交按钮 -->
                <button type="submit" class="btn btn-primary btn-sm submitbutton" name="cont">
                    <?php echo $multilingual_global_action_save; ?>
                </button>
                <button type="button" class="btn btn-default btn-sm" onClick="javascript:history.go(-1);">
                    <?php echo $multilingual_global_action_cancel; ?>
                </button>
                <input type="submit"  id="btn5" value="<?php echo $multilingual_global_action_save; ?>"  style="display:none" />

                <input type="hidden" name="MM_update" value="form1" />
              </td>
    </tr>
</table>
</form>
<?php require('foot.php'); ?>
</body>
</html>
<?php
mysql_free_result($DetailRS1);
?>
