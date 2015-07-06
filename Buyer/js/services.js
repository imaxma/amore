angular.module('starter.services', [])
/*  登录接口
 前台发送参数：
 @param username(string)    用户名
 @param password(string) 密码
 @remember(boolearn)  记住密码？true or false . default : true

 服务器返回：success or error   type : string
 * */
.factory('$LoginService', function($ionicPopup, $state,$ionicLoading,$rootScope,$http) {
      return {
        login : function(user){
          if( document.getElementById("username").value == "" ){
              alertBox("请输入用户名",1000,function(){
                  document.getElementById("username").focus();
              });
              return ;
          }
            if( document.getElementById("password").value == "" ){
                alertBox("请输入密码",1000,function(){
                    document.getElementById("password").focus();
                });
                return ;
            }
          loading("show","正在登陆...")
            user.username = document.getElementById("username").value ;
            //user.password = (document.getElementById("password").value) ;
			user.password = hex_md5(document.getElementById("password").value) ;
          var url = "../buyer/checkin.php" ;
          $http.post(url, user)
              .success(function(data){
                loading("hide");
				if( data.ret == "success" ){
				  $rootScope.user = user.username ;
					$state.go("user.index");
					$rootScope.isLogin = true ;
				   // window.location.reload();
				}
				if( data.ret == "error" ){
				  $ionicPopup.alert({
					title : '系统提示',
					template:"账号或密码错误"
				  });
				}
              })
              .error(function(data, status, headers, config){
                  loading("hide");
              })
        }
      }
})
/*判断用户是否已经登录
    前台不发送参数
 * 服务器返回： true or false ( boolearn or string )
 * */
.factory("$checkLogin",function($http){
      var url = "../buyer/checkauth.php";
      var http = $http.get(url) ;
      return {
        success : http.success,
        error : http.error
      }
})
    /*获取首页列表的数据  上拉加载用的就是这个方法
*@param LastItemID 表示已经加载的items中的最后一个item ID。  如果为0，就从第一个返回
*@param Type 类型 （ 可选 用于搜索）
* @param companyid  报关公司ID。（可选，用于搜索。）
*
* 服务器返回：
*
 * */

.factory("$getDatas",function($http){
    var datas  ;
    return {
        getAll:function(params,callback){
            var url = "../buyer/querylist.php";
            var http = $http.post(url,params) ;
            http.success(function(data){
                callback && callback(data);
                datas = data ;
            });
            http.error(function(){
                alertBox("服务器错误");
            });
        },

        /*删除一个批次
        前台发送参数：
        @param batch 批次Id
        服务器返回：
        success or error   type : string
        *
        * */
        remove:function(item,params,callback){
            var url = "../buyer/deleteitem.php" ;
            var http = $http.post(url,params);
            loading("show",'正在删除...');
            http.success(function(data){
				loading("hide");
				if(data.ret == 'success') {
					datas.splice(datas.indexOf(item),1);
					callback && callback(datas,data);
					alertBox('删除成功！');
				}else if (data.ret == 'error'){
					alertBox(data.reason);
				} else {
					alertBox('删除失败！');
				}
            });
            http.error(function(){
                loading("hide");
				alertBox("服务器错误");
            })
        },
        /*发送通知
        前台参数：CompanyID ：报关公司Id
        *服务器返回   success or error   type string
        * */
        sendMessage:function(params,callback){
            var url = "../buyer/sendmessage.php" ;
            var http = $http.post(url,params);
            loading("show",'正在发送...');
            http.success(function(data){
				callback && callback(data);
                loading("hide");
            });
            http.error(function(){
                loading("hide");
				alertBox("服务器错误");
            })
        },
        /*创建一个批次 */
        create:function(params,callback){
            var url = "../buyer/insertnew.php";
            var http = $http.post(url,params);
            loading("show",'正在创建...');
            http.success(function(data){
				if(data.ret == 'success') {
					callback && callback(data);
				} else if (data.ret == 'error') {
					alertBox(data.reason);
				} else {
					alertBox('创建失败');
				}
                loading("hide");
            });
            http.error(function(){
                loading("hide");
				alertBox("服务器错误");
            })
        }
    }
})


/*
  获取所有的报关公司
*/
.factory("$get_gys",function($http){
    var url = "../buyer/customscompany.php";
    var http = $http.get(url) ;
    return {
        getAll:function(callback){
            http.success(callback);
            http.error(function(){
               alertBox("服务器错误");
            });
        }
    };
})
/*

公用搜索方法

报关公司搜索
*前台发送参数:
* @param Company ->报关公司名称 或 关键词
* @param CompanyID -> 报关公司id。如果有值：说明直接点击的是列举出来的，如果没有值：说明是自己手动输入的
* @param LastItemID  -> 0
* */
.factory("$search_gys",function($http){
    return {
        search:function(param,callback){

            loading("show","加载中...");
            var url = "../buyer/querylist.php";
            var http = $http.post(url,param) ;
            http.success(function(data){
                callback && callback(data);
                loading("hide");
            });
            http.error(function(){
                alertBox("服务器错误");
                loading("hide");
            });
        }
    };
})


/*更改日期组件接口
 *@param type 类型。是 换单还是 报检等
 * @param time  时间戳
 *服务器返回 sueess or error
 * */
.factory("$changeDate",function($http){
    return {
        change:function(param,callback){
            loading("show","加载中...");
            var url = "../buyer/postdate.php";
            var http = $http.post(url,param) ;
            http.success(function(data){
				if(data.ret == 'success') {
					callback && callback(data);
				} else if (data.ret == 'error') {
					alertBox(data.reason);
				} else {
					alertBox('失败');
				}
                loading("hide");
            });
            http.error(function(){
                alertBox("服务器错误");
                loading("hide");
            });
        }
    };
})