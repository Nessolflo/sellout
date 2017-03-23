app.service('dashboardService',['$http', 'APP',  function($http, APP) {

    delete $http.defaults.headers.common['X-Requested-With'];

    this.getSellOutPDV = function(orden) {
        return $http.post(APP.api + 'obtenerselloutpuntoventa?orden='+orden);
    };

    this.getVentasPorSemana = function(item) {
        return $http.post(APP.api + 'obtenerventasporsemana',item);
    };

    this.getTop15ModelSellout= function () {
        return $http.get(APP.api +'top15modelsellout');
    }
    this.getTop15PDVSellout= function () {
        return $http.get(APP.api +'top15pdvsellout');
    }
    this.getTop5Model= function(item){
        return $http.post(APP.api +'obtenerTop5Model', item);
    }

}]);