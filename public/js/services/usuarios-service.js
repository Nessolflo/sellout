app.service('usuariosService',['$http', 'APP',  function($http, APP) {

    delete $http.defaults.headers.common['X-Requested-With'];

    this.getData = function(metodo, parametros) {

        return $http({
            method: metodo,
            url: APP.api + 'usuarios',
            params: parametros,
            headers: {
                'Authorization': 'Token token=xxxxYYYYZzzz'
            }
        });
    };

    this.getTipos = function()
    {
    	return $http.get(APP.api + 'tiposusuarios');
    }

    this.delete = function(id) {
        return $http.delete(APP.api + 'usuarios/' + id);
    };

    this.update = function(parametros) {
        return $http.put(APP.api + 'usuarios/' + parametros.id, parametros);
    };

    this.create = function(parametros) {
        return $http.post(APP.api + 'usuarios', parametros);
    };

}]);