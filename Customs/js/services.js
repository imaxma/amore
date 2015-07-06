angular.module('starter.services', [])

/*  登录接口 */
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
          loading("show","正在登录...")
            user.username = document.getElementById("username").value ;
            user.password = hex_md5(document.getElementById("password").value );
          var url = "../customs/checkin.php" ;
          $http.post(url, user)
              .success(function(data){
                loading("hide");
				if( data.ret == "success" ){
				  $rootScope.user = user.username ;
					$state.go("user.index");
					$rootScope.isLogin = true ;
				   // window.location.reload();
				} else if( data.ret == "error" ){
				  $ionicPopup.alert({
					title : '系统提示',
					template: data.reason, //"账号或密码错误"
				  });
				} else {
					alertBox('服务器错误');
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
*/
.factory("$checkLogin",function($http){
      var url = "../customs/checkauth.php";
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
* */

.factory("$getDatas",function($http){
    var datas  ;
    return {
        getAll:function(params,callback){
            var url = "../customs/querylist.php";
            var http = $http.post(url,params) ;
            http.success(function(data){
                callback && callback(data);
                datas = data ;
            });
            http.error(function(){
                alertBox("服务器错误");
            });
        }
    }
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
            var url = "../customs/postdate.php";
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

.factory("$sendNotes",function($http){
    return {
        send:function(param,callback){
            loading("show","发送中...");
            var url = "../customs/postnotes.php";
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
/*修改密码接口
 * 前台发送的参数格式如下：
 * */
.factory("$editPassword",function($http){
    return {
        edit:function(param,callback){
            loading("show","正在修改...");
            var url = "../customs/changepassword.php";
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