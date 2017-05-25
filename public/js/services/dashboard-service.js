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
    };
    this.getsemanamaximaporsucursal = function($id){
        return $http.get(APP.api + 'top15modelsellout/'+$id);
    };
    this.getTop15PDVSellout= function () {
        return $http.get(APP.api +'top15pdvsellout');
    };
 
 
    this.getConsultaSemana = function(semanai,semanaf,anio,idgrupo,idsucursal,idpuntoventa,idmodelo) {
         console.log('Modificaciones DOI dashboar-services.js');
        return $http.post(APP.api + 'obtenersemanaventa?semanai='+semanai+'&semanaf='+semanaf+'&anio='+anio+'&idgrupo='+idgrupo
            +'&idsucursal='+idsucursal+'&idpuntoventa='+idpuntoventa+'&idmodelo='+idmodelo);
    };
/*
    this.getConsultaVentaSemana = function(semana) {
        return $http.post(APP.api + 'obtenerconsultaventasemana?semana='+semana);
    };
*/
/*cambios wilson*/


     this.getGrupos = function()
    {
        return $http.get(APP.api + 'cuentas');
    }
     this.getSucursales = function(idgrupo)
    {

        return $http.get(APP.api + 'sucursalesporpais2?idgrupo='+idgrupo);
    }

    this.getPuntosVentas = function(idsucursal)
    {
        return $http.get(APP.api + 'puntosventasporsucursal?idsucursal='+idsucursal);
    }
     this.getModelos = function()
    {
        return $http.get(APP.api + 'modelos');
    }

/*000*/

/*pendiente de averiguar xq estaba aqui esta funcion
 
    this.getSucursales = function()
    {
        return $http.get(APP.api + 'obtenersucursal');
    }
*/
    this.getSucursalesPorUsuario = function(id)
    {
        return $http.get(APP.api + 'sucursales_por_usuario?id='+id);
    }

}]);