;(function($,win,doc,undefined){
	$.fn.autoInput = function(options){
		var ops ;
		ops = $.extend({
			url:"" ,  //后台验证地址
			data:{}, //参数
			error:null, //错误处理
			success:null , //回调成功处理
			dataType:"json",
			type:"get",
			async:true,
			trigger:"input", //触发条件
			count : 5 , //超过5条  显示滚动条
			itemHeight : 30, //每一行数据的高度
			message:"没有匹配的数据" , //没有数据时的提示
			
		},options || {});
		var _this = this ;
		var width = $(this).width();
		var height = $(this).height();
		var offset = $(this).offset();
		var padd = parseInt( $(this).css("paddingTop") )
		
		function setHtml(content,len){
			var html =  '<div class="autoInputBox">'+
							(content || "<p class='no-data'>"+ops.message+"</p>" ) +
						'</div>';
			$("body").append(html);
			len = len > ops.count ? ops.count : len <= 0 ? 1 : len ;
			$(".autoInputBox").css({
				width:width + padd*2 + 2 +"px",
				height :　ops.itemHeight * len + "px" ,
				top:offset.top + height + padd*2 + "px",
				left: offset.left + "px"
			}).show();
			clickEvent();
		}
		function clickEvent(){
			$(".autoInputBox p").unbind("click");
			$(".autoInputBox p").bind("click",function(){
				if( !$(this).hasClass("no-data") ){
					$(_this).val( $(this).text().trim() );
				}
				$(_this).focus();
				$(".autoInputBox").remove();
			});
		}
		function send(value){
			var params = $.extend(ops.data,value || {});
			$.ajax({
				url:ops.url,
				data:params,
				success:function(data){
					var result = getContent(data,params);
					setHtml(result.content,result.len);					
					ops.success && ops.success();
				},
				error:ops.error,
				type:ops.type,
				dataType : ops.dataType,
				async:ops.async
			});
		}
		
		function getContent(data,params){
			var result = "";
			for( var i=0;i<data.length;i++ ){
				var reg = new RegExp(params.value, "g");
				var value = data[i].value.replace(reg,'<strong>'+params.value+'</strong>');
				result += '<p class="fontnum">'+value+'</p>';
			}
			return {
				content : result,
				len : data.length
			} ;
		}
		
		$(this).bind(ops.trigger,function(){
			var value = $(this).val();
			if( value.length > 0 ){
				send({"value":value});
			}else{
				$(".autoInputBox").remove();
			}
		});
		
		
		return this ;
	}
})(jQuery,window,document);
