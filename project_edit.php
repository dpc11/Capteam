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

    //开始时间
    if ( empty( $_POST['project_start'] ) )
        $_POST['project_start'] = '0000-00-00';
    //结束时间
    if ( empty( $_POST['project_end'] ) )
        $_POST['project_end'] = '0000-00-00';
    
    //项目id
    $project_id = "-1";
  if (isset($_GET['editID'])) {
    $project_id = $_GET['editID'];
  }
  //获得项目的详细信息
  $project_info = get_project_by_id($project_id);
  //获取被选中的用户
  $users_selecred_old = get_user_selected($project_id,$_SESSION['MM_uid']);
  //保存现在有的用户的id数组
  $user_id_arr = array();
  //获取所有用户
  $selected="";
  $userRS = get_all_user_select($_SESSION['MM_uid']);
  $user_arr_list = mysql_fetch_assoc($userRS);
  do { 
      if($users_selecred_old[$user_arr_list["uid"]]){
          //如果该成员是原先被选中的
          if($user_arr_list["tk_user_contact"]==""){ 
            $phone="空";
          }else{
            $phone=$user_arr_list["tk_user_contact"]; 
          }
          $selected .= "||".$user_arr_list["tk_display_name"].
          "【".$user_arr_list["tk_user_contact"].
          "】【".$user_arr_list["tk_user_email"].
          "】=".$user_arr_list["uid"]."%".
          $user_arr_list["tk_display_name"]."%".
          $phone ."%".$user_arr_list["tk_user_email"]."%".
          $user_arr_list["tk_display_name"]."'";

          $user_id_arr[$user_arr_list["uid"]]=$user_arr_list["uid"];
      }else{
          //如果该成员时原先未被选中的
          if($user_arr_list["tk_user_contact"]==""){ 
            $phone="空";
          }else{
            $phone=$user_arr_list["tk_user_contact"]; 
          }
          $constraint .= "||".$user_arr_list["tk_display_name"].
          "【".$user_arr_list["tk_user_contact"].
          "】【".$user_arr_list["tk_user_email"].
          "】=".$user_arr_list["uid"]."%".
          $user_arr_list["tk_display_name"]."%".
          $phone ."%".$user_arr_list["tk_user_email"]."%".
          $user_arr_list["tk_display_name"]."'";
      }//else
  } while ($user_arr_list = mysql_fetch_assoc($userRS)); 

  
  echo '<input style="display:none" id="constraint" value="'.$constraint.'"/>';
  echo '<input style="display:none" id="selected" name="selected" value="'.$selected.'"/>';



    if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
        //数据库修改后的SQL语句
        $new_project_createtime = date('Y-m-d');
        $new_project_lastupdate = date("Y-m-d H:i:s");

        $today_date = date('Y-m-d');
        //判断时间
        if($_POST['project_end']<$today_date)
        {
          //echo("illegal");
            $dateError = -1;//结束时间小于今天
        }else if($_POST['project_end'] <$_POST['project_start'])
        {
          //echo("can't");
            $dateError = -2;//结束时间小于开始时间
        }else{          
          $projectNAME = $_POST['project_name'];
          //更新项目数据库
          $insertSQL = sprintf("UPDATE tk_project set project_name=%s, project_text=%s, project_start=%s, project_end=%s, project_lastupdate=%s where id=%s", 
            GetSQLValueString($projectNAME, "text"),
            GetSQLValueString($_POST['project_text'], "text"),
            GetSQLValueString($_POST['project_start'], "date"),
            GetSQLValueString($_POST['project_end'], "date"),
            GetSQLValueString($new_project_lastupdate, "date"),
            GetSQLValueString($project_id, "int"));

        mysql_select_db($database_tankdb, $tankdb);
        $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());
        //插入日志
        $timenow=date('Y-m-d H:i:s',time());
        $insertSQLLog=sprintf("INSERT into tk_log(tk_log_user,tk_log_action,tk_log_time,tk_log_type,tk_log_class)
          VALUES(%s,'编辑了项目','$timenow','$project_id','1')",GetSQLValueString($_SESSION['MM_uid'], "int")); 
        mysql_select_db($database_tankdb, $tankdb);
        $Result2 = mysql_query($insertSQLLog, $tankdb) or die(mysql_error());

              //更新文件夹信息
        $CurDate = date("Y-m-d H:i:s");
        $tk_doc_description="'本文件夹用于存放【".str_replace("'","",$projectNAME)."】团队的所有资料。'";
              $updateSQLFolder = sprintf("UPDATE tk_document set tk_doc_title = %s,tk_doc_description =%s,tk_doc_lastupdate=%s where docid = %s",
                  GetSQLValueString($_POST['project_name'], "text"),
                  GetSQLValueString($tk_doc_description, "text"),
                  GetSQLValueString($CurDate, "date"),
                  GetSQLValueString($project_info['project_folder_id'], "int")); 

          mysql_select_db($database_tankdb, $tankdb);
          $Result_folder = mysql_query($updateSQLFolder, $tankdb) or die(mysql_error());
            //往tk_team表中插入相关的用户成员
        //获取选中的项目成员
        $tk_team_pid= $project_id;//项目id
        $user_list= $_POST['now_selected'];
        //$user_list ="<script>document.write(sel_now).value;</script>";
        //echo $user_list;
        //当前被选中的用户id数组
        $user_selected_new = array();
            
        $a_user = explode("||", $user_list);

        $i=0;
        while($a_user[$i])
        {
          $r = explode("=",$a_user[$i]);
          $user_info = $r[1];
          $d = explode("%", $user_info);
          $user_id = $d[0];

          
          //在当前被选中的数组中添加id
          $user_selected_new[$user_id]=$user_id;
          //array_push($user_selected_new, $user_id);
          //如果该成员是老成员，则不操作
          if($users_selecred_old[$user_id]){
            //该成员原先就存在，不操作
          }else{
            //该成员原先不存在
          $tk_team_ulimit=1;//用户权限,组长是3，组员是1，副组长是2
          $tk_team_del_status=1;//该用户在该项目中的删除状态
          $tk_team_jointeamtime=date('Y-m-d H:i:s');//该用户加入该项目的时间，PHP date() 函数会返回服
          $addnewmemSQL="INSERT INTO tk_team (tk_team_pid,tk_team_uid,tk_team_ulimit,tk_team_del_status,tk_team_jointeamtime)
                  VALUES ($tk_team_pid,$user_id,$tk_team_ulimit,$tk_team_del_status,'$tk_team_jointeamtime')";
          mysql_select_db($database_tankdb, $tankdb);
          $Result1 = mysql_query($addnewmemSQL, $tankdb) or die(mysql_error());
          //添加项目成员的log记录
          $searchmemSQL="SELECT* FROM tk_user WHERE uid=$user_id";
          mysql_select_db($database_tankdb, $tankdb);
          $Result1 = mysql_query($searchmemSQL, $tankdb) or die(mysql_error());
          
          $FoundUser = mysql_num_rows($Result1);
            if ($FoundUser) {  
              $loginStrDisplayname  = mysql_result($Result1,0,'tk_display_name');
            }
          $action='添加了成员:'.$user_id.'--'.$loginStrDisplayname;
          $timenow=date('Y-m-d H:i:s',time());
          $insertSQLLog=sprintf("INSERT into tk_log(tk_log_user,tk_log_action,tk_log_time,tk_log_type,tk_log_class)
                    VALUES(%s,'$action','$timenow','$tk_team_pid','1')",GetSQLValueString($_SESSION['MM_uid'], "int"));
   
          mysql_select_db($database_tankdb, $tankdb);
          $Result2 = mysql_query($insertSQLLog, $tankdb) or die(mysql_error());
          }//else
        $i++;
      }//while 

        //删除原先选择但是现在没有选的人
         foreach ($users_selecred_old as $key => $val) {
            $user_id = $val['uid'];
              if($user_selected_new[$user_id]){
                  //说明该用户已经出现过，跳过不处理
              }else{
                  if($user_id =="" || $user_id == null){

                  }else{
                    //该用户为出现过，删除该用户
                    $deletememSQL="DELETE from tk_team where tk_team_pid = $project_id and tk_team_uid = $user_id";
                    mysql_select_db($deletememSQL, $tankdb);
                    $Result1 = mysql_query($deletememSQL, $tankdb) or die(mysql_error());
                    //添加项目成员的log记录
                    $searchmemSQL="SELECT* FROM tk_user WHERE uid=$user_id";
                    mysql_select_db($database_tankdb, $tankdb);
                    $Result1 = mysql_query($searchmemSQL, $tankdb) or die(mysql_error());
                    
                    $FoundUser = mysql_num_rows($Result1);
                      if ($FoundUser) {  
                        $loginStrDisplayname  = mysql_result($Result1,0,'tk_display_name');
                      }
                    $action='删除了成员:'.$user_id.'--'.$loginStrDisplayname;
                    $timenow=date('Y-m-d H:i:s',time());
                    $insertSQLLog=sprintf("INSERT into tk_log(tk_log_user,tk_log_action,tk_log_time,tk_log_type,tk_log_class)
                         VALUES(%s,'$action','$timenow','$project_id','1')",GetSQLValueString($_SESSION['MM_uid'], "int"));
                   mysql_select_db($database_tankdb, $tankdb);
                   $Result2 = mysql_query($insertSQLLog, $tankdb) or die(mysql_error());
                  }//else
                  
              }  //foreach            
           }
        
            $insertGoTo = "project_view.php?recordID=$project_id";
            // if (isset($_SERVER['QUERY_STRING'])) {
            //   $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
            //   $insertGoTo .= $_SERVER['QUERY_STRING'];
            // }
            header(sprintf("Location: %s", $insertGoTo));
        exit;
        }//else
        
      }//MM_update

    
 ?>
<form action="<?php echo $editFormAction; ?>" method="post" name="myform" id="form1" >
    <table width="100%" height="100px"  border="0" cellspacing="0" cellpadding="0" id="form1_table" >
        <tr>
      <!-- 左边20%的宽度的树或者说明  -->
      <td width="20%" height="100%" class="input_task_right_bg"  valign="top">
        <table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <div class=" add_title col-xs-12">
              <h3 ><?php echo $multilingual_projectlist_edit; ?></h3>
            </div>
            <td valign="top" class="gray2">
              <h4 style="margin-top:40px; margin-left: 5px;" ><?php echo $multilingual_project_view_nowbs; ?></h4>
              <p > <?php echo $multilingual_project_edit_text; ?></p>
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
                        <input type="text" name="project_name" id="project_name" 
                        value=<?php echo $project_info['project_name'] ?> 
                        class="form-control" placeholder="<?php echo $multilingual_project_title_tips; ?>" />
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
                        <input type="text" name="project_start" id="datepicker" value="<?php echo $project_info['project_start']; ?>" class="form-control"  />
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
                        <input type="text" name="project_end" id="datepicker2" value="<?php echo $project_info['project_end']; ?>" class="form-control" />
                      </div>
                    </div>
                  </td>
                  <td valign="top">
                  

                    <!-- 项目组长为当前用户，此处添加多个项目组员 -->
                    <div class="form-group" id="select_team" class="select_team">
                      <label for="select2" ><?php echo $multilingual_project_touser; ?><span id="csa_to_user_msg"></span></label>
                      <span class="help-block"><?php echo $multilingual_project_tips2; ?></span> 
                      <div>
                        <div style="display:none" id="now_sel" ><input type="text" id="now_selected" name="now_selected"/></div>
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
                          <?php
                          foreach ($user_id_arr as $key => $val) {
                          ?>
                            <tr>
                              <td style="display:none;">
                              <?php ?>
                                <span id="<?php echo $val?>"><?php echo $val?></span>
                              </td>
                              <td data-ellipsis="true" style="width:150px;text-align:center;">
                                <span id="<?php echo  $users_selecred_old[$val]['name'];?>"><?php echo  $users_selecred_old[$val]['name'];?></span>
                              </td>
                              <td data-ellipsis="true" style="width:150px;text-align:center;">
                                <span id="<?php echo  $users_selecred_old[$val]['phone_num'];?>"><?php echo  $users_selecred_old[$val]['phone_num'];?></span>
                              </td>
                              <td data-ellipsis="true" style="width:200px;text-align:center;">
                                <span id="<?php echo  $users_selecred_old[$val]['email'];?>"><?php echo  $users_selecred_old[$val]['email'];?></span>
                              </td>
                              <td>
                                <a href="#" onclick="return delet('teamlist_tr',this);">X</a>
                              </td>
                              <td></td>
                            </tr>
                          <?php } ?>
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
                  <textarea name="project_text" id="project_text">
                                      <?php echo htmlentities($project_info['project_text'], ENT_COMPAT, 'utf-8'); ?>
                  </textarea>
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
                    <input type="hidden" name="MM_update" value="form1" />
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
   //function get_selected() {  var sel =  document.getElementById('selected').value; alert(sel);} 
   var sel_now="init";
  
    function delet(id,obj){

      var tr= obj.parentNode.parentNode;
      var rowIndex = tr.rowIndex;
      //alert(rowIndex);
      var uu_id = tr.childNodes[0].childNodes[0].id;
      var uu_name = tr.childNodes[1].childNodes[0].id;
      var uu_phone = tr.childNodes[2].childNodes[0].id;
      var uu_mail = tr.childNodes[3].childNodes[0].id;
      old_con=document.getElementById('constraint').value;
      //alert(document.getElementById('constraint').value);
      // alert(document.getElementById('selected').value);
      var xx=uu_name+"【"+uu_phone+"】【"+uu_mail+"】";
      var j= document.getElementById('selected').value.indexOf(xx); 
      var sel_left="";
      var sel_right="";
      if(j>0){
        sel_left =document.getElementById('selected').value.substr(0,j-2);  
        //alert(left);
        sel_right =document.getElementById('selected').value.substr(j,document.getElementById('selected').length); 
        //alert(right);
      }else {
        sel_right =document.getElementById('selected').value; 
        //alert(right);
      }
      var sr =sel_right.split('||');
      var y=1;
      new_sel = sel_left;
      while(sr[y])
      {
        new_sel = new_sel+"||"+sr[y];
        y++;
      }
      document.getElementById('selected').value=new_sel;
      // alert(document.getElementById('selected').value);

      var add=uu_name+"【"+uu_phone+"】【"+uu_mail+"】="+uu_id+"%"+uu_name+"%"+uu_phone+"%"+uu_mail+"%"+uu_name+"'";
      document.getElementById('constraint').value = old_con+"||"+add;
      // alert(document.getElementById('constraint').value);
      document.getElementById('now_sel').innerHTML="<input type=\"text\" id=\"now_selected\" name=\"now_selected\" value=\"" +document.getElementById('selected').value+"\">" ;
      document.getElementById(id).deleteRow(rowIndex-1);  
      document.getElementById('project_team_name').value="";
    }
      
    J.check.rules = [
        { name: 'project_name', mid: 'projecttitle', type: 'limit', requir: true, min: 2, max: 32, warn: '<?php echo $multilingual_projectstatus_titlerequired; ?>' },
      //{ name: 'datepicker', mid: 'datepicker_msg', type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' },
      //{ name: 'datepicker2', mid: 'datepicker2_msg', type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' }
    ];
  
  function add_to_list(){
      
    document.getElementById('teamlist_tr').innerHTML=document.getElementById('teamlist_tr').innerHTML+"<tr><td style=\"display:none;\"><span id=\""+document.getElementById('uuid').value+"\">"+document.getElementById('uuid').value+"</span></td><td  data-ellipsis=\"true\" data-ellipsis-max-width=\"30px\" style=\"width:150px;text-align:center;\"><span id=\""+document.getElementById('uuname').value+"\">"+document.getElementById('uuname').value+"</span></td>" +
        "<td  data-ellipsis=\"true\" data-ellipsis-max-width=\"30px\" style=\"width:150px;text-align:center;\"><span id=\""+document.getElementById('uuphone').value+"\">"+document.getElementById('uuphone').value+"</span></td><td  data-ellipsis=\"true\" data-ellipsis-max-width=\"30px\" style=\"width:200px;text-align:center;\"><span id=\""+document.getElementById('uuemail').value+"\">"+document.getElementById('uuemail').value+"</span></td><td ><a href=\"#\" onclick=\"return delet('teamlist_tr',this);\""+">X</a><td></td></tr>";
      var x=document.getElementById('uuname').value+"【"+document.getElementById('uuphone').value+"】【"+document.getElementById('uuemail').value+"】";
      var i= document.getElementById('constraint').value.indexOf(x); 
      //alert(i);
      var left=""
      var right="";
      //alert(document.getElementById('constraint').value);
      if(i>0){
        left =document.getElementById('constraint').value.substr(0,i-2);  
        //alert(left);
        right =document.getElementById('constraint').value.substr(i,document.getElementById('constraint').length); 
        //alert(right);
      }else {
        right =document.getElementById('constraint').value; 
        //alert(right);
      }         
        
      var rrr =right.split('||');
      var old_sel=document.getElementById('selected').value;
      document.getElementById('selected').value=rrr[0]+"||"+old_sel;
      //alert(document.getElementById('selected').value);
      var k=1;
      new_con = left;
      while(rrr[k])
      {
        new_con = new_con+"||"+rrr[k];
        k++;
      }
      //alert(document.getElementById('now_sel').innerHTML);
      document.getElementById('now_sel').innerHTML="<input type=\"text\" id=\"now_selected\" name=\"now_selected\" value=\"" +document.getElementById('selected').value+"\">" ;
      //alert(document.getElementById('now_sel').innerHTML);
      document.getElementById('constraint').value=new_con;
           //alert(document.getElementById('constraint').value);
      document.getElementById('project_team_name').value="";
      
  }   
    
</script>
</body>
</html>
