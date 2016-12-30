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

    this.getSeries = function(parametros)
    {
        return $http.get(APP.api + 'series',parametros);
    }

    this.getCategorias = function()
    {
        return $http.get(APP.api + 'categorias');
    }

    this.getModelos = function()
    {
        return $http.get(APP.api + 'modelos');
    }

    this.getPaises = function()
    {
        return $http.get(APP.api + 'paises');
    }

    this.getSucursales = function()
    {
        return $http.get(APP.api + 'sucursales');
    }

    this.getPuntosVentas = function()
    {
        return $http.get(APP.api + 'puntosventas');
    }

}]);