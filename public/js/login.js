/**
 * AngularJS Tutorial 1
 * @author Nick Kaye <nick.c.kaye@gmail.com>
 */

/**
 * Main AngularJS Web Application
 */

/**
 * Configure the Routes
 */
var app = angular.module('myAppClient', [
  'LocalStorageModule',
  'app.constants'
  ])

app.controller('LoginController', function ($scope, $window, $http, APP, localStorageService) {

    $scope.user = {};
    $scope.tag = 1;

    if (localStorageService.get('login')) {
        $window.location.href = 'index.html';
    }
    localStorageService.cookie.clearAll();

    $scope.login = function()
    {
      $http({
        method: 'POST',
        url: APP.api + 'login',
        params: $scope.user
      })
      .then(function(dataResponse){
        if(dataResponse.data.result)
        {
          if (localStorageService.cookie.isSupported) {
              localStorageService.cookie.set('login', dataResponse.data.records, 10);
              $window.location.href = 'index.html';
          }
        }
        else
        {
          alert(dataResponse.data.message);
        }
      });
    }

});

  