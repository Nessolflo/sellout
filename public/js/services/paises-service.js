app.service('paisesService',['$http', 'APP',  function($http, APP) {

    delete $http.defaults.headers.common['X-Requested-With'];

    this.getData = function(metodo, parametros) {

        return $http({
            method: metodo,
            url: APP.api + 'paises',
            params: parametros,
            headers: {
                'Authorization': 'Token token=xxxxYYYYZzzz'
            }
        });
    };

    this.delete = function(id) {
        return $http.delete(APP.api + 'paises/' + id);
    };

    this.update = function(parametros) {
        return $http.put(APP.api + 'paises/' + parametros.id, parametros);
    };

    this.create = function(parametros) {
        return $http.post(APP.api + 'paises', parametros);
    };

}]);