$(function(){
	var $hov_t = $('.toptable tr th')
	$hov_t.addClass('trhover_1')	   
	$hov_t.hover(function(){
		$(this).addClass('thhover')							
	},function(){
		$(this).removeClass('thhover')
		})		   
})


$(function(){
	$('.maintable tr:even').addClass('even')
	var $j = $('.maintable tr');
	$j.hover(function(){
		$(this).addClass('trhover')								  
	},function(){
		$(this).removeClass('trhover')
		});
})



