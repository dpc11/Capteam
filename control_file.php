<?php require_once('function/file_function.php'); ?>

<script type="text/javascript">
function addfolder()
{
    J.dialog.get({ id: "test7", title: '<?php echo $multilingual_project_file_addfolder; ?>', width: 600, height: 500, page: "file_add_folder.php?projectid=<?php echo $project_id; ?>&pid=<?php echo $colname_DetailRS1; ?>&pagetab=<?php echo $pagetabs;?>" });
}
</script>

<table align="center" class="fontsize-s glink" width="100%">
<tbody>

	<tr>
		<td >
			<div class="search_div pagemarginfix">
			<form id="form1" name="form1" method="get" action="file.php"  class="saerch_form form-inline">
				<?php  if($filenames == ""){ ?>
					<input type="text" name="filetitle" id="filetitle" class="form-control input-sm" placeholder="<?php echo $multilingual_project_file_search; ?>">				
				<?php  }else{ ?>
					<input type="text" name="filetitle" id="filetitle" class="form-control input-sm" value="<?php echo $filenames; ?>">				
				<?php  } ?>
				<input name="search" type="text" id="search" value="1" style="display:none;">
				<input name="pagetab" type="text" id="pagetab" value="<?php echo $pagetabs; ?>" style="display:none;">
				<input name="projectID" type="text" id="projectID" value="<?php echo $project_id; ?>" style="display:none;">
				<input name="recordID" type="text" id="recordID" value="<?php echo $colname_DetailRS1; ?>" style="display:none;">
			  
				<button type="submit" name="button11" id="button11" class="btn btn-default btn-sm" ><span class="glyphicon glyphicon-search" style="display:inline;"></span> <?php echo $multilingual_global_searchbtn; ?></button>
            </form>
			</div>
		</td>
	</tr>
	<?php if ( ($colname_DetailRS1 <> "-1" && $project_id <> "-1") || $searchf == "1" ) { // 如果是一级页面不显示任何面包屑 ?>
	<tr>
		<td>
			<ul class="breadcrumb"  style="margin-top:10px;">
				<?php if($colname_DetailRS1 <> "-1" && $project_id <> "-1" ){// 项目文档面包屑 ?>

					<li><a href="file.php?pagetab=<?php echo $pagetabs; ?>">
					<?php if($pagetabs== "allfile"){echo $multilingual_project_file_allfile;}else{echo $multilingual_project_file_myfile;} ?></a> </li>
						<?php if(get_parent_folder_id($colname_DetailRS1) > 0){
							$Pid1 = get_parent_folder_id($colname_DetailRS1);
							if(get_parent_folder_id($Pid1) > 0){
								$Pid2 = get_parent_folder_id($Pid1);
								if(get_parent_folder_id($Pid2) > 0){//超过3层 ?>
									<li>
									......<a href="file.php?pagetab=<?php echo $pagetabs; ?>&projectID=<?php echo get_projectID($Pid2); ?>&recordID=<?php echo $Pid2; ?>"><?php echo get_document_name($Pid2); ?></a>
									</li>
									<li>
									<a href="file.php?pagetab=<?php echo $pagetabs; ?>&projectID=<?php echo get_projectID($Pid1); ?>&recordID=<?php echo $Pid1; ?>"><?php echo get_document_name($Pid1); ?></a>
									</li>
									<li>
									<a href="file.php?pagetab=<?php echo $pagetabs; ?>&projectID=<?php echo get_projectID($colname_DetailRS1); ?>&recordID=<?php echo $colname_DetailRS1; ?>"><?php echo get_document_name($colname_DetailRS1); ?></a>
									</li>
								<?php }else{//3层 ?>
									<li>
									<a href="file.php?pagetab=<?php echo $pagetabs; ?>&projectID=<?php echo get_projectID($Pid2); ?>&recordID=<?php echo $Pid2; ?>"><?php echo get_document_name($Pid2); ?></a>
									</li>
									<li>
									<a href="file.php?pagetab=<?php echo $pagetabs; ?>&projectID=<?php echo get_projectID($Pid1); ?>&recordID=<?php echo $Pid1; ?>"><?php echo get_document_name($Pid1); ?></a>
									</li>
									<li>
									<a href="file.php?pagetab=<?php echo $pagetabs; ?>&projectID=<?php echo get_projectID($colname_DetailRS1); ?>&recordID=<?php echo $colname_DetailRS1; ?>"><?php echo get_document_name($colname_DetailRS1); ?></a>
									</li>
								<?php }
							}else{//2层 ?>
								<li>
								<a href="file.php?pagetab=<?php echo $pagetabs; ?>&projectID=<?php echo get_projectID($Pid1); ?>&recordID=<?php echo $Pid1; ?>"><?php echo get_document_name($Pid1); ?></a>
								</li>
								<li>
								<a href="file.php?pagetab=<?php echo $pagetabs; ?>&projectID=<?php echo get_projectID($colname_DetailRS1); ?>&recordID=<?php echo $colname_DetailRS1; ?>"><?php echo get_document_name($colname_DetailRS1); ?></a>
								</li>
							<?php }			
						}else{//1层 ?>
							<li>
							<a href="file.php?pagetab=<?php echo $pagetabs; ?>&projectID=<?php echo get_projectID($colname_DetailRS1); ?>&recordID=<?php echo $colname_DetailRS1; ?>"><?php echo get_document_name($colname_DetailRS1); ?></a>
							</li>
						<?php } ?>
				<?php }else{ ?>
					<a href="file.php?pagetab=<?php echo $pagetabs; ?>">
					<?php if($pagetabs== "allfile"){echo $multilingual_project_file_allfile;}else{echo $multilingual_project_file_myfile;} ?></a>				
				<?php } ?>	
				<?php if ( $searchf == "1") { //搜索结果面包屑  ?>
					<span class="ui-icon month_next float_left"></span>
						<span >&nbsp;&nbsp;中对于&nbsp;“<?php echo $filenames; ?>”&nbsp;的搜索结果：</span>	
				<?php } ?>	
			</ul>
		</td>
	 </tr>
	<?php } //如果是一级页面不显示任何面包屑 ?>	


	<?php if(get_doc_description($colname_DetailRS1)<>""&& $searchf <> "1"){ //显示文档详情 ?>

		<tr valign="baseline">
			<td colspan="2" style="padding-left:10px; padding-bottom:15px ">
				<span  style="font-family:Arial;line-height:normal;"><?php echo get_doc_description($colname_DetailRS1); ?></span>
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
		
	<?php if($totalRows_DetailRS1> "0"){  //文档列表 ?>
		<tr>
			<td align="center">
				<table  class="table table-striped table-hover glink" style="width:98%;">
					<thead >
						<tr>
							<?php if($searchf == "1"){ ?>
							<th  width="2%"  >
							</th>
							<th  width="15%"  style="padding-left:2%" >
							<?php echo $multilingual_project_file_management; ?>
							</th>
							<th width="25%" style="text-align:center;" >
							<?php echo $multilingual_project_file_path; ?>
							</th>
							<th width="15%" style="text-align:center;" >
							<?php echo $multilingual_project_file_project; ?>
							</th>
							<th width="10%" style="text-align:center;" >
							<?php echo $multilingual_project_file_update_by; ?>
							</th>
							<th width="15%" style="text-align:center;" >
							<?php echo $multilingual_project_file_update; ?>
							</th>
							<th width="16%" >	
							</th>
							<th  width="2%"  >
							</th>
							<?php  } else { ?>
							<th  width="10%"  >
							</th>
							<th  width="20%"  style="padding-left:4%" >
							<?php echo $multilingual_project_file_management; ?>
							</th>
							<th width="15%" style="text-align:center;" >
							<?php echo $multilingual_project_file_update_by; ?>
							</th>
							<th width="15%" style="text-align:center;" >
							<?php echo $multilingual_project_file_update; ?>
							</th>
							<th width="20%" >	
							</th>
							<th  width="10%"  >
							</th>
							<?php  } ?>
						</tr>
					</thead>
					<tbody>
					<?php if($totalRows_DetailRS1 > "0" ){  //显示所有的文档 ?>
						<?php do { //循环文档列表 ?>
							<tr>
								<td>
								</td>
								<td style="text-align:center;">
									<?php if($row_DetailRS1['tk_doc_backup1']=="1"){ //如果是文件夹 ?>
										<a href="file.php?pagetab=<?php echo $pagetabs; ?>&projectID=<?php echo $row_DetailRS1['tk_doc_pid']; ?>&recordID=<?php echo $row_DetailRS1['docid']; ?>" class="icon_folder"><?php echo $row_DetailRS1['tk_doc_title']; ?></a>
						  
									<?php } else { //如果是文件 ?>
										<a href="file_view.php?recordID=<?php echo $row_DetailRS1['docid']; ?>" class="icon_file" target="_blank"><?php echo $row_DetailRS1['tk_doc_title']; ?></a>
						  
										<?php if ($row_DetailRS1['tk_doc_attachment'] <> ""  && $row_DetailRS1['tk_doc_attachment'] <> " ") {  ?>
											<div class="float_left">
												&nbsp;&nbsp;<a href="<?php echo $row_DetailRS1['tk_doc_attachment']; ?>" class="icon_atc"><?php echo $multilingual_project_file_download; ?></a>
											</div>
										<?php } ?>
						  
									<?php } //如果是文件 ?>
								</td>
								<?php if($searchf == "1"){ ?>
									<td style="text-align:center;" >
										<?php echo getPATH($row_DetailRS1['tk_doc_parentdocid'],""); ?>
									</td>
									<td style="text-align:center;" >
										<a href="project_view.php?recordID=<?php echo $row_DetailRS1['tk_doc_pid']; ?>">
											<?php echo get_projectNAME($row_DetailRS1['tk_doc_pid']); ?>
										</a>
									</td>
								<?php  } ?>
								<td style="text-align:center;" >
									<?php if($row_DetailRS1['tk_doc_create']<>0){ ?>
										<a href="user_view.php?recordID=<?php echo $row_DetailRS1['tk_doc_create']; ?>">
										<?php echo $row_DetailRS1['tk_display_name']; ?>
										</a>
									<?php }else{?>
										<?php echo $row_DetailRS1['tk_display_name']; ?>
									<?php } ?>
								</td>
								<td style="text-align:center;">
									<?php echo $row_DetailRS1['tk_doc_lastupdate']; ?>
								</td>
								<td style="text-align:center;">
									<?php if ($row_DetailRS1['tk_doc_backup1'] <> "1") {  ?>
										<a href="word.php?fileid=<?php echo $row_DetailRS1['docid']; ?>" class="icon_word"><?php echo $multilingual_project_file_word; ?></a> 
									<?php } ?>
									&nbsp;
	  
	  
									<?php if($_SESSION['MM_uid'] == $row_DetailRS1['tk_doc_create']) { ?>
										<?php if($row_DetailRS1['tk_doc_backup1']=="1"){ //如果是文件夹 ?>
											<script type="text/javascript">
												function editfolder<?php echo $row_DetailRS1['docid']; ?>()
												{
													J.dialog.get({ id: "test", title: '<?php echo $multilingual_project_file_editfolder; ?>', width: 600, height: 500, page: "file_edit_folder.php?editID=<?php echo $row_DetailRS1['docid']; ?>&projectID=<?php  echo $project_id; ?>&pid=<?php echo $row_DetailRS1['tk_doc_parentdocid']; ?>&pagetab=<?php echo $pagetabs; ?>" 
													});
												}
											</script>
											<a onclick="editfolder<?php echo $row_DetailRS1['docid']; ?>()" class="mouse_hover"><?php echo $multilingual_global_action_edit; ?></a> 
										<?php } else{ //如果是文件 ?>
											<a href="file_edit.php?editID=<?php echo $row_DetailRS1['docid']; ?>&projectID=<?php  echo $project_id;  ?>&pid=<?php echo $row_DetailRS1['tk_doc_parentdocid']; ?>&pagetab=<?php echo $pagetabs; ?>" target="_blank">
												<?php echo $multilingual_global_action_edit; ?></a> 
										<?php } ?>
										&nbsp;
								  
									<?php } ?>
	  
										<?php  if (($row_DetailRS1['tk_doc_create'] == $_SESSION['MM_uid'])||(get_leader_id($row_DetailRS1['docid'])==$_SESSION['MM_uid']&&$row_DetailRS1['tk_doc_create']>0)){  ?>
											<a  class="mouse_hover" 
												onclick="javascript:if(confirm( '<?php 
													if ($row_DetailRS1['tk_doc_backup1'] == 0){
														echo $multilingual_global_action_delconfirm;
													} else {
														echo $multilingual_global_action_delconfirm5;
													} ?>'))self.location='file_del.php?delID=<?php echo $row_DetailRS1['docid']; ?>&url=<?php echo $host_url; ?>';"><?php echo $multilingual_global_action_del; ?></a>
										<?php } ?>
								</td>
								<td>
								</td>
							</tr>
    
						<?php
						} while ($row_DetailRS1 = mysql_fetch_assoc($DetailRS1));
							$rows = mysql_num_rows($DetailRS1);
							if($rows > 0) {
								mysql_data_seek($Recordset_file, 0);
								$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);
							} //文档列表循环结束
					} ?>
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
									  <a href="<?php printf("%s?pageNum_Recordset_file=%d%s", $currentPage, $totalPages_Recordset_file, $queryString_Recordset_file); ?>#task"><?php  echo $multilingual_global_last; ?></a>
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
	<?php  }else{ ?>
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
</tbody>
</table>

<?php mysql_free_result($row_DetailRS1); ?>