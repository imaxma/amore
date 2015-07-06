

function getQueryString(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	var r = window.location.search.substr(1).match(reg);
	if (r != null) return unescape(r[2]); return null;
}


//loading提示框
function loading(type,val){
    //loadIng('show','演示..') 显示 ; loadIng('hide') 隐藏
    var search = 	'<div id="loading_box" class="LoadingBigBox hide" ontouchmove="mobileStop(event)"> '+
        '<div class=" loadingBox ">'+
        '<div class="tc"><b class="icon-spinner icon-spin"></b></div>'+
        '<span></span>'+
        '</div></div>	';
    $("body").append(search);
    if(type == 'show'){
        $("#loading_box .loadingBox > span").text(val);
        $("#loading_box").removeClass('hide');
    }
    if(type == 'hide'){
        $("#loading_box").remove();
    }
}

function alertBox(text,time,callback){
	var search = 	'<div  class="LoadingBigBox hide  alertBox" ontouchmove="mobileStop(event)"> '+
		    '<div class="loadingBox tran_2" >'+
		    '<span></span>'+
		    '</div></div>	';
	$("body").append(search);
	$(".alertBox").removeClass('hide');
	$(".alertBox .loadingBox > span").text(text);
	setTimeout(function(){
		$(".alertBox .loadingBox").addClass("scaleAniamte");
	},10);
	setTimeout(function(){
		$(".alertBox").remove();
		callback && callback();
	},time || 1000);
}



//弹出层
var commonDialog = function(options){
	var ops = $.extend({
		title:"编辑",
		content:"",
		success:null,
		close:null,
		ready:null,
		width:300,
		height:180,
		chaHeight:80,//头部和底部的和
		buttons:[
		    {
		    	text:"取消",
		    	cls:"w-close",
		    },
		    {
		    	text:"确定",
		    	cls:"w-success"
		    }
		]
	},options ||{});
	
	var html = '<div class="w-dialog">'+
					'<div class="w-dialog-content animated">'+
					'<div class="w-dialog-header">'+
						'<h1>'+ops.title+'</h1>'+
						'<b class="icon-remove w-close"></b>'+
					'</div>'+
					'<div class="w-dialog-body">'+ops.content+'</div>'+
					'<div class="w-dialog-footer"></div>'+
				'</div>'+
				'</div>';
	$("body").append(html);
	var dialog = {
		close:function(){
			$(".w-dialog .w-dialog-content").addClass("flipOutY");
			setTimeout(function(){
				$(".w-dialog").remove();
			},1000);
		}
	}
	//设置宽高
	$(".w-dialog-content").css({
		height:ops.height+"px",
		width:ops.width+"px",
		marginTop:-ops.height/2+"px",
		marginLeft:-ops.width/2+"px"
	}).find(".w-dialog-body").css({
		height:ops.height - ops.chaHeight +"px"
	});
	//绑定事件,初始化按钮
	for( var i=0;i<ops.buttons.length;i++ ){
		var button = ops.buttons[i];
		var a = '<a href="javascript:;" class="'+button.cls+'">'+button.text+'</a>';
		$(".w-dialog-content .w-dialog-footer").append(a);
		button.handler && $(".w-dialog-content .w-dialog-footer").find("."+button.cls).bind("click",function(){
			button.handler.call(dialog);
		})
	}
	if( ops.buttons.length == 1 ){
		$(".w-dialog-content .w-dialog-footer a").css({
			width:"100%",
			borderRight:"0"
		});
	}
	$(".w-dialog-content .w-close").click(function(){
		ops.close && ops.close.call(dialog);
		dialog.close();
	});
	$(".w-dialog-content .w-dialog-footer .w-success").click(function(){
		ops.success && ops.success.call(dialog);
	});
	
	$(".w-dialog").show().find(".w-dialog-content").addClass("flipInY");
	ops.ready && ops.ready(dialog);
}
//手机端日期插件
function showDate(obj,options){
	var currYear = (new Date()).getFullYear();	
	var ops = $.extend({
		theme: 'android-ics light', //皮肤样式
	    display: 'modal', //显示方式 
	    mode: 'scroller', //日期选择模式
		dateFormat: 'yyyy-mm-dd',
		lang: 'zh',
		showNow: true,
		nowText: "今天",
		preset : 'date',
		onSelect:null,
		ready:null,
	},options||{});
	$(obj).mobiscroll(ops);
	ops.ready && ops.ready(obj);
}

Date.prototype.format = function (fmt) { //author: meizz
	var o = {
		"M+": this.getMonth() + 1, //月份
		"d+": this.getDate(), //日
		"h+": this.getHours(), //小时
		"m+": this.getMinutes(), //分
		"s+": this.getSeconds(), //秒
		"q+": Math.floor((this.getMonth() + 3) / 3), //季度
		"S": this.getMilliseconds() //毫秒
	};
	if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
	for (var k in o)
		if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
	return fmt;
}
