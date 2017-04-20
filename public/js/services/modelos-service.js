app.service('modelosService',['$http', 'APP',  function($http, APP) {

    delete $http.defaults.headers.common['X-Requested-With'];

    this.getData = function(metodo, parametros) {

        return $http({
            method: metodo,
            url: APP.api + 'modelos',
            params: parametros,
            headers: {
                'Authorization': 'Token token=xxxxYYYYZzzz'
            }
        });
    };

    this.getSeries = function()
    {
    	return $http.get(APP.api + 'series');
    }

    this.delete = function(id) {
        return $http.delete(APP.api + 'modelos/' + id);
    };

    this.update = function(parametros) {
        return $http.put(APP.api + 'modelos/' + parametros.id, parametros);
    };

    this.create = function(parametros) {
        return $http.post(APP.api + 'modelos', parametros);
    };

}]);