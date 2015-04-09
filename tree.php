<?php
$str=get_tree( $project_id );
$prjurl="project_view.php?recordID=".$project_id."&pagetab=allprj";
?>

<link rel="StyleSheet" href="dtree/dtree.css" type="text/css" />
<script type="text/javascript" src="dtree/dtree.js"></script>

<body onLoad="javascript: d.openTo(<?php  if ($pagename == "project_view.php"){ echo "-1"; } else { echo $node_id_task; } ?>,true);">

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
    <h5 class="font_big18 fontbold"><?php echo $multilingual_project_view_wbs; ?></h5>
    
<script type="text/javascript">
        <!--
		var r =<?php echo $str; ?>;

        d = new dTree('d');
		d.add(0,-1,'<?php echo "<b>[".$multilingual_head_project."]".$project_name."</b>"; ?>','<?php  echo $prjurl; ?>');
        for(i=0;i<r.length;i++)
        {
            d.add(Number(r[i].id),Number(r[i].pid),r[i].name,"default_task_edit.php?pagetab=alltask&editID="+r[i].id,r[i].title);
        }
        document.write(d);
    //-->
</script>
    <?php } else{ ?>
    <div style="margin:15px">
        <span class="font_big18 fontbold breakwordsfloat_left"><?php echo $multilingual_project_view_nowbs; ?></span><br /><br />
    <span class="gray2"><?php echo $multilingual_project_view_nowbstext; ?></span>
    </div>
    <?php } ?>
    

</div>
</body>