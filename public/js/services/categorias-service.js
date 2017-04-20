app.service('categoriasService',['$http', 'APP',  function($http, APP) {

    delete $http.defaults.headers.common['X-Requested-With'];

    this.getData = function(metodo, parametros) {

        return $http({
            method: metodo,
            url: APP.api + 'categorias',
            params: parametros,
            headers: {
                'Authorization': 'Token token=xxxxYYYYZzzz'
            }
        });
    };

    this.delete = function(id) {
        return $http.delete(APP.api + 'categorias/' + id);
    };

    this.update = function(parametros) {
        return $http.put(APP.api + 'categorias/' + parametros.id, parametros);
    };

    this.create = function(parametros) {
        return $http.post(APP.api + 'categorias', parametros);
    };

}]);