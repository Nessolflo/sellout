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
        .when("/inventarios", {templateUrl: "views/inventarios.html", controller: "inventariosController"})
        .when("/ventaspendientes", {
            templateUrl: "views/ventaspendientes.html",
            controller: "ventasPendientesController"
        })
        .when("/reportes", {templateUrl: "views/reportes.html", controller: "reportesController"})
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

app.controller('DashboardController', function ($scope, $window, dashboardService, $http, APP) {
    $scope.data = [];
    $scope.item = {};
    $scope.contador=0;
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
        dashboardService.getTop15ModelSellout().then(function (dataResponse) {
            $scope.datatopmodelsellout = dataResponse.data.records;
        });
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
    $scope.cargar_grafica();
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
app.controller('inventariosController', function ($scope, $window, inventariosService, localStorageService) {
    $scope.data = [];
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
app.controller('ventasPendientesController', function ($scope, $window, ventaspendientesService, localStorageService) {
    $scope.data = [];
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
            $scope.data = dataResponse.data.records;
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
app.controller('PuntosVentasController', function ($scope, $window, puntosVentasService) {

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
app.controller('SinonimosController', function ($scope, $window, sinonimosService) {

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
            $scope.data = dataResponse.data.records;
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