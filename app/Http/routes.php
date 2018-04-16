<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['middleware' => 'auth', function () {
    if(\Auth::user()->is('red-frio')){
        return redirect('dashboard');
    } else {
        return redirect('dashboard');
    }        
}]);

// JWT
Route::post('obtener-token',    'AutenticacionController@autenticar');
Route::post('refresh-token',    'AutenticacionController@refreshToken');
Route::get('check-token',       'AutenticacionController@verificar');

// APP MÓVIL
Route::group(['namespace' => 'Movil', 'prefix' => 'movil/catalogo', 'middleware' => 'jwt'], function () {
    // CLUE
    // Route::resource('clue',                 'ClueController');
    // CONTENEDOR
    // FALLAS CONTENEDORES
});


// AUTH
Route::group(['namespace' => 'Auth','prefix' => 'auth'], function () {
    Route::get('login',                 'AuthController@getLogin');
    Route::post('login',                ['as' =>'auth/login',       'uses' => 'AuthController@postLogin']);
    Route::get('logout',                ['as' => 'auth/logout',     'uses' => 'AuthController@getLogout']);
});

// TRANSACCIONES
Route::group(['middleware' => 'auth'], function () {
    Route::group(['namespace' => 'Transaccion'], function () {
        // TRANSACCION
        Route::resource('usuario',                             'UserController');
        Route::resource('permiso',                             'PermissionController');

        // VACUNACIÓN
        Route::get('persona/buscar',                           'PersonaController@buscar');
        Route::get('persona/curp-repetida',                    'PersonaController@curpRepetida');
        Route::get('persona/curp',                             'PersonaController@curp');
        Route::get('persona/reporte',                          'PersonaController@reporte');
        Route::resource('persona',                             'PersonaController');                
        
        Route::resource('monitoreo',                           'MonitoreoController');
        Route::resource('cuadro-dist-juris',                   'CuadroDistribucionJurisdiccionalController');
        Route::resource('cuadro_distribucion_clue',            'CuadroDistribucionClueController');   
        // RED DE FRÍO
        Route::resource('temperatura',                         'TemperaturaContenedorController');

        Route::get('pedido/clue-detalle',                      'PedidoController@clueDetalle');
        Route::resource('pedido',                              'PedidoController');
    });  

    // DASHBOARD
    Route::group(['prefix' =>   'dashboard', 'namespace' => 'Dashboard'], function () {
        // VACUNACIÓN
        Route::get('/',                                     'DashboardController@index');
        Route::get('capturas',                              'DashboardController@capturas');  
        Route::get('cobertura',                             'DashboardController@coberturas');
        Route::get('esquema-completo',                      'DashboardController@esquemaCompleto');
        Route::get('concordancia',                          'DashboardController@concordancia');
        Route::get('contenedores-biologico',                'DashboardController@contenedoresBiologico');
        Route::get('ubicacion-contenedores',                'DashboardController@ubicacionContenedores');
    });

    // REPORTES
    Route::group(['prefix' => 'persona/reporte', 'namespace' => 'Reporte'], function () {
        // VACUNACIÓN
        Route::get('buscar',                    'ReportePersonaController@buscar');
        Route::get('seguimiento',               'ReportePersonaController@seguimiento');
        Route::get('actividad',                 'ReportePersonaController@actividad');
        Route::get('biologico',                 'ReportePersonaController@biologico');
    });

    // CATALOGOS
    Route::group(['prefix' => 'catalogo', 'namespace' => 'Catalogo'], function () {
        // GENÉRICOS
        Route::resource('ageb',                 'AgebController',                   ['only' => ['index', 'show']]);
        Route::resource('clue',                 'ClueController');
        Route::get('clue-contenedor',           'ClueController@clueContenedor');
        Route::resource('codigo',               'CodigoCensoController',            ['only' => ['index', 'show']]);
        Route::resource('entidad',              'EntidadController',                ['only' => ['index', 'show']]);
        Route::resource('esquema',              'EsquemaController',                ['only' => ['index', 'show']]);
        Route::resource('institucion',          'InstitucionController',            ['only' => ['index', 'show']]);
        Route::resource('jurisdiccion',         'JurisdiccionController',           ['only' => ['index', 'show']]);
        Route::resource('localidad',            'LocalidadController');
        Route::resource('municipio',            'MunicipioController',              ['only' => ['index', 'show']]);
        Route::resource('colonia',              'ColoniaController');
        Route::resource('vacuna',               'VacunaController');
        Route::resource('pais',                 'PaisController',                   ['only' => ['index', 'show']]);
        Route::resource('tipo-parto',           'TipoPartoController',              ['only' => ['index', 'show']]);
        Route::resource('poblacion-conapo',     'PoblacionConapoController');
        Route::post('poblacion-conapo/importar','PoblacionConapoController@importar');
        // VACUNACIÓN
        Route::group(['prefix' => 'vacunacion', 'namespace' => 'Vacunacion'], function () {
            Route::get('piramide-poblacional/clue-detalle',     'PiramidePoblacionalController@clueDetalle');
            Route::resource('piramide-poblacional',     'PiramidePoblacionalController');
        });
        // RED DE FRÍO
        Route::group(['prefix' => 'red-frio', 'namespace' => 'RedFrio'], function () {
            Route::resource('contenedor-biologico', 'ContenedorBiologicoController');
            Route::resource('estatus-contenedor',   'EstatusContenedorController',      ['only' => ['index', 'show']]);
            Route::resource('tipo-contenedor',      'TipoContenedorController',         ['only' => ['index', 'show']]);
            Route::resource('marca',                'MarcaController');
            Route::resource('modelo',               'ModeloController');
        });
                
        
    });
});
// PDF
Route::group(['namespace' => 'Pdf'], function () {
    // VACUNACIÓN
    Route::get('persona-pdf',               'PdfController@persona');
    Route::get('persona-filtro-pdf',        'PdfController@filter');
});

// ERROR ABORT
Route::get('error', function(){
    abort(500);             
});

