/**
 * AngularJS Tutorial 1
 * @author Nick Kaye <nick.c.kaye@gmail.com>
 */

/**
 * Main AngularJS Web Application
 */

/**
 * Configure the Routes
 */
 var app = angular.module('myAppClient', [
    'ngRoute',
    'LocalStorageModule',
    'ngTable',
    'app.constants',
    'chart.js'
    ]).config(['$routeProvider', function ($routeProvider) {
        $routeProvider
        .when("/", {templateUrl: "views/dashboard.html", controller: "DashboardController"})
        .when("/usuarios", {templateUrl: "views/usuarios.html", controller: "UsuariosController"})
        .when("/categorias", {templateUrl: "views/categorias.html", controller: "CategoriasController"})
        .when("/paises", {templateUrl: "views/paises.html", controller: "PaisesController"})
        .when("/modelos", {templateUrl: "views/modelos.html", controller: "ModelosController"})
        .when("/permisos", {templateUrl: "views/permisos.html", controller: "PermisosController"})
        .when("/puntosventas", {templateUrl: "views/puntosventas.html", controller: "PuntosVentasController"})
        .when("/series", {templateUrl: "views/series.html", controller: "SeriesController"})
        .when("/sinonimos", {templateUrl: "views/sinonimos.html", controller: "SinonimosController"})
        .when("/sucursales", {templateUrl: "views/sucursales.html", controller: "SucursalesController"})
        .when("/grupos", {templateUrl: "views/grupos.html", controller: "CuentasController"})
        .when("/categoriasplantillas", {templateUrl: "views/categoriasplantillas.html", controller: "CategoriasPlantillasController"})
        .when("/plantillas", {templateUrl: "views/plantillas.html", controller: "PlantillasController"})
        .when("/inventarios", {templateUrl: "views/inventarios.html", controller: "inventariosController"})
        .when("/cobertura", {templateUrl: "views/coberturaespecial.html", controller: "coberturaEspecialController"})
        .when("/ventaspendientes", {
            templateUrl: "views/ventaspendientes.html",
            controller: "ventasPendientesController"
        })
        .when("/reportes", {templateUrl: "views/reportes.html", controller: "reportesController"})
        .when("/dashsellout", {templateUrl: "views/dashsellout.html", controller: "dashselloutController"})
        .when("/404", {templateUrl: "views/404.html"})

        // else 404
        .otherwise({
            redirectTo: '/404'
        })

    }]);



    function iniciar() {
        $("#progressBar").css({
            "opacity": 1,
            "width": "10%"
        });
    }

    function loader() {
        $(document).scrollTop(0);

        var loaded = 0;
        var imgCounter = $(".main-content img").length;
        if (imgCounter > 0) {
            function doProgress() {
                $(".main-content img").load(function () {
                    loaded++;
                    var newWidthPercentage = (loaded / imgCounter) * 100;
                    animateLoader(newWidthPercentage + '%');
                })
            }

            function animateLoader(newWidth) {
                $("#progressBar").width(newWidth);
                if (imgCounter === loaded) {
                    setTimeout(function () {
                        $("#progressBar").animate({opacity: 0});
                    }, 500);
                }
            }

            doProgress();
        } else {
            setTimeout(function () {
                $("#progressBar").css({
                    "opacity": 0,
                    "width": "100%"
                });
            }, 500);
        }

    // Activates Tooltips for Social Links
    $('[data-toggle="tooltip"]').tooltip();

    // Activates Popovers for Social Links 
    $('[data-toggle="popover"]').popover();

    //*** Refresh Content ***//
    $('.refresh-content').on("click", function () {
        $(this).parent().parent().addClass("loading-wait").delay(3000).queue(function (next) {
            $(this).removeClass("loading-wait");
            next();
        });
        $(this).addClass("fa-spin").delay(3000).queue(function (next) {
            $(this).removeClass("fa-spin");
            next();
        });
    });

    //*** Expand Content ***//
    $('.expand-content').on("click", function () {
        $(this).parent().parent().toggleClass("expand-this");
    });

    //*** Delete Content ***//
    $('.close-content').on("click", function () {
        $(this).parent().parent().slideUp();
    });

    // Activates Tooltips for Social Links
    $('.tooltip-social').tooltip({
        selector: "a[data-toggle=tooltip]"
    });
}

app.controller('MainController', function ($scope, $window, localStorageService) {

    if (!localStorageService.cookie.get('login')) {
        $window.location.href = 'login.html';
    }

    $scope.id = localStorageService.cookie.get('login').id;
    $scope.usuario = localStorageService.cookie.get('login').usuario;
    $scope.tipo = localStorageService.cookie.get('login').idtipo;

    loader();

    $scope.cerrarSesion = function () {
        localStorageService.cookie.remove('login');
        $window.location.href = 'login.html';
    }

});

app.controller('DashboardController', function ($scope, $window, dashboardService, $http, APP, localStorageService) {

    $scope.id = localStorageService.cookie.get('login').id;
    $scope.usuario = localStorageService.cookie.get('login').usuario;
    $scope.tipo = localStorageService.cookie.get('login').idtipo;

    $scope.data = [];
    $scope.item = {};
    $scope.contador = 0;
    $scope.settings = {
        singular: 'Reporte',
        plural: 'Reportes',
        accion: 'Filtrar'
    }
    $scope.msg = {
        mostrar: 0,
        title: "",
        message: "",
        color: ""
    }
    $scope.mostrar = 0;
/////////////////////Wilson functions/////////////////////////////////////////
$scope.getScoreData = function (dataI,dataF,dataA,dataS) {
    dashboardService.getConsultaSemana(dataI,dataF,dataA,dataS).then(function (dataResponse) {
        if (dataResponse.data.result) {
                //console.log(dataResponse.data);
                $scope.datatopmodelsellout = dataResponse.data.records;
                //$scope.datatoppdvsellout= dataResponse.data.records2;
                $scope.item.desde = dataI;
                $scope.item.hasta = dataF;
                $scope.item.aniodesde = dataA;
                $scope.item.sucursal = dataS;
            }
            else {
                showAlert("red", "Espera!", dataResponse.data.message);
            }
        });
}

$scope.exportarexcel = function (item) {
    console.log(item);
    $window.open('ws/exportarexcelTopSeller?' + serializeObj(item), '_blank');
};
function serializeObj(obj) {
    var result = [];

    for (var property in obj)
        result.push(encodeURIComponent(property) + "=" + encodeURIComponent(obj[property]));

    return result.join("&");
}
////////////////////////////////////////////////////////////////////////////////
$scope.cargar_datos = function () {
    $scope.mostrar = 0;
    $scope.msg = {
        mostrar: 0,
        title: "",
        message: "",
        color: ""
    }
    /*
    dashboardService.getSellOutPDV(1).then(function (dataResponse) {
        if (dataResponse.data.result) {
            $records = dataResponse.data.records;
            if ($records.length > 0) {
                $scope.maxNombrePDV = $records[0].nombre;
                $scope.maxSellOutPDV = $records[0].sellout;
            } else {
                $scope.maxNombrePDV = "Sin datos";
                $scope.maxSellOutPDV = "0";
            }
        }
        else {
            showAlert("red", "Espera!", dataResponse.data.message);
        }
    });
    dashboardService.getSellOutPDV(2).then(function (dataResponse) {
        if (dataResponse.data.result) {
            $records = dataResponse.data.records;
            if ($records.length > 0) {
                $scope.minNombrePDV = $records[0].nombre;
                $scope.minSellOutPDV = $records[0].sellout;
            } else {
                $scope.minNombrePDV = "Sin datos";
                $scope.minSellOutPDV = "0";
            }
        }
        else {
            showAlert("red", "Espera!", dataResponse.data.message);
        }
    });
    */
    if($scope.tipo==1)
    {
        dashboardService.getTop15ModelSellout().then(function (dataResponse) {
            $scope.datatopmodelsellout2 = dataResponse.data.records;////////Wil
        });
        dashboardService.getSucursales().then(function (dataResponse) {
            $scope.paises = dataResponse.data.records;
        });
    }else{
        dashboardService.getsemanamaximaporsucursal($scope.id).then(function (dataResponse) {
            $scope.datatopmodelsellout2 = dataResponse.data.records;////////Wil
        });
        dashboardService.getSucursalesPorUsuario($scope.id).then(function (dataResponse) {
            $scope.paises = dataResponse.data.records;
        });
    }
    dashboardService.getTop15PDVSellout().then(function (dataResponse) {
        $scope.datatoppdvsellout = dataResponse.data.records;
    });
        
    }
    $scope.cargar_datos();
    $scope.cargar_grafica = function () {
        dashboardService.getVentasPorSemana().then(function (dataResponse) {
            if (dataResponse.data.result) {
                records = dataResponse.data.records;
                if (records.length > 0) {
                    $scope.labels = [];//semanas
                    $scope.series = ['Sellout'];//serie
                    $scope.data = [];//datos
                    data = [];
                    for (var record in records) {
                        $scope.labels.push('Semana ' + records[record].semana);
                        data.push(records[record].sellout);
                    }
                    $scope.data.push(data);
                    $scope.datasetOverride = [{yAxisID: 'y-axis-1'}];
                    $scope.options = {
                        scales: {
                            yAxes: [
                            {
                                id: 'y-axis-1',
                                type: 'linear',
                                display: true,
                                position: 'left'
                            }
                            ]
                        }
                    };

                }
            }
        });
    }//Fin function cargar_grafica
    //$scope.cargar_grafica();
});
app.controller('reportesController', function ($scope, $window, reportesService, localStorageService) {
    $scope.data = [];
    $scope.item = {};
    $scope.settings = {
        singular: 'Reporte',
        plural: 'Reportes',
        accion: 'Filtrar'
    }
    $scope.msg = {
        mostrar: 0,
        title: "",
        message: "",
        color: ""
    }
    $scope.mostrar = 0;

    $scope.cargar_datos = function () {
        $scope.mostrar = 0;
        $scope.msg = {
            mostrar: 0,
            title: "",
            message: "",
            color: ""
        }
        $scope.item.desde = 1;
        $scope.item.hasta = 1;
        $scope.item.aniodesde = 2016;
        $scope.item.aniohasta = 2017;
    }
    reportesService.getTop15ModelSellout().then(function (dataResponse) {
            $scope.datatopmodelsellout2 = dataResponse.data.records;////////Wil
        });
    $scope.cargar_datos();

    $scope.exportarexcel = function (item) {
        $window.open('ws/exportarexcel?' + serializeObj(item), '_blank');
    };
    function serializeObj(obj) {
        var result = [];

        for (var property in obj)
            result.push(encodeURIComponent(property) + "=" + encodeURIComponent(obj[property]));

        return result.join("&");
    }

    reportesService.getPaises().then(function (dataResponse) {
        $scope.paises = dataResponse.data.records;
    });

    $scope.cargarcuentas = function (idpais) {
        reportesService.getSucursales(idpais).then(function (dataResponse) {
            $scope.sucursales = dataResponse.data.records;
        });
    };

    $scope.cargarpuntosventas = function (idsucursal) {
        reportesService.getPuntosVentas(idsucursal).then(function (dataResponse) {
            $scope.puntosventas = dataResponse.data.records;
        });
    };

    $scope.cargarseries = function (idcategoria) {
        reportesService.getSeries(idcategoria).then(function (dataResponse) {
            $scope.series = dataResponse.data.records;
        });
    };
    $scope.cargarmodelos = function (idserie) {
        reportesService.getModelos(idserie).then(function (dataResponse) {
            $scope.modelos = dataResponse.data.records;
        });
    };

    reportesService.getCategorias().then(function (dataResponse) {
        $scope.categorias = dataResponse.data.records;
    });

    $scope.filtrar = function (item) {
        reportesService.getFiltro(item).then(function (dataResponse) {
            if (dataResponse.data.result) {
                $scope.data = dataResponse.data.records;
                showAlert("green", "Exito!", dataResponse.data.message);
                setTimeout(function () {
                    $scope.cargar_datos();
                }, 3000);
            }
            else {
                showAlert("red", "Espera!", dataResponse.data.message);
            }
        });
    }
    function showAlert(color, title, message) {
        $scope.msg = {
            mostrar: 1,
            title: title,
            message: message,
            color: color
        }
    }

});

app.controller('coberturaEspecialController', function ($scope, $window, dashcoberturaService, localStorageService) {
    $scope.data = [];
    $scope.item = {};
    $scope.item2 = {};
    $scope.item3 = {};

    //variable para guardar el arreglo de los ids de los modelos seleccionados
    $scope.idsmodelos=[];
    //variable para guardar los objetos como tal de los modelos seleccionados.
    $scope.nombresmodelos=[];
    //variable para guardar los nombres de las columnas a partir de los modelos seleccionados.
    $scope.columnas=[];
    //variable para guardar temporalmente el valor de la columna inventario
    $scope.inventario=0;
    /**
     *variable para guardar temporalmente el valor de la columna plantilla, junto con la anterior se restan para saber
     * que producto comprar
     */
     $scope.plantilla=0;
     $scope.settings = {
        singular: 'Reporte',
        plural: 'Reportes',
        accion: 'Filtrar'
    }
    $scope.msg = {
        mostrar: 0,
        title: "",
        message: "",
        color: ""
    }
    $scope.mostrar = 0;
    $scope.mostrarDatos=0;

    $scope.cargar_datos = function () {
        $scope.mostrar = 0;
        $scope.msg = {
            mostrar: 0,
            title: "",
            message: "",
            color: ""
        }
        $scope.mostrarDatos=0;
        $scope.item = {};
        $scope.item2 = {};
        $scope.item3 = {};
        $scope.data = [];
        $scope.item.semana = 1;
        $scope.idsmodelos=[];
        $scope.nombresmodelos=[];
        $scope.columnas=[];
        $scope.inventario=0;
        $scope.plantilla=0;


    }
    dashcoberturaService.getTop15ModelSellout().then(function (dataResponse) {
            $scope.datatopmodelsellout2 = dataResponse.data.records;////////Wil
            console.log(dataResponse.data);
        });
    /**
     * Método para limpiar e inicializar las variables
     */
     $scope.cargar_datos();


    /**
     * Método para obtener los paises
     */
     dashcoberturaService.getPaises().then(function (dataResponse) {
        $scope.paises = dataResponse.data.records;
    });

     $scope.cargarcuentas = function (idpais) {
        dashcoberturaService.getSucursales(idpais).then(function (dataResponse) {
            $scope.sucursales = dataResponse.data.records;
        });
    };

    dashcoberturaService.getModelos().then(function (dataResponse) {
        $scope.modelos = dataResponse.data.records;
    });


    $scope.cargarpuntosventas = function (idsucursal) {
        dashcoberturaService.getPuntosVentas(idsucursal).then(function (dataResponse) {
            $scope.puntosventas = dataResponse.data.records;
        });
    };

    /**
     * Método para calcular la operación por vender, las tiendas que tengan plantillas tienen un limite de inventario
     * @param item El valor
     * @param indice El indice de la columna
     * @returns {*}
     */
     $scope.calcularsuma=function (item, indice) {

        var temp= indice-1;

        if(temp>=0 && temp<$scope.columnas.length) {
            if($scope.columnas[temp].indexOf("Inventory ")!== -1){
                $scope.inventario=item;
            }else if ($scope.columnas[temp].indexOf("Plantilla ") !== -1) {
                $scope.plantilla= item;
            }
            if ($scope.columnas[temp].indexOf("Comprar ") !== -1) {
                var comprar = $scope.plantilla- $scope.inventario;
                if (comprar > 0)
                    return comprar;
                else
                    return 0;
            }
        }
        return item;
    }


    /**
     * Método para agregar modelos a los arreglos.
     */
     $scope.agregarmodelo=function () {
        var obModelo = $scope.modeloselected;
        if(!$scope.buscarmodelo(obModelo.id)) {
            obModelo['posicion'] = $scope.nombresmodelos.length;
            $scope.nombresmodelos.push(obModelo);
            $scope.idsmodelos.push($scope.modeloselected.id);
        }
    }
    /**
     * Método para eliminar un modelo del arreglo
     * @param modelo
     */
     $scope.eliminarmodelo= function (modelo) {
        $scope.nombresmodelos.splice(modelo.posicion,1);
        $scope.idsmodelos.splice(modelo.posicion,1);
    }

    /**
     * Método para buscar un modelo
     * @param modelo_id
     * @returns {boolean}
     */
     $scope.buscarmodelo=function(modelo_id) {
        for (var i=0; i<$scope.idsmodelos.length; i++){
            var idtemp=$scope.idsmodelos[i];
            if(idtemp==modelo_id)
                return true;
        }
        return false;
    }
    /**
     * Método para agregar al arreglo las columnas de la tabla, generadas por medio de los modelos seleccionados
     */
     $scope.ordenarColumnas= function () {
        for (var i=0; i<$scope.nombresmodelos.length; i++){
            var idtemp=$scope.nombresmodelos[i];
            $scope.columnas.push("Sell out "+idtemp.nombre);
            $scope.columnas.push("Inventory "+idtemp.nombre);
            $scope.columnas.push("Plantilla "+idtemp.nombre);
            $scope.columnas.push("Comprar "+idtemp.nombre);
            $scope.columnas.push("Dias Exhibición "+idtemp.nombre);
            $scope.columnas.push("Dias Venta "+idtemp.nombre);
        }
    }

    $scope.filtrar = function (item,item2,item3) {
        console.log(item);
        $scope.mostrarDatos=0;
        $scope.columnas=[];
        item['modelos']=$scope.idsmodelos;
        showAlert("green", "Consultando, ", "espera un momento por favor..");
        dashcoberturaService.filtrar(item,item2,item3).then(function (dataResponse){
            $scope.ordenarColumnas();
            $scope.datoscobertura=dataResponse.data.records;
            $scope.item2.coberturaDisplay=dataResponse.data.cde;
            $scope.item2.coberturaVenta = dataResponse.data.cdv;
            $scope.item.tmodelos = dataResponse.data.tmodelos;
            $scope.item2.tmodelo = dataResponse.data.tmodelo;
            for (var i = 0; i < $scope.item.tmodelos; i++) {
                $scope.item3[i]=i;
            }
            //$scope.item3 = dataResponse.data.tmodelos;
            console.log('wilson');
            console.log(  $scope.item3);
            showAlert("green", "Exito!", dataResponse.data.message);
            setTimeout(function () {
                $scope.msg = {
                    mostrar: 0,
                    title: "",
                    message: "",
                    color: ""
                }
            }, 3000);
            $scope.mostrarDatos=1;
        });

    };

    $scope.exportarexcel = function (item) {
        $window.open('ws/exportarexcelcobertura?' + serializeObj(item), '_blank');
    };
    function serializeObj(obj) {
        var result = [];

        for (var property in obj)
            result.push(encodeURIComponent(property) + "=" + encodeURIComponent(obj[property]));

        return result.join("&");
    }
    function obtenerInfoVentas(pdvs, indice) {
        if(indice<pdvs.length){
            var puntoventa= pdvs[indice];
            console.log(puntoventa.nombre);
            var x=indice+1;
            obtenerInfoVentas(pdvs, x);
        }
    }
    function showAlert(color, title, message) {
        $scope.msg = {
            mostrar: 1,
            title: title,
            message: message,
            color: color
        }
    }


});
app.controller('dashselloutController', function ($scope, $window, dashselloutService, localStorageService) {
    $scope.data = [];
    $scope.item = {};
    $scope.settings = {
        singular: 'Reporte',
        plural: 'Reportes',
        accion: 'Filtrar'
    }
    $scope.msg = {
        mostrar: 0,
        title: "",
        message: "",
        color: ""
    }
    $scope.mostrar = 0;
    $scope.mostrarDatos=0;

    $scope.cargar_datos = function () {
        $scope.mostrar = 0;
        $scope.msg = {
            mostrar: 0,
            title: "",
            message: "",
            color: ""
        }
        $scope.mostrarDatos=0;
        $scope.item = {};
        $scope.data = [];
        $scope.item.desde = 1;
        $scope.item.hasta = 1;
        $scope.item.aniodesde = 2016;
        $scope.item.aniohasta = 2017;
    }

    $scope.cargar_datos();


    dashselloutService.getPaises().then(function (dataResponse) {
        $scope.paises = dataResponse.data.records;
    });

    $scope.cargarcuentas = function (idpais) {
        dashselloutService.getSucursales(idpais).then(function (dataResponse) {
            $scope.sucursales = dataResponse.data.records;
        });
    };

    dashselloutService.getCategorias().then(function (dataResponse) {
        $scope.categorias = dataResponse.data.records;
    });
    $scope.cargarseries = function (idcategoria) {
        dashselloutService.getSeries(idcategoria).then(function (dataResponse) {
            $scope.series = dataResponse.data.records;
        });
    };

    $scope.cargarpuntosventas = function (idsucursal) {
        dashselloutService.getPuntosVentas(idsucursal).then(function (dataResponse) {
            $scope.puntosventas = dataResponse.data.records;
        });
    };


    $scope.filtrar = function (item) {
        dashselloutService.getFiltroPorCategoria(item).then(function (dataResponse) {
            if (dataResponse.data.result) {
                $scope.dataPorCategoria = dataResponse.data.records;
                records = dataResponse.data.records;
                showAlert("green", "Exito!", dataResponse.data.message);
                setTimeout(function () {
                    $scope.msg = {
                        mostrar: 0,
                        title: "",
                        message: "",
                        color: ""
                    }
                }, 3000);
                $scope.labelsCategoria = [];//semanas
                $scope.seriesCategoria = ['Sellout'];//serie
                $scope.dataCategoria = [];//datos
                data = [];
                for (var record in records) {
                    $scope.labelsCategoria.push('Semana ' + records[record].semana);
                    data.push(records[record].sellout);
                }
                $scope.dataCategoria.push(data);
                $scope.datasetOverrideCategoria = [{yAxisID: 'y-axis-1'}];
                $scope.optionsCategoria = {
                    scales: {
                        yAxes: [
                        {
                            id: 'y-axis-1',
                            type: 'linear',
                            display: true,
                            position: 'left'
                        }
                        ]
                    }
                };
                $scope.mostrarDatos=1;

            }
            else {
                showAlert("red", "Espera!", dataResponse.data.message);
            }
        });
    }
    function showAlert(color, title, message) {
        $scope.msg = {
            mostrar: 1,
            title: title,
            message: message,
            color: color
        }
    }


});
app.controller('inventariosController', function ($scope, $window, inventariosService, localStorageService) {
    $scope.data = [];
    $scope.count = 0;
    $scope.settings = {
        singular: 'Inventario',
        plural: 'Inventarios',
        accion: 'Nuevo'
    }
    $scope.msg = {
        mostrar: 0,
        title: "",
        message: "",
        color: ""
    }
    $scope.mostrar = 0;
    $scope.cargar_datos = function () {
        $scope.mostrar = 0;
        $scope.msg = {
            mostrar: 0,
            title: "",
            message: "",
            color: ""
        }

        $scope.item = {};
        $scope.item.idusuario = localStorageService.cookie.get('login').id;
        $scope.item.idtipo = localStorageService.cookie.get('login').idtipo;

        inventariosService.getRegistros($scope.item).then(function (dataResponse) {

            $scope.data = dataResponse.data.records;
            $scope.count = dataResponse.data.count;
        });
        /*inventariosService.getData("GET", {}).then(function(dataResponse)
         {
         $scope.data = dataResponse.data.records;
     });*/
 }

 $scope.cargar_datos();
 $scope.crear = function () {
    $scope.settings.accion = 'Crear';
    $scope.mostrar = 1;
    $scope.item = {};
}
$scope.uploadFile = function (files) {

    $scope.cargando = 1;
    showAlert("green", "Procesando!", "Espera un momento.. ");

    var fd = new FormData();

    fd.append("file", files[0]);
    fd.append("idusuario", localStorageService.cookie.get('login').id);

    inventariosService.upload(fd).then(function (dataResponse) {
        if (dataResponse.data.result) {
            showAlert("green", "Exito!", dataResponse.data.message);
            setTimeout(function () {
                $scope.cargar_datos();
                angular.element("input[type='file']").val(null);
            }, 3000);
        }
        else {
            setTimeout(function () {
                angular.element("input[type='file']").val(null);
            }, 3000);
            showAlert("red", "Espera!", dataResponse.data.message);
        }
    });

};
$scope.cancelar = function () {
    $scope.mostrar = 0;
}


function showAlert(color, title, message) {
    $scope.msg = {
        mostrar: 1,
        title: title,
        message: message,
        color: color
    }
}
});


app.controller('UsuariosController', function ($scope, $window, usuariosService) {

    $scope.data = [];
    $scope.settings = {
        singular: 'Usuario',
        plural: 'Usuarios',
        accion: 'Crear'
    }
    $scope.msg = {
        mostrar: 0,
        title: "",
        message: "",
        color: ""
    }
    $scope.mostrar = 0;

    $scope.cargar_datos = function () {
        $scope.mostrar = 0;
        $scope.msg = {
            mostrar: 0,
            title: "",
            message: "",
            color: ""
        }
        usuariosService.getData("GET", {}).then(function (dataResponse) {
            $scope.data = dataResponse.data.records;
        });
    }

    $scope.cargar_datos();

    usuariosService.getTipos().then(function (dataResponse) {
        $scope.tipos_usuarios = dataResponse.data.records;
    });

    $scope.crear = function () {
        $scope.settings.accion = 'Crear';
        $scope.mostrar = 1;
        $scope.item = {};
    }

    $scope.editar = function (item) {
        $scope.settings.accion = 'Editar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.eliminar = function (item) {
        $scope.settings.accion = 'Eliminar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.cancelar = function () {
        $scope.mostrar = 0;
    }

    $scope.guardar = function (item) {
        if ($scope.settings.accion == "Crear") {
            usuariosService.create(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else if ($scope.settings.accion == "Editar") {
            usuariosService.update(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else {
            usuariosService.delete(item.id).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
    }


    function showAlert(color, title, message) {
        $scope.msg = {
            mostrar: 1,
            title: title,
            message: message,
            color: color
        }
    }


});
app.controller('ventasPendientesController', function ($scope, $window, ventaspendientesService, localStorageService, NgTableParams) {
    $scope.data = [];
    $scope.count = 0;
    $scope.settings = {
        singular: 'Venta',
        plural: 'Ventas',
        accion: 'Crear'
    };
    $scope.msg = {
        mostrar: 0,
        title: "",
        message: "",
        color: ""
    };
    $scope.mostrar = 0;
    $scope.cargar_datos = function () {
        $scope.mostrar = 0;
        $scope.msg = {
            mostrar: 0,
            title: "",
            message: "",
            color: ""
        };
        ventaspendientesService.getData("GET", {}).then(function (dataResponse) {
            $scope.data = dataResponse.data.records.length;
            $scope.dataTable = new NgTableParams({}, {
                dataset: dataResponse.data.records
            });
        });
    };
    $scope.cargar_datos();
    $scope.editar = function (item) {
        $scope.settings.accion = 'Editar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.eliminar = function (item) {
        $scope.settings.accion = 'Eliminar';
        $scope.mostrar = 1;
        $scope.item = item;
    }
    $scope.cancelar = function () {
        $scope.mostrar = 0;
    }
    $scope.guardar = function (item) {
        if ($scope.settings.accion == 'Editar') {
            item.idusuario = localStorageService.cookie.get('login').id;
            ventaspendientesService.update(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                } else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        } else if ($scope.settings.accion == 'Eliminar') {
            ventaspendientesService.delete(item.id).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                } else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
    };
    function showAlert(color, title, message) {
        $scope.msg = {
            mostrar: 1,
            title: title,
            message: message,
            color: color
        }
    }
});
//Controlador Categorias
app.controller('CategoriasController', function ($scope, $window, categoriasService) {

    $scope.data = [];
    $scope.settings = {
        singular: 'Categoria',
        plural: 'Categorias',
        accion: 'Crear'
    }
    $scope.msg = {
        mostrar: 0,
        title: "",
        message: "",
        color: ""
    }
    $scope.mostrar = 0;

    $scope.cargar_datos = function () {
        $scope.mostrar = 0;
        $scope.msg = {
            mostrar: 0,
            title: "",
            message: "",
            color: ""
        }
        categoriasService.getData("GET", {}).then(function (dataResponse) {
            $scope.data = dataResponse.data.records;
        });
    }

    $scope.cargar_datos();

    $scope.crear = function () {
        $scope.settings.accion = 'Crear';
        $scope.mostrar = 1;
        $scope.item = {};
    }

    $scope.editar = function (item) {
        $scope.settings.accion = 'Editar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.eliminar = function (item) {
        $scope.settings.accion = 'Eliminar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.cancelar = function () {
        $scope.mostrar = 0;
    }

    $scope.guardar = function (item) {
        if ($scope.settings.accion == "Crear") {
            categoriasService.create(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else if ($scope.settings.accion == "Editar") {
            categoriasService.update(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else {
            categoriasService.delete(item.id).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
    }


    function showAlert(color, title, message) {
        $scope.msg = {
            mostrar: 1,
            title: title,
            message: message,
            color: color
        }
    }


});
//Controlador Paises
app.controller('PaisesController', function ($scope, $window, paisesService) {

    $scope.data = [];
    $scope.settings = {
        singular: 'Pais',
        plural: 'Paises',
        accion: 'Crear'
    }
    $scope.msg = {
        mostrar: 0,
        title: "",
        message: "",
        color: ""
    }
    $scope.mostrar = 0;

    $scope.cargar_datos = function () {
        $scope.mostrar = 0;
        $scope.msg = {
            mostrar: 0,
            title: "",
            message: "",
            color: ""
        }
        paisesService.getData("GET", {}).then(function (dataResponse) {
            $scope.data = dataResponse.data.records;
        });
    }

    $scope.cargar_datos();

    $scope.crear = function () {
        $scope.settings.accion = 'Crear';
        $scope.mostrar = 1;
        $scope.item = {};
    }

    $scope.editar = function (item) {
        $scope.settings.accion = 'Editar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.eliminar = function (item) {
        $scope.settings.accion = 'Eliminar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.cancelar = function () {
        $scope.mostrar = 0;
    }

    $scope.guardar = function (item) {
        if ($scope.settings.accion == "Crear") {
            paisesService.create(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else if ($scope.settings.accion == "Editar") {
            paisesService.update(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else {
            paisesService.delete(item.id).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
    }


    function showAlert(color, title, message) {
        $scope.msg = {
            mostrar: 1,
            title: title,
            message: message,
            color: color
        }
    }


});

//Controlador Modelos
app.controller('ModelosController', function ($scope, $window, modelosService) {

    $scope.data = [];
    $scope.settings = {
        singular: 'Modelo',
        plural: 'Modelos',
        accion: 'Crear'
    }
    $scope.msg = {
        mostrar: 0,
        title: "",
        message: "",
        color: ""
    }
    $scope.mostrar = 0;

    $scope.cargar_datos = function () {
        $scope.mostrar = 0;
        $scope.msg = {
            mostrar: 0,
            title: "",
            message: "",
            color: ""
        }
        modelosService.getData("GET", {}).then(function (dataResponse) {
            $scope.data = dataResponse.data.records;
        });
    }

    $scope.cargar_datos();

    modelosService.getSeries().then(function (dataResponse) {
        $scope.series = dataResponse.data.records;
    });

    $scope.crear = function () {
        $scope.settings.accion = 'Crear';
        $scope.mostrar = 1;
        $scope.item = {};
    }

    $scope.editar = function (item) {
        $scope.settings.accion = 'Editar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.eliminar = function (item) {
        $scope.settings.accion = 'Eliminar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.cancelar = function () {
        $scope.mostrar = 0;
    }

    $scope.guardar = function (item) {
        if ($scope.settings.accion == "Crear") {
            modelosService.create(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else if ($scope.settings.accion == "Editar") {
            modelosService.update(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else {
            modelosService.delete(item.id).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
    }


    function showAlert(color, title, message) {
        $scope.msg = {
            mostrar: 1,
            title: title,
            message: message,
            color: color
        }
    }


});

//Controlador Permisos
app.controller('PermisosController', function ($scope, $window, permisosService) {

    $scope.data = [];
    $scope.settings = {
        singular: 'Permiso',
        plural: 'Permisos',
        accion: 'Crear'
    }
    $scope.msg = {
        mostrar: 0,
        title: "",
        message: "",
        color: ""
    }
    $scope.mostrar = 0;

    $scope.cargar_datos = function () {
        $scope.mostrar = 0;
        $scope.msg = {
            mostrar: 0,
            title: "",
            message: "",
            color: ""
        }
        permisosService.getData("GET", {}).then(function (dataResponse) {
            $scope.data = dataResponse.data.records;
        });
    }

    $scope.cargar_datos();

    permisosService.getUsuarios().then(function (dataResponse) {
        $scope.usuarios = dataResponse.data.records;
    });

    permisosService.getPaises().then(function (dataResponse) {
        $scope.paises = dataResponse.data.records;
    });

    $scope.cargarsucursales = function (idpais) {
        permisosService.getSucursales(idpais).then(function (dataResponse) {
            $scope.sucursales = dataResponse.data.records;
        });
    };

    $scope.cargarpdv = function (idsucursal) {
        permisosService.getPuntosVentas(idsucursal).then(function (dataResponse) {
            $scope.puntosventas = dataResponse.data.records;
        });
    };

    $scope.crear = function () {
        $scope.settings.accion = 'Crear';
        $scope.mostrar = 1;
        $scope.item = {};
    }

    $scope.editar = function (item) {
        console.log(item);
        $scope.settings.accion = 'Editar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.eliminar = function (item) {
        $scope.settings.accion = 'Eliminar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.cancelar = function () {
        $scope.mostrar = 0;
    }

    $scope.guardar = function (item) {
        if ($scope.settings.accion == "Crear") {
            permisosService.create(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else if ($scope.settings.accion == "Editar") {
            permisosService.update(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else {
            permisosService.delete(item.id).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
    }


    function showAlert(color, title, message) {
        $scope.msg = {
            mostrar: 1,
            title: title,
            message: message,
            color: color
        }
    }


});

//Controlador puntosventas
app.controller('PuntosVentasController', function ($scope, $window, puntosVentasService, NgTableParams, $filter) {

    $scope.data = [];
    $scope.settings = {
        singular: 'Punto de venta',
        plural: 'Puntos de ventas',
        accion: 'Crear'
    }
    $scope.msg = {
        mostrar: 0,
        title: "",
        message: "",
        color: ""
    }
    $scope.mostrar = 0;

    $scope.cargar_datos = function () {
        $scope.mostrar = 0;
        $scope.msg = {
            mostrar: 0,
            title: "",
            message: "",
            color: ""
        }
        puntosVentasService.getData("GET", {}).then(function (dataResponse) {

            $scope.data = dataResponse.data.records;
            $scope.dataTable = new NgTableParams({}, {
                dataset: dataResponse.data.records
            });

        });
    }

    $scope.cargar_datos();

    puntosVentasService.getSucursales().then(function (dataResponse) {
        $scope.sucursales = dataResponse.data.records;
    });

    $scope.crear = function () {
        $scope.settings.accion = 'Crear';
        $scope.mostrar = 1;
        $scope.item = {};
    }

    $scope.editar = function (item) {
        $scope.settings.accion = 'Editar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.eliminar = function (item) {
        $scope.settings.accion = 'Eliminar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.cancelar = function () {
        $scope.mostrar = 0;
    }

    $scope.guardar = function (item) {
        if ($scope.settings.accion == "Crear") {
            puntosVentasService.create(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else if ($scope.settings.accion == "Editar") {
            puntosVentasService.update(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else {
            puntosVentasService.delete(item.id).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
    }


    function showAlert(color, title, message) {
        $scope.msg = {
            mostrar: 1,
            title: title,
            message: message,
            color: color
        }
    }


});

//Controlador series
app.controller('SeriesController', function ($scope, $window, seriesService) {

    $scope.data = [];
    $scope.settings = {
        singular: 'Serie',
        plural: 'Series',
        accion: 'Crear'
    }
    $scope.msg = {
        mostrar: 0,
        title: "",
        message: "",
        color: ""
    }
    $scope.mostrar = 0;

    $scope.cargar_datos = function () {
        $scope.mostrar = 0;
        $scope.msg = {
            mostrar: 0,
            title: "",
            message: "",
            color: ""
        }
        seriesService.getData("GET", {}).then(function (dataResponse) {
            $scope.data = dataResponse.data.records;
        });
    }

    $scope.cargar_datos();

    seriesService.getCategorias().then(function (dataResponse) {
        $scope.categorias = dataResponse.data.records;
    });


    $scope.crear = function () {
        $scope.settings.accion = 'Crear';
        $scope.mostrar = 1;
        $scope.item = {};
    }

    $scope.editar = function (item) {
        $scope.settings.accion = 'Editar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.eliminar = function (item) {
        $scope.settings.accion = 'Eliminar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.cancelar = function () {
        $scope.mostrar = 0;
    }

    $scope.guardar = function (item) {
        if ($scope.settings.accion == "Crear") {
            seriesService.create(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else if ($scope.settings.accion == "Editar") {
            seriesService.update(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else {
            seriesService.delete(item.id).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
    }


    function showAlert(color, title, message) {
        $scope.msg = {
            mostrar: 1,
            title: title,
            message: message,
            color: color
        }
    }


});
//Controlador sinonimos
app.controller('SinonimosController', function ($scope, $window, sinonimosService, NgTableParams) {

    $scope.data = [];
    $scope.settings = {
        singular: 'Sinonimo',
        plural: 'Sinonimos',
        accion: 'Crear'
    }
    $scope.msg = {
        mostrar: 0,
        title: "",
        message: "",
        color: ""
    }
    $scope.mostrar = 0;

    $scope.cargar_datos = function () {
        $scope.mostrar = 0;
        $scope.msg = {
            mostrar: 0,
            title: "",
            message: "",
            color: ""
        }
        sinonimosService.getData("GET", {}).then(function (dataResponse) {
            $scope.data = dataResponse.data.records.length;
            $scope.dataTable = new NgTableParams({}, {
                dataset: dataResponse.data.records
            });
        });
    }

    $scope.cargar_datos();

    sinonimosService.getModelos().then(function (dataResponse) {
        $scope.modelos = dataResponse.data.records;
    });


    $scope.crear = function () {
        $scope.settings.accion = 'Crear';
        $scope.mostrar = 1;
        $scope.item = {};
    }

    $scope.editar = function (item) {
        $scope.settings.accion = 'Editar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.eliminar = function (item) {
        $scope.settings.accion = 'Eliminar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.cancelar = function () {
        $scope.mostrar = 0;
    }

    $scope.guardar = function (item) {
        if ($scope.settings.accion == "Crear") {
            sinonimosService.create(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else if ($scope.settings.accion == "Editar") {
            sinonimosService.update(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else {
            sinonimosService.delete(item.id).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
    }


    function showAlert(color, title, message) {
        $scope.msg = {
            mostrar: 1,
            title: title,
            message: message,
            color: color
        }
    }


});
app.controller('PlantillasController', function ($scope, $window, plantillasService, NgTableParams) {

    $scope.data = [];
    $scope.settings = {
        singular: 'Plantilla',
        plural: 'Plantillas',
        accion: 'Crear'
    }
    $scope.msg = {
        mostrar: 0,
        title: "",
        message: "",
        color: ""
    }
    $scope.mostrar = 0;

    $scope.cargar_datos = function () {
        $scope.mostrar = 0;
        $scope.msg = {
            mostrar: 0,
            title: "",
            message: "",
            color: ""
        }
        plantillasService.getData("GET", {}).then(function (dataResponse) {
            $scope.data = dataResponse.data.records;
            $scope.dataTable = new NgTableParams({}, {
                dataset: dataResponse.data.records
            });
        });
    }

    $scope.cargar_datos();

    plantillasService.getSucursales().then(function (dataResponse) {
        $scope.sucursales = dataResponse.data.records;
    });

    plantillasService.getModelos().then(function (dataResponse) {
        $scope.modelos = dataResponse.data.records;
    });

    $scope.cargarpdvycategorias = function (idsucursal) {
        plantillasService.getPuntosVentas(idsucursal).then(function (dataResponse) {
            $scope.puntosventas = dataResponse.data.records;
        });
        plantillasService.getCategoriasPlantillas(idsucursal).then(function (dataResponse) {
            $scope.categoriasplantillas = dataResponse.data.records;
        });
    }

    $scope.crear = function () {
        $scope.settings.accion = 'Crear';
        $scope.mostrar = 1;
        $scope.item = {};
    }

    $scope.editar = function (item) {
        $scope.settings.accion = 'Editar';
        $scope.mostrar = 1;
        $scope.item = item;
        $scope.cargarpdvycategorias(item.puntos_ventas.sucursal.id);
    }

    $scope.eliminar = function (item) {
        $scope.settings.accion = 'Eliminar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.cancelar = function () {
        $scope.mostrar = 0;
    }

    $scope.guardar = function (item) {
        if ($scope.settings.accion == "Crear") {
            plantillasService.create(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else if ($scope.settings.accion == "Editar") {

            plantillasService.update(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else if($scope.settings.accion=="Eliminar") {

            plantillasService.delete(item.id).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
    }


    function showAlert(color, title, message) {
        $scope.msg = {
            mostrar: 1,
            title: title,
            message: message,
            color: color
        }
    }


});
//PlantillasController
app.controller('CategoriasPlantillasController', function ($scope, $window, categoriasPlantillasService) {

    $scope.data = [];
    $scope.settings = {
        singular: 'Categoria',
        plural: 'Categorias',
        accion: 'Crear'
    }
    $scope.msg = {
        mostrar: 0,
        title: "",
        message: "",
        color: ""
    }
    $scope.mostrar = 0;

    $scope.cargar_datos = function () {
        $scope.mostrar = 0;
        $scope.msg = {
            mostrar: 0,
            title: "",
            message: "",
            color: ""
        }
        categoriasPlantillasService.getData("GET", {}).then(function (dataResponse) {
            $scope.data = dataResponse.data.records;
        });
    }

    $scope.cargar_datos();


    $scope.crear = function () {
        $scope.settings.accion = 'Crear';
        $scope.mostrar = 1;
        $scope.item = {};
    }

    $scope.editar = function (item) {
        $scope.settings.accion = 'Editar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.eliminar = function (item) {
        $scope.settings.accion = 'Eliminar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.cancelar = function () {
        $scope.mostrar = 0;
    }

    $scope.guardar = function (item) {
        if ($scope.settings.accion == "Crear") {
            categoriasPlantillasService.create(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else if ($scope.settings.accion == "Editar") {
            categoriasPlantillasService.update(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else {
            categoriasPlantillasService.delete(item.id).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
    }


    function showAlert(color, title, message) {
        $scope.msg = {
            mostrar: 1,
            title: title,
            message: message,
            color: color
        }
    }


});
//CuentasController
app.controller('CuentasController', function ($scope, $window, cuentasService) {

    $scope.data = [];
    $scope.settings = {
        singular: 'Grupo',
        plural: 'Grupos',
        accion: 'Crear'
    }
    $scope.msg = {
        mostrar: 0,
        title: "",
        message: "",
        color: ""
    }
    $scope.mostrar = 0;

    $scope.cargar_datos = function () {
        $scope.mostrar = 0;
        $scope.msg = {
            mostrar: 0,
            title: "",
            message: "",
            color: ""
        }
        cuentasService.getData("GET", {}).then(function (dataResponse) {
            $scope.data = dataResponse.data.records;
        });
    }

    $scope.cargar_datos();


    $scope.crear = function () {
        $scope.settings.accion = 'Crear';
        $scope.mostrar = 1;
        $scope.item = {};
    }

    $scope.editar = function (item) {
        $scope.settings.accion = 'Editar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.eliminar = function (item) {
        $scope.settings.accion = 'Eliminar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.cancelar = function () {
        $scope.mostrar = 0;
    }

    $scope.guardar = function (item) {
        if ($scope.settings.accion == "Crear") {
            cuentasService.create(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else if ($scope.settings.accion == "Editar") {
            cuentasService.update(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else {
            cuentasService.delete(item.id).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
    }


    function showAlert(color, title, message) {
        $scope.msg = {
            mostrar: 1,
            title: title,
            message: message,
            color: color
        }
    }


});
//Controlador sucursales
app.controller('SucursalesController', function ($scope, $window, sucursalesService) {

    $scope.data = [];
    $scope.settings = {
        singular: 'Cuenta',
        plural: 'Cuentas',
        accion: 'Crear'
    }
    $scope.msg = {
        mostrar: 0,
        title: "",
        message: "",
        color: ""
    }
    $scope.mostrar = 0;

    $scope.cargar_datos = function () {
        $scope.mostrar = 0;
        $scope.msg = {
            mostrar: 0,
            title: "",
            message: "",
            color: ""
        }
        sucursalesService.getData("GET", {}).then(function (dataResponse) {
            $scope.data = dataResponse.data.records;
        });
    }

    $scope.cargar_datos();

    sucursalesService.getCuentas().then(function (dataResponse) {
        $scope.cuentas = dataResponse.data.records;
    });
    sucursalesService.getPaises().then(function (dataResponse) {
        $scope.paises = dataResponse.data.records;
    });


    $scope.crear = function () {
        $scope.settings.accion = 'Crear';
        $scope.mostrar = 1;
        $scope.item = {};
    }

    $scope.editar = function (item) {
        $scope.settings.accion = 'Editar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.eliminar = function (item) {
        $scope.settings.accion = 'Eliminar';
        $scope.mostrar = 1;
        $scope.item = item;
    }

    $scope.cancelar = function () {
        $scope.mostrar = 0;
    }

    $scope.guardar = function (item) {
        if ($scope.settings.accion == "Crear") {
            sucursalesService.create(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else if ($scope.settings.accion == "Editar") {
            sucursalesService.update(item).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
        else {
            sucursalesService.delete(item.id).then(function (dataResponse) {
                if (dataResponse.data.result) {
                    showAlert("green", "Exito!", dataResponse.data.message);
                    setTimeout(function () {
                        $scope.cargar_datos();
                    }, 3000);
                }
                else {
                    showAlert("red", "Espera!", dataResponse.data.message);
                }
            });
        }
    }


    function showAlert(color, title, message) {
        $scope.msg = {
            mostrar: 1,
            title: title,
            message: message,
            color: color
        }
    }


});