app.service('empleadosService',['$http', 'APP',  function($http, APP) {

    delete $http.defaults.headers.common['X-Requested-With'];

    this.getData = function(metodo, parametros) {

        return $http({
            method: metodo,
            url: APP.api + 'empleados',
            params: parametros,
            headers: {
                'Authorization': 'Token token=xxxxYYYYZzzz'
            }
        });
    };

    this.getTipos = function()
    {
    	return $http.get(APP.api + 'tipos_empleados');
    }

    this.delete = function(id) {
        return $http.delete(APP.api + 'empleados/' + id);
    };

    this.update = function(parametros) {
        return $http.put(APP.api + 'empleados/' + parametros.id, parametros);
    };

    this.create = function(parametros) {
        return $http.post(APP.api + 'empleados', parametros);
    };

    this.activar = function(id) {
        return $http.get(APP.api + 'empleados/activar/' + id);
    };

}]);