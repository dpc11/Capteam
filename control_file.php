<script type="text/javascript">
function addfolder()
{
    J.dialog.get({ id: "test7", title: '<?php echo $multilingual_project_file_addfolder; ?>', width: 600, height: 500, page: "file_add_folder.php?projectid=<?php echo $filepro; ?>&pid=<?php echo $filepid; ?>&folder=1<?php if ( $pfiles== "1") {
	  echo "&pfile=1";
	  }?>&pagetab=<?php echo $pagetabs;?>" });
}

function editfolder()
{
    J.dialog.get({ id: "test8", title: '<?php echo $multilingual_project_file_editfolder; ?>', width: 600, height: 500, page: "file_edit_folder.php?editID=<?php echo $row_Recordset_file['docid']; ?>&projectID=<?php 
	  if ( $pfiles== "1" || $colname_DetailRS1 == "-1") { 
	  echo $project_id;
	  } else {
	  echo $row_DetailRS1['tk_doc_pid'];
	  } ?>&pid=<?php echo $row_DetailRS1['docid']; ?>&folder=<?php echo $row_Recordset_file['tk_doc_backup1']; ?><?php if ( $pfiles== "1") {
	  echo "&pfile=1";
	  }?>&pagetab=<?php echo $pagetabs;?>" });
}
</script>

<table align="center" class="fontsize-s glink" width="100%">

<!--面包屑 即文件路径 -->
<tbody>
	<?php if ( $colname_DetailRS1 <> "-1" && $project_id <> "-1" ) { // 如果是一级页面不显示任何面包屑 ?>
	<tr>
		<td>
			<ul class="breadcrumb"  style="margin-top:10px;">
				<?php if ( $searchf == "1") { //搜索结果面包屑 ?>

					<span class="float_left"><a href="file.php?pagetab=<?php echo $pagetabs;?>"><?php echo $multilingual_breadcrumb_filelist; ?></a></span>
					<span class="ui-icon month_next float_left"></span>
					<span class="float_left"><?php echo $multilingual_project_file_searchr; ?>:<?php echo $filenames; ?></span>
	 
				<?php } else{// 项目文档面包屑 ?>

					<li><a href="file.php?pagetab=<?php echo $pagetabs;?>"><?php echo $multilingual_project_file_allfile; ?></a> </li>
						<?php if(get_parent_folder_id($colname_DetailRS1)>0){
							$Pid1=get_parent_folder_id($colname_DetailRS1);
							if(get_parent_folder_id($Pid1)>0){
								$Pid2=get_parent_folder_id($Pid1);
								if(get_parent_folder_id($Pid2)>0){//超过3层?>
									<li>
									......<a href="file.php?pagetab=<?php echo $pagetabs;?>&projectID=<?php get_projectID($Pid2) ?>&recordID=$colname_DetailRS1"><?php echo get_document_name($Pid2) ?></a>
									</li>
									<li>
									<a href="file.php?pagetab=<?php echo $pagetabs;?>&projectID=<?php get_projectID($Pid1) ?>&recordID=$colname_DetailRS1"><?php echo get_document_name($Pid1) ?></a>
									</li>
									<li>
									<a href="file.php?pagetab=<?php echo $pagetabs;?>&projectID=<?php get_projectID($colname_DetailRS1) ?>&recordID=$colname_DetailRS1"><?php echo get_document_name($colname_DetailRS1) ?></a>
									</li>
								<?php}else{//3层?>
									<li>
									<a href="file.php?pagetab=<?php echo $pagetabs;?>&projectID=<?php get_projectID($Pid2) ?>&recordID=$colname_DetailRS1"><?php echo get_document_name($Pid2) ?></a>
									</li>
									<li>
									<a href="file.php?pagetab=<?php echo $pagetabs;?>&projectID=<?php get_projectID($Pid1) ?>&recordID=$colname_DetailRS1"><?php echo get_document_name($Pid1) ?></a>
									</li>
									<li>
									<a href="file.php?pagetab=<?php echo $pagetabs;?>&projectID=<?php get_projectID($colname_DetailRS1) ?>&recordID=$colname_DetailRS1"><?php echo get_document_name($colname_DetailRS1) ?></a>
									</li>
								<?php}
							}else{//2层?>
								<li>
								<a href="file.php?pagetab=<?php echo $pagetabs;?>&projectID=<?php get_projectID($Pid1) ?>&recordID=$colname_DetailRS1"><?php echo get_document_name($Pid1) ?></a>
								</li>
								<li>
								<a href="file.php?pagetab=<?php echo $pagetabs;?>&projectID=<?php get_projectID($colname_DetailRS1) ?>&recordID=$colname_DetailRS1"><?php echo get_document_name($colname_DetailRS1) ?></a>
								</li>
							<?php}			
						}else{//1层?>
							<li>
							<a href="file.php?pagetab=<?php echo $pagetabs;?>&projectID=<?php get_projectID($colname_DetailRS1) ?>&recordID=$colname_DetailRS1"><?php echo get_document_name($colname_DetailRS1) ?></a>
							</li>
						<?php}?>
				<?php } ?>	
	
			</ul>
		</td>
	 </tr>
	<?php } //如果是一级页面不显示任何面包屑 ?>	
	<!--面包屑结束 -->
	<tr>
		<td >
			<!--搜索 -->
			<div class="search_div pagemarginfix">
			<form id="form1" name="form1" method="get" action="file.php"  class="saerch_form form-inline">
				<input type="text" name="filetitle" id="filetitle" class="form-control input-sm" placeholder="<?php echo $multilingual_project_file_search; ?>"><input name="search" type="text" id="search" value="1" style="display:none;"><input name="pagetab" type="text" id="pagetab" value="allfile" style="display:none;">
			  
				<button type="submit" name="button11" id="button11" class="btn btn-default btn-sm" /><span class="glyphicon glyphicon-search" style="display:inline;"></span> <?php echo $multilingual_global_searchbtn; ?></button>
            </form>

			</div>
			<!--搜索 -->
		</td>
	</tr>

	<?php if(get_doc_description($colname_DetailRS1)<>""){ //显示文档详情 ?>

		<tr valign="baseline">
			<td colspan="2" style="padding-left:10px; padding-bottom:15px ">
				<span  style="font-family:Arial;line-height:normal;"><?php echo get_doc_description($colname_DetailRS1) ?></span>
			</td>
		</tr>
	<?php }?>

  
	<?php if($totalRows_Recordset_actlog > 0){ //显示操作记录，如果有 ?>
		<tr valign="baseline">
			<td colspan="2" nowrap="nowrap"><span class="input_task_title "><?php echo $multilingual_log_title; ?></span></td>
		</tr>
	
		<tr>
			<td colspan="2">
				<table border="0" cellspacing="0" cellpadding="0" width="100%" >


					<?php do { ?>
					<tr>
						<td class="comment_list">
							<?php echo $row_Recordset_actlog['tk_log_time']; ?> <a href="user_view.php?recordID=<?php echo $row_Recordset_actlog['tk_log_user']; ?>"><?php echo $row_Recordset_actlog['tk_display_name']; ?></a> <?php echo $row_Recordset_actlog['tk_log_action']; ?>
						</td>
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
				<table class="rowcon" border="0" align="center">
					<tr>
						<td>   
							<table border="0">
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
							</table>
						</td>
						<td align="right">   <?php echo ($startRow_Recordset_actlog + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_actlog + $maxRows_Recordset_actlog, $totalRows_Recordset_actlog) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_actlog ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
					</tr>
				</table> 	
			</td>
		</tr>
	<?php } //显示操作记录，如果有?>
	
	<!--file start -->
	<?php if($totalRows_DetailRS1> "0"){  //文档列表 ?>
		<tr>
			<td>
				<table  class="table table-striped table-hover glink" width="98%" >
					<thead>
						<tr>
							<th>
							<?php echo $multilingual_project_file_management; ?>
							</th>
							<th width="100px">
							<?php echo $multilingual_project_file_update_by; ?>
							</th>
							<th width="160px">
							<?php echo $multilingual_project_file_update; ?>
							</th>
							<th width="160px">
	
							</th>
						</tr>
						<a href="file.php?pagetab=<?php echo $pagetabs;?>&projectID=<?php get_projectID($Pid2) ?>&recordID=$colname_DetailRS1"><?php echo get_document_name($Pid2) ?></a>
					</thead>
					<tbody>
					<?php if($totalRows_DetailRS1 > "0" ){  //显示所有的文档 ?>
						<?php do { //循环文档列表 ?>
							<tr>
								<td>
						  
									<?php if($row_DetailRS1['tk_doc_backup1']=="1"){ //如果是文件夹?>
										<a href="file.php?pagetab=<?php echo $pagetabs;?>&projectID=<?php echo $project_id; ?>&recordID=<?php echo $row_DetailRS1['docid']; ?>" class="icon_folder"><?php echo $row_DetailRS1['tk_doc_title']; ?></a>
						  
									<?php } else { //如果是文件?>
										<a href="file_view.php?recordID=<?php echo $row_DetailRS1['docid']; ?>" class="icon_file" target="_blank"><?php echo $row_DetailRS1['tk_doc_title']; ?></a>
						  
										<?php if ($row_DetailRS1['tk_doc_attachment'] <> ""  && $row_DetailRS1['tk_doc_attachment'] <> " ") {  ?>
											<div class="float_left">
												&nbsp;&nbsp;<a href="<?php echo $row_DetailRS1['tk_doc_attachment']; ?>" class="icon_atc"><?php echo $multilingual_project_file_download; ?></a>
											</div>
										<?php } ?>
						  
									<?php } //如果是文件?>
	
	
								</td>
								<td>
									<?php if($row_DetailRS1['tk_doc_create']<>0){ ?>
										<a href="user_view.php?recordID=<?php echo $row_DetailRS1['tk_doc_create']; ?>">
										<?php echo $row_DetailRS1['tk_display_name']; ?>
										</a>
									<?php }else{?>
										<?php echo $row_DetailRS1['tk_display_name']; ?>
									<?php }?>
								</td>
								<td>
									<?php echo $row_DetailRS1['tk_doc_lastupdate']; ?>
								</td>
								<td>
									<?php if ($row_DetailRS1['tk_doc_backup1'] <> "1") {  ?>
										<a href="word.php?fileid=<?php echo $row_DetailRS1['docid']; ?>" class="icon_word"><?php echo $multilingual_project_file_word; ?></a> 
									<?php } ?>
									&nbsp;
	  
	  
									<?php if($_SESSION['MM_uid'] == $row_DetailRS1['tk_doc_create']) { ?>
										<?php if($row_DetailRS1['tk_doc_backup1']=="1"){ //如果是文件夹?>
											<script type="text/javascript">
												function editfolder<?php echo $row_DetailRS1['docid']; ?>()
												{
													J.dialog.get({ id: "test", title: '<?php echo $multilingual_project_file_editfolder; ?>', width: 600, height: 500, page: "file_edit_folder.php?editID=<?php echo $row_DetailRS1['docid']; ?>&projectID=<?php 
														if ( $pfiles== "1" || $colname_DetailRS1 == "-1") { 
															echo $project_id;
														} else {
															echo $row_DetailRS1['tk_doc_pid'];
														} ?>&pid=<?php echo $row_DetailRS1['docid']; ?>&folder=<?php echo $row_DetailRS1['tk_doc_backup1']; ?>
														<?php if ( $pfiles== "1") {
															echo "&pfile=1";
														}?>&pagetab=<?php echo $pagetabs;?>" });
													}
												}
											</script>
											<a onclick="editfolder<?php echo $row_DetailRS1['docid']; ?>()" class="mouse_hover"><?php echo $multilingual_global_action_edit; ?></a> 
										<?php } else{ //如果是文件?>
											<a href="file_edit.php?editID=<?php echo $row_DetailRS1['docid']; ?>&projectID=<?php 
												if ( $pfiles== "1" || $colname_DetailRS1 == "-1") { 
													echo $project_id;
												} else {
													echo $row_DetailRS1['tk_doc_pid'];
												} ?>&pid=<?php echo $row_DetailRS1['docid']; ?>&folder=<?php echo $row_DetailRS1['tk_doc_backup1']; ?>
												<?php if ( $pfiles== "1") {
													echo "&pfile=1";
												}?>&pagetab=<?php echo $pagetabs;?>" target="_blank">
												<?php echo $multilingual_global_action_edit; ?></a> 
										<?php } ?>
										&nbsp;
								  
	  
										<?php if ($_SESSION['MM_rank'] > "4" || $row_DetailRS1['tk_doc_create'] == $_SESSION['MM_uid']) {  ?>
											<a  class="mouse_hover" 
												onclick="javascript:if(confirm( '<?php 
													if ($row_DetailRS1['tk_doc_backup1'] == 0){
														echo $multilingual_global_action_delconfirm;
													} else {
														echo $multilingual_global_action_delconfirm5;
													} ?>'))self.location='file_del.php?delID=<?php echo $row_DetailRS1['docid']; ?>&url=<?php echo $host_url; ?>';"><?php echo $multilingual_global_action_del; ?></a>
										<?php } ?>
		
									<?php } ?>
								</td>
							</tr>
    
						<?php
						} while ($row_DetailRS1 = mysql_fetch_assoc($Recordset_file));
							$rows = mysql_num_rows($Recordset_file);
							if($rows > 0) {
								mysql_data_seek($Recordset_file, 0);
								$row_DetailRS1 = mysql_fetch_assoc($Recordset_file);
							} //文档列表循环结束
						?>
					<?php } ?>
					</tbody>
				</table>

			</td>
		</tr>
		<tr valign="baseline">
			<td colspan="2" >
				<table class="rowcon" border="0" align="center">
					<tr>
						<td>   
							<table border="0">
								<tr>
								  <td><?php if ($pageNum_Recordset_file > 0) { // Show if not first page ?>
									  <a href="<?php printf("%s?pageNum_Recordset_file=%d%s", $currentPage, 0, $queryString_Recordset_file); ?>#task"><?php echo $multilingual_global_first; ?></a>
									  <?php } // Show if not first page ?></td>
								  <td><?php if ($pageNum_Recordset_file > 0) { // Show if not first page ?>
									  <a href="<?php printf("%s?pageNum_Recordset_file=%d%s", $currentPage, max(0, $pageNum_Recordset_file - 1), $queryString_Recordset_file); ?>#task"><?php echo $multilingual_global_previous; ?></a>
									  <?php } // Show if not first page ?></td>
								  <td><?php if ($pageNum_Recordset_file < $totalPages_Recordset_file) { // Show if not last page ?>
									  <a href="<?php printf("%s?pageNum_Recordset_file=%d%s", $currentPage, min($totalPages_Recordset_file, $pageNum_Recordset_file + 1), $queryString_Recordset_file); ?>#task"><?php echo $multilingual_global_next; ?></a>
									  <?php } // Show if not last page ?></td>
								  <td><?php if ($pageNum_Recordset_file < $totalPages_Recordset_file) { // Show if not last page ?>
									  <a href="<?php printf("%s?pageNum_Recordset_file=%d%s", $currentPage, $totalPages_Recordset_file, $queryString_Recordset_file); ?>#task"><?php echo $multilingual_global_last; ?></a>
									  <?php } // Show if not last page ?></td>
								</tr>
							</table>
						</td>
						<td align="right">   <?php echo ($startRow_Recordset_file + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_file + $maxRows_Recordset_file, $totalRows_Recordset_file) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_file ?>)&nbsp;&nbsp;&nbsp;&nbsp;
						</td>
					</tr>
				</table>
			</td>
		</tr>
	<?php  } else {?>
		<tr>
			<td colspan="2">
				<table>
					<div class="alert alert-warning" style="margin:6px;">
						<?php echo $multilingual_project_file_nofile; ?>
					</div>
				</table>
			</td>
		</tr>
	<?php } ?>
	<p>&nbsp;</p>
</tbody>
</table>

<?php mysql_free_result($row_DetailRS1); ?>