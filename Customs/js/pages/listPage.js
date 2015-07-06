//各个参数编辑操作
function setListEdit(callback){
	$(".deliveryList p").bind("click",function(){
		if( $(this).attr("utype") ){
			var type = $(this).attr("utype");
			var value = $(this).children("span").text().trim();
			if( type ){
				callback && callback.call(this,type,value);
			}
		}
	});
}

