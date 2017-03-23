app.service('puntosVentasService',['$http', 'APP',  function($http, APP) {

    delete $http.defaults.headers.common['X-Requested-With'];

    this.getData = function(metodo, parametros) {

        return $http({
            method: metodo,
            url: APP.api + 'puntosventas',
            params: parametros,
            headers: {
                'Authorization': 'Token token=xxxxYYYYZzzz'
            }
        });
    };

    this.getSucursales = function()
    {
    	return $http.get(APP.api + 'sucursales');
    }


    this.delete = function(id) {
        return $http.delete(APP.api + 'puntosventas/' + id);
    };

    this.update = function(parametros) {
        return $http.put(APP.api + 'puntosventas/' + parametros.id, parametros);
    };

    this.create = function(parametros) {
        return $http.post(APP.api + 'puntosventas', parametros);
    };

}]);