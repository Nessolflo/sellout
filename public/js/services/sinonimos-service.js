app.service('sinonimosService',['$http', 'APP',  function($http, APP) {

    delete $http.defaults.headers.common['X-Requested-With'];

    this.getData = function(metodo, parametros) {

        return $http({
            method: metodo,
            url: APP.api + 'sinonimos',
            params: parametros,
            headers: {
                'Authorization': 'Token token=xxxxYYYYZzzz'
            }
        });
    };

    this.getModelos = function()
    {
    	return $http.get(APP.api + 'modelos');
    }

    this.delete = function(id) {
        return $http.delete(APP.api + 'sinonimos/' + id);
    };

    this.update = function(parametros) {
        return $http.put(APP.api + 'sinonimos/' + parametros.id, parametros);
    };

    this.create = function(parametros) {
        return $http.post(APP.api + 'sinonimos', parametros);
    };

}]);