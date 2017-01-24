app.service('ventaspendientesService',['$http', 'APP',  function($http, APP) {

    delete $http.defaults.headers.common['X-Requested-With'];

    this.getData = function(metodo, parametros) {

        return $http({
            method: metodo,
            url: APP.api + 'ventaspendientes',
            params: parametros,
            headers: {
                'Authorization': 'Token token=xxxxYYYYZzzz'
            }
        });
    };

    this.delete = function(id) {
        return $http.delete(APP.api + 'ventaspendientes/' + id);
    };

    this.update = function(parametros) {
        return $http.post(APP.api + 'actualizarregistro', parametros);
    };

    this.create = function(parametros) {
        return $http.post(APP.api + 'ventaspendientes', parametros);
    };

}]);