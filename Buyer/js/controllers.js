angular.module('starter.controllers', [])

.filter("filterDay",function(){
     return function(date){
         var day = date.split("-")[2] + "日" ;
         return day ;
     }
})

/*首页*/
.controller('index', ["$scope","$ionicActionSheet","$getDatas","$get_gys","$state","$changeDate",
        "DatepickerService","$ionicPopup","$rootScope","$search_gys",
function($scope,$ionicActionSheet,$getDatas,$get_gys,$state,$changeDate,DatepickerService,$ionicPopup,$rootScope,$search_gys){
    $scope.items = [] ;

    $scope.bottomMenu = function($event,item){
        bottomMenu($event,item,$ionicActionSheet,$state,$scope,$getDatas,$get_gys,$ionicPopup);
    }
    $scope.currentDate = new Date() ;
    //DatepickerService.maxDate = new Date();

	
	$scope.create = function(){
		createList($get_gys,$scope,$ionicPopup,$getDatas)
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
                    $scope.items.push( datas[i] ) ;
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
.controller("LoginCtrl",["$scope","$LoginService","$state","$rootScope",
function($scope,$LoginService,$state,$rootScope){
      $scope.initData = {
        username:"",
        password:"",
        remember:true
      } ;
      $scope.login = $LoginService.login ;
      $scope.reset = function(){
        $scope.user = angular.copy( $scope.initData );
      }
      $scope.reset();
}])

/*报关公司搜索*/
.controller("search_gys",["$scope","$get_gys","$rootScope","$state",
function($scope,$get_gys,$rootScope,$state){

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
        }
        $state.go("user.index");
    }
    $get_gys.getAll(function(data){
    	$scope.all_gys = [] ;
        for( var i=0;i<data.length;i++ ){
        	data[i].status && $scope.all_gys.push(data[i]);
        }
    });

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
        }
        $state.go("user.index");
    }
    $scope.change = function(item){
        $scope.type = item.value ;
    }
}])


function bottomMenu($event,item,$ionicActionSheet,$state,$scope,$getDatas,$get_gys,$ionicPopup){
    var hideSheet = $ionicActionSheet.show({
        buttons: [
            { text: '删除' },
            { text: '发送提醒' },
            { text:"创建新批次"}
        ],
        //titleText: '菜单',
        cancelText: '取消',
        cancel: function() {
        },
        buttonClicked: function(index) {
            if( index == 0 ){
            	$ionicPopup.confirm({
			       title: '系统提示',
			       template: '删除批次 '+item.batch+'?',
			       buttons:[
			       		{text:"取消"},
			       		{
			       			text: '<b>确定</b>',
	        				type: 'button-positive',
	        				onTap:function(){
	        					$getDatas.remove(item,{id:item.id},function(datas,data){
				                    if( data.ret == "success" ){
				                        $scope.items = datas ;
				                    }
				                });
	        				}
			       		}
			       ]
			    });
            }else if( index == 1 ){
            	$ionicPopup.confirm({
			       title: '系统提示',
			       template: '向'+(item.customs || "公司")+'发送提醒?',
			       buttons:[
			       		{text:"取消"},
			       		{
			       			text: '<b>确定</b>',
	        				type: 'button-positive',
	        				onTap:function(){
	        					$getDatas.sendMessage({id:item.id},function(data){
									if(data.ret == 'success') {
										alertBox('发送成功');
									} else if(data.ret == 'error') {
										alertBox(data.reason);
									} else {
										alertBox('服务器错误');
									}
				                });
	        				}
			       		}
			       ]
			    });
                
            }else if( index == 2 ){
                createList($get_gys,$scope,$ionicPopup,$getDatas)
            }
            return true;
        }
    });
}

function getCreateHtml(datas,closeData){
    var opstions ;
    for( var i=0;i<datas.length;i++ ){
    	var select = "";
    	if( closeData && closeData.order_company == datas[i].id ){
    		select = 'selected="selected"' ;
    	}
        opstions += '<option value="'+datas[i].id+'" '+select+' >'+datas[i].name+'</option>';
    }
    var order_type_full = "" , order_type_air = "" , order_type_bulk = "" ;
    if( closeData && closeData.order_type ){
    	switch(closeData.order_type){
    		case "full":
	    		order_type_full = "checked='checked'" ;
	    		break;
	    	case "air":
	    		order_type_air = "checked='checked'" ;
	    		break;
	    	case "bulk":
	    		order_type_bulk = "checked='checked'" ;
	    		break;
	    	default :
	    		order_type_full = "checked='checked'" ;
    	}
    }else{
    	order_type_full = "checked='checked'" ;
    }
    
    
    var html =  '<form class="forecastForm createListForm">'+
        '<ionic-datepicker idate="currentDate" callback="datePickerCallback">'+
         '</ionic-datepicker>'+
        '<div class="flist row"><span>批次编号:</span><input type="text" id="order_number" name="order_number" value="'+(closeData ? closeData.order_number : "")+'" /></div>'+
        '<div class="flist row radioDiv"><span>类型:</span>'+
        '<label style="margin-left:4px;"><input type="radio" '+order_type_full+' name="order_type" class="order_type"  value="full"  />整柜</label>'+
        '<label><input type="radio"  value="air" '+order_type_air+' name="order_type" class="order_type" />空运</label>'+
        '<label><input type="radio"  value="bulk" '+order_type_bulk+' name="order_type"  class="order_type"/>散货</label>'+
        '</div>'+
        '<div class="flist row"><span>报关公司:</span>'+
        '<select name="order_company" id="order_company" autocomplete="off">'+
        opstions+
        '</select>'+
        '</div>'+
        '<div class="flist row"><span>ETD:</span>' +
            '<div style="width: 160px">' +
        '<ionic-datepicker idate="form.etd" callback="date_etd">' +
        '<p class="dateBox" >${data.etd | date:"yyyy-MM-dd"}</p> '+
        '</ionic-datepicker>'+
        '</div>'+
        '</div>'+
        '<div class="flist row"><span>ETA:</span>' +
        '<div style="width: 160px">' +
        '<ionic-datepicker idate="data.eta" callback="date_eta" disablepreviousdates="data.mindate" >' +
        '<p class="dateBox">${data.eta | date:"yyyy-MM-dd"}</p> '+
        '</ionic-datepicker>'+
        '</div>'
        '</div>'+
        '</form>';
    return html ;
}


function createList($get_gys,$scope,$ionicPopup,$getDatas){
	$get_gys.getAll(function(data){
                	
    $scope.data = {} ;
    $scope.data.etd =  new Date();
    $scope.data.eta =  new Date();
    $scope.data.mindate = $scope.data.etd.getTime() - 86400000;
	var closeBefore = null ;
        
    var pastData = [] ;
    for( var i=0;i<data.length;i++ ){
    	data[i].status && pastData.push(data[i]);
    }
    var myPopup ;
	function initDate(){
		
        
		myPopup = $ionicPopup.show({
            template: getCreateHtml(pastData,closeBefore),
            title: '创建批次',
            scope: $scope,
            buttons: [
                {text:"取消"},
                {
                    text: '<b>创建</b>',
                    type: 'button-positive',
                    onTap: function(e) {
                        var form = $(".createListForm");
                        var order_number = form.find("[name='order_number']").val();
                        var order_type = form.find(".order_type:checked").val();
                        var order_company = form.find("[name='order_company']").val();

                        if (order_number == "" ) {
                            alertBox("请输入批次编号",1000,function(){
                                document.getElementById("order_number").focus();
                            });
                            e.preventDefault();
                            return ;
                        }
                        $scope.data.type = order_type;
                        $scope.data.batch = order_number ;
                        $scope.data.customsid = order_company;
                        $scope.data.etd = new Date($scope.data.etd).format("yyyy-MM-dd");
                        $scope.data.eta = new Date($scope.data.eta).format("yyyy-MM-dd");
                        $getDatas.create($scope.data,function(data){
                            if( data ){
                                alertBox("创建成功！");
                                window.location.reload();
                            }
                        })
                    }
                }
            ]
        });
        
        if( closeBefore ){
        	closeBefore = null ;
        }
        
        
	}
    
    initDate(); 
    
    $scope.date_etd = function(val){
    	if( val ){
    		$scope.data.etd = val ;
    		$scope.data.mindate = new Date(val).getTime() ;
    		myPopup.close();
    		myPopup = null ;
    		var form = $(".createListForm");
            var order_number = form.find("[name='order_number']").val();
            var order_type = form.find(".order_type:checked").val();
            var order_company = form.find("[name='order_company']").val();
            closeBefore = {
            	order_number : order_number,
            	order_type : order_type,
            	order_company : order_company
            }
            if( $scope.data.mindate > new Date($scope.data.eta).getTime() ){
            	$scope.data.eta = val ;
            }
            
    		initDate(); 
    	}
    } ;
    $scope.date_eta = function(val){
        val && ($scope.data.eta = val )
    } ;
    
});
}
