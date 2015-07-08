angular.module('starter.controllers', [])

.filter("filterDay",function(){
        return function(date){
            var day ;
            if( typeof date == "string" ){
                day = date.split("-")[2] + "日" ;
            }else{
                day = new Date(date).getDate() + "日";
            }
            return day ;
        }
})

/*首页*/
.controller('index', ["$scope","$ionicActionSheet","$getDatas","$state","$changeDate","$sendNotes","$rootScope","$ionicPopup","DatepickerService",
function($scope,$ionicActionSheet,$getDatas,$state,$changeDate,$sendNotes,$rootScope,$ionicPopup,DatepickerService){
    $scope.items = [] ;

		$scope.sendNotes = function($event,item){
        var txt = $($event.target).parents(".deliveryList").find(".clearanceBz").text().trim();
        var html =  '<form class="forecastForm issueForm">'+
            '<textarea  class="one-area" id="issue"  name="issue"  placeholder="清关过程中如有问题，请描述" >'+txt+'</textarea>'+
            '</form>';
            var myPopup = $ionicPopup.show({
                template: html,
                title: '填写备注',
                scope: $scope,
                buttons: [
                    {text:"取消"},
                    {
                        text: '<b>确定</b>',
                        type: 'button-positive',
                        onTap: function(e) {
                            var notes = document.getElementById("issue").value ;
                            if( notes == "" ){
                                alertBox("内容不能为空",1000,function(){
                                    document.getElementById("issue").focus();
                                });
                                e.preventDefault();
                                return ;
                            }
                            var id = item.id ;
                            var params = {
                                id : id,
                                notes : notes
                            }
                            $sendNotes.send(params,function(data){
                                if( data.ret == "success" ){
                                    item.notes = notes ;
									alertBox("发送成功");
                                }else if(data.ret == "error" ) {
									alertBox(data.reason);
								} else {
                                    alertBox("发送失败");
                                }
                            })
                        }
                    }
                ]
            });

    }

    var selectDate ;
    $scope.tap = function($event,item,type,index){
        selectDate = {
            event:$event,
            item:item,
            type:type,
            index:index
        }
    }

    //获取列表
    $scope.more = true ;
    $scope.isNotData = false ;
    //上拉加载
    $scope.loadMore = function(){
        //刚进来的时候，没有数据，所以ID会是0，之后每次加载的时候，会去取数据的最后一个的id（已服务器返回的顺序为准）
        var id = $scope.items[ $scope.items.length - 1] ? $scope.items[ $scope.items.length - 1].id : 0 ;

        var indexParam = {} ;
        var params = $rootScope.searchParams ? $rootScope.searchParams : indexParam ;
        params.LastItemID = id ;
        $getDatas.getAll(params,function(datas){
			if(datas.ret == 'error') {
				$state.go("login");
                alertBox( "请先登录" );				
			}
            if( datas.length > 0 ){
                for( var i=0;i<datas.length;i++ ){
                    var item = datas[i] ;

                    ;["DE","AFE","DI","TAX","Customs","Inspection","Warehouse"].forEach(function(list,index,array){
                        item[list].currentDate = new Date(item[list].date || item[list].standard) ;
                        item[list].minDate = index > 0 ? new Date(item[array[index - 1]].currentDate)  : new Date(item.eta);

                        var fnName = list+"_"+item.id ;
                        $scope[fnName] = function (val) {
                            if(typeof(val)!='undefined'){
                                var params = {
                                    time: new Date(val).getTime(),
                                    id: selectDate.item.id,
                                    step: selectDate.type
                                }
                                $changeDate.change(params,function(data){
                                    if( data.ret == "success" ){
                                        ["DE","AFE","DI","TAX","Customs","Inspection","Warehouse"].forEach(function(_list,_index,_array){
                                            data[_list].currentDate = new Date( data[_list].date || data[_list].standard );
                                            data[_list].minDate = _index > 0 ?
                                            new Date( data[_array[_index - 1]].currentDate) : data.eta ;
                                            data[_list].calls =  $scope[fnName] ;
                                        })
                                        $scope.items[selectDate.index] = angular.copy(data);
                                        alertBox("成功"); 
                                    }else if(data.ret == "error") {
                                        alertBox(data.reason);
                                    }else{
                                        alertBox("失败");
                                    }
                                })

                            }
                        };
                        item[list].calls =  $scope[fnName] ;
                    });

                    $scope.items.push( item ) ;

                }
                $scope.$broadcast('scroll.infiniteScrollComplete');
            }else{
                $scope.more = false ;
                $scope.isNotData = true ;
            }

        });
    }
    $scope.moreDataCanBeLoaded = function(){
        return $scope.more ;
    }
}])

.controller("user",["$scope","$state","$rootScope","$timeout",
function($scope,$state,$rootScope,$timeout){
		$scope.hrefIndex = function(){
			$state.go("user.index");
			$timeout(function(){
				window.location.reload();
			},10);
		}
}])

/*登陆*/
.controller("LoginCtrl",["$scope","$LoginService","$state","$rootScope","$editPassword","$ionicPopup",
function($scope,$LoginService,$state,$rootScope,$editPassword,$ionicPopup){
      $scope.initData = {
        remember:true
      } ;
      $scope.login = $LoginService.login ;
      $scope.reset = function(){
        $scope.user = angular.copy( $scope.initData );
      }
      $scope.reset();
      $scope.edit = {};
      $scope.findPassword = function(){
      	
      	var html =  '<form class="forecastForm findPasswordForm">'+
			        '<div class="flist row"><span>用户名:</span><input type="text" ng-model="edit.username"/></div>'+
			        '<div class="flist row"><span>原密码:</span><input type="password" ng-model="edit.password"/></div>'+
			        '<div class="flist row"><span>新密码:</span><input type="password" ng-model="edit.new_password"/></div>'+
			        '<div class="flist row"><span>确认密码:</span><input type="password" ng-model="edit.confrim_password"/></div>'+
			        '</form>';
		
      	var myPopup = $ionicPopup.show({
		    template: html,
		    title: '修改密码',
		    scope: $scope,
		    buttons: [
		      { text: '取消' },
		      {
		        text: '<b>修改</b>',
		        type: 'button-positive',
		        onTap: function(e) {
		          if( !$scope.edit.username ){
		          	alertBox("请输入账号");
		          	e.preventDefault();
		          	return ;
		          }
		          if( !$scope.edit.password ){
		          	alertBox("请输入密码");
		          	e.preventDefault();
		          	return ;
		          }
		          if( !$scope.edit.new_password ){
		          	alertBox("请输入新密码");
		          	e.preventDefault();
		          	return ;
		          }
		          if( !$scope.edit.confrim_password ){
		          	alertBox("请再输入新密码");
		          	e.preventDefault();
		          	return ;
		          }
		          if( $scope.edit.new_password != $scope.edit.confrim_password ){
		          	alertBox("密码必须一致");
		          	e.preventDefault();
		          	return ;
		          }
		          
		          $editPassword.edit($scope.edit,function(data){
		          	if( data.ret == "success" ){
		          		document.getElementById("password").value = "" ; // 清空密码输入框
		          		alertBox("密码修改成功");
					}else if( data.ret == "error" ){
						alertBox(data.reason);
						e.preventDefault(); //阻止弹层关闭
		          	}else{
		          		alertBox("密码修改失败");
		          		e.preventDefault(); //阻止弹层关闭
		          	}
		          });
		          
		        }
		      }
		    ]
		  });
		  myPopup.then(function(res) {
		    $scope.edit = {} ;
		  });
      }
}])

/*报关公司搜索*/
.controller("search_gys",["$scope","$rootScope","$state",
function($scope,$rootScope,$state){

    $scope.search = function($event,box){
        var value = box ? $(box).val() : $($event.target).text();
        var id =  box ? "" :  $($event.target).attr("id") ;
        if( value == "" ){
            alertBox("请输入关键词",1000,function(){
                document.getElementById("searchTxt").focus();
            });
            return ;
        }
        //搜索
        $rootScope.searchParams = {
            Company:value,
            CompanyID:id,
            LastItemID:0
        }
        $state.go("user.index");
    }

}])
/*批次编号搜索*/
.controller("search_wl",["$scope","$rootScope","$state",
function($scope,$rootScope,$state){

    $scope.search = function($event,box){
        var value = $(box).val() ;
        if( value == "" ){
            alertBox("请输入批次号",1000,function(){
                document.getElementById("wlTxt").focus();
            });
            return ;
        }
        //搜索
        $rootScope.searchParams = {
            batch:value,
            LastItemID:0
        }
        $state.go("user.index");
    }

}])

/*搜索类型*/
.controller("search_order",["$scope","$rootScope","$state","$stateParams",
function($scope,$rootScope,$state,$stateParams){
    $scope.items = [
        {text:"整柜",value:"full"},
        {text:"空运",value:"air"},
        {text:"散货",value:"bulk"}
    ]
    $scope.type = $stateParams.type ;
    $scope.search = function($event){
        //搜索
        $rootScope.searchParams = {
            Type:$scope.type,
            LastItemID:0
        }
        $state.go("user.index");
    }
    $scope.change = function(item){
        $scope.type = item.value ;
    }
}])

