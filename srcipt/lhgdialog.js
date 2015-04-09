/*
 *@lhgdialog - Dialog Window v2.0.2 - Date : 2009-8-19
 *@Copyright lhgcore.js (c) 2009 By LiHuiGang Reserved
 */

J.exend({

dialog : (function()
{
    var twin = window.parent, cover;
	while( twin.parent && twin.parent != twin )
	{
	    try{ if( twin.parent.document.domain != document.domain ) break; }
		catch(e){break;}
		twin = twin.parent;
	}
	var tdoc = twin.document;
	
	var restyle = function(el)
	{
	    el.style.cssText = 'margin:0;padding:0;background-image:none;background-color:transparent;border:0;';
	};
	
	var getzi = function()
	{
	    if(!J.dialog.cfg.bzi) J.dialog.cfg.bzi = 1999; return ++J.dialog.cfg.bzi;
	};
	
	var resizehdl = function()
	{
		if(!cover) return; var rel = J.idtd(tdoc) ? tdoc.documentElement : tdoc.body;
		J(cover).stcs({
		    width: Math.max( rel.scrollWidth, rel.clientWidth, tdoc.scrollWidth || 0 ) - 1 + 'px',
			height: Math.max( rel.scrollHeight, rel.clientHeight, tdoc.scrollHeight || 0 ) - 1 + 'px'
		});
	};
	
	return {
	    cfg : { bzi: null, opac: 0.50, bgcolor: '#fff' }, indoc : {}, infrm : {}, inwin : {},
		get : function(d)
		{
		    if( !d || 'object' != typeof d || !d.id || J('#lhg_'+d.id,tdoc) ) return;
			if(d.cover) this.dcover(); else{ if(cover) cover = null; }
			d.width = d.width || 600; d.height = d.height || 500; d.title = d.title || 'Dialog';
			
			var dinfo = { tit: d.title, page: d.page, link: d.link, html: d.html, win: window, top: twin }
			var cize = J.vsiz(twin), pos = J.spos(twin);
			
			var itop = d.top ? pos.y + d.top : Math.max( pos.y + ( cize.h - d.height - 20 ) / 2, 0 );
			var ileft = d.left ? pos.x + d.left : Math.max( pos.x + ( cize.w - d.width - 20 ) / 2, 0 );
			
			var dfrm = J(tdoc).crte('iframe'); restyle(dfrm);
			J(dfrm).attr({ id: 'lhg_' + d.id, frameBorder: 0 }).stcs({
			    top: itop + 'px', left: ileft + 'px', position: 'absolute',
				width: d.width + 'px', height: d.height + 'px', zIndex: getzi()
			});
			dfrm._dlgargs = dinfo; J(tdoc.body).apch(dfrm); var doc = dfrm.contentWindow.document;
			
			doc.open();
			doc.writeln([
			    '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
				'<html xmlns="http://www.w3.org/1999/xhtml">',
				'<head>',
				    '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>',
					'<link type="text/css" href="' + J.path() + '../skin/themes/base/lhgdialog.css" rel="stylesheet"/>',
					'<script type="text/javascript">',
'var W=frameElement._dlgargs.win,dlgcover=W.J.dialog.gcover(),l=window.document;function A(){return frameElement._dlgargs};window.focus();if(W.J.ie){try{document.execCommand("BackgroundImageCache",false,true)}catch(e){}};var recontze=function(){if(W.J.ie&&!W.J.i7){W.J("contain",l).stcs({width:document.body.offsetWidth-2+"px",height:document.body.offsetHeight-2+"px"})}var h=W.J("#contain",l).offsetHeight;h-=W.J("#dtit",l).offsetHeight;h-=W.J("#dfoot",l).offsetHeight;W.J("dinner",l).stcs({height:Math.max(h-9,0)+"px"})};var crtel=function(t,l,w,h){var o=W.J(A().top.document).crte("div");W.J(o).stcs({position:"absolute",top:t+"px",left:l+"px",border:"1px solid #000",width:w+"px",height:h+"px",zIndex:W.J.dialog.cfg.bzi+1,backgroundColor:"#999"}).stopac(0.30);W.J(A().top.document.body).apch(o);return o};var drag=function(){var e=[],lacoor,curpos,tdark;var f=function(){for(var i=0;i<e.length;i++){W.J(e[i].document).revt("mousemove",g);W.J(e[i].document).revt("mouseup",h)}};var g=function(a){if(!lacoor)return;if(!a)a=W.J.edoc(this).parentWindow.event;var b={x:a.screenX,y:a.screenY};curpos={x:curpos.x+(b.x-lacoor.x),y:curpos.y+(b.y-lacoor.y)};lacoor=b;W.J(tdark).stcs({left:curpos.x+"px",top:curpos.y+"px"})};var h=function(a){if(!lacoor)return;if(!a)a=W.J.edoc(this).parentWindow.event;f();W.J.rech(tdark);lacoor=null;tdark=null;W.J(frameElement).stcs({left:curpos.x+"px",top:curpos.y+"px"})};return{downhdl:function(a){var b=null;if(!a){b=W.J.edoc(this).parentWindow;a=b.event}else b=a.view;var c=a.srcElement||a.target;if(c.id=="xbtn")return;var d=frameElement.offsetWidth,fh=frameElement.offsetHeight;curpos={x:frameElement.offsetLeft,y:frameElement.offsetTop};lacoor={x:a.screenX,y:a.screenY};tdark=crtel(curpos.y,curpos.x,d,fh);for(var i=0;i<e.length;i++){W.J(e[i].document).aevt("mousemove",g);W.J(e[i].document).aevt("mouseup",h)}if(a.preventDefault)a.preventDefault();else a.returnValue=false},reghdl:function(w){e.push(w)}}}();var resize=function(){var d=[],lacoor,curpos,tdark,frsize;var e=function(a){if(!lacoor)return;if(!a)a=W.J.edoc(this).parentWindow.event;var b={x:a.screenX,y:a.screenY};frsize={w:b.x-lacoor.x,h:b.y-lacoor.y};if(frsize.w<200||frsize.h<100){frsize.w=200;frsize.h=100};W.J(tdark).stcs({width:frsize.w+"px",height:frsize.h+"px",top:curpos.y+"px",left:curpos.x+"px"})};var f=function(a){if(!lacoor)return;if(!a)a=W.J.edoc(this).parentWindow.event;for(var i=0;i<d.length;i++){W.J(d[i].document).revt("mousemove",e);W.J(d[i].document).revt("mouseup",f)}W.J(frameElement).stcs({width:frsize.w+"px",height:frsize.h+"px"});recontze();W.J.rech(tdark);lacoor=null;tdark=null;if(W.J.ie&&!W.J.i7&&W.J("#frmain",l))W.J("#frmain",l).height=W.J("#dinner",l).style.height};return{downhdl:function(a){var b=null;if(!a){b=W.J.edoc(this).parentWindow;a=b.event}else b=a.view;var c=frameElement.offsetWidth,fh=frameElement.offsetHeight;curpos={x:frameElement.offsetLeft,y:frameElement.offsetTop};frsize={w:c,h:fh};lacoor={x:a.screenX-c,y:a.screenY-fh};tdark=crtel(curpos.y,curpos.x,c,fh);for(var i=0;i<d.length;i++){W.J(d[i].document).aevt("mousemove",e);W.J(d[i].document).aevt("mouseup",f)}if(a.preventDefault)a.preventDefault();else a.returnValue=false},reghdl:function(w){d.push(w)}}}();(function(){var d=function(a){a.onkeydown=function(e){e=e||event||this.parentWindow.event;switch(e.keyCode){case 27:cancel();return false;case 9:W.J.canc(e);return false}return true}};window.onload=function(){W.J("throbber",l).stcs("visibility","");loadinnfrm();W.J(document).cmenu(W.J.canc);if(W.J.ie)W.J(window.document).msdown(g);else W.J(window).msdown(g);W.J("dtit",l).msdown(drag.downhdl);drag.reghdl(window);drag.reghdl(A().top);drag.reghdl(W);W.J("dark",l).msdown(resize.downhdl);resize.reghdl(window);resize.reghdl(A().top);resize.reghdl(W);if(A().link||A().html)W.J("throbber",l).stcs("visibility","hidden");h();recontze();d(document);var a=frameElement.id.substr(4),o=W.J.dialog;o.indoc[a]=document;o.infrm[a]=W.J("#frmain",l);o.inwin[a]=window};window.loadinnfrm=function(){if(A().html)W.J("dinner",l).html(A().html);else{var a=A().link?A().link:A().page,_css=A().link?"":"style=\'visibility:hidden;\' ";W.J("dinner",l).html("<iframe id=\'frmain\' src=\'"+a+"\' name=\'frmain\' frameborder=\'0\' "+"width=\'100%\' height=\'100%\' scrolling=\'auto\' "+_css+"allowtransparency=\'true\'><\/iframe>")}};window.loadinndlg=function(){if(!frameElement.parentNode)return null;var a=W.J("#frmain",l),innwin=a.contentWindow,inndoc=innwin.document;W.J("throbber",l).stcs("visibility","hidden");a.style.visibility="";if(W.J.ie)W.J(inndoc).msdown(g);else W.J(innwin).msdown(g);drag.reghdl(innwin);resize.reghdl(innwin);innwin.focus();d(inndoc);return W};window.cancel=function(){return closedlg()};window.closedlg=function(){if(W.J("#frmain",l))W.J("#frmain",l).src=W.J.gtvod();W.J("throbber",l).stcs("visibility","hidden");W.J.dialog.close(window,dlgcover)};window.reload=function(a,c,b){a=a?a:W;W.J.dialog.close(window,dlgcover);if(!c)a.location.reload();else{if(!b)a.location.href=c;else a.src=c}};var g=function(a){if(!a)a=event||this.parentWindow.event;W.J(frameElement).stcs("zIndex",parseInt(W.J.dialog.cfg.bzi,10)+1);W.J.dialog.cfg.bzi=frameElement.style.zIndex;a.stopPropagation?a.stopPropagation():(a.cancelBubble=true)};var h=function(){if(W.J.ie){var a=new Image();a.src="images/btn_bg.gif"};W.J("xbtn",l).msover(function(){W.J(this).stcs("backgroundPosition","0 0")}).msout(function(){W.J(this).stcs("backgroundPosition","-22px 0")}).click(cancel);W.J("txt",l).html(A().tit);crebtn("cbtn","Cancel",cancel)};window.crebtn=function(i,t,f){if(W.J("#"+i,l)){W.J(i,l).html("<span>"+t+"</span>");W.J(i,l).click(f)}else{var a=W.J(l).crte("li"),span=W.J(l).crte("span");W.J(span).html(t);W.J(a).attr("id",i).apch(span);W.J(a).msover(function(){W.J(this).stcs("backgroundPosition","0 -42px")}).msout(function(){W.J(this).stcs("backgroundPosition","0 -21px")}).click(f);W.J("btns",l).apch(a);a=span=null}};window.rembtn=function(a){if(W.J("#"+a,l))W.J.rech(W.J("#"+a,l))}})();',
					'</script>',
				'</head>',
				'<body>',
				    '<div id="contain" class="contain">',
					    '<div id="dtit" class="dlgtit"><span id="txt"></span><div id="xbtn"></div></div>',
						'<div id="dinner" class="dlginner"></div>',
						'<div id="dfoot" class="dlgfoot" style="display:none;"><ul id="btns"><li id="dark"></li></ul></div>',
					'</div>',
					'<div id="throbber" style="position:absolute;visibility:hidden;">Loading, please wait...</div>',
				'</body>',
				'</html>'
			].join(''));
			doc.close();
		},
		
		close : function(d,c)
		{
		    var dlg = ( 'object' == typeof(d) ) ? d.frameElement : J('#lhg_'+d);
			if(dlg) J.rech(dlg); if(c) this.hcover(c);
		},
		
		dcover : function()
		{
		    cover = J(tdoc).crte('div'); restyle(cover);
			J(cover).stcs({
				position: 'absolute', zIndex: getzi(), top: '0px',
				left: '0px', backgroundColor: this.cfg.bgcolor
			}).stopac(this.cfg.opac);
			
			if( J.ie && !J.i7 )
			{
			    var ifrm = J(tdoc).crte('iframe'); restyle(ifrm);
				J(ifrm).attr({
				    hideFocus: true, frameBorder: 0, src: J.gtvod()
				}).stcs({
				    width: '100%', height: '100%', position: 'absolute', left: '0px',
					top: '0px', filter: 'progid:DXImageTransform.Microsoft.Alpha(opacity=0)'
				});
				J(cover).apch(ifrm);
			}
			
			J(twin).aevt( 'resize', resizehdl ); resizehdl(); J(tdoc.body).apch(cover);
		},
		
		gcover : function(){ return cover; },
		hcover : function(o){ J.rech(o); cover = null; o = null; }
	};
})()

});