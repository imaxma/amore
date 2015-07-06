function setTop(){
	var winheight = $(window).height();
	var formheig = $(".signinForm").height();
	var footerheig = $(".signinFooterInfo").height();
	$(".signinForm").css({
		marginTop:(winheight-formheig-footerheig)/2 - 30
	});
	$(".signinBox").css({
		height:winheight-45+"px"
	})
}
setTop();

$(".signinForm").validateForm({
	before:function(){
		var result =  checkFormData({
			"username":{
				rule:{required:true},
				message:{required:"请输入账号"}
			},
			"password":{
				rule:{required:true},
				message:{required:"请输入密码"}
			}
		});
		if( result ){
			loading("show","正在登录");
		}
		return result ;
	},
	url:"http://www.baidu.com",
	success:function(data){
		loading("hide");
		//成功的回调
	}
});

//首次登录
$(".firstLogin").bind("click",function(){
	var html =  '<p class="mt10">1. 首次登陆时，请以供货合同上的公司全称作为用户名，以供应商编号作为密码登陆。</p>'+
				'<p class="mt10">2.首次登陆成功后，请先填写手机号和邮箱地址，并修改密码。</p>'+
				'<p class="mt10">3. 后续登陆，可以直接使用手机号作为用户名，以及设定的新密码进行登陆。</p>';
	commonDialog({
		title:"首次登陆 - 提示",
		content:html,
		height:240,
		buttons:[
		    {
		    	text:"确定",
		    	cls:"w-close"
		    }
		]
	});
});
//忘记密码
$(".forgetPass").bind("click",function(){
	var html =  '<form class="forgetPassBox">'+
					'<input type="tel" id="phone" name="phone" placeholder="输入认证过的手机号"/>'+
					'<button type="submit" class="btn btn-45">发送密码到手机</button>'+
					'<p class="mt15">提示：如果手机号尚未认证，请联系管理员重置密码。</p>'+
				'</form>';
	commonDialog({
		title:"找回密码",
		content:html,
		height:240,
		buttons:[
		    {
		    	text:"确定",
		    	cls:"w-close",
		    	handler:function(){
		    		this.close();
		    	}
		    }
		],
		ready:function(){
			$(".w-dialog-body .forgetPassBox").validateForm({
				url:"http://www.baidu.com",
				before:function(){
					var result = checkFormData({
						"phone":{
							rule:{required:true,phone:true},
							message:{required:"请输入手机号"}
						}
					});
					if(result){
						loading("show","正在发送");
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


