<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php require_once('function/board_function.php'); ?>

<?php 
    $pid = "-1";
    if(isset($_GET['pid'])){
        $pid = $_GET['pid'];
    }
    $id_seq=0;

    $board_info = get_board_info($pid);
    $board_num =mysql_num_rows($board_info);
?>

<?php require('head.php');  ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 



<head> 

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<style> 
#displayRoom{background:white;position:relative;float:left;clear:both;padding:30px 0px 0px 20px;margin-left:20px;margin-top:10px;} 
.row{display:inline-block;float:left;width:16em;margin-right: 30px;margin-left: 30px;clear:none;top:0;background:white;} 
.row span{display:block;width:15em;clear:none;background:white;height:15em;line-height:30px;margin-right: 30px;margin-bottom:30px;text-align:center;} 
span.usr{text-decoration:none; 
color:#000; 
background:#ffc; 
display:block; 
height:15em; 
width:15em; 
padding:1em;  

-moz-box-shadow: 5px 5px 7px rgba(33,33,33,1); /* Safari+Chrome */ 
-webkit-box-shadow: 5px 5px 7px rgba(33,33,33,.7); /* Opera */ 
box-shadow: 5px 5px 7px rgba(33,33,33,.7);
cursor:pointer;display:block;width:15em;clear:none;height:15em;line-height:30px;margin-right: 2em;margin-bottom:30px;text-align:left;} 

span.usr_text{
    width: 150px;
    height: 140px;
    margin: 0px;
    margin-left: 3px;
    margin-top: 5px;
    background: rgba(255,255,255,0);
    text-align: left;
    text-decoration: none; 
    overflow-y:auto;
    position: absolute;
}

span.usr.catch{background:#ffc!important;}

/* 设置滚动条的样式 */
::-webkit-scrollbar {
    width: 0px;
}
/* 滚动槽 */
::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0);
    border-radius: 10px;
}
/* 滚动条滑块 */
:window-inactive
::-webkit-scrollbar-thumb {
    border-radius: 10px;
    background: rgba(0,0,0,0.1);
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0);
}
::-webkit-scrollbar-thumb:window-inactive {
    background: rgba(0,0,0,0);
}

</style>

</head> 
<body>
<!--<script type="text/javascript" src="js/jquery/jquery.js"></script>-->
<script type="text/javascript" src="js/jquery/jquery-1.9.1.js"></script>
<script type="text/javascript">
    var curTarget = null; //鼠标拖拽的目标元素 
    var curPos = null; 
    var dropTarget = null; //要放下的目标元素 
    var iMouseDown = false; //鼠标是否按下 
    var lMouseState = false; //前一个iMouseDown状态 
    var dragreplaceCont = []; 
    var mouseOffset = null; 
    var callbackFunc = null; 
    Number.prototype.NaN0 = function() { return isNaN(this) ? 0 : this; } 
    function setdragreplace(obj, callback) { 
        dragreplaceCont.push(obj); 
        obj.setAttribute('candrag', '1'); 
        if (callback != null && typeof (callback) == 'function') { 
            callbackFunc = callback; 
        } 
    } 
    //鼠标移动 
    function mouseMove(ev) { 
        ev = ev || window.event; 
        var target = ev.target || ev.srcElement; 
        var mousePos = mouseCoords(ev); 
        //如果当前元素可拖拽 
        var dragObj = target.getAttribute('candrag'); 
        if (dragObj != null) { 
            if (iMouseDown && !lMouseState) { 
                //刚开始拖拽 
                curTarget = target; 
                curPos = getPosition(target); 
                mouseOffset = getMouseOffset(target, ev); 
                // 清空辅助层 
                for (var i = 0; i < dragHelper.childNodes.length; i++) dragHelper.removeChild(dragHelper.childNodes[i]); 
                //克隆元素到辅助层，并移动到鼠标位置 
                dragHelper.appendChild(curTarget.cloneNode(true)); 
                dragHelper.style.display = 'block'; 
                dragHelper.firstChild.removeAttribute('candrag'); 
                //记录拖拽元素的位置信息 
                curTarget.setAttribute('startWidth', parseInt(curTarget.offsetWidth)); 
                curTarget.setAttribute('startHeight', parseInt(curTarget.offsetHeight)); 
                curTarget.style.display = 'none'; 
                //记录每个可接纳元素的位置信息，这里一次记录以后多次调用，获取更高性能 
                for (var i = 0; i < dragreplaceCont.length; i++) { 
                        with (dragreplaceCont[i]) { 
                        if (dragreplaceCont[i] == curTarget) 
                        continue; 
                        var pos = getPosition(dragreplaceCont[i]); 
                        setAttribute('startWidth', parseInt(offsetWidth)); 
                        setAttribute('startHeight', parseInt(offsetHeight)); 
                        setAttribute('startLeft', pos.x); 
                        setAttribute('startTop', pos.y); 
                    } 
                } //记录end 
            } //刚开始拖拽end 
        } 
        //正在拖拽 
        if (curTarget != null) { 
            // move our helper div to wherever the mouse is (adjusted by mouseOffset) 
            dragHelper.style.top = mousePos.y - mouseOffset.y + "px"; 
            dragHelper.style.left = mousePos.x - mouseOffset.x + "px"; 
            //拖拽元素的中点 
            var xPos = mousePos.x - mouseOffset.x + (parseInt(curTarget.getAttribute('startWidth')) / 2); 
            var yPos = mousePos.y - mouseOffset.y + (parseInt(curTarget.getAttribute('startHeight')) / 2); 
            var havedrop = false; 
            for (var i = 0; i < dragreplaceCont.length; i++) { 
                with (dragreplaceCont[i]) { 
                    if (dragreplaceCont[i] == curTarget) 
                        continue; 
                    if ((parseInt(getAttribute('startLeft')) < xPos) && 
                        (parseInt(getAttribute('startTop')) < yPos) && 
                        ((parseInt(getAttribute('startLeft')) + parseInt(getAttribute('startWidth'))) > xPos) && 
                        ((parseInt(getAttribute('startTop')) + parseInt(getAttribute('startHeight'))) > yPos)) { 
                            havedrop = true; 
                            dropTarget = dragreplaceCont[i]; 
                            dropTarget.className = 'usr catch'; 
                            break; 
                    } 
                } 
            } 
            if (!havedrop && dropTarget != null) { 
                dropTarget.className = 'usr'; 
                dropTarget = null; 
            } 
        } //正在拖拽end 
        lMouseState = iMouseDown; 
        if (curTarget) return false; //阻止其它响应（如：鼠标框选文本） 
    } 
    //鼠标松开 
    function mouseUp(ev) { 
        if (curTarget) { 
            dragHelper.style.display = 'none'; //隐藏辅助层 
            if (curTarget.style.display == 'none' && dropTarget != null) { 
                //有元素接纳，两者互换 
                var destP = dropTarget.parentNode; 
                var sourceP = curTarget.parentNode;
                var curBID = curTarget.parentNode.id;
                var desBID = dropTarget.parentNode.id;
                $.ajax( {
                        type: "post",
                        url : "trans_board.php",
                        data: {"cur_board":curBID,"drop_board":desBID},
                        success: function(data){//如果调用php成功,data为执行php文件后的返回值
                        if(data == 1);
                        else;
                        }
                 });
                destP.appendChild(curTarget); 
                sourceP.appendChild(dropTarget); 
                dropTarget.className = 'usr'; 
                dropTarget = null; 
                if (callbackFunc != null) { 
                    callbackFunc(curTarget); 
                }
            } 
            curTarget.style.display = ''; 
            curTarget.style.visibility = 'visible'; 
            curTarget.setAttribute('candrag', '1'); 
        } 
        curTarget = null; 
        iMouseDown = false; 
    } 
    //鼠标按下 
    function mouseDown(ev) { 
        //记录变量状态 
        iMouseDown = true; 
        //获取事件属性 
        ev = ev || window.event; 
        var target = ev.target || ev.srcElement; 
        if (target.onmousedown || target.getAttribute('candrag')) {//阻止其它响应（如：鼠标双击文本） 
            return false; 
        } 
    } 
    //返回当前item相对页面左上角的坐标 
    function getPosition(e) { 
        var left = 0; 
        var top = 0; 
        while (e.offsetParent) { 
            left += e.offsetLeft + (e.currentStyle ? (parseInt(e.currentStyle.borderLeftWidth)).NaN0() : 0); 
            top += e.offsetTop + (e.currentStyle ? (parseInt(e.currentStyle.borderTopWidth)).NaN0() : 0); 
            e = e.offsetParent; 
        } 
        left += e.offsetLeft + (e.currentStyle ? (parseInt(e.currentStyle.borderLeftWidth)).NaN0() : 0); 
        top += e.offsetTop + (e.currentStyle ? (parseInt(e.currentStyle.borderTopWidth)).NaN0() : 0); 
        return { x: left, y: top }; 
    } 
    //返回鼠标相对页面左上角的坐标 
    function mouseCoords(ev) { 
        if (ev.pageX || ev.pageY) { 
            return { x: ev.pageX, y: ev.pageY }; 
        } 
        return { 
            x: ev.clientX + document.body.scrollLeft - document.body.clientLeft, 
            y: ev.clientY + document.body.scrollTop - document.body.clientTop 
        }; 
    } 
    //鼠标位置相对于item的偏移量 
    function getMouseOffset(target, ev) { 
        ev = ev || window.event; 
        var docPos = getPosition(target); 
        var mousePos = mouseCoords(ev); 
        return { x: mousePos.x - docPos.x, y: mousePos.y - docPos.y }; 
    } 
    window.onload = function() { 
        document.onmousemove = mouseMove; 
        document.onmousedown = mouseDown; 
        document.onmouseup = mouseUp; 
        //辅助层用来显示拖拽 
        dragHelper = document.createElement('DIV'); 
        dragHelper.style.cssText = 'position:absolute;display:none;'; 
        document.body.appendChild(dragHelper); 
        var bNum = document.getElementById('boardNum').value;
        for(var i =1; i<=bNum;i++)
        {
        	setdragreplace(document.getElementById(i)); 
        }
        //setdragreplace(document.getElementById('1')); 
        //setdragreplace(document.getElementById('2')); 
        //setdragreplace(document.getElementById('3')); 
    }; 

</script> 
<script>
    // var editor;
    //     KindEditor.ready(function(K) {
    //             editor = K.create('#tk_stage_desc', {
    //         width : '100%',
    //         height: '50px',
    //         items:[
    //      'fontname', 'fontsize']
    //         });
    //     });
</script>



                   <!-- <script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>-->
                    <script src="js/bootstrap/bootstrap-transition.js"></script>
                    <script src="js/bootstrap/bootstrap-modal.js"></script>


    <script charset="utf-8" src="plug-in/editor/kindeditor.js"></script>
<script charset="utf-8" src="plug-in/editor/lang/zh_CN.js"></script>
<script>
        var editor;
        KindEditor.ready(function(K) {
                editor = K.create('#tk_stage_desc', {
            width : '100%',
            height: '150px',
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
</script>
      
    <input type="hidden" id="boardNum" name="boardNum" value="<?php echo $board_num; ?>" />

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>

            <!-- 左边20%的宽度的树或者说明  -->
            <td width="20%" class="input_task_right_bg" valign="top">
                <table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>
                                            <td valign="top" class="gray2">
                            <h4 style="margin-top:40px; margin-left: 5px;"><strong><?php echo $multilingual_stage_view_nowbs; ?></strong></h4>
                            <p>
                                <?php echo $multilingual_stage_add_text; ?>
                            </p>

                        </td>
                    </tr>
                </table>
            </td>

            <!-- 右边80%宽度的主体内容 -->
            <td  id="tb" position=relative width="80%" valign="top" >
            		 
            <div id="displayRoom"> 

                <?php while($row_board = mysql_fetch_assoc($board_info)){ 
                     $id_seq = $id_seq + 1;?>
                    <div class="row"style="margin-right:0px"> 
                        <span id="parent<?php echo $row_board['board_seq']; ?>">
                            <span class="usr" id="<?php echo $id_seq; ?>">
                                <p style="margin: 0px;margin-bottom: 10px;">
                                    <a href="base_delete.php?delID=<?php echo $row_board['board_id']; ?>&pid=<?php echo $pid; ?>">
                                        <img src="images/ui/base_close.png" style="float: right;margin-left: 150px;position: absolute;" width="8px">
                                    </a>
                                    <a href="">
                                        <img src="images/ui/base_edit.png" style="float: left;margin-top: -2px;position: absolute;" height="10px">
                                    </a>
                                </p><span class="usr_text" ><?php echo $row_board['board_content']; ?></span>
                                <!--<div class="form-group col-xs-12">
                                    <label for="tk_stage_desc"><?php echo $multilingual_default_task_description; ?><span  id="tk_stage_title_msg"></span></label>
                                <div>
                                    <textarea id="tk_stage_desc" name="tk_stage_desc" style="width: 155px;height: 150px;background: #ffc;border: 0px;" ></textarea>-->
                                <!--</div>
                                </div>-->
                            </span>
                        </span>
                    </div>
                <?php } ?>
                <div class="row"style="margin-right:0px"> 
                    <span><span class="usr" id="add"><button class="btn btn-primary"  style="display:inline-block;-moz-box-shadow: 0 1px 2px rgba(0,0,0,0.5);
                    background-color: #ffc;border-color: #ffc;
                    text-shadow: 0 -1px 1px rgba(0,0,0,0.25);width:130px;clear:none;
                    height:129px;line-height:30px;margin-top: 15px;margin-left: 13px;text-align:center;background-image: url(images/ui/add.png);"; type="button"; ></button></span></span> 
                    <div class="modal" id="mymodal">
                        <div 
                        style="margin-top: 80px;"
                    class="modal-dialog">
                            <div class="modal-content"style="align="center";">
                                <div class="modal-header">
                                    <button type="button"style="height:10em;width:10em;" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                                    <h4 class="modal-title">添加新的标签</h4>
                                </div>
                                <div class="modal-body">
                                   <textarea id="tk_stage_desc" name="tk_stage_desc" ></textarea> 
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                    <button type="button" class="btn btn-primary">保存</button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    <script>
                      $(function(){
                        $(".btn").click(function(){
                          $("#mymodal").modal("toggle");
                        });
                      });
                    </script> 
                </div> 
             </div>
            </td>

        </tr>
        
    </table>

<?php require( 'foot.php'); ?>
</body>

</html>