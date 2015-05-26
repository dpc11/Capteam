<?php
$str=get_tree( $project_id );
$prjurl="project_view.php?recordID=".$project_id."&pagetab=allprj";
?>

<link rel="StyleSheet" href="css/dtree/dtree.css" type="text/css" />
<script type="text/javascript" src="js/dtree/dtree.js"></script>

<body >

<script type="text/javascript" language="javascript">    
 
 
function TuneHeight()    
{    
var frm = document.getElementById("frame_content");    
var subWeb = document.frames ? document.frames["main_frame"].document : frm.contentDocument;    
if(frm != null && subWeb != null)    
{ frm.height = subWeb.body.scrollHeight;}    
}    
 

        $(document).ready(function() {
            var h = $(window).height(), h2;
            var h = h - <?php if($totalRows_Recordset_anc > 0) {echo "80";} else {echo "50";} ?>;
            $("#main_left").css("height", h);
            $(window).resize(function() {
                h2 = $(this).height();
                $("#main_left").css("height", h2);
            });
        })

</script>
<div class="tree_div" id="main_left">
    <?php if($str <> "0"){ ?>
	<td>
	<div class=" add_title col-xs-12">
		<h5 ><?php echo $multilingual_project_view_wbs; ?></h5>
	</div>
<script type="text/javascript">

		var r =<?php echo $str; ?>;
        
        d = new dTree('d');
        d.add( 0 ,-1,'<?php echo "<b>[".$multilingual_head_project."]".$project_name."</b>"; ?>','<?php  echo $prjurl; ?>');

        for(i=0;i<r.length;i++)
        {
            if(r[i].id>100000){//表示是阶段
                var stage_id = r[i].id;
                stage_id = stage_id - 100000;
                d.add(r[i].id,r[i].pid,r[i].name,"stage_view.php?sid="+stage_id+"&pid="+<?php echo $project_id;?>,r[i].title);
            }else{//表示是任务
                d.add(r[i].id,r[i].pid,r[i].name,"task_view.php?pagetab=alltask&editID="+r[i].id,r[i].title);
            }                 
        }
        document.write(d);

</script>
	</td>
	<td valign="top" class="gray2">
		<h4 style="margin-top:40px; margin-left: 5px;" ><?php echo $multilingual_project_view_nowbs; ?></h4>
		<p > <?php echo $multilingual_project_view_nowbstext; ?></p>
	</td>
	
    <?php } else{ ?>
	<td valign="top" class="gray2">
		<h4 style="margin-top:40px; margin-left: 5px;" ><?php echo $multilingual_project_view_nowbs; ?></h4>
		<p > <?php echo $multilingual_project_view_nowbstext; ?></p>
	</td>
    <?php } ?>
    

</div>
</body>