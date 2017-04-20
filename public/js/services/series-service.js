app.service('seriesService',['$http', 'APP',  function($http, APP) {

    delete $http.defaults.headers.common['X-Requested-With'];

    this.getData = function(metodo, parametros) {

        return $http({
            method: metodo,
            url: APP.api + 'series',
            params: parametros,
            headers: {
                'Authorization': 'Token token=xxxxYYYYZzzz'
            }
        });
    };

    this.getCategorias = function()
    {
    	return $http.get(APP.api + 'categorias');
    }

    this.delete = function(id) {
        return $http.delete(APP.api + 'series/' + id);
    };

    this.update = function(parametros) {
        return $http.put(APP.api + 'series/' + parametros.id, parametros);
    };

    this.create = function(parametros) {
        return $http.post(APP.api + 'series', parametros);
    };

}]);