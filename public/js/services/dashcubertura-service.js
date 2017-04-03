app.service('dashcoberturaService',['$http', 'APP',  function($http, APP) {

    delete $http.defaults.headers.common['X-Requested-With'];

    this.getData = function(metodo, parametros) {

        return $http({
            method: metodo,
            url: APP.api + 'calcularcobertura',
            params: parametros,
            headers: {
                'Authorization': 'Token token=xxxxYYYYZzzz'
            }
        });
    };

    this.filtrar= function (parametros) {
        console.log(APP.api+ 'calcularcobertura?'+parametros);
        return $http.post(APP.api+ 'calcularcobertura', parametros);
    }

    

    this.getPaises = function()
    {
        return $http.get(APP.api + 'paises');
    }

    this.getSucursales = function(idpais)
    {
        return $http.get(APP.api + 'sucursalesporpais?idpais='+idpais);
    }
    this.getCategorias = function()
    {
        return $http.get(APP.api + 'categorias');
    }
    this.getModelos = function()
    {
        return $http.get(APP.api + 'modelos');
    }
    this.getPuntosVentas = function(idsucursal)
    {
        return $http.get(APP.api + 'puntosventasporsucursal?idsucursal='+idsucursal);
    }

}]);