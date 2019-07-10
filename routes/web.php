<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function () {

    Route::get("/dashboard", "DashBoardController@index")->name("dashboard.index");

    Route::get("/getloader", "UserController@getloader")->name("loader.getloader");

    //TIPO DE PERSONA
    Route::get('/tipo-persona/all', 'TipoPersonaController@all')->name('tipo-persona.all');
    Route::get('/tipo-persona/all-sin-paginado', 'TipoPersonaController@allSinPaginado')->name('tipo-persona.all-sin-paginado');
    Route::get('/tipo-persona/getPageSession', 'TipoPersonaController@getPageSession')->name('tipo-persona.getPageSession');
    Route::resource('tipo-persona','TipoPersonaController');
    Route::get('/{validacion}/tipo-persona', 'TipoPersonaController@index')->name('tipo-persona.index');
    Route::get('/tipo-persona/{id}/{pagina}/edit', 'TipoPersonaController@edit')->name('tipo-persona.edit');

    //TERRITORIO VECINAL
    Route::get('/territorio-vecinal/all', 'TerritorioVecinalController@all')->name('territorio-vecinal.all');
    Route::get('/territorio-vecinal/all-sin-paginado', 'TerritorioVecinalController@allSinPaginado')->name('territorio-vecinal.all-sin-paginado');
    Route::get('/territorio-vecinal/view-map', 'TerritorioVecinalController@viewMap')->name('territorio-vecinal.view-all');
    Route::post('/territorio-vecinal/store-all', 'TerritorioVecinalController@storeAll')->name('territorio-vecinal.store-all');
    Route::get('/territorio-vecinal/getPageSession', 'TerritorioVecinalController@getPageSession')->name('territorio-vecinal.getPageSession');
    Route::resource('territorio-vecinal','TerritorioVecinalController');
    Route::get('/{validacion}/territorio-vecinal', 'TerritorioVecinalController@index')->name('territorio-vecinal.index');
    Route::get('/territorio-vecinal/{id}/{pagina}/edit', 'TerritorioVecinalController@edit')->name('territorio-vecinal.edit');

    // Route::get('/{validacion}/territorio-vecinal', 'TerritorioVecinalController@index')->name('territorio-vecinal.index');

    //URBANIZACION
    Route::get('/urbanizacion/all', 'UrbanizacionController@all')->name('urbanizacion.all');
    Route::get('/urbanizacion/all-sin-paginado', 'UrbanizacionController@allSinPaginado')->name('urbanizacion.all-sin-paginado');
    Route::post('/urbanizacion/store-all', 'UrbanizacionController@storeAll')->name('urbanizacion.store-all');

    Route::get('/urbanizacion/getPageSession', 'UrbanizacionController@getPageSession')->name('urbanizacion.getPageSession');
    
    Route::resource('urbanizacion','UrbanizacionController');

    Route::get('/{validacion}/urbanizacion', 'UrbanizacionController@index')->name('urbanizacion.index');
    Route::get('/urbanizacion/{id}/{pagina}/edit', 'UrbanizacionController@edit')->name('urbanizacion.edit');

    //NIVEL CIUDADANO
    Route::get('/nivel-ciudadano/all', 'NivelCiudadanoController@all')->name('ciudadano.all');
    Route::get('/nivel-ciudadano/getPageSession', 'NivelCiudadanoController@getPageSession')->name('nivel-ciudadano.getPageSession');
    Route::resource('nivel-ciudadano','NivelCiudadanoController');
    Route::get('/{validacion}/nivel-ciudadano', 'NivelCiudadanoController@index')->name('nivel-ciudadano.index');
    Route::get('/nivel-ciudadano/{id}/{pagina}/edit', 'NivelCiudadanoController@edit')->name('nivel-ciudadano.edit');

    //ROLES
    Route::get('/rol/permisos', 'RolController@getPermisos')->name('rol.permisos');
    Route::get('/rol/all', 'RolController@all')->name('rol.all');
    Route::get('/rol/getPageSession', 'RolController@getPageSession')->name('rol.getPageSession');
    Route::resource('rol','RolController');
    Route::get('/{validacion}/rol', 'RolController@index')->name('rol.index');
    Route::get('/rol/{id}/{pagina}/edit', 'RolController@edit')->name('rol.edit');

    //NACIONALIDAD
    Route::get('/nacionalidad/all', 'NacionalidadController@all')->name('nacionalidad.all');
    Route::get('/nacionalidad/getPageSession', 'NacionalidadController@getPageSession')->name('nacionalidad.getPageSession');
    Route::resource('nacionalidad','NacionalidadController');
    Route::get('/{validacion}/nacionalidad', 'NacionalidadController@index')->name('nacionalidad.index');
    Route::get('/nacionalidad/{id}/{pagina}/edit', 'NacionalidadController@edit')->name('nacionalidad.edit');

    //PERSONA
    Route::get('/persona/all', 'PersonaController@all')->name('persona.all');
    Route::get('/persona/inactives', 'PersonaController@inactivePersons')->name('persona.inactives');
    Route::get('/persona/inactive', 'PersonaController@inactivePerson')->name('persona.inactive');
    Route::put('/persona/activepersona/{persona}', 'PersonaController@active_persona')->name('persona.activepersona');
    Route::get('/persona/search', 'PersonaController@search')->name('persona.search');
    Route::get('/persona/getPageSession', 'PersonaController@getPageSession')->name('persona.getPageSession');
    Route::get('/persona/getTipoPersona', 'PersonaController@getTipoPersona')->name('persona.getTipoPersona');
    Route::get('/persona/getEstadoPersona', 'PersonaController@allestadoPersona')->name('persona.getEstadoPersona');
    Route::resource('persona','PersonaController');
    Route::get('/{validacion}/persona', 'PersonaController@index')->name('persona.index');
    Route::get('/persona/{id}/{pagina}/edit', 'PersonaController@edit')->name('persona.edit');
    //TIPO DE PERSONA
    //EXPORTAR A EXCEL
    Route::get('/persona/exportar/exportarpersonas', 'PersonaController@exportarAExcel')->name('persona.export');

    //USER
    Route::get('/user/all', 'UserController@all')->name('user.all');
    Route::get('/user/search', 'UserController@search')->name('user.search');
    Route::get('/user/getPageSession', 'UserController@getPageSession')->name('user.getPageSession');
    Route::resource('user','UserController');
    Route::get('/{validacion}/user', 'UserController@index')->name('user.index');
    Route::get('/user/{id}/{pagina}/edit', 'UserController@edit')->name('user.edit');
    //EXPORTAR A EXCEL
    Route::get('/user/exportar/exportarusers', 'UserController@exportarAExcel')->name('user.export');

    //ESTADO INCIDENTE
    Route::get('/estado-incidente/list', 'EstadoIncidenteController@list')->name('estado-incidente.list');
    Route::get('/estado-incidente/all', 'EstadoIncidenteController@all')->name('estado-incidente.all');
    Route::get('/estado-incidente/getPageSession', 'EstadoIncidenteController@getPageSession')->name('estado-incidente.getPageSession');
    Route::resource('estado-incidente','EstadoIncidenteController');
    Route::get('/{validacion}/estado-incidente', 'EstadoIncidenteController@index')->name('estado-incidente.index');
    Route::get('/estado-incidente/{id}/{pagina}/edit', 'EstadoIncidenteController@edit')->name('estado-incidente.edit');

    //TIPO INCIDENTE
    Route::get('/tipo-incidente/all', 'TipoIncidenteController@all')->name('tipo-incidente.all');
    Route::get('/tipo-incidente/getPageSession', 'TipoIncidenteController@getPageSession')->name('tipo-incidente.getPageSession');
    Route::resource('tipo-incidente','TipoIncidenteController');
    Route::get('/{validacion}/tipo-incidente', 'TipoIncidenteController@index')->name('tipo-incidente.index');
    Route::get('/tipo-incidente/{id}/{pagina}/edit', 'TipoIncidenteController@edit')->name('tipo-incidente.edit');

    //NIVEL AGUA
    Route::get('/nivel-agua/all', 'NivelAguaController@all')->name('nivel-agua.all');
    Route::get('/nivel-agua/getPageSession', 'NivelAguaController@getPageSession')->name('nivel-agua.getPageSession');
    Route::resource('nivel-agua','NivelAguaController');
    Route::get('/{validacion}/nivel-agua', 'NivelAguaController@index')->name('nivel-agua.index');
    Route::get('/nivel-agua/{id}/{pagina}/edit', 'NivelAguaController@edit')->name('nivel-agua.edit');

    //TIPO OBSTACULO
    Route::get('/tipo-obstaculo/all', 'TipoObstaculoController@all')->name('tipo-obstaculo.all');
    Route::get('/tipo-obstaculo/getPageSession', 'TipoObstaculoController@getPageSession')->name('tipo-obstaculo.getPageSession');
    Route::resource('tipo-obstaculo','TipoObstaculoController');
    Route::get('/{validacion}/tipo-obstaculo', 'TipoObstaculoController@index')->name('tipo-obstaculo.index');
    Route::get('/tipo-obstaculo/{id}/{pagina}/edit', 'TipoObstaculoController@edit')->name('tipo-obstaculo.edit');

    //INCIDENTE
    Route::get('/incidente/all', 'IncidenteController@all')->name('incidente.all');
    Route::get('/incidente/export', 'IncidenteController@exportToExcel')->name('incidente.export');
    Route::get('/incidente/attentions', 'IncidenteController@attentions')->name('incidente.attentions');
    Route::get('/incidente/{pagina}/attention', 'IncidenteController@attention')->name('incidente.attention');    
    //RUTA PARA MOSTRAR EL DETALLE DESDE ATENCION
    Route::get('/incidente/{incidente}/{pagina}/detalleatencion', 'IncidenteController@detalleatencion')->name("incidente.detalleatencion");
    //RUTA PARA MOSTRAR EL DETALLE DESDE INCIDENTE
    Route::get('/incidente/{incidente}/{pagina}/detalle', 'IncidenteController@detalle')->name('incidente.detalle');
    //OTRAS RUTAS
    Route::post('/incidente/{incidente}/registrar-coordinacion', 'IncidenteController@registrarCoordinacion')->name('incidente.registrar-coordinacion');
    Route::get('/incidente/getPageSession', 'IncidenteController@getPageSession')->name('incidente.getPageSession');
    Route::resource('incidente','IncidenteController');
    Route::get('/{validacion}/incidente', 'IncidenteController@index')->name('incidente.index');
    // Route::get('/incidente/{id}/{pagina}/edit', 'IncidenteController@edit')->name('incidente.edit');


     //EXPORTAR A EXCEL
     Route::get('/user/exportar/exportarAtenciones', 'IncidenteController@exportarAtenciones')->name('incidente.exportarAtenciones');

     //ALCALDE VECINAL
    Route::get('/alcalde-vecinal/all', 'AlcaldeVecinalController@all')->name('alcalde-vecinal.all');
    Route::get('/alcalde-vecinal/getPageSession', 'AlcaldeVecinalController@getPageSession')->name('alcalde-vecinal.getPageSession');
    Route::resource('alcalde-vecinal','AlcaldeVecinalController');
    Route::get('/{validacion}/alcalde-vecinal', 'AlcaldeVecinalController@index')->name('alcalde-vecinal.index');
    Route::get('/alcalde-vecinal/{id}/{pagina}/edit', 'AlcaldeVecinalController@edit')->name('alcalde-vecinal.edit');

    //COMITE DE GESTION
    Route::get('/comite-gestion/all', 'ComiteGestionController@all')->name('comite-gestion.all');
    Route::get('/comite-gestion/getPageSession', 'ComiteGestionController@getPageSession')->name('comite-gestion.getPageSession');
    Route::resource('comite-gestion','ComiteGestionController');
    Route::get('/{validacion}/comite-gestion', 'ComiteGestionController@index')->name('comite-gestion.index');
    Route::get('/comite-gestion/{id}/{pagina}/edit', 'ComiteGestionController@edit')->name('comite-gestion.edit');

    //DIRECTORIO
    Route::get('/directorio/all', 'DirectorioController@all')->name('directorio.all');
    Route::get('/directorio/getPageSession', 'DirectorioController@getPageSession')->name('directorio.getPageSession');
    Route::resource('directorio','DirectorioController');
    Route::get('/{validacion}/directorio', 'DirectorioController@index')->name('directorio.index');
    Route::get('/directorio/{id}/{pagina}/edit', 'DirectorioController@edit')->name('directorio.edit');

    //ACTIVIDAD PUNTUACION
    Route::get('/actividad-puntuacion/all', 'ActividadPuntuacionController@all')->name('actividad-puntuacion.all');
    Route::get('/actividad-puntuacion/getPageSession', 'ActividadPuntuacionController@getPageSession')->name('actividad-puntuacion.getPageSession');
    Route::resource('actividad-puntuacion','ActividadPuntuacionController');
    Route::get('/{validacion}/actividad-puntuacion', 'ActividadPuntuacionController@index')->name('actividad-puntuacion.index');
    Route::get('/actividad-puntuacion/{id}/{pagina}/edit', 'ActividadPuntuacionController@edit')->name('actividad-puntuacion.edit');

    //COORDINACION
    Route::get('/coordinacion/list', 'CoordinacionController@list')->name('coordinacion.list');
    Route::get('/coordinacion/all', 'CoordinacionController@all')->name('coordinacion.all');
    Route::get('/coordinacion/getPageSession', 'CoordinacionController@getPageSession')->name('coordinacion.getPageSession');
    Route::resource('coordinacion','CoordinacionController');
    Route::get('/{validacion}/coordinacion', 'CoordinacionController@index')->name('coordinacion.index');
    Route::get('/coordinacion/{id}/{pagina}/edit', 'CoordinacionController@edit')->name('coordinacion.edit');

    //NOTIFICACION
    Route::resource('notificacion','NotificacionController');

    //CONFIGURACION
    Route::get('/configuracion/all', 'ConfiguracionController@all')->name('configuracion.all');
    Route::get('/configuracion/getPageSession', 'ConfiguracionController@getPageSession')->name('configuracion.getPageSession');
    Route::resource('configuracion','ConfiguracionController');
    Route::get('/{validacion}/configuracion', 'ConfiguracionController@index')->name('configuracion.index');
    Route::get('/configuracion/{id}/{pagina}/edit', 'ConfiguracionController@edit')->name('configuracion.edit');

    //LOGS
    Route::get('/log/all', 'LogController@all')->name('log.all');
    Route::get('/log/export', 'LogController@exportToExcel')->name('log.export');
    Route::resource('log','LogController');

    //FUNCIONALIDAD BOTON DE PANICO
    Route::get('/botonpanico/getBotonPanico', 'BotonPanicoController@getBotonPanico')->name('botonpanico.getBotonPanico');
    Route::resource('botonpanico','BotonPanicoController');

    // Reportes
    Route::prefix("reportes")->group(function() {
        // Route::get('ciudadanos-puntuaciones-filtrar-filas', 'ReportesController@rankingCiudadanosPorPuntuacionNumeroFilas')->name('reportes.ranking-ciudadanos-puntuacion-numero-filas');
        Route::get("ciudadanos-puntuaciones", "ReportesController@rankingCiudadanosPorPuntuacion")->name("reportes.ranking-ciudadanos-puntuacion");
        Route::get("incidentes-atentidos", "ReportesController@listarIncidentesAtendidos")->name("reportes.incidentes-atendidos");
        Route::get("incidentes-atendidos-excel", "ReportesController@exportarIncidentesAtendidos")->name("reportes.incidentes-atendidos.excel");
        Route::get("incidentes-por-atender", "ReportesController@listarIncidentesPorAtender")->name("reportes.incidentes-por-atender");
        Route::get("incidentes-por-atender-excel", "ReportesController@exportarIncidentesPorAtender")->name("reportes.incidentes-por-atender.excel");
        Route::get("incidentes-por-tipo-incidente/{tipo_incidente_id?}/{fechaInicio?}/{fechaFin?}", "ReportesController@listarIncidentesPorTipoIncidente")->name("reportes.incidentes-por-tipo-incidente");
        Route::get("incidentes-por-tipo-incidente-excel", "ReportesController@exportarIncidentesPorTipoIncidente")->name("reportes.incidentes-por-tipo-incidente-excel");

        Route::get("ciudadanos-puntuaciones-excel", "ReportesController@exportarCiudadanosPorPuntuacion")->name("reportes.ciudadanos_puntuacion.excel");
        Route::get("incidentes-por-estado", "ReportesController@totalIncidentesPorEstadoIncidente")->name("reportes.incidentes-por-estado");
        Route::get("total-usuarios-por-tipo", "ReportesController@totalUsuarioPorTipo")->name("reportes.total-usuarios-por-tipo");
        Route::get("total-incidentes-por-estado-incidente", "ReportesController@totalIncidentesPorEstadoIncidente")->name("reportes.total-incidentes-por-estado-incidente");
        Route::get("total-metrica-general", "ReportesController@metricaGeneral")->name("reportes.metrica-general");
        Route::get("total-metricas", "ReportesController@metricas")->name("reportes.metricas");

        /* URL PARA LOS TERRITORIOS CON MAS DESCARGAS */
        Route::get("territorioMasDescarga", "ReportesController@territorioMasDescarga")->name("reportes.territorioMasDescarga");
        Route::get("exportarTerritorioMasDescarga", "ReportesController@exportarTerritorioMasDescarga")->name("reportes.exportarTerritorioMasDescarga");

        /* URL PARA LOS USUARIOS QUE MAS SE USARON EN LOS SIMULACROS */
        Route::get("usuarioMasUsoSimulacro", "ReportesController@usuarioMasUsoSimulacro")->name("reportes.usuarioMasUsoSimulacro");
        Route::get("exportarUsuarioMasUsoSimulacro", "ReportesController@exportarUsuarioMasUsoSimulacro")->name("reportes.exportarUsuarioMasUsoSimulacro");

        /* URL PARA PERSONA QUE HA USADO MAS LA APLICACION HASTA LA FECHA */
        Route::get("usuarioMasUsoAppFecha", "ReportesController@usuarioMasUsoAppFecha")->name("reportes.usuarioMasUsoAppFecha");
        Route::get("exportarUsuarioMasUsoAppFecha", "ReportesController@exportarUsuarioMasUsoAppFecha")->name("reportes.exportarUsuarioMasUsoAppFecha");

        /* URL PARA PERSONA REGISTRADA HASTA LA FECHA */
        Route::get("personas-registradas", "ReportesController@personaRegistradaFecha")->name("reportes.personaRegistradaFecha");
        Route::get("exportarPersonaRegistradaFecha", "ReportesController@exportarPersonaRegistradaFecha")->name("reportes.exportarPersonaRegistradaFecha");

        /* URL PARA PERSONA REGISTRADA HASTA LA FECHA */
        Route::get("validacionAlcaldeVecinal", "ReportesController@validacionAlcaldeVecinal")->name("reportes.validacionAlcaldeVecinal");
        Route::get("exportarValidacionAlcaldeVecinal", "ReportesController@exportarValidacionAlcaldeVecinal")->name("reportes.exportarValidacionAlcaldeVecinal");

        /* URL DE INCIDENTES A LA FECHA */
        Route::get("incidentes-registrados", "ReportesController@incidentesRegistradosFecha")->name("reportes.incidentes-registrados-fecha");
        Route::get("exportar-incidentes-registrados", "ReportesController@exportarIncidentesRegistrados")->name("reportes.exportar-incidentes-registrados");
    
        /* URL DE INCIDENTES POR CIUDADANO */
        Route::get("incidentes-por-ciudadano", "ReportesController@incidentesPorCiudadano")->name("reportes.incidentes-por-ciudadano");
        Route::get("exportar-incidentes-por-ciudadano", "ReportesController@exportarIncidentesPorCiudadano")->name("reportes.exportar-incidentes-por-ciudadano");
    
        /* URL DE USUARIOS REGISTRADOS EN TERRITORIO VECINAL */
        Route::get("ciudadanos-registrados-territorio-vecinal", "ReportesController@ciudadanosRegistradosPorTerritorioVecinal")->name("reportes.ciudadanos-registrados-territorio-vecinal");
        Route::get("exportar-ciudadanos-registrados-territorio-vecinal", "ReportesController@exportarCiudadanosRegistradosPorTerritorioVecinal")->name("reportes.exportar-ciudadanos-registrados-territorio-vecinal");
    
        /* URL DE INCIDENCIAS VALIDADAS POR ALCALDE VECINAL */
        Route::get("incidencias-validadas-por-alcalde-vecinal", "ReportesController@incidenciasValidadasAlcaldeVecinal")->name("reportes.incidencias-validadas-por-alcalde-vecinal");
        Route::get("exportar-incidencias-validadas-por-alcalde-vecinal", "ReportesController@exportarIncidenciasValidadasAlcaldeVecinal")->name("reportes.exportar-incidencias-validadas-por-alcalde-vecinal");
    
        /* URL TERRITORIOS VECINALES REGISTRADOS */
        Route::get("territorios-vecinales-registrados", "ReportesController@territoriosVecinales")->name("reportes.territorios-vecinales-registrados");
        Route::get("exportar-territorios-vecinales-registrados", "ReportesController@exportarTerritoriosVecinales")->name("reportes.exportar-territorios-vecinales-registrados");
   
        /* URL USUARIO MÁS USO EN SIMULACRO CON MÁS USO DE LA APLICACIÓN */
        Route::get("frecuencia-uso", "ReportesController@frecuenciaUso")->name("reportes.frecuencia-uso");
        Route::get("exportar-frecuencia-uso", "ReportesController@exportarFrecuenciaUso")->name("reportes.exportar-frecuencia-uso");
    });

    Route::get("/ubicacion", "FamiliarController@listarUbicaciones")->name("ubicacion.listar-ubicaciones");
    Route::get('/ubicacion/all', 'FamiliarController@ubicacionesAll')->name('ubicacion.all');
});
