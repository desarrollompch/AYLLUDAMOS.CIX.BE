<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/** api */
Route::get("listarIncidencias", "IncidenteController@getIncidentes"); // listado de incidencias

Route::get("listarPersonas", "PersonaController@getPersonas"); // obtener personas

Route::get("listarPersona/{id}", "PersonaController@getPersonaById"); // obtener persona por id

Route::post("login", "UsuarioController@logueo"); // login de usuario

Route::post("registrarUsuario", "UsuarioController@nuevoRegistroCiudadano"); // registro de ciudadano

Route::get("listarUrbanizaciones", "UrbanizacionController@getUrbanizaciones"); // listado de urbanizaciones

Route::post("actualizarPersona", "PersonaController@udpPersonaById"); // Actualizacion de datos de persona

Route::post("actualizarContrasena", "UsuarioController@udpContrasenaById"); // Actualizacion de datos de persona

Route::get("listardirectorio","DirectorioController@getDirectorio"); // Listado de Directorios

Route::post("registrarfamiliar","FamiliarController@nuevoRegistroFamiliar"); // Registro de nuevo familiar

Route::post("listarfamiliarespersona","FamiliarController@getFamiliaresbyPersona"); // Listado de Familiares de una persona

Route::post("eliminarfamiliar","FamiliarController@delFamiliarbyId"); // Eliminar familiar por ID

Route::post("estadopersona","UsuarioController@getEstadoUserbyId"); // Obtener el estado de una persona por su id

Route::get("listarnivelagua","NivelAguaController@getNivelAgua"); // Listado de los niveles de inundación

Route::get("listartipoobstaculo","TipoObstaculoController@getTipoObstaculo"); // Listar Tipos de Obstaculo

Route::post("registrarIncidencia","IncidenteController@nuevoRegistroIncidencia"); // Registro de Incidencias

Route::post("registrarMediaIncidente","IncidenteController@nuevoRegistroMediaIncidente"); // Registro de material multimedia de un incidente

Route::get("listarIncidencias/{id}", "IncidenteController@getIncidentesById"); // listado de incidencias por ID

Route::get("listarIncidencias/ciudadano/{id}", "IncidenteController@getIncidentesByCiudadano"); // listado de incidencias por Ciudadano

Route::get("ListarUbicacionFamiliar/{id}", "FamiliarController@getUbicacionFamiliarbyId"); // Listado de ubicación de una familiar por ID

Route::post("registrarUbicacionFamiliar","FamiliarController@nuevoRegistroUbicacionFamiliar"); // Registro de ubicación de un familiar

Route::get("listarmisubicaciones/{telefono}","FamiliarController@getUbicacionbyTelefono"); //Listado de mis ubicaciones registradas

Route::get("listarIncidencias/estado/{id}/{fecha_inicio?}/{fecha_final?}","IncidenteController@getIncidentesByEstadoFechas"); // Listar incidencias por estado

Route::get("listarIncidenciasSinConfirmar/alcalde/{id}","IncidenteController@getIncidentesSinConfirmarByAlcalde"); // Listar Incidencias sin confirmar por territorio vecinal de un Alcalde o jefe de accion vecinal


Route::post("actualizarAtencionIncidente","IncidenteController@updateIncidenteAtencion"); // Actualizar estado de Incidente

Route::get("listarIncidenciasValidadas/alcalde/{id}","IncidenteController@getIncidentesValidadasByAlcalde"); // Listado de Incidencias validadas por Alcalde  o jefe de un territorio vecinal

Route::get("listarConfiguracion","ConfiguracionController@getConfiguracion"); // Listado de valores de configuración

Route::post("recuperarcontrasena","UsuarioController@getContrasenabyemail"); // Recuperar contraseña por correo

Route::get("listarUrbanizaciones/alcalde", "UrbanizacionController@getUrbanizacionesbyalcaldecomite"); // listado de urbanizaciones

Route::get("listarnacionalidades","NacionalidadController@getNacionalidad"); // Listado de nacionalidades

Route::post("registroPolyline","IncidenteController@nuevoRegistroIncidenciaPolyline"); //Registro de Lineas y comentarios a las incidencias

Route::post("eliminarPolyline","IncidenteController@eliminarRegistroIncidenciaPolyline"); //Eliminar lineas de incidencias

// Nuevos servicios
Route::get("coordenadasUrbanizacion/{urbanizacion_id}/{latitude}/{longitude}", "IncidenteController@getCoordenadasUrbanizacion");
Route::delete("eliminarUbicaciones/{id}", "FamiliarController@eliminarUbicacionesFamiliar");

// Botón de pánico
Route::get("estado-boton-panico", "BotonPanicoController@comprobarEstado");
Route::post("registro-estado-boton-panico", "BotonPanicoController@registroEstadoPorUsuario");
Route::get("estado-boton-panico-user/{telefono}", "BotonPanicoController@comprobarEstadoPorUsuario");
Route::post("registro-envio-automatico-ubicacion", "EnvioAutomaticoController@registroEstadoPorUsuario");
Route::get("notificar-ubicacion-automatica", "FamiliarController@notificarUbicacionAutomatica");
// Route::post("notificar-no-ubicacion/{telefono}/{registroUbicacion}", "FamiliarController@notificarNoUbicacion");
// Route::post("registro-automatico-ubicacion", "FamiliarController@registroUbicacionFamiliarAutomatico");

// Eliminar ubicaciones masivamente
Route::delete("eliminar-ubicaciones-masivo/{ids}", "FamiliarController@eliminarUbicacionesMasivo");

// Optimización servicio incidencias
Route::get("listarIncidenciasCab", "IncidenteController@getIncidentesCab");
Route::get("listarIncidenciasDet/{id}", "IncidenteController@getIncidentesDet");

// Route::get("listarIncidenciasCab/estado/{id}/{fecha_inicio?}/{fecha_final?}","IncidenteController@getIncidentesByEstadoFechasCab");
// Route::get("listarIncidenciasDet/estado/{id}/{fecha_inicio?}/{fecha_final?}","IncidenteController@getIncidentesByEstadoFechasDet");

Route::get("listarIncidenciasCab/estado","IncidenteController@getIncidentesByEstadoFechasCab");
Route::get("listarIncidenciasDet/estado","IncidenteController@getIncidentesByEstadoFechasDet");

Route::get("listarIncidenciasCab/ciudadano/{id}", "IncidenteController@getIncidentesByCiudadanoCab");
Route::get("listarIncidenciasDet/ciudadano/{id}", "IncidenteController@getIncidentesByCiudadanoDet");

Route::get("listarIncidenciasSinConfirmarDet/alcalde/{id}","IncidenteController@getIncidentesSinConfirmarByAlcaldeDet"); 
Route::get("listarIncidenciasValidadasDet/alcalde/{id}","IncidenteController@getIncidentesValidadasByAlcaldeDet");

