<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php require_once('function/config_function.php'); ?>
<?php require_once('function/project_function.php'); ?>
<?php require_once('function/user_function.php'); ?>

<?php require('head.php'); ?>
<link type="text/css" href="css/jquery/horsey.css" rel="stylesheet" />
<link type="text/css" href="css/ui/ui.all.css" rel="stylesheet" />
<link type="text/css" href="css/lhgcore/lhgcheck.css" rel="stylesheet" />
<script type="text/javascript" src="js/lhgcore/lhgcheck.js"></script>
<link rel="stylesheet" href="css/bootstrap/bootstrap-multiselect.css" type="text/css"/>
<script type="text/javascript" src="js/bootstrap/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="css/bootstrap/datepicker3.css" type="text/css"/>
<script type="text/javascript" src="js/bootstrap/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/bootstrap/locales/bootstrap-datepicker.zh-CN.js"></script>

<?php
    $dateError = 1;//no error
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }

    if ( empty( $_POST['project_text'] ) ){
    $project_text = "'',";
    }else{
    $project_text = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['project_text']), "text"));
    }

    if ( empty( $_POST['project_start'] ) )
    		$_POST['project_start'] = '0000-00-00';

    if ( empty( $_POST['project_end'] ) )
    		$_POST['project_end'] = '0000-00-00';

    $newID =0;
	
    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
		//数据库修改后的SQL语句
        $new_project_createtime = date('Y-m-d');
        $new_project_lastupdate = date("Y-m-d H:i:s");

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
  
				$projectNAME = GetSQLValueString($_POST['project_name'], "text");

                $insertSQL = sprintf("INSERT INTO tk_project (project_name, project_text, project_start, project_end, project_to_user,project_lastupdate,project_create_time)
                  VALUES ($projectNAME, $project_text %s, %s, %s, '$new_project_lastupdate','$new_project_createtime')",
                                   GetSQLValueString($_POST['project_start'], "date"),
                                   GetSQLValueString($_POST['project_end'], "date"),
                                   GetSQLValueString($_SESSION['MM_uid'], "int"));

              mysql_select_db($database_tankdb, $tankdb);
              $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());
              $newID = mysql_insert_id();
			  

              date_default_timezone_set('PRC');
              $timenow=date('Y-m-d H:i:s',time());
              $insertSQLLog=sprintf("INSERT into tk_log(tk_log_user,tk_log_action,tk_log_time,tk_log_type,tk_log_class)
                VALUES(%s,'创建了项目','$timenow','$newID','1')",GetSQLValueString($_SESSION['MM_uid'], "int"));
 
               mysql_select_db($database_tankdb, $tankdb);
              $Result2 = mysql_query($insertSQLLog, $tankdb) or die(mysql_error());

			  $CurDate = date("Y-m-d H:i:s");
			  $tk_doc_description="'本文件夹用于存放【".str_replace("'","",$projectNAME)."】团队的所有资料。'";
			  $insertSQLFolder = sprintf("INSERT INTO tk_document (tk_doc_title, tk_doc_description,tk_doc_pid, tk_doc_parentdocid, tk_doc_create, tk_doc_lastupdate,tk_doc_backup1, tk_doc_type) VALUES ($projectNAME, $tk_doc_description,$newID, -1, 0,'$CurDate',1,1)");

				  mysql_select_db($database_tankdb, $tankdb);
				  $Result_folder = mysql_query($insertSQLFolder, $tankdb) or die(mysql_error());

				  $folderID = mysql_insert_id();
				
				$insertSQL = sprintf("UPDATE tk_project SET project_folder_id = $folderID WHERE id=$newID");
              mysql_select_db($database_tankdb, $tankdb);
              $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());
			  
              $insertGoTo = "project_view.php?recordID=$newID";
              if (isset($_SERVER['QUERY_STRING'])) {
                $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
                $insertGoTo .= $_SERVER['QUERY_STRING'];
              }
              header(sprintf("Location: %s", $insertGoTo));
			  exit;
        }
    }
	$selected="";
    $userRS = get_all_user_select($_SESSION['MM_uid']);
	$user_arr_list = mysql_fetch_assoc($userRS);
	$phone="空";
	if($user_arr_list["tk_user_contact"]==""){ $phone="空";}else{ 	$phone=$user_arr_list["tk_user_contact"]; }
	$constraint = $user_arr_list["tk_display_name"]."【".$user_arr_list["tk_user_contact"]."】【".$user_arr_list["tk_user_email"]."】=".$user_arr_list["uid"]."%".$user_arr_list["tk_display_name"]."%".$phone ."%".$user_arr_list["tk_user_email"]."%".$user_arr_list["tk_display_name"]."'";
					
	if($user_arr_list = mysql_fetch_assoc($userRS)){
		do { 
			if($user_arr_list["tk_user_contact"]==""){ $phone="空";}else{ 	$phone=$user_arr_list["tk_user_contact"]; }
			$constraint .= "||".$user_arr_list["tk_display_name"]."【".$user_arr_list["tk_user_contact"]."】【".$user_arr_list["tk_user_email"]."】=".$user_arr_list["uid"]."%".$user_arr_list["tk_display_name"]."%".$phone ."%".$user_arr_list["tk_user_email"]."%".$user_arr_list["tk_display_name"]."'";
		} while ($user_arr_list = mysql_fetch_assoc($userRS)); 
	}
	
	echo '<input style="display:none" id="constraint" value="'.$constraint.'"/>';
	echo '<input style="display:none" id="selected" value="'.$selected.'"/>';
	
    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    //往tk_team表中插入相关的用户成员
    //插入项目负责人
    $tk_team_pid= $newID;//项目id
    $tk_team_uid= $_SESSION['MM_uid'];//用户id
    $tk_team_disname=$_SESSION['MM_Displayname'];//组长显示名
    $tk_team_ulimit=3;//用户权限,组长是3
    $tk_team_del_status=1;//该用户在该项目中的删除状态
    $tk_team_jointeamtime=date('Y-m-d H:i:s');//该用户加入该项目的时间
    /*开始操作数据库了，insert语句*/
    $addnewmemSQL="INSERT INTO tk_team (tk_team_pid,tk_team_uid,tk_team_ulimit,tk_team_del_status,tk_team_jointeamtime)
    VALUES ($tk_team_pid,$tk_team_uid,$tk_team_ulimit,$tk_team_del_status,'$tk_team_jointeamtime')";
    mysql_select_db($database_tankdb, $tankdb);
    $Result1 = mysql_query($addnewmemSQL, $tankdb) or die(mysql_error());
    //添加项目负责人的log记录
    date_default_timezone_set('PRC');
    $action='添加了成员:'.$tk_team_uid.' --'.$tk_team_disname;
              $timenow=date('Y-m-d H:i:s',time());
              $insertSQLLog=sprintf("INSERT into tk_log(tk_log_user,tk_log_action,tk_log_time,tk_log_type,tk_log_class)
                VALUES(%s,'$action','$timenow','$tk_team_pid','1')",GetSQLValueString($_SESSION['MM_uid'], "int"));
 
               mysql_select_db($database_tankdb, $tankdb);
              $Result2 = mysql_query($insertSQLLog, $tankdb) or die(mysql_error());
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
        //添加项目成员的log记录
        $searchmemSQL="SELECT* FROM tk_user WHERE uid=$tk_team_uid";
        mysql_select_db($database_tankdb, $tankdb);
        $Result1 = mysql_query($searchmemSQL, $tankdb) or die(mysql_error());
        
        $FoundUser = mysql_num_rows($Result1);
          if ($FoundUser) {  
            $loginStrDisplayname  = mysql_result($Result1,0,'tk_display_name');
          }
    date_default_timezone_set('PRC');
    $action='添加了成员:'.$tk_team_uid.'--'.$loginStrDisplayname;
              $timenow=date('Y-m-d H:i:s',time());
              $insertSQLLog=sprintf("INSERT into tk_log(tk_log_user,tk_log_action,tk_log_time,tk_log_type,tk_log_class)
                VALUES(%s,'$action','$timenow','$tk_team_pid','1')",GetSQLValueString($_SESSION['MM_uid'], "int"));
 
               mysql_select_db($database_tankdb, $tankdb);
              $Result2 = mysql_query($insertSQLLog, $tankdb) or die(mysql_error());
    }

    }
 ?>
<form action="<?php echo $editFormAction; ?>" method="post" name="myform" id="form1">
    <table width="100%" height="100px"  border="0" cellspacing="0" cellpadding="0" id="form1_table" >
        <tr>
			<!-- 左边20%的宽度的树或者说明  -->
			<td width="20%" height="100%" class="input_task_right_bg"  valign="top">
				<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
					<tr>
						<div class=" add_title col-xs-12">
							<h3 ><?php echo $multilingual_projectlist_new; ?></h3>
						</div>
						<td valign="top" class="gray2">
							<h4 style="margin-top:40px; margin-left: 5px;" ><?php echo $multilingual_project_view_nowbs; ?></h4>
							<p > <?php echo $multilingual_project_add_text; ?></p>
						</td>
					</tr>
				</table>
			</td>
			<!-- 右边80%宽度的主体内容 -->
			<td width="80%"  height="100%" valign="top" align="center">
				<table width="90%" border="0" cellspacing="0" cellpadding="5" align="center" id="add_table"class="add_table">
					<tr>
						<td>
							<table width="98%" border="0" cellspacing="0" cellpadding="5" >
								<tr>
									<td  width="540px">
										<!-- 项目名称 -->
										<div class="form-group">
											<label for="project_name" class="project_label"><?php echo $multilingual_project_title; ?><span id="projecttitle"></span></label>
											<div>
												<input type="text" name="project_name" id="project_name" value="" class="form-control" placeholder="<?php echo $multilingual_project_title_tips; ?>" />
											</div>
										</div>
										<!-- 起始时间 -->
										<div class="form-group">
											<label for="datepicker"><?php echo $multilingual_project_start; ?><!--<span id="datepicker_msg"></span>-->
												<label style="color:#F00;font-size:14px">
													<?php if($dateError==-2) { echo ('&nbsp&nbsp&nbsp');echo "结束时间小于开始时间";} ?>
												</label>
											</label>
											<div>
												<input type="text" name="project_start" id="datepicker" value="<?php echo date('Y-m-d'); ?>" class="form-control"  />
											</div>
										</div>
										<!-- 结束时间 -->		  
										<div class="form-group " >
											<label for="datepicker2"><?php echo $multilingual_project_end; ?><!--<span id="datepicker2_msg"></span>-->
												<label style="color:#F00;font-size:14px">
													<?php if($dateError==-2) {echo ('&nbsp&nbsp&nbsp');echo "结束时间小于开始时间";} else if ($dateError==-1) {echo ('&nbsp&nbsp&nbsp'); echo "结束时间小于今天";} ?>
												</label>
											</label>
											<div>
												<input type="text" name="project_end" id="datepicker2" value="<?php echo date("Y-m-d",strtotime("+7 day")); ?>" class="form-control" />
											</div>
										</div>
									</td>
									<td valign="top">
									

										<!-- 项目组长为当前用户，此处添加多个项目组员 -->
										<div class="form-group" id="select_team" class="select_team">
											<label for="select2" ><?php echo $multilingual_project_touser; ?><span id="csa_to_user_msg"></span></label>
											<span class="help-block"><?php echo $multilingual_project_tips2; ?></span> 
											<div>
												<input type="text" name="project_team_name" id="project_team_name" value="" style="float:left" 
												class="form-control" style="width:600px;" data-ellipsis="true" data-ellipsis-max-width="150px"  autocomplete="off"  placeholder="<?php echo $multilingual_project_team_tips; ?>"  
												/>												
												<button type="button" style="font-size:20px;margin-left:50px;height:45px;"  name="button11" id="button11" style="float:left" class="btn btn-default" onclick="return add_to_list();"/><span class="glyphicon glyphicon-plus-sign"style="display:inline;"></span> <?php echo $multilingual_global_addbtn; ?>
												</button>
												<input id="uuid" style="display:none;"/>
												<input id="uuname" style="display:none;"/>
												<input id="uuemail" style="display:none;"/>
												<input id="uuphone" style="display:none;"/>
												<div style="border:2px solid #ddd;margin-top:20px;width:620px;height:150px;overflow:scroll">
												<table id="teamlist" height="150px" width="650px" class="teamlist_table table table-condensed " border="0" cellspacing="0" cellpadding="5" align="center">
													<thead>
														<tr>
														<th style="display:none">id
														</th>
														<th style="width:150px;text-align:center;">用户名
														</th>
														<th style="width:150px;text-align:center;">联系方式
														</th>
														<th style="width:250px;text-align:center;">注册邮箱
														</th>
														<th style="width:5px;text-align:center;">
														</th>
														</tr>
													</thead>
													<tbody id="teamlist_tr">
													</tbody>
												</table>
												</div>
											</div>
										</div>	
									</td>
								</tr>
							</table>	
							<script src="js/jquery/jquery.ellipsis.js"></script>
							<script src="js/jquery/jquery.ellipsis.unobtrusive.js"></script>					
							<div class="form-group ">
								<label for="project_text"><?php echo $multilingual_project_description; ?></label>
								<div>
								  <textarea name="project_text" id="project_text"></textarea>
								</div>
							</div>
						</td>
					</tr>
					<tr >
						<td align="left" >
							<table width="250px" border="0" cellspacing="0" cellpadding="5" style="margin-left:650px;margin-top:20px;">
							<!-- 提交按钮 -->
								<tr >
									<td >
										<button type="submit" class="btn btn-primary btn-sm" name="cont" style="width:100px"><?php echo $multilingual_global_action_save; ?></button>
									</td>
									<td  width="20%" align="center" >
										<button type="button" class="btn btn-default btn-sm" style="width:100px" onClick="javascript:history.go(-1);"><?php echo $multilingual_global_action_cancel; ?></button>
										<input type="hidden" name="MM_insert" value="form1" />
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
</div>
<?php require('foot.php'); ?>
 
<script charset="utf-8" src="plug-in/editor/kindeditor.js"></script>
<script charset="utf-8" src="plug-in/editor/lang/zh_CN.js"></script>
<script  src="js/jquery/horsey.js"></script>
<script >

    var editor;
    KindEditor.ready(function(K) {
        editor = K.create('#project_text', {
    		width : '1150px',
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
    $(window).load(function()
	{
        J.check.regform('form1');
		
    	$('#datepicker').datepicker({
    		format: "yyyy-mm-dd"
			<?php if ($language=="cn") {echo ", language: 'zh-CN'" ;}?>
    	});
    		
		$('#datepicker2').datepicker({
    		format: "yyyy-mm-dd"
			<?php if ($language=="cn") {echo ", language: 'zh-CN'" ;}?>
    	});
		horsey(project_team_name, {
	
			suggestions:[
			document.getElementById("constraint").value
			],
			render: function (li, suggestion) {		
				li.innerHTML = suggestion.split('=')[0];
			}
		});
		$("#form1_table").css("height",document.getElementById("add_table").clientHeight+"px");
		$("#foot_top").css("min-height",document.getElementById("form1_table").offsetHeight+document.getElementById("top_height").offsetHeight-20+"px");

    });
	$(window).resize(function()
	{	
		$("#form1_table").css("height",document.getElementById("add_table").clientHeight+"px");
		$("#foot_top").css("min-height",document.getElementById("form1_table").offsetHeight+document.getElementById("top_height").offsetHeight+60+"px"); 
		
	});
	
		function delet(id,obj){
			var rowIndex = obj.parentElement.rowIndex;
			document.getElementById(id).deleteRow(rowIndex);
		}
			
    J.check.rules = [
        { name: 'project_name', mid: 'projecttitle', type: 'limit', requir: true, min: 2, max: 32, warn: '<?php echo $multilingual_projectstatus_titlerequired; ?>' },
    	//{ name: 'datepicker', mid: 'datepicker_msg', type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' },
    	//{ name: 'datepicker2', mid: 'datepicker2_msg', type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' }
    ];
	
	function add_to_list(){
			
		document.getElementById('teamlist_tr').innerHTML=document.getElementById('teamlist_tr').innerHTML+"<tr ><td style=\"display:none;\">"+document.getElementById('uuid').value+"</td><td  data-ellipsis=\"true\" data-ellipsis-max-width=\"30px\" style=\"width:150px;text-align:center;\">"+document.getElementById('uuname').value+"</td>" +
				"<td  data-ellipsis=\"true\" data-ellipsis-max-width=\"30px\" style=\"width:150px;text-align:center;\">"+document.getElementById('uuphone').value+"</td><td  data-ellipsis=\"true\" data-ellipsis-max-width=\"30px\" style=\"width:200px;text-align:center;\">"+document.getElementById('uuemail').value+"</td><td ><a href='#'; onclick=\"delet(\"teamlist\",this);\""+">X</a><td></td></tr>";
			var x=document.getElementById('uuname').value+"【"+document.getElementById('uuphone').value+"】【"+document.getElementById('uuemail').value+"】";
			var i= document.getElementById('constraint').value.indexOf(x); 
			var left=""
			var right="";
			if(i>0){
				left =document.getElementById('constraint').value.substr(0,i-2);  
				right =document.getElementById('constraint').value.substr(i,document.getElementById('constraint').length); 
			}else {
				right =document.getElementById('constraint').value; 
			}					
				
			var rrr =right.split('||');
			document.getElementById('selected').value=rrr[0]+"||";
			document.getElementById('constraint').value=left+"||"+rrr[1];
            		
			document.getElementById('project_team_name').value="";
			
	}		
		
</script>
</body>
</html>
<?php
    mysql_free_result($Recordset3);
?>