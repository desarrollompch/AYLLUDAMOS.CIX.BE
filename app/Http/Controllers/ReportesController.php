<?php

namespace App\Http\Controllers;
use DB;
use Excel;
use App\PuntuacionPersona;
use App\Persona;
use App\ActividadPuntuacion;
use App\TerritorioVecinal;
use App\TipoIncidente;
use App\Incidente;
use App\EstadoIncidente;
use Session;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

use Illuminate\Http\Request;

class ReportesController extends Controller
{
    /**
     * Obtiene los datos del procedimiento pa_incidentes_atendidos
     * luego los guarda en sesión y envía a la vista
     * 
     * @return view
     */
    public function listarIncidentesAtendidos(Request $request)
    {
        $data = $request->all();
        if(isset($data['numero_filas']) && count($data['numero_filas']) > 0) {
            $numero_filas = (int)$data['numero_filas'];
        }else {
            $numero_filas = 10;
        }
        $result = DB::select('CALL pa_incidentes_atendidos()');
        Session::put('incidentesAtendidos',$result);
        $result = $this->fnPaginarDataReportes($result, $numero_filas);
        $result->setPath(route('reportes.incidentes-atendidos'));
        return view("reportes.incidentes-atendidos", compact("result", "numero_filas"));
    }

    /**
     * Genera un excel en base a los datos guardados en la sesión
     * 
     * @return excel
     */
    public function exportarIncidentesAtendidos() {
        $result = Session::get("incidentesAtendidos");
        $fecha = now();
        $filename = "incidentes-atendidos".$fecha;
        Excel::create($filename, function($excel) use ($result, $fecha) {
            $excel->sheet("Datos", function($sheet) use ($result, $fecha) {
                $sheet->mergeCells("A1:G1");
                $sheet->cell("A1", function($cell) {
                    $cell->setValue("Incidentes atendidos");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size" => 14, "bold" => true));
                });
                $sheet->mergeCells("A2:G2");
                $sheet->cell("A2", function($cell) use ($fecha) {
                    $cell->setValue("FECHA: {$fecha}");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size" => 14, "bold" => true));
                });
                $sheet->cell("A3", function($cell) {$cell->setValue("N° ORDEN"); $cell->setAlignment("center"); });
                $sheet->cell("B3", function($cell) {$cell->setValue("DIRECCIÓN"); $cell->setAlignment("center"); });
                $sheet->cell("C3", function($cell) {$cell->setValue("LATITUD"); $cell->setAlignment("center"); });
                $sheet->cell("D3", function($cell) {$cell->setValue("LONGITUD"); $cell->setAlignment("center"); });
                $sheet->cell("E3", function($cell) {$cell->setValue("URBANIZACIÓN"); $cell->setAlignment("center"); });
                $sheet->cell("F3", function($cell) {$cell->setValue("FECHA REGISTRO"); $cell->setAlignment("center"); });
                $sheet->cell("G3", function($cell) {$cell->setValue("FECHA ATENCIÓN"); $cell->setAlignment("center"); });
                if(!empty($result)) {
                    foreach($result as $key => $value) {
                        $i = $key + 4;
                        $sheet->cell('A'.$i, $key + 1);
                        $sheet->cell('B'.$i, $value->direccion);
                        $sheet->cell('C'.$i, $value->latitud);
                        $sheet->cell('D'.$i, $value->longitud);
                        $sheet->cell('E'.$i, $value->nombre_urbanizacion);
                        $sheet->cell('F'.$i, $value->fecha_registro);
                        $sheet->cell("G".$i, $value->fecha_atencion);
                    }
                }
            });
        })->download('xls');
    }

    /**
     * Obtiene los datos del procedimiento pa_incidentes_por_atender
     * luego los guarda en sesión y envía a la vista
     * 
     * @return view
     */
    public function listarIncidentesPorAtender(Request $request) {
        $data = $request->all();
        if(isset($data['numero_filas']) && count($data['numero_filas']) > 0) {
            $numero_filas = (int)$data['numero_filas'];
        }else {
            $numero_filas = 10;
        }
        $result = DB::select('CALL pa_incidentes_por_atender()');
        Session::put('incidentesPorAtender',$result);
        $result = $this->fnPaginarDataReportes($result, $numero_filas);
        $result->setPath(route('reportes.incidentes-por-atender'));
        return view("reportes.incidentes-por-atender", compact("result", "numero_filas"));

        $incidentes = $this->listarIncidentesPorEstadoIncidente(2);
        return view("reportes.incidentes-por-atender", compact("incidentes", "numero_filas"));
    }

    /**
     * Genera un excel en base a los datos guardados en la sesión
     * 
     * @return excel
     */
    public function exportarIncidentesPorAtender() {
        $result = Session::get("incidentesPorAtender");
        $fecha = now();
        $filename = "incidentes-por-atender-".$fecha;
        Excel::create($filename, function($excel) use ($result, $fecha) {
            $excel->sheet("Datos", function($sheet) use ($result, $fecha) {
                $sheet->mergeCells("A1:F1");
                $sheet->cell("A1", function($cell) {
                    $cell->setValue("Incidentes por atender");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size" => 14, "bold" => true));
                });
                $sheet->mergeCells("A2:F2");
                $sheet->cell("A2", function($cell) use ($fecha) {
                    $cell->setValue("FECHA: {$fecha}");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size" => 14, "bold" => true));
                });
                $sheet->cell("A3", function($cell) {$cell->setValue("N° ORDEN"); $cell->setAlignment("center"); });
                $sheet->cell("B3", function($cell) {$cell->setValue("FECHA"); $cell->setAlignment("center"); });
                $sheet->cell("C3", function($cell) {$cell->setValue("DIRECCIÓN"); $cell->setAlignment("center"); });
                $sheet->cell("D3", function($cell) {$cell->setValue("LATITUD"); $cell->setAlignment("center"); });
                $sheet->cell("E3", function($cell) {$cell->setValue("LONGITUD"); $cell->setAlignment("center"); });
                $sheet->cell("F3", function($cell) {$cell->setValue("URBANIZACION"); $cell->setAlignment("center"); });
                if(!empty($result)) {
                    foreach($result as $key => $value) {
                        $i = $key + 4;
                        $sheet->cell('A'.$i, $key + 1);
                        $sheet->cell('B'.$i, $value->fecha_registro);
                        $sheet->cell('C'.$i, $value->direccion);
                        $sheet->cell('D'.$i, $value->latitud);
                        $sheet->cell('E'.$i, $value->longitud);
                        $sheet->cell('F'.$i, $value->nombre_urbanizacion);
                    }
                }
            });
        })->download('xls');
    }
    
    /**
     * Obtiene los datos del procedimiento pa_ciudadanos_por_puntuacion_update
     * luego los guarda en sesión y envía a la vista
     * 
     * @return view
     */
    public function rankingCiudadanosPorPuntuacion(Request $request) {
        $data = $request->all();
        if(isset($data['numero_filas']) && count($data['numero_filas']) > 0) {
            $numero_filas = (int)$data['numero_filas'];
        }else {
            $numero_filas = 10;
        }
        // $result = DB::select('CALL pa_ciudadanos_por_puntuacion()');
        $result = DB::select("CALL pa_ciudadanos_por_puntuacion_update()");
        Session::put('ciudadanosPorPuntuacion',$result);
        $result = $this->fnPaginarDataReportes($result, $numero_filas);
        $result->setPath(route('reportes.ranking-ciudadanos-puntuacion'));
        return view("reportes.ciudadanos-por-puntuacion", compact("result", "numero_filas"));
    }

    /**
     * Genera un excel en base a los datos guardados en la sesión
     * 
     * @return excel
     */
    public function exportarCiudadanosPorPuntuacion() {
        $result = Session::get("ciudadanosPorPuntuacion");
        $fecha = now();
        $filename = "ranking_ciudadanos_por_puntuacion-".$fecha;
        Excel::create($filename, function($excel) use ($result, $fecha) {
            $excel->sheet("Datos", function($sheet) use ($result, $fecha) {
                $sheet->mergeCells("A1:H1");
                $sheet->cell("A1", function($cell) {
                    $cell->setValue("Ranking ciudadanos por puntuación");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size" => 14, "bold" => true));
                });
                $sheet->mergeCells("A2:H2");
                $sheet->cell("A2", function($cell) use ($fecha) {
                    $cell->setValue("FECHA: {$fecha}");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size" => 14, "bold" => true));
                });
                $sheet->cell("A3", function($cell) {$cell->setValue("N° ORDEN"); $cell->setAlignment("center"); });
                $sheet->cell("B3", function($cell) {$cell->setValue("ID"); $cell->setAlignment("center"); });
                $sheet->cell("C3", function($cell) {$cell->setValue("NOMBRES"); $cell->setAlignment("center"); });
                $sheet->cell("D3", function($cell) {$cell->setValue("APELLIDOS"); $cell->setAlignment("center"); });
                $sheet->cell("E3", function($cell) {$cell->setValue("ROL"); $cell->setAlignment("center"); });
                $sheet->cell("F3", function($cell) {$cell->setValue("NIVEL"); $cell->setAlignment("center"); });
                $sheet->cell("G3", function($cell) {$cell->setValue("DNI"); $cell->setAlignment("center"); });
                $sheet->cell("H3", function($cell) {$cell->setValue("PUNTAJE"); $cell->setAlignment("center"); });
                if(!empty($result)) {
                    foreach($result as $key => $value) {
                        $i = $key + 4;
                        $sheet->cell('A'.$i, $key + 1);
                        $sheet->cell("B".$i, $value->persona_id);
                        $sheet->cell('C'.$i, $value->nombres);
                        $sheet->cell('D'.$i, $value->ape_paterno." ".$value->ape_materno);
                        $sheet->cell('E'.$i, $value->nombre_rol);
                        $sheet->cell('F'.$i, $value->nivel);
                        $sheet->cell('G'.$i, $value->dni);
                        $sheet->cell("H".$i, $value->total);
                    }
                }
            });
        })->download('xls');
    }

    /**
     * Obtiene los datos del procedimiento pa_territorio_mas_descarga
     * luego los guarda en sesion y envia la vista
     *
     * @return view
     */
    public function territorioMasDescarga(Request $request){
      $fechaInicio = $request->fecha_inicio;
      $fechaFin = $request->fecha_final;
      $result = DB::select("CALL pa_territorio_mas_descarga('{$fechaInicio}','{$fechaFin}')");
      Session::put('territoriosMasDescarga',$result);
      Session::put('fechaInicioTMD',$fechaInicio);
      Session::put('fechaFinTMD',$fechaFin);
      $result = $this->fnPaginarDataReportes($result, $numero_filas = 10);
      $result->setPath(route('reportes.territorioMasDescarga'));
      return view("reportes.territorioMasDescarga", compact("result"));
    }

    /**
     * Genera un excel en base a los datos guardados en 
     * la sesion
     *
     * @return excel
     */
    public function exportarTerritorioMasDescarga(){
      $result = Session::get('territoriosMasDescarga');
      $fechaInicio = Session::get('fechaInicioTMD');
      $fechaFin = Session::get('fechaFinTMD');
      $fecha = now();
      $filename = "territorio_mas_usuarios_registrados-".$fecha;
      Excel::create($filename, function($excel) use($result,$fecha,$fechaInicio,$fechaFin){
        $excel->sheet("Datos", function($sheet) use($result,$fecha,$fechaInicio,$fechaFin){
            $sheet->mergeCells("A1:B1");
            $sheet->cell("A1", function($cell){
                $cell->setValue("Territorios con más usuarios registrados");
                $cell->setAlignment("center");
                $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
            });
            $sheet->mergeCells("A2:B2");
            $sheet->cell("A2", function($cell) use($fecha){
                $cell->setValue("FECHA GENERACIÓN : {$fecha}");
                $cell->setAlignment("center");
                $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
            });
            $sheet->cell("A3", function($cell) use($fechaInicio){
                $cell->setValue("FECHA INICIO : {$fechaInicio}");
                $cell->setAlignment("center");
                $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
            });
            $sheet->cell("B3", function($cell) use($fechaFin){
              $cell->setValue("FECHA FIN : {$fechaFin}");
              $cell->setAlignment("center");
              $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
          });
            $sheet->cell("A4", function($cell) {$cell->setValue("TERRITORIO"); $cell->setAlignment("center"); });
            $sheet->cell("B4", function($cell) {$cell->setValue("USUARIOS REGISTRADOS"); $cell->setAlignment("center"); });
            if(!empty($result)) {
              foreach($result as $key => $value) {
                $i = $key + 5;
                $sheet->cell('A'.$i, $value->descripcion);
                $sheet->cell('B'.$i, $value->descarga);
              }
            }
        });
      })->download('xls');
    }

    /**
     * Obtiene los datos del procedimiento pa_usuario_mas_uso_simulacro_test
     * los guarda en sesion y luego envia la vista
     *
     * @return view
     */
    public function usuarioMasUsoSimulacro(Request $request){
      $fecha_inicio = $request->fecha_inicio;
      $fecha_final = $request->fecha_final;
      if(isset($request->numero_filas)) {
        $numero_filas = (int)$data["numero_filas"];
      }else {
        $numero_filas = 10;
      }
      $result = DB::select("CALL pa_usuario_mas_uso_simulacro_test('{$fecha_inicio}','{$fecha_final}')");
      Session::put('usuarioMasUsoSimulacro',$result);
      Session::put('fechaInicioMUS',$fecha_inicio);
      Session::put('fechaFinMUS',$fecha_final);
      $result = $this->fnPaginarDataReportes($result, $numero_filas);
      $result->setPath(route('reportes.usuarioMasUsoSimulacro'));
      return view("reportes.usuarioMasUsoSimulacro", compact("result", "numero_filas", "fecha_inicio", "fecha_final"));
    }

        /**
     * Genera un excel en base a los datos guardados en 
     * la sesion
     *
     * @return excel
     */
    public function exportarUsuarioMasUsoSimulacro(){
      $result = Session::get('usuarioMasUsoSimulacro');
      $fechaInicio = Session::get('fechaInicioMUS');
      $fechaFin = Session::get('fechaFinMUS');
      $fecha = now();
      $filename = "usuario_mas_uso_simulacro-".$fecha;
      Excel::create($filename, function($excel) use($result,$fecha,$fechaInicio,$fechaFin){
        $excel->sheet("Datos", function($sheet) use($result,$fecha,$fechaInicio,$fechaFin){
            $sheet->mergeCells("A1:J1");
            $sheet->cell("A1", function($cell){
                $cell->setValue("Usuarios que más uso en el simulacro");
                $cell->setAlignment("center");
                $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
            });
            $sheet->mergeCells("A2:J2");
            $sheet->cell("A2", function($cell) use($fecha){
                $cell->setValue("FECHA GENERACIÓN: {$fecha}");
                $cell->setAlignment("center");
                $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
            });
            $sheet->cell("A3", function($cell) use($fechaInicio){
              $cell->setValue("FECHA INICIO: {$fechaInicio}");
              $cell->setAlignment("center");
              $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
            });
            $sheet->cell("F3", function($cell) use($fechaFin){
              $cell->setValue("FECHA FIN: {$fechaFin}");
              $cell->setAlignment("center");
              $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
            });
            $sheet->cell("A4", function($cell) {$cell->setValue("CODIGO"); $cell->setAlignment("center"); });
            $sheet->cell("B4", function($cell) {$cell->setValue("USUARIO"); $cell->setAlignment("center"); });
            $sheet->cell("C4", function($cell) {$cell->setValue("FECHA"); $cell->setAlignment("center"); });
            $sheet->cell("D4", function($cell) {$cell->setValue("USOS"); $cell->setAlignment("center"); });
            $sheet->cell("E4", function($cell) {$cell->setValue("SIN CONFIRMAR"); $cell->setAlignment("center"); });
            $sheet->cell("F4", function($cell) {$cell->setValue("CONFIRMADO"); $cell->setAlignment("center"); });
            $sheet->cell("G4", function($cell) {$cell->setValue("FALSO POSITIVO"); $cell->setAlignment("center"); });
            $sheet->cell("H4", function($cell) {$cell->setValue("ATENDIDO"); $cell->setAlignment("center"); });
            $sheet->cell("I4", function($cell) {$cell->setValue("EN PROCESO"); $cell->setAlignment("center"); });
            $sheet->cell("J4", function($cell) {$cell->setValue("PUNTAJE"); $cell->setAlignment("center"); });
            if(!empty($result)) {
              foreach($result as $key => $value) {
                $i = $key + 5;
                $sheet->cell('A'.$i, $value->id);
                $sheet->cell('B'.$i, "{$value->ape_paterno} {$value->ape_materno} {$value->nombres}");
                $sheet->cell('C'.$i, "{$value->fecha}");
                $sheet->cell('D'.$i, $value->contador);
                $sheet->cell('E'.$i, $this->fnObtenerValor($value->sin_confirmar));
                $sheet->cell('F'.$i, $this->fnObtenerValor($value->confirmado));
                $sheet->cell('G'.$i, $this->fnObtenerValor($value->falso_positivo));
                $sheet->cell('H'.$i, $this->fnObtenerValor($value->atendido));
                $sheet->cell('I'.$i, $this->fnObtenerValor($value->En_proceso));
                $sheet->cell('J'.$i, $value->puntaje);
              }
            }
        });
      })->download('xls');
    }
    
    /**
     * Obtiene los datos del procedimiento pa_persona_mas_uso_aplicacion_fecha
     * los guarda en sesion y luego envia la vista
     *
     * @return view
     */
    public function usuarioMasUsoAppFecha(Request $request){
        $data = $request->all();
        if(isset($data['numero_filas']) && count($data['numero_filas']) > 0) {
            $numero_filas = (int)$data['numero_filas'];
        }else {
            $numero_filas = 10;
        }
      $result = DB::select("CALL pa_persona_mas_uso_aplicacion_fecha()");
      Session::put('usuarioMasUsoAppFecha',$result);
      $result = $this->fnPaginarDataReportes($result, $numero_filas);
      $result->setPath(route('reportes.usuarioMasUsoAppFecha'));
      return view("reportes.usuarioMasUsoAppFecha", compact("result", "numero_filas", "fecha"));
    }

    /**
     * Genera un excel en base a los datos guardados en 
     * la sesion
     *
     * @return excel
     */
    public function exportarUsuarioMasUsoAppFecha(){
      $result = Session::get('usuarioMasUsoAppFecha');
      $fecha = now();
      $filename = "usuario_mas_uso_app_fecha-".$fecha;
      Excel::create($filename, function($excel) use($result,$fecha){
        $excel->sheet("Datos", function($sheet) use($result,$fecha){
            $sheet->mergeCells("A1:G1");
            $sheet->cell("A1", function($cell){
                $cell->setValue("Personas que ha usado mas la aplicación hasta la fecha");
                $cell->setAlignment("center");
                $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
            });
            $sheet->mergeCells("A2:G2");
            $sheet->cell("A2", function($cell) use($fecha){
                $cell->setValue("FECHA : {$fecha}");
                $cell->setAlignment("center");
                $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
            });
            $sheet->cell("A3", function($cell) {$cell->setValue("CODIGO"); $cell->setAlignment("center"); });
            $sheet->cell("B3", function($cell) {$cell->setValue("PERSONA"); $cell->setAlignment("center"); });
            $sheet->cell("C3", function($cell) {$cell->setValue("URBANIZACIÓN"); $cell->setAlignment("center"); });
            $sheet->cell("D3", function($cell) {$cell->setValue("TERRITORIO VECINAL"); $cell->setAlignment("center"); });
            $sheet->cell("E3", function($cell) {$cell->setValue("INCIDENTE"); $cell->setAlignment("center"); });
            $sheet->cell("F3", function($cell) {$cell->setValue("ESTADO"); $cell->setAlignment("center"); });
            $sheet->cell("G3", function($cell) {$cell->setValue("PUNTAJE"); $cell->setAlignment("center"); });
            if(!empty($result)) {
              foreach($result as $key => $value) {
                $i = $key + 4;
                $sheet->cell('A'.$i, $value->id);
                $sheet->cell('B'.$i, "{$value->nombres} {$value->ape_paterno} {$value->ape_materno}");
                $sheet->cell('C'.$i, $value->urbanizacion);
                $sheet->cell('D'.$i, $value->territorioVecinal);
                $sheet->cell('E'.$i, $value->incidente);
                $sheet->cell('F'.$i, $value->confirmado);
                $sheet->cell('G'.$i, $value->puntaje);
              }
            }
        });
      })->download('xls');
    }

    /**
     * Obtiene los datos del procedimiento pa_persona_registrada_fecha_update
     * los guarda en sesion y luego envia la vista
     *
     * @return view
     */
    public function personaRegistradaFecha(Request $request){
        $data = $request->all();
        if(isset($data['numero_filas']) && count($data['numero_filas']) > 0) {
            $numero_filas = (int)$data['numero_filas'];
        }else {
            $numero_filas = 10;
        }
      $result = DB::select("CALL pa_persona_registrada_fecha_update()");
      Session::put('personaRegistradaFecha',$result);
      $result = $this->fnPaginarDataReportes($result, $numero_filas);
      $result->setPath(route('reportes.personaRegistradaFecha'));
      return view("reportes.personaRegistradaFecha", compact("result", "numero_filas"));
    }

    /**
     * Genera un excel en base a los datos guardados en 
     * la sesion
     *
     * @return excel
     */
    public function exportarPersonaRegistradaFecha(){
        $result = Session::get('personaRegistradaFecha');
        $fecha = now();
        $filename = "personas_registradas_fecha-".$fecha;
        Excel::create($filename, function($excel) use($result,$fecha){
          $excel->sheet("Datos", function($sheet) use($result,$fecha){
              $sheet->mergeCells("A1:J1");
              $sheet->cell("A1", function($cell){
                  $cell->setValue("Personas registradas");
                  $cell->setAlignment("center");
                  $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
              });
              $sheet->mergeCells("A2:J2");
              $sheet->cell("A2", function($cell) use($fecha){
                  $cell->setValue("FECHA : {$fecha}");
                  $cell->setAlignment("center");
                  $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
              });
              $sheet->cell("A3", function($cell) {$cell->setValue("CODIGO"); $cell->setAlignment("center"); });
              $sheet->cell("B3", function($cell) {$cell->setValue("DNI"); $cell->setAlignment("center"); });
              $sheet->cell("C3", function($cell) {$cell->setValue("NOMBRES"); $cell->setAlignment("center"); });
              $sheet->cell("D3", function($cell) {$cell->setValue("APELLIDOS"); $cell->setAlignment("center"); });
              $sheet->cell("E3", function($cell) {$cell->setValue("FECHA REGISTRO"); $cell->setAlignment("center"); });
              $sheet->cell("F3", function($cell) {$cell->setValue("ROL"); $cell->setAlignment("center"); });
              $sheet->cell("G3", function($cell) {$cell->setValue("URBANIZACIÓN"); $cell->setAlignment("center"); });
              $sheet->cell("H3", function($cell) {$cell->setValue("TERRITORIO VECINAL"); $cell->setAlignment("center"); });
              $sheet->cell("I3", function($cell) {$cell->setValue("USUARIO"); $cell->setAlignment("center"); });
              $sheet->cell("J3", function($cell) {$cell->setValue("ESTADO"); $cell->setAlignment("center"); });
              if(!empty($result)) {
                foreach($result as $key => $value) {
                  $i = $key + 4;
                  $sheet->cell('A'.$i, $value->persona_id);
                  $sheet->cell('B'.$i, $value->dni);
                  $sheet->cell('C'.$i, $value->nombres);
                  $sheet->cell('D'.$i, $value->ape_paterno." ".$value->ape_materno);
                  $sheet->cell('E'.$i, $value->fechaRegistro);
                  $sheet->cell('F'.$i, $value->roles);
                  $sheet->cell('G'.$i, $value->urbanizacion);
                  $sheet->cell('H'.$i, $value->territorioVecinal);
                  $sheet->cell('I'.$i, $value->mail);
                  $sheet->cell('J'.$i, $value->estado);
                }
              }
          });
        })->download('xls');
    }

    /**
     * Obtiene los datos del procedimiento pa_incidentes_por_ciudadano
     * los guarda en sesion y luego envia la vista
     *
     * @return view
     */
    public function incidentesPorCiudadano(Request $request) {
        $data = $request->all();
        $parametro = "";
        
        if(isset($data['numero_filas']) && count($data['numero_filas']) > 0) {
            $numero_filas = (int)$data['numero_filas'];
        }else {
            $numero_filas = 10;
        }

        if(isset($data['parametro']) && strlen($data['parametro']) > 0) {
            $parametro = $data['parametro'];
        }

        $result = DB::select("CALL pa_incidentes_por_ciudadano('{$parametro}')");
        Session::put("incidentesPorCiudadano", $result);
        $result = $this->fnPaginarDataReportes($result, $numero_filas);
        $result->setPath(route('reportes.incidentes-por-ciudadano'));
        return view("reportes.incidentesPorCiudadano", compact("result", "numero_filas"));   
    }

    /**
     * Genera un excel en base a los datos guardados en 
     * la sesion
     *
     * @return excel
     */
    public function exportarIncidentesPorCiudadano(){
        $result = Session::get('incidentesPorCiudadano');
        $fecha = now();
        $filename = "incidentes_por_ciudadano-".$fecha;
        Excel::create($filename, function($excel) use($result,$fecha){
            $excel->sheet("Datos", function($sheet) use($result,$fecha){
                $sheet->mergeCells("A1:E1");
                $sheet->cell("A1", function($cell){
                    $cell->setValue("Incidentes por Ciudadano");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
                });
                $sheet->mergeCells("A2:E2");
                $sheet->cell("A2", function($cell) use($fecha){
                    $cell->setValue("FECHA : {$fecha}");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
                });
                $sheet->cell("A3", function($cell) {$cell->setValue("FECHA - HORA"); $cell->setAlignment("center"); });
                $sheet->cell("B3", function($cell) {$cell->setValue("DIRECCIÓN"); $cell->setAlignment("center"); });
                $sheet->cell("C3", function($cell) {$cell->setValue("URBANIZACIÓN"); $cell->setAlignment("center"); });
                $sheet->cell("D3", function($cell) {$cell->setValue("TERRITORIO VECINAL"); $cell->setAlignment("center"); });
                $sheet->cell("E3", function($cell) {$cell->setValue("ESTADO"); $cell->setAlignment("center"); });
                if(!empty($result)) {
                    foreach($result as $key => $value) {
                        $i = $key + 4;
                        $sheet->cell('A'.$i, $value->created_at);
                        $sheet->cell('B'.$i, $value->direccion);
                        $sheet->cell('C'.$i, $value->urbanizacion);
                        $sheet->cell('D'.$i, $value->territorio_vecinal);
                        $sheet->cell('E'.$i, $value->estado_incidente);
                    }
                }
            });

        })->download('xls');
    }

    /**
     * Obtiene los datos del procedimiento pa_incidentes_registrados_fecha_update
     * los guarda en sesion y luego envia la vista
     *
     * @return view
     */
    public function incidentesRegistradosFecha(Request $request){
        $estadoIncidente = EstadoIncidente::get();
        $estado_incidente_id = -1;
        $data = $request->all();
        
        if(isset($data['numero_filas']) && count($data['numero_filas']) > 0) {
            $numero_filas = (int)$data['numero_filas'];
        }else {
            $numero_filas = 10;
        }

        if(isset($data['estado_incidente']) && strlen($data['estado_incidente']) > 0) {
            $estado_incidente_id = (int)$data['estado_incidente'];
        }

        $result = DB::select("CALL pa_incidentes_registrados_fecha_update('{$estado_incidente_id}')");
        Session::put("incidentesRegistradosFecha", $result);
        $result = $this->fnPaginarDataReportes($result, $numero_filas);
        $result->setPath(route('reportes.incidentes-registrados-fecha'));
        return view("reportes.incidentesRegistradosFecha", compact("result", "numero_filas", "estadoIncidente"));
    }

    /**
     * Genera un excel en base a los datos guardados en 
     * la sesion
     *
     * @return excel
     */
    public function exportarIncidentesRegistrados(){
        $result = Session::get("incidentesRegistradosFecha");
        $fecha = now();
        $filename = "incidentes_registrados_fecha-".$fecha;
        Excel::create($filename, function($excel) use($result, $fecha) {
            $excel->sheet("Datos", function($sheet) use($result, $fecha) {
                $sheet->mergeCells("A1:i1");
                $sheet->cell("A1", function($cell){
                    $cell->setValue("Incidentes Registrados");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
                });
                $sheet->mergeCells("A2:I2");
                $sheet->cell("A2", function($cell) use($fecha){
                    $cell->setValue("FECHA : {$fecha}");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
                });
                $sheet->cell("A3", function($cell) {$cell->setValue("FECHA - HORA"); $cell->setAlignment("center"); });
                $sheet->cell("B3", function($cell) {$cell->setValue("ESTADO"); $cell->setAlignment("center"); });
                $sheet->cell("C3", function($cell) {$cell->setValue("DIRECCIÓN"); $cell->setAlignment("center"); });
                $sheet->cell("D3", function($cell) {$cell->setValue("URBANIZACIÓN"); $cell->setAlignment("center"); });
                $sheet->cell("E3", function($cell) {$cell->setValue("TERRITORIO VECINAL"); $cell->setAlignment("center"); });
                $sheet->cell("F3", function($cell) {$cell->setValue("LATITUD"); $cell->setAlignment("center"); });
                $sheet->cell("G3", function($cell) {$cell->setValue("LONGITUD"); $cell->setAlignment("center"); });
                $sheet->cell("H3", function($cell) {$cell->setValue("CIUDADANO"); $cell->setAlignment("center"); });
                $sheet->cell("I3", function($cell) {$cell->setValue("ALCALDE VECINAL"); $cell->setAlignment("center"); });
                if(!empty($result)) {
                    foreach($result as $key => $value) {
                        $i = $key + 4;
                        $sheet->cell('A'.$i, $value->created_at);
                        $sheet->cell('B'.$i, $value->estado_incidente);
                        $sheet->cell('C'.$i, $value->direccion);
                        $sheet->cell('D'.$i, $value->urbanizacion);
                        $sheet->cell('E'.$i, $value->territorio_vecinal);
                        $sheet->cell('F'.$i, $value->latitud);
                        $sheet->cell('G'.$i, $value->longitud);
                        $sheet->cell('H'.$i, $value->nombres." ".$value->ape_paterno." ".$value->ape_materno);
                        $sheet->cell('I'.$i, "");
                    }
                }
            });
        })->download('xls');
    }

    /**
     * Obtiene los datos del procedimiento pa_validacion_alcalde_vecinal
     * los guarda en sesion y luego envia la vista
     *
     * @return view
     */
    public function validacionAlcaldeVecinal(Request $request){
    //   $fechaInicio = $request->fecha_inicio;
    //   $fechaFin = $request->fecha_final;
      $fecha_inicio = $request->fecha_inicio;
      $fecha_final = $request->fecha_final;
      $data = $request->all();
        if(isset($data['numero_filas']) && count($data['numero_filas']) > 0) {
            $numero_filas = (int)$data['numero_filas'];
        }else {
            $numero_filas = 10;
        }
      $result = DB::select("CALL pa_validacion_alcalde_vecinal('{$fecha_inicio}','{$fecha_final}')");
      Session::put('validacionAlcaldeVecinal',$result);
      Session::put('fechaInicioVAV',$fecha_inicio);
      Session::put('fechaFinVAV',$fecha_final);
      $result = $this->fnPaginarDataReportes($result, $numero_filas);
      $result->setPath(route('reportes.validacionAlcaldeVecinal'));
      return view("reportes.validacionAlcaldeVecinal", compact("result", "numero_filas", "fecha_inicio", "fecha_final"));
    }

    /**
     * Genera un excel en base a los datos guardados en 
     * la sesion
     *
     * @return excel
     */
    public function exportarValidacionAlcaldeVecinal(){
      $result = Session::get('validacionAlcaldeVecinal');
      $fechaInicio = Session::get('fechaInicioVAV');
      $fechaFin = Session::get('fechaFinVAV');
      $fecha = now();
      $filename = "validacion_alcalde_vecinal-".$fecha;
      Excel::create($filename, function($excel) use($result,$fecha,$fechaInicio,$fechaFin){
        $excel->sheet("Datos", function($sheet) use($result,$fecha,$fechaInicio,$fechaFin){
            $sheet->mergeCells("A1:E1");
            $sheet->cell("A1", function($cell){
                $cell->setValue("Validaciones por alcalde vecinal");
                $cell->setAlignment("center");
                $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
            });
            $sheet->mergeCells("A2:E2");
            $sheet->cell("A2", function($cell) use($fecha){
                $cell->setValue("FECHA GENERACIÓN : {$fecha}");
                $cell->setAlignment("center");
                $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
            });
            $sheet->cell("A3", function($cell) use($fechaInicio){
              $cell->setValue("FECHA INICIO : {$fechaInicio}");
              $cell->setAlignment("center");
              $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
            });
            $sheet->cell("C3", function($cell) use($fechaFin){
              $cell->setValue("FECHA FIN : {$fechaFin}");
              $cell->setAlignment("center");
              $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
            });
            $sheet->cell("A4", function($cell) {$cell->setValue("CODIGO"); $cell->setAlignment("center"); });
            $sheet->cell("B4", function($cell) {$cell->setValue("PERSONA"); $cell->setAlignment("center"); });
            $sheet->cell("C4", function($cell) {$cell->setValue("URBANIZACIÓN"); $cell->setAlignment("center"); });
            $sheet->cell("D4", function($cell) {$cell->setValue("TERRITORIO VECINAL"); $cell->setAlignment("center"); });
            $sheet->cell("E4", function($cell) {$cell->setValue("CANTIDAD"); $cell->setAlignment("center"); });
            if(!empty($result)) {
              foreach($result as $key => $value) {
                $i = $key + 5;
                $sheet->cell('A'.$i, $value->persona_id_validador);
                $sheet->cell('B'.$i, "{$value->nombres} {$value->ape_paterno} {$value->ape_materno}");
                $sheet->cell('C'.$i, $value->urbanizacion);
                $sheet->cell('D'.$i, $value->territorioVecinal);
                $sheet->cell('E'.$i, $value->contador);
              }
            }
        });
      })->download('xls');
    }

    /**
     * Filtrar ranking ciudadanos por puntuaciones por número de filas
     * 
     */
    public function rankingCiudadanosPorPuntuacionNumeroFilas(Request $request) {
        $data = $request->all();
        return $data['numero_filas'];
    }

    /**
     * Obtiene los datos del procedimiento pa_incidentes_por_tipo_incidente
     * los guarda en sesion y luego envia la vista
     *
     * @return view
     */
    public function listarIncidentesPorTipoIncidente(Request $request) {
        $estadoIncidente = EstadoIncidente::get();
        $tipo_incidente_id = 0;
        $fecha_inicio = "";
        $fecha_final = "";
        $data = $request->all();

        if(isset($data["tipo_incidente_id"])) {
            $tipo_incidente_id = $data["tipo_incidente_id"];
        }

        if(isset($data["fecha_inicio"])) {
            $fecha_inicio = $data["fecha_inicio"];
        }

        if(isset($data["fecha_final"])) {
            $fecha_final = $data["fecha_final"];
        }

        if(isset($data['numero_filas']) && count($data['numero_filas']) > 0) {
            $numero_filas = (int)$data['numero_filas'];
        }else {
            $numero_filas = 10;
        }
        $result = DB::select("CALL pa_incidentes_por_tipo_incidente('{$tipo_incidente_id}','{$fecha_inicio}', '{$fecha_final}')");
        Session::put('incidentesPorTipoIncidente',$result);
        $result = $this->fnPaginarDataReportes($result, $numero_filas);
        $result->setPath(route('reportes.incidentes-por-tipo-incidente'));
        return view("reportes.incidentes-por-tipo-incidente", compact("result", "numero_filas", "estadoIncidente", "tipo_incidente_id", "fecha_inicio", "fecha_final"));
    }

    /**
     * Genera un excel en base a los datos guardados en 
     * la sesion
     *
     * @return excel
     */
    public function exportarIncidentesPorTipoIncidente() {
        $result = Session::get("incidentesPorTipoIncidente");
        $fecha = now();
        $filename = "incidentes-por-tipo-incidente-".$fecha;
        Excel::create($filename, function($excel) use ($result, $fecha) {
            $excel->sheet("Datos", function($sheet) use ($result, $fecha) {
                $sheet->mergeCells("A1:F1");
                $sheet->cell("A1", function($cell) {
                    $cell->setValue("Incidentes por tipo de incidente");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size" => 14, "bold" => true));
                });
                $sheet->mergeCells("A2:F2");
                $sheet->cell("A2", function($cell) use ($fecha) {
                    $cell->setValue("FECHA: {$fecha}");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size" => 14, "bold" => true));
                });
                $sheet->cell("A3", function($cell) {$cell->setValue("N° ORDEN"); $cell->setAlignment("center"); });
                $sheet->cell("B3", function($cell) {$cell->setValue("FECHA"); $cell->setAlignment("center"); });
                $sheet->cell("C3", function($cell) {$cell->setValue("DIRECCIÓN"); $cell->setAlignment("center"); });
                $sheet->cell("D3", function($cell) {$cell->setValue("URBANIZACIÓN"); $cell->setAlignment("center"); });
                $sheet->cell("E3", function($cell) {$cell->setValue("TERRITORIO VECINAL"); $cell->setAlignment("center"); });
                $sheet->cell("F3", function($cell) {$cell->setValue("ESTADO INCIDENTE"); $cell->setAlignment("center"); });
                if(!empty($result)) {
                    foreach($result as $key => $value) {
                        $i = $key + 4;
                        $sheet->cell('A'.$i, $key + 1);
                        $sheet->cell('B'.$i, $value->fecha);
                        $sheet->cell('C'.$i, $value->direccion);
                        $sheet->cell('D'.$i, $value->urbanizacion);
                        $sheet->cell('E'.$i, $value->territorio_vecinal);
                        $sheet->cell('F'.$i, $value->estado_incidente);
                    }
                }
            });
        })->download('xls');
    }

    /**
     * Obtiene los datos del procedimiento pa_usuarios_registrados_territorio_vecinal
     * los guarda en sesion y luego envia la vista
     *
     * @return view
     */
    public function ciudadanosRegistradosPorTerritorioVecinal(Request $request) {
        $fecha_inicio = "";
        $fecha_final = "";
        $data = $request->all();

        if(isset($data["fecha_inicio"])) {
            $fecha_inicio = $data["fecha_inicio"];
        }

        if(isset($data["fecha_final"])) {
            $fecha_final = $data["fecha_final"];
        }
        
        if(isset($data['numero_filas']) && count($data['numero_filas']) > 0) {
            $numero_filas = (int)$data['numero_filas'];
        }else {
            $numero_filas = 10;
        }

        $result = DB::select("CALL pa_usuarios_registrados_territorio_vecinal('{$fecha_inicio}', '{$fecha_final}')");
        Session::put('ciudadanosRegistradosPorTerritorioVecinal',$result);
        $result = $this->fnPaginarDataReportes($result, $numero_filas);
        $result->setPath(route('reportes.ciudadanos-registrados-territorio-vecinal'));
        return view("reportes.ciudadanos-registrados-por-territorio-vecinal", compact("result", "numero_filas", "fecha_inicio", "fecha_final"));
    }

    /**
     * Genera un excel en base a los datos guardados en 
     * la sesion
     *
     * @return excel
     */
    public function exportarCiudadanosRegistradosPorTerritorioVecinal() {
        $result = Session::get("ciudadanosRegistradosPorTerritorioVecinal");
        $fecha = now();
        $filename = "ciudadanos-registrados-por-territorio-vecinal-".$fecha;
        Excel::create($filename, function($excel) use ($result, $fecha) {
            $excel->sheet("Datos", function($sheet) use ($result, $fecha) {
                $sheet->mergeCells("A1:D1");
                $sheet->cell("A1", function($cell) {
                    $cell->setValue("Ciudadanos Registrados");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size" => 14, "bold" => true));
                });
                $sheet->mergeCells("A2:D2");
                $sheet->cell("A2", function($cell) use ($fecha) {
                    $cell->setValue("FECHA: {$fecha}");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size" => 14, "bold" => true));
                });
                $sheet->cell("A3", function($cell) {$cell->setValue("N°"); $cell->setAlignment("center"); });
                $sheet->cell("B3", function($cell) {$cell->setValue("ID"); $cell->setAlignment("center"); });
                $sheet->cell("C3", function($cell) {$cell->setValue("TERRITORIO VECINAL"); $cell->setAlignment("center"); });
                $sheet->cell("D3", function($cell) {$cell->setValue("TOTAL"); $cell->setAlignment("center"); });
                if(!empty($result)) {
                    foreach($result as $key => $value) {
                        $i = $key + 4;
                        $sheet->cell('A'.$i, $key + 1);
                        $sheet->cell('B'.$i, $value->territorio_vecinal_id);
                        $sheet->cell('C'.$i, $value->territorio_vecinal);
                        $sheet->cell('D'.$i, $value->total);
                    }
                }
            });
        })->download('xls');
    }

    /**
     * Obtiene los datos del procedimiento pa_incidencias_validadas_por_alcalde_vecinal
     * los guarda en sesion y luego envia la vista
     *
     * @return view
     */
    public function incidenciasValidadasAlcaldeVecinal(Request $request) {
        $fecha_inicio = "";
        $fecha_final = "";
        $data = $request->all();

        if(isset($data["fecha_inicio"])) {
            $fecha_inicio = $data["fecha_inicio"];
        }

        if(isset($data["fecha_final"])) {
            $fecha_final = $data["fecha_final"];
        }
        
        if(isset($data['numero_filas']) && count($data['numero_filas']) > 0) {
            $numero_filas = (int)$data['numero_filas'];
        }else {
            $numero_filas = 10;
        }

        $result = DB::select("CALL pa_incidencias_validadas_por_alcalde_vecinal('{$fecha_inicio}', '{$fecha_final}')");
        Session::put('incidentesValidadosAlcaldeVecinal',$result);
        $result = $this->fnPaginarDataReportes($result, $numero_filas);
        $result->setPath(route('reportes.incidencias-validadas-por-alcalde-vecinal'));
        return view("reportes.incidentes-validados-alcalde-vecinal", compact("result", "numero_filas", "fecha_inicio", "fecha_final"));
    }

    /**
     * Genera un excel en base a los datos guardados en 
     * la sesion
     *
     * @return excel
     */
    public function exportarIncidenciasValidadasAlcaldeVecinal() {
        $result = Session::get("incidentesValidadosAlcaldeVecinal");
        $fecha = now();
        $filename = "incidentes_validados_alcalde_vecinal-".$fecha;
        Excel::create($filename, function($excel) use ($result, $fecha) {
            $excel->sheet("Datos", function($sheet) use ($result, $fecha) {
                $sheet->mergeCells("A1:E1");
                $sheet->cell("A1", function($cell) {
                    $cell->setValue("Ciudadanos Registrados");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size" => 14, "bold" => true));
                });
                $sheet->mergeCells("A2:E2");
                $sheet->cell("A2", function($cell) use ($fecha) {
                    $cell->setValue("FECHA: {$fecha}");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size" => 14, "bold" => true));
                });
                $sheet->cell("A3", function($cell) {$cell->setValue("ID"); $cell->setAlignment("center"); });
                $sheet->cell("B3", function($cell) {$cell->setValue("Apellido Paterno"); $cell->setAlignment("center"); });
                $sheet->cell("C3", function($cell) {$cell->setValue("Apellido Materno"); $cell->setAlignment("center"); });
                $sheet->cell("D3", function($cell) {$cell->setValue("NOMBRES"); $cell->setAlignment("center"); });
                $sheet->cell("E3", function($cell) {$cell->setValue("N° Validaciones"); $cell->setAlignment("center"); });
                if(!empty($result)) {
                    foreach($result as $key => $value) {
                        $i = $key + 4;
                        $sheet->cell('A'.$i, $value->id);
                        $sheet->cell('B'.$i, $value->ape_paterno);
                        $sheet->cell('C'.$i, $value->ape_materno);
                        $sheet->cell('D'.$i, $value->nombres);
                        $sheet->cell('E'.$i, $value->total);
                    }
                }
            });
        })->download('xls');
    }

    /**
     * Obtiene los datos del procedimiento pa_territorios_vecinales_registrados
     * los guarda en sesion y luego envia la vista
     *
     * @return view
     */
    public function territoriosVecinales(Request $request) {
        $data = $request->all();
        
        if(isset($data['numero_filas']) && count($data['numero_filas']) > 0) {
            $numero_filas = (int)$data['numero_filas'];
        }else {
            $numero_filas = 10;
        }

        $result = DB::select("CALL pa_territorios_vecinales_registrados()");
        Session::put('territoriosVecinalesRegistrados',$result);
        $result = $this->fnPaginarDataReportes($result, $numero_filas);
        $result->setPath(route('reportes.territorios-vecinales-registrados'));
        return view("reportes.territorios-vecinales-registrados", compact("result", "numero_filas", "fecha_inicio", "fecha_final"));    
    }

    /**
     * Genera un excel en base a los datos guardados en 
     * la sesion
     *
     * @return excel
     */
    public function exportarTerritoriosVecinales() {
        $result = Session::get("territoriosVecinalesRegistrados");
        $fecha = now();
        $filename = "territorios_vecinales_registrados-".$fecha;
        Excel::create($filename, function($excel) use ($result, $fecha) {
            $excel->sheet("Datos", function($sheet) use ($result, $fecha) {
                $sheet->mergeCells("A1:D1");
                $sheet->cell("A1", function($cell) {
                    $cell->setValue("Territorios Vecinales regitrados");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size" => 14, "bold" => true));
                });
                $sheet->mergeCells("A2:D2");
                $sheet->cell("A2", function($cell) use ($fecha) {
                    $cell->setValue("FECHA: {$fecha}");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size" => 14, "bold" => true));
                });
                $sheet->cell("A3", function($cell) {$cell->setValue("ID"); $cell->setAlignment("center"); });
                $sheet->cell("B3", function($cell) {$cell->setValue("DESCRIPCIÓN"); $cell->setAlignment("center"); });
                $sheet->cell("C3", function($cell) {$cell->setValue("LATITUDE"); $cell->setAlignment("center"); });
                $sheet->cell("D3", function($cell) {$cell->setValue("LONGITUDE"); $cell->setAlignment("center"); });
                if(!empty($result)) {
                    foreach($result as $key => $value) {
                        $i = $key + 4;
                        $sheet->cell('A'.$i, $value->id);
                        $sheet->cell('B'.$i, $value->descripcion);
                        $sheet->cell('C'.$i, $value->latitude);
                        $sheet->cell('D'.$i, $value->longitude);
                    }
                }
            });
        })->download('xls');
    }

    /**
     * Obtiene los datos del procedimiento pa_usuario_mas_uso_simulacro_mas_uso_aplicacion
     * los guarda en sesion y luego envia la vista
     *
     * @return view
     */
    public function frecuenciaUso(Request $request) {
        $fecha_inicio = "";
        $fecha_final = "";
        $data = $request->all();

        if(isset($data["fecha_inicio"])) {
            $fecha_inicio = $data["fecha_inicio"];
        }

        if(isset($data["fecha_final"])) {
            $fecha_final = $data["fecha_final"];
        }
        
        if(isset($data['numero_filas']) && count($data['numero_filas']) > 0) {
            $numero_filas = (int)$data['numero_filas'];
        }else {
            $numero_filas = 10;
        }

        $result = DB::select("CALL pa_usuario_mas_uso_simulacro_mas_uso_aplicacion('{$fecha_inicio}', '{$fecha_final}')");
        Session::put('frecuenciaUso',$result);
        $result = $this->fnPaginarDataReportes($result, $numero_filas);
        $result->setPath(route('reportes.frecuencia-uso'));
        return view("reportes.frecuencia-uso", compact("result", "numero_filas", "fecha_inicio", "fecha_final"));
    }

    public function exportarFrecuenciaUso() {
        $result = Session::get("frecuenciaUso");
        $fecha = now();
        $filename = "frecuencia_uso-".$fecha;
        Excel::create($filename, function($excel) use ($result, $fecha) {
            $excel->sheet("Datos", function($sheet) use ($result, $fecha) {
                $sheet->mergeCells("A1:L1");
                $sheet->cell("A1", function($cell) {
                    $cell->setValue("Frecuencia de uso");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size" => 14, "bold" => true));
                });
                $sheet->mergeCells("A2:L2");
                $sheet->cell("A2", function($cell) use ($fecha) {
                    $cell->setValue("FECHA: {$fecha}");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size" => 14, "bold" => true));
                });
                $sheet->cell("A3", function($cell) {$cell->setValue("ID"); $cell->setAlignment("center"); });
                $sheet->cell("B3", function($cell) {$cell->setValue("APELLIDO PATERNO"); $cell->setAlignment("center"); });
                $sheet->cell("C3", function($cell) {$cell->setValue("APELLIDO MATERNO"); $cell->setAlignment("center"); });
                $sheet->cell("D3", function($cell) {$cell->setValue("NOMBRES"); $cell->setAlignment("center"); });
                $sheet->cell("E3", function($cell) {$cell->setValue("FECHA"); $cell->setAlignment("center"); });
                $sheet->cell("F3", function($cell) {$cell->setValue("INCIDENCIAS"); $cell->setAlignment("center"); });
                $sheet->cell("G3", function($cell) {$cell->setValue("SIN CONFIRMAR"); $cell->setAlignment("center"); });
                $sheet->cell("H3", function($cell) {$cell->setValue("CONFIRMADO"); $cell->setAlignment("center"); });
                $sheet->cell("I3", function($cell) {$cell->setValue("FALSO POSITIVO"); $cell->setAlignment("center"); });
                $sheet->cell("J3", function($cell) {$cell->setValue("ATENDIDO"); $cell->setAlignment("center"); });
                $sheet->cell("K3", function($cell) {$cell->setValue("EN PROCESO"); $cell->setAlignment("center"); });
                $sheet->cell("L3", function($cell) {$cell->setValue("PUNTAJE"); $cell->setAlignment("center"); });
                if(!empty($result)) {
                    foreach($result as $key => $value) {
                        $i = $key + 4;
                        $sheet->cell('A'.$i, $value->id);
                        $sheet->cell('B'.$i, $value->ape_paterno);
                        $sheet->cell('C'.$i, $value->ape_materno);
                        $sheet->cell('D'.$i, $value->nombres);
                        $sheet->cell('E'.$i, $value->fecha);
                        $sheet->cell('F'.$i, $value->incidencias);
                        $sheet->cell('G'.$i, $value->sin_confirmar);
                        $sheet->cell('H'.$i, $value->confirmado);
                        $sheet->cell('I'.$i, $value->falso_positivo);
                        $sheet->cell('J'.$i, $value->atendidos);
                        $sheet->cell('K'.$i, $value->en_proceso);
                        $sheet->cell('L'.$i, $value->puntaje);
                    }
                }
            });
        })->download('xls');
    }

    /**
     * Permite paginar los resultados obtenidos de los procedimientos
     * de los reportes
     *
     * @param array $resultados
     * @return LengthAwarePaginator $paginadoResultados
     */
    private function fnPaginarDataReportes($resultados, $numero_filas){
      $currentPage = LengthAwarePaginator::resolveCurrentPage()-1;
      $collection = new Collection($resultados);
    //   $perPage = 5;
      $perPage = $numero_filas;
      $resultadosPorPagina = $collection->slice($currentPage * $perPage, $perPage)->all();
      $paginadoResultados= new LengthAwarePaginator($resultadosPorPagina, count($collection), $perPage);
      return $paginadoResultados;
    }

    public function totalUsuarioPorTipo(){
        /*$result = DB::table("users as u")
            ->select("tp.descripcion", DB::raw("COUNT(u.id) as total"))
            ->join("persona as p", "p.id", "=", "u.persona_id")
            ->join("tipo_persona as tp", "tp.id", "=", "p.tipo_persona_id")
            ->where("u.state", "Activo")
            ->groupBy("tp.id", "tp.descripcion")
            ->get();*/

        $result = DB::table("persona as p")
            ->select("r.descripcion", DB::raw("COUNT(ur.user_id) as total"))
            ->join("users as u", "p.id", "=", "u.persona_id")
            ->join("user_role as ur", "u.id", "=", "ur.user_id")
            ->join("rol as r", "r.id", "=", "ur.role_id")
            ->where("u.state", "Activo")
            ->where("p.state", "Activo")
            ->groupBy("r.descripcion")
            ->get();

        $labels = $result->pluck("descripcion");
        $data = $result->pluck("total");
        return ["labels" => $labels, "data" => $data];
    }

    public function totalIncidentesPorEstadoIncidente(){
        $result = DB::table("estado_incidente as ei")
            ->select("ei.descripcion", DB::raw("COUNT(ei.id) as total"))
            ->join("incidente as i", "ei.id", "=", "i.estado_incidente_id")
            ->groupBy("ei.id", "ei.descripcion")
            ->orderBy("ei.id", "desc")
            ->get();

        $labels = $result->pluck("descripcion");
        $data = $result->pluck("total");
        return ["labels" => $labels, "data" => $data];
    }

    public function metricaGeneral() {
        return ["labels" => $this->metricasLabelsGeneral(), "data" => $this->metricasValoresGeneral()];
    }

    public function metricas() {
        return ["labels" => $this->metricasLabels(), "data" => $this->metricasValores()];
    }

    private function numeroTotalPorTipoPersona($tipoPersona) {
        $result = Persona::where("estado_persona_id", 1)
            ->where("tipo_persona_id", $tipoPersona)
            ->count();

        return $result;
    }

    private function numeroTotalTerritoriosVecinales() {
        $result = TerritorioVecinal::count();
        return $result;
    }

    private function numeroIncidentesValidos() {
        return $this->listarIncidentePorTipo(2);
    }

    private function numeroIncidentesNoValidos() {
        return $this->listarIncidentePorTipo(3);
    }

    private function numeroIncidentesAtentidos() {
        return $this->listarIncidentePorTipo(4);
    }

    private function metricasLabels()
    {
        $return = array();
        $return[] = "N° Ciudadanos";
        $return[] = "N° Alcaldes Vecinales";
        $return[] = "N° Territorios Vecinales";
        $return[] = "N° Incidentes confirmados";
        $return[] = "N° Incidentes falso positivo";
        $return[] = "N° Incidentes atendidos";
        return $return;
    }

    private function metricasLabelsGeneral()
    {
        $return = array();
        $return[] = "N° Incidentes confirmados";
        $return[] = "N° Incidentes atendidos";
        return $return;
    }

    private function metricasValores()
    {
        $return = array();
        $return[] = $this->numeroTotalPorTipoPersona(2);
        $return[] = $this->numeroTotalPorTipoPersona(4);
        $return[] = $this->numeroTotalTerritoriosVecinales();
        $return[] = $this->numeroIncidentesValidos();
        $return[] = $this->numeroIncidentesNoValidos();
        $return[] = $this->numeroIncidentesAtentidos();
        return $return;
    }

    private function metricasValoresGeneral()
    {
        $return = array();
        //$return[] = $this->numeroTotalPorTipoPersona(2);
        $return[] = $this->numeroIncidentesValidos();
        $return[] = $this->numeroIncidentesAtentidos();
        return $return;
    }

    private function listarIncidentePorTipo($estadoIncidente)
    {
        $result = Incidente::where("estado_incidente_id", $estadoIncidente)->count();
        return $result;
    }

    private function listarIncidentesPorEstadoIncidente($estado) {
        $incidentes = Incidente::join('estado_persona','estado_persona.id','=','incidente.estado_incidente_id')
                                ->where("incidente.estado_incidente_id", $estado)
                                ->paginate(5);
        return $incidentes;
    }

    private function fnObtenerValor($valor){
        if($valor==null || $valor==0)
          return "N/A";
        return $valor;
    }
}
