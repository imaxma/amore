angular.module('starter', ['ionic', 'starter.controllers', 'starter.services',"ionic-datepicker"])
.run(function($ionicPlatform) {
  $ionicPlatform.ready(function() {
    if (window.cordova && window.cordova.plugins && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
    }
    if (window.StatusBar) {
      StatusBar.styleLightContent();
    }
  });
})
.config(function($stateProvider, $urlRouterProvider,$ionicConfigProvider,$interpolateProvider) {
      $interpolateProvider.startSymbol('${');
      $interpolateProvider.endSymbol('}');
      $ionicConfigProvider.platform.ios.tabs.style('standard');
      $ionicConfigProvider.platform.ios.tabs.position('bottom');
      $ionicConfigProvider.platform.android.tabs.style('standard');
      $ionicConfigProvider.platform.android.tabs.position('bottom');

      $ionicConfigProvider.platform.ios.navBar.alignTitle('center');
      $ionicConfigProvider.platform.android.navBar.alignTitle('center');

      $ionicConfigProvider.platform.ios.backButton.previousTitleText('').icon('ion-ios-arrow-thin-left');
      $ionicConfigProvider.platform.android.backButton.previousTitleText('').icon('ion-android-arrow-back');

      $ionicConfigProvider.platform.ios.views.transition('ios');
      $ionicConfigProvider.platform.android.views.transition('android');
      $ionicConfigProvider.platform.android.views.maxCache(3);

  $stateProvider
    .state('user', {
    url: "/user",
    abstract: true,
    templateUrl: "templates/tabs.html",
    controller:"user"
  })
  .state('user.index', {
    url: '/index',
    views: {
      'tab-index': {
        templateUrl: 'templates/clearance.php',
        controller: 'index'
      }
    }
  })
  .state('user.search_gys', {
      url: '/search_gys',
      views: {
          'tab-index': {
              templateUrl: 'templates/s-search-gys.html',
              controller: 'search_gys'
          }
      }
  })
  .state('user.search_order', {
      url: '/search_order/:type',
      views: {
          'tab-index': {
              templateUrl: 'templates/s-search-order.html',
              controller: 'search_order'
          }
      }
  })
  .state('user.search_wl', {
      url: '/search_wl',
      views: {
          'tab-index': {
              templateUrl: 'templates/s-search-wl.html',
              controller: 'search_wl'
          }
      }
  })

  .state('login', {
    url: '/login',
    templateUrl: 'templates/login.php',
    controller: 'LoginCtrl'
  })
  $urlRouterProvider.otherwise('/login');

});
