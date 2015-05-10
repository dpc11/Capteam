<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>

<?php require( 'head.php'); ?>
<link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
<link href="board/fancybox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgcheck.js"></script>
<link rel="stylesheet" href="bootstrap/css/bootstrap-multiselect.css" type="text/css" />
<script type="text/javascript" src="bootstrap/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="bootstrap/css/datepicker3.css" type="text/css" />
<script type="text/javascript" src="bootstrap/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="bootstrap/js/locales/bootstrap-datepicker.zh-CN.js"></script>


<script charset="utf-8" src="editor/kindeditor.js"></script>
<script charset="utf-8" src="editor/lang/zh_CN.js"></script>

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
            <td width="80%" valign="top" style="margin:3em;font-family:arial,sans-serif; font-size:100%; ">
            		<ul> 
					<li><a href="# "rel="stylesheet" type="fancybox/css" > 
						<h2>保存不了啊</h3> 
						<p>随便写个小帖子</p> 
					</a></li> 
					<li><a href="#" > 
						<h2>好像能保存了</h3> 
						<p>再来一个小帖子</p> 
					</a></li>  
					<li><a href="#" > 
						<h2>请叫我贴条小公主</h2> 
						<p >这个h不起作用</p> 
					</a></li> 
					<li><a href="#"> 
						<h2>调下h，h2</h2> 
						<p>看看显示效果</p> 
					</a></li> 
					<li><a href="#"> 
						<h2>调下h，h1</h2> 
						<p>见证奇迹的时刻到了，好Q的字体，哇哈哈</p> 
					</a></li> 
					<li><a href="#"> 
						<h2>再试下h3</h2> 
						<p>比较比较，字体还是QQ哒</p> 
					</a></li> 
					</ul> 
                </td>
        </tr>
    </table>
</form>
<?php require( 'foot.php'); ?>
</body>

</html>