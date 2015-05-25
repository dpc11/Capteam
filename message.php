<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php require_once('function/message_function.php'); ?>
<?php

$pagetabs = "allmsg";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}
$currentPage = $_SERVER["PHP_SELF"];

$where="";
if($pagetabs=="allmsg"){
$where ="tk_mess_status >= 0";
}else if($pagetabs=="nomsg"){
$where ="tk_mess_status>0";
}else if($pagetabs=="readmsg"){
$where ="tk_mess_status = 0";
}else if($pagetabs=="delmsg"){
$where ="tk_mess_status = -1";
}

//获得当前页面的url
$currentPage = $_SERVER["PHP_SELF"];
//设置消息最大显示行数为30行
$maxRows_Recordset1 = 30;
//设置当前显示页面为0
$pageNum_Recordset1 = 0;
//获取当前显示页面
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT meid,tk_mess_fromuser,tk_display_name,project_name,id,
									tk_mess_title,tk_mess_status,tk_mess_time 
							FROM tk_message,tk_project,tk_user,tk_task
							WHERE  tk_message.tk_task_id=tk_task.tid and tk_message.tk_mess_fromuser=tk_user.uid and  tk_task.csa_project=tk_project.id  and tk_mess_touser = %s and $where ORDER BY meid DESC", GetSQLValueString($_SESSION['MM_uid'], "int"));
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $pageNum_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysql_query($query_Recordset1);
  $totalRows_Recordset1 = mysql_num_rows($all_Recordset1);
}

$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;

$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset1") == false && 
        stristr($param, "totalRows_Recordset1") == false) {
      array_push($newParams, $param);
    }
  }
	  if (count($newParams) != 0) {
		$queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
	  }
	}
	$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);

?>

<?php require('head.php'); ?>

<link rel="stylesheet" type="text/css" href="css/select/component.css" />
<script src="js/select/modernizr.custom.js"></script>

<div class="subnav" id="subnav">		
<div class="float_left" style="width:50%">
	<div class="btn-group">
		<a type="button" class="btn btn-default btn-lg <?php if($pagetabs == "allmsg") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=allmsg">所有消息</a>
		<a type="button" class="btn btn-default btn-lg <?php if($pagetabs == "nomsg") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=nomsg">未读消息</a>
		<a type="button" class="btn btn-default btn-lg <?php if($pagetabs == "readmsg") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=readmsg">已读消息</a>
		<a type="button" class="btn btn-default btn-lg <?php if($pagetabs == "delmsg") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=delmsg">已删除</a>
		<?php if($pagetabs == "delmsg"){ ?>
		<button type="button" name="button11" id="button11" class="btn btn-link btn-lg" onclick="delete_delete_msg();"><span class="glyphicon glyphicon-remove" style="display:inline;margin-left:10px;"></span>彻底删除</button>
		<button type="button" name="button11" id="button11" class="btn btn-link btn-lg"onclick="delete_all();"><span class="glyphicon glyphicon-trash" style="display:inline;"></span> 清空</button>
		<?php }else{ ?>
		<button type="button" name="button11" id="button11" class="btn btn-link btn-lg" onclick="delete_msg();"><span class="glyphicon glyphicon-remove" style="display:inline;margin-left:10px;"></span>删除</button>
		<button type="button" name="button11" id="button11" class="btn btn-link btn-lg" onclick="delete_delete_msg();" ><span class="glyphicon glyphicon-trash" style="display:inline;" ></span>彻底删除</button>
		<?php } ?>
	</div>
</div>
</div>
<div class="clearboth"></div>
<div class="pagemargin" id="pagemargin">  
<div class="filesubtab" id="tasktab" style="margin-top:30px;">
<div class="filetab " id="filesubtab">	 
<?php if ($totalRows_Recordset1 > 0) { // Show if recordset not empty ?>
    <form name="form1" id="form1" method="post">
		<section class="me-select" style="width:90%">
				<ul id="me-select-all">
					<li class="topicline" style="border-bottom: 2px solid #D1D1D1;height:55px;margin-top:15px;padding-top:15px;">
						<input style="width:5%;margin-top:-5px;" id="selectall" name="selectall" type="checkbox" onclick="check_all();" />
						<label for="selectall" >
							<span style="width:45%;padding-left:5%;font-size:22px;"><?php echo $multilingual_message; ?></span>
							<span style="width:25%;font-size:22px;">消息所属项目</span>
							<span style="width:15%;font-size:22px;"><?php echo $multilingual_message_time; ?></span>
						</label>
					</li>
					</ul>
					<ul id="me-select-list">
					    <?php do { ?>
							<li >
							<input  style="width:5%" id="cb1" name="cb1" type="checkbox" value="<?php echo $row_Recordset1['meid']; ?>" >
							<label for="cb1">
								<span  style="width:45%;padding-left:5%;<?php if($row_Recordset1['tk_mess_status'] == 1 || $row_Recordset1['tk_mess_status'] == 2) {echo "font-weight:bold"; }else{echo "font-weight:normal";} ?>">
									<a  style="font-size:20px;font-family:微软雅黑;<?php if($row_Recordset1['tk_mess_status'] == 1 || $row_Recordset1['tk_mess_status'] == 2) {echo "font-weight:bold"; }else{echo "font-weight:normal";} ?>" href="user_view.php?recordID=<?php echo $row_Recordset1['tk_mess_fromuser']; ?>" ><?php echo $row_Recordset1['tk_display_name']; ?></a>
									<span style="font-size:20px;font-family:微软雅黑;<?php if($row_Recordset1['tk_mess_status'] == 1 || $row_Recordset1['tk_mess_status'] == 2) {echo "font-weight:bold"; }else{echo "font-weight:normal";} ?>" 
									onclick="ajax_update_message(<?php echo $row_Recordset1['meid']; ?>)">
									<?php echo $row_Recordset1['tk_mess_title']; ?>
									<?php update_message($row_Recordset1['meid'],1,2); ?> 
									</span>
								</span>
								<span style="width:25%;font-size:20px;font-family:微软雅黑;font-weight:normal;">
								<a  style="font-size:20px;font-family:微软雅黑;font-weight:normal;" href="project_view.php?recordID=<?php echo $row_Recordset1['id']; ?>" ><?php echo $row_Recordset1['project_name']; ?></a></span>
								<span style="width:15%;font-size:20px;font-family:微软雅黑;font-weight:normal;"><?php echo $row_Recordset1['tk_mess_time']; ?></span>
							</label>
							</li>
						<?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
					</ul>
			</section>
      </form>
  </table>
<table class="rowcon" border="0" align="center">
	<tr>    
		<td align="left" valign="bottom"><?php echo ($startRow_Recordset1 + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset1 + $maxRows_Recordset1, $totalRows_Recordset1) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset1 ?>)</td>
        <td >
          <table border="0">
            <tr>
              <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, 0, $queryString_Recordset1); ?>"><?php echo $multilingual_global_first; ?></a>
                <?php } // Show if not first page ?></td>
              <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, max(0, $pageNum_Recordset1 - 1), $queryString_Recordset1); ?>"><?php echo $multilingual_global_previous; ?></a>
                <?php } // Show if not first page ?></td>
              <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, min($totalPages_Recordset1, $pageNum_Recordset1 + 1), $queryString_Recordset1); ?>"><?php echo $multilingual_global_next; ?></a>
                <?php } // Show if not last page ?></td>
              <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, $totalPages_Recordset1, $queryString_Recordset1); ?>"><?php echo $multilingual_global_last; ?></a>
                <?php } // Show if not last page ?></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
	
<?php } else { // Show if recordset empty ?> 
  		<tr>
			<td colspan="2">
				<table>
					<div class="alert alert-warning search_warning" style="margin:6px;" >
						<?php echo $multilingual_message_nomsg; ?>
					</div>
				</table>
			</td>
		</tr>
<?php } // Show if recordset empty ?>  
	</tbody>
	</table>
	</div>
	</div>
  </div><!--pagemargin结束 -->
 </div>
  <?php require('foot.php'); ?>
  
<script src="js/select/magicselection.js"></script>
<script language="javascript">

	$(window).load(function()
	{
		$(window).resize();	
		var selList = document.getElementById( 'me-select-list' ),
					items = selList.querySelectorAll( 'li' );
				
				[].slice.call( items ).forEach( function( el ) {
					el.className = el.querySelector( 'input[type="checkbox"]' ).checked ? 'selected' : '';
				} );

				function checkUncheck( el ) {
					var elCheckbox = el.querySelector( 'input[type="checkbox"]' );
					el.className = elCheckbox.checked ? '' : 'selected';
					elCheckbox.checked = !elCheckbox.checked;
				}

				new magicSelection( selList.querySelectorAll( 'li' ), {
					onSelection : function( el ) { checkUncheck( el ); },
					onClick : function( el ) {
						el.className = el.querySelector( 'input[type="checkbox"]' ).checked ? 'selected' : '';
					}
				} );
	});
	$(window).resize(function()
	{	
		$("#headerlink").css("width",$("#tasktab").width()/0.8929+"px");
		$("#foot_div").css("width",$("#tasktab").width()/0.8929+"px");
		$("#foot_div").css("width",$("#tasktab").width()/0.8929+"px");
		$("#foot_top").css("min-height",document.getElementById("pagemargin").clientHeight+document.getElementById("subnav").clientHeight+66+110+70+"px"); 
	});
	
	function delete_msg(){
		var optionstr="";
		var selList = document.getElementById( 'me-select-list' ),
				items = selList.querySelectorAll( 'li' );
				[].slice.call( items ).forEach( function( el ) {
					if(el.querySelector( 'input[type="checkbox"]' ).checked){
						if(optionstr==""){
							optionstr=optionstr+"("+el.querySelector( 'input[type="checkbox"]').value;
						}else{
							optionstr=optionstr+","+el.querySelector( 'input[type="checkbox"]').value;
						}
					}
				} );
		optionstr=optionstr+")";
		var xmlhttp;
		if (window.XMLHttpRequest)
		  {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		  }
		else
		  {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xmlhttp.onreadystatechange=function()
		  {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{			
				location.reload();	
			}
		  }
		xmlhttp.open("POST","message_del_real.php",false);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		alert(optionstr);
		xmlhttp.send("option=1&mid="+optionstr);
	}
	
	function delete_delete_msg(){
		var optionstr="";
		var selList = document.getElementById( 'me-select-list' ),
				items = selList.querySelectorAll( 'li' );
				[].slice.call( items ).forEach( function( el ) {
					if(el.querySelector( 'input[type="checkbox"]' ).checked){
						if(optionstr==""){
							optionstr=optionstr+"("+el.querySelector( 'input[type="checkbox"]').value;
						}else{
							optionstr=optionstr+","+el.querySelector( 'input[type="checkbox"]').value;
						}
					}
				} );
		optionstr=optionstr+")";
		var xmlhttp;
		if (window.XMLHttpRequest)
		  {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		  }
		else
		  {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xmlhttp.onreadystatechange=function()
		  {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{			
				location.reload();	
			}
		  }
		xmlhttp.open("POST","message_del_real.php",false);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("option=2&mid="+optionstr);
	}
	
	function delete_all(){
		var xmlhttp;
		if (window.XMLHttpRequest)
		  {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		  }
		else
		  {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xmlhttp.onreadystatechange=function()
		  {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{			
				location.reload();	
			}
		  }
		xmlhttp.open("POST","message_del_real.php",false);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("option=3&mid=''");
	}
	
		//该方法实现全选和取消全选
		function check_all(){
			
		var selList = document.getElementById( 'me-select-all' ),
					items = selList.querySelectorAll( 'li' );
				
				[].slice.call( items ).forEach( function( el ) {
					var t = el.querySelector( 'input[type="checkbox"]' ).checked;
					var selList1 = document.getElementById( 'me-select-list' ),
						items1 = selList1.querySelectorAll( 'li' );
					
					[].slice.call( items1 ).forEach( function( el ) {
						el.querySelector( 'input[type="checkbox"]' ).checked=t;
						el.className = t ? 'selected' : '';
					} );
				} );
		}
function ajax_update_message(meid)
{
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    alert(xmlhttp.responseText);
    }
  }
xmlhttp.open("POST","message_update.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send("meid="+meid);
}

</script>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
