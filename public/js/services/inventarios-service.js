app.service('inventariosService',['$http', 'APP',  function($http, APP) {

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

    this.delete = function(id) {
        return $http.delete(APP.api + 'inventarios/' + id);
    };

    this.update = function(parametros) {
        return $http.put(APP.api + 'inventarios/' + parametros.id, parametros);
    };

    this.create = function(parametros) {
        return $http.post(APP.api + 'inventarios', parametros);
    };

    this.upload = function(parametros) {
        return $http.post(APP.api + 'upload', parametros, {
            withCredentials: true,
            headers: {'Content-Type': undefined },
            transformRequest: angular.identity
        });
    }

}]);