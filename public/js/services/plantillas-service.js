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

//////////wil
this.getFilter= function (parametros) {
        console.log('getfilterfunc');
        console.log(APP.api+ 'filtrarplantilla?idsucursal='+parametros['idsucursal']+'&idmodelo='+parametros['idmodelo']['id']);
        return $http.get(APP.api+ 'filtrarplantilla?idsucursal='+parametros['idsucursal']+'&idmodelo='+parametros['idmodelo']['id']);
    };

////

    this.getCategoriasPlantillas = function(idsucursal)
    {
        console.log('categorias plantillas service js');
        return $http.get(APP.api + 'obtenercategoriasplantillasporsucursal?idsucursal='+idsucursal);
    };
    this.getSucursales = function()
    {
        console.log('sucursales service js');
        return $http.get(APP.api + 'sucursales');
    };
    this.getPuntosVentas = function(idsucursal)
    {
        console.log('puntos ventas service js');
        return $http.get(APP.api + 'puntosventasporsucursal?idsucursal='+idsucursal);
    };
    this.getModelos = function()
    {
        console.log('modelos service js');
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