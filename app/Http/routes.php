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
    return view('home');
}]);

Route::group(['namespace' => 'Auth','prefix' => 'auth'], function () {
    // Authentication routes...
    Route::get('login',                 'AuthController@getLogin');
    Route::post('login',                ['as' =>'auth/login',       'uses' => 'AuthController@postLogin']);
    Route::get('logout',                ['as' => 'auth/logout',     'uses' => 'AuthController@getLogout']);
});

Route::group(['namespace' => 'Transaccion', 'middleware' => 'auth'], function () {
    // Trancacciones
    Route::resource('usuario',                             'UserController');
    Route::resource('monitoreo',                           'MonitoreoController');
    Route::post('persona/curp',                            'PersonaController@curp');
    Route::get('persona/reporte',                          'PersonaController@report');
    Route::resource('persona',                             'PersonaController');
    Route::resource('cuadro-dist-juris',                   'CuadroDistribucionJurisdiccionalController');
    Route::resource('cuadro_distribucion_clue',            'CuadroDistribucionClueController');    
});  

Route::group(['prefix' => 'catalogo', 'namespace' => 'Catalogo', 'middleware' => 'auth'], function () {
    // Catalogos
    Route::resource('ageb',             'AgebController',            ['only' => ['index', 'show']]);
    Route::resource('clue',             'ClueController',            ['only' => ['index', 'show']]);
    Route::resource('codigo',           'CodigoCensoController',     ['only' => ['index', 'show']]);
    Route::resource('entidad',          'EntidadController',         ['only' => ['index', 'show']]);
    Route::resource('esquema',          'EsquemaController',         ['only' => ['index', 'show']]);
    Route::resource('institucion',      'InstitucionController',     ['only' => ['index', 'show']]);
    Route::resource('jurisdiccion',     'JurisdiccionController',    ['only' => ['index', 'show']]);
    Route::resource('localidad',        'LocalidadController',       ['only' => ['index', 'show']]);
    Route::resource('municipio',        'MunicipioController',       ['only' => ['index', 'show']]);
    Route::resource('pais',             'PaisController',            ['only' => ['index', 'show']]);
    Route::resource('tipo-parto',       'TipoPartoController',       ['only' => ['index', 'show']]);

});

// PDF
Route::get('persona-pdf',               'Pdf\PdfController@persona');
Route::get('persona-filtro-pdf',        'Pdf\PdfController@filter');

// Error abort
Route::get('error', function(){
    abort(500);             
});

