<?php  
//$num = mt_rand(1,2);  
$num = 2;  
if($num == 1) {  
    echo json_encode(array('title' => "【闪动标题1】",'ddd'=>$num));  
}else{  
    echo json_encode(array('title' => "【         】",'ddd'=>$num));  
}  
?>  
<script type="text/javascript">
;(function($) {
$.extend({
/**
*/
blinkTitle : {
show : function() {	//有新消息时在title处闪烁提示
var step=0, _title = document.title;
var timer = setInterval(function() {
step++;
if (step==3) {step=1};
if (step==1) {document.title='<?php echo $multilingual_newmessage1;?>'+_title};
if (step==2) {document.title='<?php echo $multilingual_newmessage2;?>'+_title};
}, 500);
return [timer, _title];
},
/**

*/
clear : function(timerArr) {	//去除闪烁提示，恢复初始title文本
if(timerArr) {
clearInterval(timerArr[0]);
document.title = timerArr[1];
};
}
}
});
})(jQuery);
// 
jQuery(function($) {
var timerArr = $.blinkTitle.show();
setTimeout(function() {		//此处是过一定时间后自动消失
$.blinkTitle.clear(timerArr);
}, 10000);
//
});
</script>