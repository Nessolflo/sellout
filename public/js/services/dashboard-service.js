app.service('dashboardService',['$http', 'APP',  function($http, APP) {
 
    delete $http.defaults.headers.common['X-Requested-With'];
 
    this.getSellOutPDV = function(orden) {
        return $http.post(APP.api + 'obtenerselloutpuntoventa?orden='+orden);
    };
 
 
    this.getVentasPorSemana = function() {
        return $http.post(APP.api + 'obtenerventasporsemana');
    };
 
    this.getTop15ModelSellout= function () {
        return $http.get(APP.api +'top15modelsellout');
    }
    this.getTop15PDVSellout= function () {
        return $http.get(APP.api +'top15pdvsellout');
    }
 
 
    this.getConsultaSemana = function(semanaI,semanaF,anio,sucursal) {
        return $http.post(APP.api + 'obtenersemanaventa?semanaI='+semanaI+'&semanaF='+semanaF+'&anio='+anio+'&sucursal='+sucursal);
    };
/*
    this.getConsultaVentaSemana = function(semana) {
        return $http.post(APP.api + 'obtenerconsultaventasemana?semana='+semana);
    };
*/
 
    this.getSucursales = function()
    {
        return $http.get(APP.api + 'obtenersucursal');
    }
}]);