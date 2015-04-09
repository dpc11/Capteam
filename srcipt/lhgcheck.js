/*
 *@Generator -> Form Validator v2.0.0 - Date : 2009-8-5
 *@Copyright lhgcore.js (c) 2009 By LiHuiGang Reserved
 */
 
J.exend({
    check :
	{
	    msgid : [], ermsg : [], rules : [], sucid : [], sucmg : [], dtype :
		{
			email : /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
			english : /^[A-Za-z]+$/,
			chinese : /^[\u0391-\uFFE5]+$/,
			url : /^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/,
			ip : /^(0|[1-9]\d?|[0-1]\d{2}|2[0-4]\d|25[0-5]).(0|[1-9]\d?|[0-1]\d{2}|2[0-4]\d|25[0-5]).(0|[1-9]\d?|[0-1]\d{2}|2[0-4]\d|25[0-5]).(0|[1-9]\d?|[0-1]\d{2}|2[0-4]\d|25[0-5])$/,
			zip : /^[1-9]\d{5}$/,
			qq : /^[1-9]\d{4,10}$/,
			alpha : /^[0-9a-zA-Z\_]+$/,
			phone : /^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/,
			
			limit : 'this.chklimit(v.length,r.min,r.max)',
			ajax : 'this.chkajax(v,r)',
			match : 'this.chkmatch(v,r.to)',
			date : 'this.chkdate(v)',
			rang : 'this.chkrang(v,r.min,r.max)',
			filter : 'this.chkfilter(v,r.accept)',
			group : 'this.chkgroup(e,r)'
		}
	}
});

J.exend( J.check, {
	chklimit : function( val, min, max )
	{
	    min = min ? min : Number.MIN_VALUE;
		max = max ? max : Number.MAX_VALUE;
		return ( val >= min && val <= max );
	},
	
	chkrang : function( val, min, max )
	{
	    return this.chklimit( val, min, max );
	},
	
	chkajax : function( val, r )
	{
	    J(r.mid).html('Ajax验证中...').acls('chkaj');
		var ret = J.A.send( r.url + val ); return (ret == 0) ? false : true;
	},
	
	chkmatch : function( val, el )
	{
	    var to = J(el).val(); return ( val == to );
	},
	
	chkdate : function(val)
	{
	    var r = val.replace(/(^\s*)|(\s*$)/g, '').match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/);
		if(!r) return false; var d = new Date( r[1], r[3]-1, r[4] );
		return ( d.getFullYear()==r[1]&&(d.getMonth()+1)==r[3]&&d.getDate()==r[4] );
	},
	
	chkfilter : function( val, r )
	{
	    return new RegExp( '^.+\.(?=EXT)(EXT)$'.replace(/EXT/g, r.split(/\s*,\s*/).join('|')), 'gi' ).test(val);
	},
	
	chkgroup : function(e,r)
	{
	    var min = r.min || 1, max = r.max || e.length, o, cn = 0;
		for( var i = 0; o = e[i]; i++ )
		{
		    if( o.checked != undefined ){ if(o.checked) cn++; }
			else
			{
			    var nosel = r.noselected || '';
				if( o.selected && nosel == o.value ) return false; if(o.selected) cn++;
			}
		}
		return ( cn >= min && cn <= max );
	},
		
	regform : function(f)
	{
	    var frm = document.forms[f]; if( this.rules.length == 0 ) return false;
		
		for( var i = 0; i < this.rules.length; i++ )
		{
		    var r = this.rules[i];
		    if( !J(r.name) )
			{
			    alert( '名称为' + r.name + '的字段不存在' ); return; 
			}
		    if( !J(r.mid) )
			{
			    alert( '显示错误信息的id为' + r.mid + '的元素不存在' ); return; 
			}
			if(r.requir) J(frm[r.name]).blur(this.blurchk);
		}
		
		J(frm).submit(function()
		{
		    var c = J.check; c.msgid = []; c.ermsg = []; c.sucid = []; c.sucmg = [];
			for( var i = 0; i < c.rules.length; i++ )
			{
			    var r = c.rules[i], el = frm[r.name];
				if(el.disabled) continue; var msgs = c.formchk(el,r);
				if( msgs.length > 0 )
				    for( var n in msgs ) c.adderrs( r.mid, msgs[n] );
				else
				    c.addsucc( r.mid, r.okmsg );
			}
			if( c.ermsg.length > 0 )
			{
			    c.showerrs( c.msgid, c.ermsg );
				c.showsucc( c.sucid, c.sucmg ); return false;
			}
			else return true;
		});
    },
	
	blurchk : function()
	{
	    for( var i = 0; i < J.check.rules.length; i++ )
		{
		    var r = J.check.rules[i];
			if( r.name == this.name || r.name == this.id ) break;
		}
		var c = J.check, msgs = c.formchk( this, r ), okmg = r.okmsg || '&nbsp;'; 
		c.ermsg = msgs; c.msgid = [r.mid]; c.sucmg = [okmg];
		
		if( msgs.length > 0 )
		    c.showerrs( c.msgid, c.ermsg );
		else
		    c.showsucc( c.msgid, c.sucmg );
	},
	
	formchk : function(e,r)
	{
	    var einfo = []; if(!r.warn) r.warn = J(r.mid).html();
		
		if( r.requir && e.length == undefined && J(e).isnl() )
		    einfo.push('&nbsp');
		else if( !r.requir && ( e.length != undefined || J(e).isnl() ) )
		    return [];
		else
		{
			if(!r.type) return []; var v = J.trim(e.value), bool;
			var types = r.type.split('|'), warns = r.warn.split('|');
			for( var i = 0; i < types.length; i++ )
			{
			    switch(types[i])
				{
				    case 'email': case 'english': case 'chinese': case 'url': case 'ip': case 'zip': case 'qq': case 'alpha': case 'phone':
					    bool = this.chkregex( v, this.dtype[types[i]] ); break;
					case 'cusre': bool = this.chkregex( v, new RegExp(r.regexp,'g') ); break;
					case 'cusfn': bool = eval( r.cusfunc ); break;
					default : bool = eval( this.dtype[types[i]] ); break;
				}
				if(!bool){ einfo.push(warns[i]); break; }
			}
		}
		return einfo;
	},
	
	showerrs : function(id,msg)
	{
	    for( var i = 0; i < msg.length; i++ ) J(id[i]).html(msg[i]).acls('chker');
	},
	
	showsucc : function(id,msg)
	{
		for( var i = 0; i < id.length; i++ ) J(id[i]).html(msg[i]).acls('chkok');
	},
	
    chkregex : function(val,reg){ return reg.test(val); },
	adderrs : function(e,m){ this.msgid.push(e); this.ermsg.push(m); },
	addsucc : function(e,m){ m = m || '&nbsp;'; this.sucid.push(e); this.sucmg.push(m); }
});	