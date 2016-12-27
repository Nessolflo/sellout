app.service('sucursalesService',['$http', 'APP',  function($http, APP) {

    delete $http.defaults.headers.common['X-Requested-With'];

    this.getData = function(metodo, parametros) {

        return $http({
            method: metodo,
            url: APP.api + 'sucursales',
            params: parametros,
            headers: {
                'Authorization': 'Token token=xxxxYYYYZzzz'
            }
        });
    };

    this.getPaises = function()
    {
    	return $http.get(APP.api + 'paises');
    }

    this.delete = function(id) {
        return $http.delete(APP.api + 'sucursales/' + id);
    };

    this.update = function(parametros) {
        return $http.put(APP.api + 'sucursales/' + parametros.id, parametros);
    };

    this.create = function(parametros) {
        return $http.post(APP.api + 'sucursales', parametros);
    };

}]);