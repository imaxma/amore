/*
setDateIpt(".dateIpt",function(value){
	$(this).parent().children("span").text(value);
})
*/
//否决
$(".deliveryTools .dev_no").click(function(){
	var html =  '<form class="bindKcBox">'+
		'<textarea  class="one-area" id="reason"  name="reason" placeholder="输入否决理由"></textarea>'+
		'</form>';
	commonDialog({
		title:"否决理由",
		content:html,
		height:180,
		buttons:[
			{
				text:"提交",
				cls:"w-success",
				handler:function(){
					$(".bindKcBox").submit();
				}
			}
		],
		ready:function(){
			$(".bindKcBox").validateForm({
				url:"http://www.baidu.com",
				before:function(){
					var result =  checkFormData({
						"reason":{
							rule:{required:true},
							message:{required:"请输入否决理由"}
						},
					});
					if( result ){
						loading("show","正在提交");
					}
					return result ;
				},
				success:function(data){
					loading("hide");
					//成功的回调
				}
			});
		}
	});
});

//确认
$(".deliveryTools .dev_yes").click(function(){
	loading("show","请稍等...");

	var url = "http://www.baidu.com"
	var params = {
		order_id : 1
	}
	$.get(url,params,function(data){
		loading("hide");
		if( data == "success" ){
			alertBox("保存成功",1000);
		}else{
			alertBox("保存失败",1000);
		}
	},"json");
})



