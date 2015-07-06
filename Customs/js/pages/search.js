/*
 数据格式：
 [
 	{id:0,value:"aaa"},
 	{id:1,value:"bbb"}
 ]
 * */

//默认会发送value参数，即输入框的值。 type是搜索的类型，如订单编号，物料编号 . search->搜索的是报关，交期，或是物料

var type = $(".searchIpt").attr("istype");
var search = $(".searchIpt").attr("search");
$(".searchIpt").autoInput({
	url:"/ajaxInput",
	data:{type:type,search:search}
})
