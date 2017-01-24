app.service('dashboardService',['$http', 'APP',  function($http, APP) {

    delete $http.defaults.headers.common['X-Requested-With'];

    this.getSellOutPDV = function(orden) {
        return $http.post(APP.api + 'obtenerselloutpuntoventa?orden='+orden);
    };
    

}]);