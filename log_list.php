<?php
//一页显示的最多记录数
$maxRows_Recordset_log = 15;
//当前显示的页数
$pageNum_Recordset_log = 0;
if (isset($_GET['pageNum_Recordset_log'])) {
  $pageNum_Recordset_log = $_GET['pageNum_Recordset_log'];
}
$startRow_Recordset_log = $pageNum_Recordset_log * $maxRows_Recordset_log;

//<!--设置创建月份--> 
$colmonth_Recordset1 = date("m");
$_SESSION['ser_month'] = $colmonth_Recordset1;
if (isset($_GET['textfield'])) {
	$colmonth_Recordset1 = $_GET['textfield'];
	$_SESSION['ser_month'] = $colmonth_Recordset1;
}

//<!--设置创建年份--> 
$colyear_Recordset1 = date("Y");
$_SESSION['ser_year'] = $colyear_Recordset1;
if (isset($_GET['select_year'])) {
	$colyear_Recordset1 = $_GET['select_year'];
	$_SESSION['ser_year'] = $colyear_Recordset1;
}
$YEAR = $colyear_Recordset1;
$MONTH = $colmonth_Recordset1;

//<!--选择月份必须选年份，选年份可以不选月份--> 
//<!--若年份为--，则为查找所有任务；若月份为空，则查找这一年的任务；--> 
if ($colyear_Recordset1 == "--"){
	$startday = "1975-09-23";
	$endday = "3000-13-31";
} else if ($colmonth_Recordset1 == "--"){
	$startday = $colyear_Recordset1."-01-01";
	$endday = $colyear_Recordset1."-12-31";
} else {
	$startday = $colyear_Recordset1."-".$colmonth_Recordset1."-01";
	$endday = $colyear_Recordset1."-".$colmonth_Recordset1."-31";
}


//<!--选择某一项目--> 
$colproject_Recordset1 = "";
$_SESSION['ser_project'] = $colproject_Recordset1;
if (isset($_GET['select_project'])) {
	$colproject_Recordset1 = $_GET['select_project'];
	$_SESSION['ser_project'] = $colproject_Recordset1;
}

//设置排序标准
$sortlist = "tk_log_time";
if (isset($_GET['sort'])) {
	$sortlist = $_GET['sort'];
}

//升序 降序
$orderlist = "ASC";
if (isset($_GET['order'])) {
  $orderlist= $_GET['order'];
}
$colproject = GetSQLValueString($colproject_Recordset1, "int");
$where = "";
//所属项目
if(!empty($colproject_Recordset1))
{
	$where.= "and  tk_project.id = $colproject ";
}
//当前用户id
$userid = $_SESSION['MM_uid']; 

//获取log数组
$log_arr = get_logs($userid,$where,$startday,$endday,$orderlist,$sortlist);
//总记录数
if (isset($_GET['totalRows_Recordset_log'])) {
  $totalRows_Recordset_log = $_GET['totalRows_Recordset_log'];
} else {
  $totalRows_Recordset_log = count($log_arr);
}
//总页数
$totalPages_Recordset_log = ceil($totalRows_Recordset_log/$maxRows_Recordset_log)-1;
$queryString_Recordset_log = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_log") == false && 
        stristr($param, "totalRows_Recordset_log") == false && 
        stristr($param, "tab") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_log = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_log = sprintf("&totalRows_Recordset_log=%d%s", $totalRows_Recordset_log, $queryString_Recordset_log);

//查找与自己相关的所有项目
mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_project = sprintf("SELECT id, project_name,project_text, project_start, project_end, project_to_user, project_lastupdate, project_create_time FROM tk_project inner join tk_team on tk_team.tk_team_pid=tk_project.id WHERE tk_team_uid = %s AND tk_team_del_status=1 ORDER BY tk_project.project_name ASC",GetSQLValueString($_SESSION['MM_uid'],"int"));
$Recordset_project = mysql_query($query_Recordset_project, $tankdb) or die(mysql_error());
$row_Recordset_project = mysql_fetch_assoc($Recordset_project);
$totalRows_Recordset_project = mysql_num_rows($Recordset_project);
?>


<script type="text/JavaScript">
function GP_popupConfirmMsg(msg) { 
  document.MM_returnValue = confirm(msg);
}

function MM_goToURL() { 
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}

function   searchtask() 
      {document.form1.action= "<?php echo $pagename; ?>"; 
        document.form1.submit(); 
        return   true; 
      
  } 

function   exportexcel() 
      {document.form1.action= "excel_log.php "; 
        document.form1.submit(); 
        return   false; 
      
      } 
</script>

<div class="filesubtab" id="tasktab">
	<div class="filetab " id="filesubtab" style="margin-top:50px;">
	
	
	<div class="clearboth"></div>
			<div class="condition">
				<span>
					<form id="form1" name="myform" method="get" class="taskform form-inline">
						<select class="form-control " style="width:110px;" name="select_year" id="select_year" >
							<option value="--"
								<?php if($_SESSION['ser_year']=="--"){
									echo "selected=\"selected\"";
								} ?>><?php echo $multilingual_taskf_year; ?>
							</option>
							<?php for($i = 2009; $i <= 2050; $i++) { ?>
								<option value="<?php echo $i; ?>" <?php 
									if (isset($_SESSION['ser_year'])) {	
										if (!(strcmp($i, $_SESSION['ser_year']))) {
											echo "selected=\"selected\"";
										}
									}else if (!(strcmp($i, date("Y")))) {
										echo "selected=\"selected\"";
									} ?>><?php echo $i; ?>
								</option>
							<?php  }?>
						</select>
						<select class="form-control"  style="width:110px;" name="textfield" id="textfield">
							<option value="--"
								<?php if($_SESSION['ser_month']=="--"){
									echo "selected=\"selected\"";
								} ?>><?php echo $multilingual_taskf_month; ?>
							</option>
							<?php for($i = 1; $i <= 12; $i++) { ?>
								<option value="<?php $xi = $i; if($i<=9){$xi ="0".$i;}   echo $xi; ?>" <?php 
									if (isset($_SESSION['ser_month'])) {	
										if (!(strcmp($xi, $_SESSION['ser_month']))) {
											echo "selected=\"selected\"";
										}
									}else if (!(strcmp($i, date("n")))) {
										echo "selected=\"selected\"";
									} ?>><?php echo $xi; ?>
								</option>
							<?php  }?>
						</select>
						<!--查找该用户所属的所有项目-->	  
						<select class="form-control " style="width:200px;" name="select_project" id="select_project" onChange="getclass('select_stage');">
							<option value=""
							<?php if($_SESSION['ser_project']==""){
								echo "selected=\"selected\"";
							} ?>>所有项目</option>
							<?php
							if(mysql_num_rows($Recordset_project)>0){
								do {  
							?>
									<option  value="<?php echo $row_Recordset_project['id']?>"
									<?php 
										if (isset($_SESSION['ser_project'])) {	
											if (!(strcmp($row_Recordset_project['id'], $_SESSION['ser_project']))) {
												echo "selected=\"selected\"";
											}
										}?> ><?php echo $row_Recordset_project['project_name']?>
									</option>
								<?php
								} while ($row_Recordset_project = mysql_fetch_assoc($Recordset_project));
								
								$rows = mysql_num_rows($Recordset_project);
								if($rows > 0) {
									mysql_data_seek($Recordset_project, 0);
									$row_Recordset_project = mysql_fetch_assoc($Recordset_project);
								}
							}
							?>
						</select>
						<input name="pagetab" id="pagetab" value="<?php echo $pagetabs; ?>" style="display:none" />
						<input name="sort" id="sort" value="<?php echo $sortlist; ?>" style="display:none" />
						<input name="order" id="order" value="<?php echo $orderlist; ?>" style="display:none" />
						<button type="submit" name="search" id="search"  class="btn btn-default btn-sm" onclick= "return   searchtask(); " ><span class="glyphicon glyphicon-filter" style="display:inline;"></span><?php echo $multilingual_global_filterbtn; ?></button>
						<button type="button" class="btn btn-default" name="export" id="export"  onclick= "return   exportexcel(); " ><?php echo $multilingual_global_excel; ?></button>
					</form>
				</span>
				<div  class="clearboth"> </div>
			</div>
			
	<table align="center" class="fontsize-s glink" width="100%">
	<tbody>
	<?php if ($totalRows_Recordset_log > 0) { ?>
		<tr>
			<td align="center">
				<table  class="table table-striped table-hover glink" style="width:100%;">
<thead>
<tr >
<th width="10%">
</th>
<th width="45%" >
<a href="<?php echo $pagenames; ?>?<?php echo $current_url; ?>&sort=tk_log_user&order=
								<?php 
									if ( $sortlist <> "tk_log_user"){
										echo "DESC";
									}else if( $sortlist == "tk_log_user" && $orderlist == "DESC"){
										echo "ASC";
									} else {
										echo "DESC";
									}
								?>" 
								<?php 
									if($sortlist=="tk_log_user" && $orderlist=="ASC"){
										echo "class='sort_asc'";
									} else if ($sortlist=="tk_log_user" && $orderlist=="DESC"){
										echo "class='sort_desc'";
									}
								?>><?php echo $multilingual_head_feed; ?></a></th>
<th width="15%"><a href="<?php echo $pagenames; ?>?<?php echo $current_url; ?>&sort=project&order=
								<?php 
									if ( $sortlist <> "project"){
										echo "DESC";
									}else if( $sortlist == "project" && $orderlist == "DESC"){
										echo "ASC";
									} else {
										echo "DESC";
									}
								?>" 
								<?php 
									if($sortlist=="project" && $orderlist=="ASC"){
										echo "class='sort_asc'";
									} else if ($sortlist=="project" && $orderlist=="DESC"){
										echo "class='sort_desc'";
									}
								?>><?php echo $multilingual_user_view_project2; ?></a></th>
<th width="15%"><a href="<?php echo $pagenames; ?>?<?php echo $current_url; ?>&sort=tk_log_time&order=
								<?php 
									if ( $sortlist <> "tk_log_time"){
										echo "DESC";
									}else if( $sortlist == "tk_log_time" && $orderlist == "DESC"){
										echo "ASC";
									} else {
										echo "DESC";
									}
								?>" 
								<?php 
									if($sortlist=="tk_log_time" && $orderlist=="ASC"){
										echo "class='sort_asc'";
									} else if ($sortlist=="tk_log_time" && $orderlist=="DESC"){
										echo "class='sort_desc'";
									}
								?>><?php echo $multilingual_project_file_update; ?></a></th>
								
<th width="10%">
</th>
</tr>
</thead>
<tbody>
  <?php 
    $log_count = 0;
    foreach($log_arr as $key => $val){    	
    	if($log_count >= $pageNum_Recordset_log * $maxRows_Recordset_log && $log_count < ($pageNum_Recordset_log+1) * $maxRows_Recordset_log){
  ?>
    <tr>
	<td>
	</td>
        <td class="glink">
	        <span class="glyphicon glyphicon-user"></span> 
	        <a href="user_view.php?recordID=<?php echo $val['user']; ?>">
	        <?php echo get_user_disName($val['user']); //显示负责人?>
	        </a>
	        <?php echo $multilingual_user_view_by; ?> 
		    <?php echo $val['time']; //显示时间?>	    
		    <?php echo $multilingual_user_view_do; ?>  
		    <?php 
		     $action = $val['action'];
		     $type_id = $val['type'];
		     $pid = $val['pid'];
		    if($val['class'] == 1){
	            echo "项目-"; 
	            echo "<a href='project_view.php?pagetab=allprj&recordID=$type_id'>$action</a>";
		    }else if($val['class'] == 2){
	            echo "阶段-"; 
	            echo "<a href='stage_view.php?sid=$type_id&pid=$pid'>$action</a>";
		    }else if($val['class'] == 3){
	            echo "任务-"; 
	            echo "<a href='default_task_edit.php?editID=$type_id&pagetab=alltask'>$action</a>";
		    }else if($val['class'] == 4){
	            echo "文件-"; 
	            echo "<a href='file.php?pagetab=allfile&projectID=$pid&recordID=$type_id'>$action</a>";
		    }?> 
        </td>
        <!-- 显示所属项目 -->
		<td class="glink" width="160px" >
		  <?php 
		  $project = get_project_by_id($val['pid']);
		  $project_name = $project['project_name'];
		  ?><a href='project_view.php?pagetab=allprj&recordID=$pid'><?php  echo $val['pid']; ?></a>
		</td>

		<td class="glink" width="240px" >
		<?php echo $val['time']; ?>  
		</td>
	<td>
	</td>
    </tr>
<?php
    }
    $log_count++;
}
?>

</tbody>
</table>
</td>
		</tr>
		<tr valign="baseline">
			<td colspan="2" >
<table class="rowcon" border="0" align="center">
<tr>
<td>   <table border="0">
        <tr></td>
<td align="left">   <?php echo ($startRow_Recordset_log + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_log + $maxRows_Recordset_log, $totalRows_Recordset_log) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_log ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
		
						<td>   
							<table border="0">
								<tr>				
          <td><?php if ($pageNum_Recordset_log > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_log=%d%s", $currentPage, 0, $queryString_Recordset_log); ?>&pagetab=<?php echo $pagetabs;?>#task"><?php echo $multilingual_global_first; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_log > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_log=%d%s", $currentPage, max(0, $pageNum_Recordset_log - 1), $queryString_Recordset_log); ?>&pagetab=<?php echo $pagetabs;?>#task"><?php echo $multilingual_global_previous; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_log < $totalPages_Recordset_log) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_log=%d%s", $currentPage, min($totalPages_Recordset_log, $pageNum_Recordset_log + 1), $queryString_Recordset_log); ?>&pagetab=<?php echo $pagetabs;?>#task"><?php echo $multilingual_global_next; ?></a>
              <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_Recordset_log < $totalPages_Recordset_log) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_log=%d%s", $currentPage, $totalPages_Recordset_log, $queryString_Recordset_log); ?>&pagetab=<?php echo $pagetabs;?>#task"><?php echo $multilingual_global_last; ?></a>
              <?php } // Show if not last page ?></td>
        </tr>
							</table>
						</td>
					</tr>
				</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
<?php } else { ?>
		<tr>
			<td colspan="2">
				<table>
					<div class="alert alert-warning search_warning" style="margin:6px;">
						<?php echo $multilingual_user_view_nolog; ?>
					</div>
				</table>
			</td>
		</tr>
<?php }  ?> 
</tbody>
</table>
</div>
</div>
