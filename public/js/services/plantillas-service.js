app.service('plantillasService',['$http', 'APP',  function($http, APP) {

    delete $http.defaults.headers.common['X-Requested-With'];

    this.getData = function(metodo, parametros) {

        return $http({
            method: metodo,
            url: APP.api + 'plantillas',
            params: parametros,
            headers: {
                'Authorization': 'Token token=xxxxYYYYZzzz'
            }
        });
    };

    this.getCategoriasPlantillas = function(idsucursal)
    {
        return $http.get(APP.api + 'obtenercategoriasplantillasporsucursal?idsucursal='+idsucursal);
    };
    this.getSucursales = function()
    {
        return $http.get(APP.api + 'sucursales');
    };
    this.getPuntosVentas = function(idsucursal)
    {
        return $http.get(APP.api + 'puntosventasporsucursal?idsucursal='+idsucursal);
    };
    this.getModelos = function()
    {
        return $http.get(APP.api + 'modelos');
    };
    this.delete = function(id) {
        return $http.delete(APP.api + 'plantillas/' + id);
    };

    this.update = function(parametros) {
        return $http.put(APP.api + 'plantillas/' + parametros.id, parametros);
    };

    this.create = function(parametros) {
        return $http.post(APP.api + 'plantillas', parametros);
    };

}]);