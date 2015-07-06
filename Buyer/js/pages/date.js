
showDate(".dateIpt",{
	onSelect:function(date){
		var values = date.split("-");
		$(this).prev().text(values[ values.length - 1 ] +"日");
		alert( "选中的日期："+date  )
	},
	ready:function(elem){
		$(elem).each(function(){
			var values = $(this).val().split("-");
			//月份-1，因为默认+了1
			values[1] = values[1] - 1 ;
			$(this).mobiscroll('setValue', values , true);
			$(this).prev().text( values[ values.length - 1 ] +"日");
		});
	}
});
