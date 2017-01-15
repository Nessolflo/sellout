app.service('reportesService',['$http', 'APP',  function($http, APP) {

    delete $http.defaults.headers.common['X-Requested-With'];

    this.getData = function(metodo, parametros) {

        return $http({
            method: metodo,
            url: APP.api + 'inventarios',
            params: parametros,
            headers: {
                'Authorization': 'Token token=xxxxYYYYZzzz'
            }
        });
    };

    this.getSeries = function(idcategoria)
    {
        return $http.get(APP.api + 'seriesporcategoria?idcategoria='+idcategoria);
    }

    this.getModelos = function(idserie)
    {
        return $http.get(APP.api + 'modelosporserie?idserie='+idserie);
    }

    this.getCategorias = function()
    {
        return $http.get(APP.api + 'categorias');
    }

    

    this.getPaises = function()
    {
        return $http.get(APP.api + 'paises');
    }

    this.getSucursales = function(idpais)
    {
        return $http.get(APP.api + 'sucursalesporpais?idpais='+idpais);
    }

    this.getPuntosVentas = function(idsucursal)
    {
        return $http.get(APP.api + 'puntosventasporsucursal?idsucursal='+idsucursal);
    }

    this.getFiltro = function(parametros)
    {
        return $http.post(APP.api + 'filtro',parametros);
    }

}]);