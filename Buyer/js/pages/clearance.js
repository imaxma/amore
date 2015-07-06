(function($){

	
$(".deliveryList").bind("longTap",function(){
	var _this = this ;
	var html =  '<p class="mt10"><button class="btn btn-danger w100 deleteList">删除</button></p>'+
				'<p class="mt10"><button class="btn w100 sendInfo">发送提醒</button></p>'+
				'<p class="mt10"><button class="btn btn-success w100 createList">创建新批次</button></p>';
	commonDialog({
		title:"批次-11523",
		content:html,
		height:250,
		buttons:[
			{
				text:"取消",
				cls:"w-close",
				handler:function(){
					this.close();
				}
			}
		],
		ready:function(dialog){
			//删除操作
			$(".w-dialog .deleteList").click(function(){
				$(_this).remove();
				dialog.close();
			});
			//发送提醒
			$(".w-dialog .sendInfo").click(function(){
				dialog.close();
				alertBox("发送成功",1000);
			});
			//创建批次
			$(".w-dialog .createList").click(function(){
				dialog.close();
				setTimeout(function(){
					createList();
				},1000);
			});
		}
	});
});

function createList(){
	var html =  '<form class="forecastForm createListForm">'+
						'<div class="flist row"><span>批次编号:</span><input type="text" name="order_number" /></div>'+
						'<div class="flist row radioDiv"><span>类型:</span>'+
							'<label><input type="radio" name="order_type" checked="checked"/>整柜</label>'+
							'<label><input type="radio" name="order_type" />空运</label>'+
							'<label><input type="radio" name="order_type" />散货</label>'+
						'</div>'+
						'<div class="flist row"><span>报关公司:</span>'+
							'<select name="order_company">'+
								'<option>公司1</option>'+
							'</select>'+
						'</div>'+
						'<div class="flist row"><span>ETD:</span><input type="date" name="order_etd" /></div>'+
						'<div class="flist row"><span>ETA:</span><input type="date" name="order_eta" /></div>'+
					'</form>';
	commonDialog({
			title:"原料数据",
			content:html,
			height:300,
			buttons:[
				    {
				    	text:"确认创建",
				    	cls:"w-success",
				    	handler:function(){
				    		$(".createListForm").submit();
				    	}
				    }
				],
			ready:function(){
				$(".createListForm").validateForm({
					url:"http://www.baidu.com",
					before:function(){
						var result = checkFormData({
							"order_number":{
								rule:{required:true},
								message:{required:"请输入批次编号"}
							},
							"order_etd":{
								rule:{required:true},
								message:{required:"请输入ETD"}
							},
							"order_eta":{
								rule:{required:true},
								message:{required:"请输入ETA"}
							}
						});
						if(result){
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
}

})(Zepto);


