/*
    http://mywebsql.net/license
*/
var taskbar={_win:{},init:function(){$("#taskbar .min-all").button().click(function(){taskbar.minimizeAll()})},openDialog:function(a,b,c,d){var e=null,a="dialog-"+a;if(obj=this.findDialog(a))return 0==this._win[a].state&&($("#"+a).dialogExtend("restore"),this._win[a].state=1),!1;this._win[a]={status:0,url:b,state:1};e=this.createDialog(a,b,c,d,!1);e.find(".dialog_contents").attr("src","javascript:false");this.updateDialog(0,e.id);e.dialog("open");e.find("ui-dialog-title").html(__("Loading")+"...");
e.find(".dialog_contents").attr("src",b);$("#taskbar").append('<input type="button" value="'+__("Loading")+'..." id="tb-button-'+a+'" />');$("#tb-button-"+a).button().click(function(){taskbar.handle(a)});main_layout.open("south")},openModal:function(a,b,c,d){dlg=this.createDialog("dialog-"+a,b,c,d,!0);dlg.find(".dialog_contents").attr("src","javascript:false");this.updateDialog(0,dlg.id);dlg.dialog("open");dlg.find("ui-dialog-title").html(__("Loading")+"...");dlg.find(".dialog_contents").attr("src",
b)},handle:function(a){0==this._win[a].state&&($("#"+a).dialogExtend("restore"),this._win[a].state=1);$("#"+a).dialog("moveToTop")},findDialog:function(a){for(dlg in this._win)if(dlg==a)return this._win[dlg];return!1},createDialog:function(a,b,c,d,e){b=$("#dialog-template").clone();b.attr("id",a);b.find(".dialog_contents").attr("id",a+"-contents");b.dialog({modal:e,autoOpen:!1,width:c,height:d,minWidth:460,minHeight:260,open:function(){c=$("#"+a).parent(".ui-dialog").width();d=$("#"+a).parent(".ui-dialog").height();
$("#"+a+"-contents").width(c).height(d)},close:function(){$("#taskbar").find("#tb-button-"+a).remove();0==$("#taskbar").find("input").length&&main_layout.close("south");$("#"+a).dialog("destory");$("#"+a+"-contents").remove();$("#"+a).remove();delete taskbar._win[a]}});e||b.dialogExtend({maximize:!1,minimize:!0,events:{minimize:function(a,b){taskbar.minimize(b.id)}}});b.bind("dialogresizestart dialogdragstart",function(){iframe=$("#"+a+"-contents");var b=$("<div></div>");$("#"+a).append(b[0]);b[0].id=
a+"-div";b.css({position:"absolute"});b.css({top:0,left:0});b.height(iframe.height());b.width("100%")});b.bind("dialogresizestop dialogdragstop",function(){$("#"+a+"-div").remove();c=$("#"+a).parent(".ui-dialog").width();d=$("#"+a).parent(".ui-dialog").height();$("#"+a+"-contents").width(c).height(d)});$("#"+a+"-contents").bind("load",function(){taskbar.updateDialog(1,a)});return b},updateDialog:function(a,b){if(b)if(1==a){$("#"+b+" .dialog_msg").css("display","none");$("#"+b+"-contents").css("display",
"block");$("#"+b).parent(".ui-dialog").trigger("resize");try{title=document.getElementById(b+"-contents").contentWindow.title,this._win[b]&&(this._win[b].status=1,$("#tb-button-"+b).button({label:title})),$("#"+b).siblings(".ui-dialog-titlebar").find(".ui-dialog-title").html(title),win=document.getElementById(b+"-contents").contentWindow,$(win.document).find("#popup_overlay").addClass("ui-helper-hidden")}catch(c){}}else $("#"+b+" .dialog_msg").css("display","block"),$("#"+b+"-contents").css("display",
"none")},minimize:function(a){this._win[a].state=0},minimizeAll:function(){for(dlg in this._win)1==this._win[dlg].state&&($("#"+dlg).dialogExtend("minimize"),this._win[dlg].state=0)}};