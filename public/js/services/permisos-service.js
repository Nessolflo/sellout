app.service('permisosService',['$http', 'APP',  function($http, APP) {

    delete $http.defaults.headers.common['X-Requested-With'];

    this.getData = function(metodo, parametros) {

        return $http({
            method: metodo,
            url: APP.api + 'permisos',
            params: parametros,
            headers: {
                'Authorization': 'Token token=xxxxYYYYZzzz'
            }
        });
    };

    this.getUsuarios = function()
    {
    	return $http.get(APP.api + 'usuarios');
    }

    this.getPuntosVentas = function()
    {
        return $http.get(APP.api + 'puntosventas');
    }

    this.delete = function(id) {
        return $http.delete(APP.api + 'permisos/' + id);
    };

    this.update = function(parametros) {
        return $http.put(APP.api + 'permisos/' + parametros.id, parametros);
    };

    this.create = function(parametros) {
        return $http.post(APP.api + 'permisos', parametros);
    };

}]);