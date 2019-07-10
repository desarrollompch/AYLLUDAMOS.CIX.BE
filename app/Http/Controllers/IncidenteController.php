<?php

namespace App\Http\Controllers;

use App\CalleObstaculo;
use App\Http\Requests\IncidenteRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Inundacion;
use App\Notificacion;
use App\Polyline;
use App\Familiar;
use App\TipoIncidente;
use App\TipoObstaculo;
use App\NivelAgua;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Incidente;
use App\IncidenteMedia;
use App\AtencionIncidente;
use App\Persona;
use Illuminate\Support\Facades\DB;
use App\EstadoIncidente;
use App\Urbanizacion;
use App\TerritorioVecinal;
use App\AlcaldeVecinal;
use App\ComiteGestion;
use App\User;
use Illuminate\Support\Facades\Mail;
use Excel;
use App\ActividadPuntuacion;
use App\PuntuacionPersona;
use Session;
use App\Configuracion;

class IncidenteController extends Controller
{

      /* FUNCIONALIDADES PARA MEJORAR LA INTERACTIVIDAD */
      private function fnSetPaginaActual(){
        $paginaActual = Session::get('paginaActualInc');
        if($paginaActual==null)
          Session::put('paginaActualInc',1);
      }
    
      public function getPageSession(){
        $this->fnSetPaginaActual();
        $paginaSession = Session::get('paginaActualInc');
        return ['success'=>true , 'paginaActual'=>$paginaSession];
      } 

    public function exportToExcel(Incidente $incidente, Request $request)
    {
        $date = $request->get('date');
        $urbanizacion_id = $request->get('urbanizacion');
        $territorio_vecinal_id = $request->get('territorio');
        $estado_id = $request->get('estado');

        $incidentes = Incidente::select(
            'incidente.id',
            'incidente.fecha',
            'incidente.descripcion',
            'incidente.direccion',
            'incidente.persona_id_validador',
            'incidente.latitud',
            'incidente.longitud',
            'incidente.urbanizacion_id',
            'incidente.persona_id',
            'incidente.estado_incidente_id',
            'incidente.tipo_incidente_id',
            'incidente.imagen',
            'incidente.created_at'
        )
            ->with(['estadoIncidente', 'polylines', 'persona','urbanizacion',
                'urbanizacion.territorioVecinal', 'tipoIncidente', 'inundacion', 'inundacion.nivelAgua',
                'calleObstaculo', 'calleObstaculo.tipoObstaculo'])
            ->filterFecha($date)
            ->filterEstado($estado_id)
            ->filterUrbanizacion($urbanizacion_id)
            ->filterTerritorioVecinal($territorio_vecinal_id)
            ->get();

        $filename = "incidentes-".now();


        Excel::create($filename, function($excel) use($incidentes){
            $excel->sheet("Datos", function($sheet) use($incidentes){
                $sheet->mergeCells("A1:G1");
                $sheet->cell("A1", function($cell) {
                    $cell->setValue("Listado de Incidentes");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
                });
                $sheet->cell("A2", function($cell) {$cell->setValue("ID"); $cell->setAlignment("center"); });
                $sheet->cell("B2", function($cell) {$cell->setValue("Fecha"); $cell->setAlignment("center"); });
                $sheet->cell("C2", function($cell) {$cell->setValue("Hora"); $cell->setAlignment("center"); });
                $sheet->cell("D2", function($cell) {$cell->setValue("Territorio Vecinal"); $cell->setAlignment("center"); });
                $sheet->cell("E2", function($cell) {$cell->setValue("Urbanización"); $cell->setAlignment("center"); });
                $sheet->cell("F2", function($cell) {$cell->setValue("Descripción"); $cell->setAlignment("center"); });
                $sheet->cell("G2", function($cell) {$cell->setValue("Estado de atención"); $cell->setAlignment("center"); });
                if(!empty($incidentes)) {
                    foreach($incidentes as $key => $value) {
                        $i = $key + 3;
                        $descUrbanizacion = (is_null($value->urbanizacion)) ? "" : $value->urbanizacion->descripcion;
                        $descTerritorioVecinal = (is_null($value->urbanizacion)) ? "" : $value->urbanizacion->territorioVecinal->descripcion;
                        $descEstadoIncidente = (is_null($value->estadoIncidente)) ? "" : $value->estadoIncidente->descripcion;
                        $sheet->cell('A'.$i, $value->id);
                        $sheet->cell('B'.$i, $value->fecha);
                        $sheet->cell('C'.$i, $value->hora);
                        $sheet->cell('D'.$i, $descTerritorioVecinal);
                        $sheet->cell('E'.$i, $descUrbanizacion);
                        $sheet->cell("F".$i, $value->descripcion);
                        $sheet->cell("G".$i, $descEstadoIncidente);
                    }
                }
            });
        })->export('xlsx');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Incidente $incidente,$validacion)
    {
      $this->authorize('view', [$incidente, 'Incidente']);
      if($validacion==0)
        Session::put('paginaActualInc',1);
      if($validacion==1)
        $this->fnSetPaginaActual();
      return view('incidente.index');
    }

    public function attention(Incidente $incidente,$validacion)
    {
        $this->authorize('attention', [$incidente, 'Incidente']);
        if($validacion==0)
          Session::put('paginaActualInc',1);
        if($validacion==1)
          $this->fnSetPaginaActual();
        return view('incidente.attention');
    }

    public function all(Incidente $incidente, Request $request)
    {
        $this->authorize('view', [$incidente, 'Incidente']);

        $date = $request->get('date');
        $urbanizacion_id = $request->get('urbanizacion');
        $territorio_vecinal_id = $request->get('territorio');
        $estado_id = $request->get('estado');

        $incidentes = Incidente::select(
            'incidente.id',
            'incidente.fecha',
            'incidente.descripcion',
            'incidente.direccion',
            'incidente.persona_id_validador',
            'incidente.latitud',
            'incidente.longitud',
            'incidente.urbanizacion_id',
            'incidente.persona_id',
            'incidente.estado_incidente_id',
            'incidente.tipo_incidente_id',
            'incidente.imagen',
            'incidente.created_at'
        )
            ->with(['estadoIncidente', 'polylines', 'persona','urbanizacion',
            'urbanizacion.territorioVecinal', 'tipoIncidente', 'inundacion', 'inundacion.nivelAgua',
            'calleObstaculo', 'calleObstaculo.tipoObstaculo','atencionIncidente','atencionIncidente.persona', 'incidentesmedia'])
            ->filterFechaFormat($date)
            ->filterEstado($estado_id)
            ->filterUrbanizacion($urbanizacion_id)
            ->filterTerritorioVecinal($territorio_vecinal_id)
            ->orderBy("fecha", "DESC")
            ->get();

        $currentPage = LengthAwarePaginator::resolveCurrentPage()-1;
        $collection = new Collection($incidentes);
        $perPage = 10;
        $resultadosPorPagina = $collection->slice($currentPage * $perPage, $perPage)->all();
        $paginadoResultados= new LengthAwarePaginator($resultadosPorPagina, count($collection), $perPage);

        $pagination = [
            'total'             => $paginadoResultados->total(),
            'current_page'      => $paginadoResultados->currentPage(),
            'per_pague'         => $paginadoResultados->perPage(),
            'last_page'         => $paginadoResultados->lastPage(),
            'from'              => $paginadoResultados->firstItem(),
            'to'                => $paginadoResultados->lastItem()
        ];

        return ["success" => true, "incidentes" => $paginadoResultados, "pagination" => $pagination];
        // return ['success' => true, 'incidentes' => $incidentes];
    }

    public function attentions(Incidente $incidente, Request $request)
    {
        $this->authorize('attention', [$incidente, 'Incidente']);

        $date = $request->get('date');
        $estado_id = $request->get('estado');

        $incidentes = Incidente::with(['estadoIncidente', 'polylines', 'persona','urbanizacion',
            'urbanizacion.territorioVecinal', 'tipoIncidente', 'inundacion', 'inundacion.nivelAgua',
            'calleObstaculo', 'calleObstaculo.tipoObstaculo', 'atencionIncidente', 'coordinaciones', 'incidentesmedia'])
            ->whereIn('estado_incidente_id', [1,2,4,5])
            ->filterFecha($date)
            ->filterEstado($estado_id)
            ->orderBy("created_at", "DESC")
            ->get();

        $currentPage = LengthAwarePaginator::resolveCurrentPage()-1;
        $collection = new Collection($incidentes);
        $perPage = 10;
        $resultadosPorPagina = $collection->slice($currentPage * $perPage, $perPage)->all();
        $paginadoResultados= new LengthAwarePaginator($resultadosPorPagina, count($collection), $perPage);

        $pagination = [
            'total'             => $paginadoResultados->total(),
            'current_page'      => $paginadoResultados->currentPage(),
            'per_pague'         => $paginadoResultados->perPage(),
            'last_page'         => $paginadoResultados->lastPage(),
            'from'              => $paginadoResultados->firstItem(),
            'to'                => $paginadoResultados->lastItem()
        ];

        return ['success'=>true , 'incidentes'=>$paginadoResultados, "pagination" => $pagination];
        // return ['success' => true, 'incidentes' => $incidentes];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Incidente $incidente)
    {
        $this->authorize('create', [$incidente, 'Incidente']);

        return view('incidente.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Incidente $incidente)
    {
        $this->authorize('view', [$incidente, 'Incidente']);

        return view('incidente.edit');
    }

    public function detalle(Incidente $incidente,$pagina)
    {
      $this->authorize('view', [$incidente, 'Incidente']);
      Session::put('paginaActualInc',$pagina);
      return view('incidente.detalle');
    }

    public function detalleatencion(Incidente $incidente,$pagina)
    {
      $this->authorize('attentiondetalle', [$incidente, 'Incidente']);
      Session::put('paginaActualInc',$pagina);
      return view('incidente.detalleatencion');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Incidente $incidente,IncidenteRequest $request)
    {
        $this->authorize('create', [$incidente, 'Incidente']);

        DB::beginTransaction();

        try {
            $data = $request->all();

            // Se agrega este artificio para poder guardar la fecha con el detalle de la hora registrada
            $hms = Carbon::now(); 
            $hms = $hms->format('h:i:s');
            $data['fecha'] = Carbon::createFromTimeString($data['fecha'])->format('Y-m-d').' '.$hms;

            $incidente = Incidente::create($data);

            if (isset($data['imagen_file']) && isset($data['imagen_file']['value'])) {
                $this->setImageFile($incidente, $data);
            }

            if ($data['tipo_incidente_id'] == 1) {
                Inundacion::create([
                    'tipo_inundacion'=>$data['inundacion']['nivel_agua_id'],
                    'incidente_id'=>$incidente->id,
                    'nivel_agua_id'=>$data['inundacion']['nivel_agua_id']
                ]);

            } elseif ($data['tipo_incidente_id'] == 2) {
                CalleObstaculo::create([
                    "incidente_id"=>$incidente->id,
                    "tipo_obstaculo_id"=>$data['calle_obstaculo']['tipo_obstaculo_id']
                ]);
            }

            foreach ($data['polylines'] as $polyline)
            {
                Polyline::create([
                    'incidente_id'=> $incidente->id,
                    'coordinates'=>$polyline['coordinates'],
                    'descripcion'=>$polyline['descripcion']

                ]);
            }

            Notificacion::create([
                'incidente_id'=>$incidente->id,
                'state'=>1,
                'descripcion'=>'Nueva Incidencia'
            ]);

            $emails = User::join('user_role', 'user_role.user_id', '=', 'users.id')
                                    ->where('user_role.role_id', 8)
                                    ->pluck('users.email');
            $territorio_vecinal = $incidente->urbanizacion->territorioVecinal;

            $alcaldes = $territorio_vecinal->alcaldes;

            foreach ($alcaldes as $alcalde)
            {
                $emails[] = $alcalde->persona->mail;
            }

            Mail::send('emails.notify', ['incidente' => $incidente, 'titulo'=>'Nuevo Incidente Registrado'], function ($m) use ($emails) {
                $m->from('aylludame@app.com', 'Administrador');
                $m->subject('Nuevo Incidente / '.date('d-m-y'));
                foreach ($emails as $email) {
                    $m->to($email);
                }
            });

            $linkToModel = '"<a href="'.route('incidente.detalle', ['incidente'=>$incidente->id,'pagina'=>1]).'">Incidente</a>"';

            activity()->log("Se creó un nuevo {$linkToModel}");

            DB::commit();

            return ['success' => true, 'emails'=>$emails];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception' => $exception->getMessage()];

        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Incidente $incidente)
    {
        $this->authorize('view', [$incidente, 'Incidente']);

        $incidente = Incidente::with(['estadoIncidente', 'polylines', 'persona','urbanizacion',
            'urbanizacion.territorioVecinal', 'tipoIncidente', 'inundacion', 'inundacion.nivelAgua',
            'calleObstaculo', 'calleObstaculo.tipoObstaculo', 'atencionIncidente', 'atencionIncidente.persona', 'coordinaciones', 'incidentesmedia'])->where('id', $incidente->id)->first();

        return ['sucess' => true, 'incidente' => $incidente];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Incidente $incidente, IncidenteRequest $request)
    {
        $this->authorize('update', [$incidente, 'Incidente']);

        DB::beginTransaction();

        try {
            $data = $request->all();

            $incidente->update($data);

            $incidente->polylines()->delete();

            foreach ($data['polylines'] as $polyline)
            {
                Polyline::create([
                    'incidente_id'=> $incidente->id,
                    'coordinates'=>$polyline['coordinates'],
                    'descripcion'=>$polyline['descripcion']
                ]);
            }

            $linkToModel = '"<a href="'.route('incidente.detalle', ['incidente'=>$incidente->id]).'">Incidente</a>"';

            activity()->log("Se actualizó el {$linkToModel}");

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception'=>$exception->getMessage()];

        }
    }


    public function setImageFile($incidente, $data)
    {

        $file = base64_decode($data['imagen_file']['value']);
        $extension = explode('.', $data['imagen_file']['filename']);
        $index = count($extension);
        //$fileName = seoUrl($incidente->descripcion . '_imagen_' . substr(md5(uniqid(rand(), true)), 0, 10)) . '.' . $extension[$index - 1];
        $fileName = seoUrl('imagen_incidente_' . substr(md5(uniqid(rand(), true)), 0, 10)) . '.' . $extension[$index - 1];

        \Storage::disk('public')->put('/images/incidentes/' . $fileName, $file);

        $exists = \Storage::disk('public')->exists('images/incidentes/' . $incidente->imagen);

        if ($exists && !empty($incidente->imagen)) {
            \Storage::disk('public')->delete('images/incidentes/' . $incidente->imagen);
        }

        //$incidente->imagen = $fileName;
        //$incidente->save();

        $file_path = \Storage::url('/images/incidentes/'.$fileName);
        $url = asset($file_path);

        // Instanciando Incidente Media
        $incidente_media = new IncidenteMedia();

        // Asignando valores a los campos de Incidente Media
        $incidente_media->incidente_id = $incidente->id;
        $incidente_media->tipo_media = isset($data['tipo_media'])?$data['tipo_media'] : "imagen";
        $incidente_media->incidente_media_name = $fileName; 
        $incidente_media->incidente_media_url = $url; 
        
        // Registrando un Incidente Media
        $incidente_media->save(); 

    }

    public function registrarCoordinacion(Incidente $incidente, Request $request)
    {
        $this->authorize('update', [$incidente, 'Incidente']);

        DB::beginTransaction();

        try {
            $data = $request->all();

            $incidente->coordinaciones()->attach($data['coordinacion_id'], ['user_id'=>auth()->user()->id]);

            AtencionIncidente::create([
                "persona_id"=>auth()->user()->persona_id,
                "incidente_id"=>$incidente->id,
                "fecha"=>date('Y-m-d H:i:s'),
                "descripcion"=>$data['descripcion']
            ]);

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception'=>$exception->getMessage()];

        }

    }


    // Registrar Media para incidentes
    // JJDCH 30062018
    public function setMediaFile($incidente, $data)
    {

        $file = base64_decode($data['media_file']);
        $extension = explode('.', $data['incidente_media_name']);
        $index = count($extension);
        $fileName = seoUrl('imagen_incidente_' . substr(md5(uniqid(rand(), true)), 0, 10)) . '.' . $extension[$index - 1];

        \Storage::disk('public')->put('/images/incidentes/' . $fileName, $file);

        /*$exists = \Storage::disk('public')->exists('images/incidentes/' . $incidente->imagen);

        if ($exists && !empty($incidente->imagen)) {
            \Storage::disk('public')->delete('images/incidentes/' . $incidente->imagen);
        }*/

        // Obteniendo ruta de imagen
        $file_path = \Storage::url('/images/incidentes/'.$fileName);
        $url = asset($file_path);

        // Instanciando Incidente Media
        $incidente_media = new IncidenteMedia();

        // Asignando valores a los campos de Incidente Media
        $incidente_media->incidente_id = $incidente->id;
        $incidente_media->tipo_media = $data['tipo_media'];
        $incidente_media->incidente_media_name = $fileName; 
        $incidente_media->incidente_media_url = $url; 
        
        // Registrando un Incidente Media
        $incidente_media->save();       

    }

    /** -- api -- */
    // Registrar Incidencia
    // JJDCH 29062018
    public function nuevoRegistroIncidencia(Request $request)
    {
        DB::beginTransaction();
        try {
            // Obtengo todos los valores
            $data = $request->all();        
            // variable que permitirá saber si se puede notificar a un alcalde cuando se registra una incidencia.
            // Teniendo en cuenta que si lo registra un alcalde esta pasara como validada y no es necesario notificar
            $bNotifyAlcalde = true;    

            //######### 20062019 JJDCH: Agregamos validaciones para que no se registre información errada
            $error = false;
            $msje = "";

            if (!isset($data['descripcion']) || empty($data['descripcion'])) {                
                $error = false;
                $msje = 'Error: La descripcion es un campo obligatorio';
            } elseif (!isset($data['direccion']) || empty($data['direccion'])) {
                $error = false;
                $msje = 'Error: La direccion es un campo obligatorio';
            } elseif (!isset($data['latitud']) || empty($data['latitud'])) {
                $error = false;
                $msje = 'Error: La latitud es un campo obligatorio';
            } elseif (!isset($data['longitud']) || empty($data['longitud'])) {
                $error = false;
                $message["mensaje"] = 'Error: La longitud es un campo obligatorio';
            } elseif (!isset($data['urbanizacion_id']) || empty($data['urbanizacion_id']) || $data['urbanizacion_id'] == 0) {
                $error = false;
                $msje = 'Error: La urbanizacion es un campo obligatorio';
            } elseif (!isset($data['persona_id']) || empty($data['persona_id']) || $data['persona_id'] == 0) {
                $error = false;
                $msje = 'Error: La persona que registra el incidente es un campo obligatorio';
            } elseif (!isset($data['tipo_usuario']) || empty($data['tipo_usuario']) || $data['tipo_usuario'] == 0) {
                $error = false;
                $msje = 'Error: El tipo de usuario es un campo obligatorio';
            } elseif (!isset($data['estado_incidente_id']) || empty($data['estado_incidente_id']) || $data['estado_incidente_id'] == 0) {
                $error = false;
                $msje = 'Error: El estado de un incidente es un campo obligatorio';
            } elseif (!isset($data['tipo_incidente_id']) || empty($data['tipo_incidente_id']) || $data['tipo_incidente_id'] == 0) {
                $error = false;
                $msje = 'Error: El tipo de incidente es un campo obligatorio';
            } elseif ($data['tipo_incidente_id'] == 1 && ( !isset($data['inundacion']['nivel_agua_id']) || empty($data['inundacion']['nivel_agua_id']) || $data['inundacion']['nivel_agua_id'] == 0)) {
                $error = false;
                $msje = 'Error: El nivel del agua es un campo obligatorio';
            } elseif ($data['tipo_incidente_id'] == 2 && ( !isset($data['calle_obstaculo']['tipo_obstaculo_id']) || empty($data['calle_obstaculo']['tipo_obstaculo_id']) || $data['calle_obstaculo']['tipo_obstaculo_id'] == 0)) {
                $error = false;
                $msje = 'Error: El tipo de obstaculo es un campo obligatorio';
            } else {
                $error = True; 
            }

            if ($error == false) {
                $message["success"] = $error;
                $message["mensaje"] = $msje;
                return response()->json($message);

            }else{

                $data['rol_id'] = $data['tipo_usuario'];

                $incidente = Incidente::create($data);

                foreach ($data['media'] as $media) {
                    if (isset($media['incidente_media_name']) && isset($media['media_file'])) {
                        $this->setMediaFile($incidente, $media);    
                    }
                }

                if ($data['tipo_incidente_id'] == 1) {
                    Inundacion::create([
                        'tipo_inundacion'=>$data['inundacion']['nivel_agua_id'],
                        'incidente_id'=>$incidente->id,
                        'nivel_agua_id'=>$data['inundacion']['nivel_agua_id']
                    ]);

                } elseif ($data['tipo_incidente_id'] == 2) {
                    CalleObstaculo::create([
                        "incidente_id"=>$incidente->id,
                        "tipo_obstaculo_id"=>$data['calle_obstaculo']['tipo_obstaculo_id']
                    ]);
                }

                

                //Aquí se debe de validar si el incidente ha sido registrado por un alcalde o comite de gestion
                //para que se registre como validado y sume los puntos.
                
                // Verificamos si la persona tiene el rol de alcalde vecinal o comité de gestión
                $persona = Persona::find($data["persona_id"]);
                $usuario = $persona->nombres." ".$persona->ape_paterno." ".$persona->ape_materno;
                // $rol_id = $persona->rol_id;
                $rol_id = $persona->user->rol_id;

                if($rol_id == 3 || $rol_id == 4) {
                    // Al ser alcalde vecinal o cómite de gestión el estado se setea por defecto a 2 = confirmado
                    // $estado_incidente_id = $data["estado_incidente_id"];
                    $estado_incidente_id = 2;
                    //Procedo Actualizar el estado del incidente y quien valida dicha incidencia
                    $incidente->persona_id_validador = $persona->id;
                    $incidente->estado_incidente_id = $estado_incidente_id;
                    $incidente->save();

                    // Verificamos si el estado del nuevo incidente es confirmado
                    if($estado_incidente_id == 2) {

                        // Seteamos variable para que ya no notifique a un alcalde.
                        $bNotifyAlcalde = false;

                        $actividad_puntuacion = ActividadPuntuacion::where('estado_incidente_id', $estado_incidente_id)
                            ->where('descripcion','Incidente Reportado')->first();
                        
                        PuntuacionPersona::create([
                            "numero_incidente"          => $incidente->id,
                            "actividad_puntuacion_id"   => $actividad_puntuacion->id,
                            "persona_id"                => $incidente->persona_id
                        ]);

                        $puntuacion_persona = PuntuacionPersona::where("persona_id", $persona->id)->get();
                        
                        // Variable que guarda la puntuación por persona
                        $puntos_persona = 0;

                        // Obtenemos los puntos del usuario
                        foreach ($puntuacion_persona as $puntos) {
                            $actividad_puntuacion = ActividadPuntuacion::where("id",$puntos->actividad_puntuacion_id)->first();
                            $puntos_persona += $actividad_puntuacion->puntaje;
                        }

                        // Actualizamos el nivel_ciudadano_id por persona
                        // de acuerdo a la puntuación
                        if($puntos_persona > 0 && $puntos_persona <= 100) {
                            $persona->nivel_ciudadano_id = 1;
                            $persona->save();
                        }else if($puntos_persona >= 101 && $puntos_persona <= 200) {
                            $persona->nivel_ciudadano_id = 2;
                            $persona->save();
                        }else if($puntos_persona >= 201 && $puntos_persona <= 300) {
                            $persona->nivel_ciudadano_id = 3;
                            $persona->save();
                        }else if($puntos_persona >= 301 && $puntos_persona <= 400) {
                            $persona->nivel_ciudadano_id = 4;
                            $persona->save();
                        }
                    }
                }

                $notify = "";
                $notifyFamiliar = "";
                // Obtenemos la data del incidente
                $incidentenotify = Incidente::with(['TipoIncidente'])
                                ->where('id',$incidente->id)
                                ->first();


                if ($bNotifyAlcalde == true)
                {
                    // Aca debe de enviarse la notificación al alcalde
                    // Obtenemos el territorio vecinal en función a la urbanización
                    $territoriovecinal = urbanizacion::where('id',$incidente->urbanizacion_id)->first();

                    // Obtenemos los alcaldes y el comite vecinal para enviarle la notificación
                    $alcalde = AlcaldeVecinal::with(['Persona','Persona.User'])
                                ->where('territorio_vecinal_id',$territoriovecinal->territorio_vecinal_id);

                    $alcalde_comite = ComiteGestion::with(['Persona','Persona.User'])
                                        ->where('territorio_vecinal_id',$territoriovecinal->territorio_vecinal_id)
                                        ->union($alcalde)       
                                        ->get();

                    $token_alcalde_comite[] = '';
                    // Paso a los usuarios de los alcaldes y comites a un array
                    foreach ($alcalde_comite as $item)
                    {
                        if (!empty($item['Persona']['User']->token)) {
                            $token_alcalde_comite[] = $item['Persona']['User']->token;
                        }               
                    }                

                    // Enviamos notificacion a los alcaldes
                    if (count($token_alcalde_comite) != 0) {
                       $notify = $this->send_notificacion_movil($token_alcalde_comite,$incidentenotify,'Validar Incidente');
                    }
                     
                }

                           

                // CJJAB - 12/10/2018
                //ENVIO DE NOTIFICACIONES A FAMILIARES
                //PLATAFORMA PARA FAMILIARES = Otros
                // Seteamos valores
                $incidentenotify->id = 0;
                $incidentenotify->TipoIncidente->descripcion = $usuario." registro un incidente ".$incidentenotify->TipoIncidente->descripcion;
                $notifyFamiliar = $this->fnCrearNotificacionFamiliar($data["persona_id"],$incidentenotify,'Otros');  

                DB::commit();   

                $message["success"] = true;
                $message["mensaje"] = "El incidente se registro correctamente";
                $message["notificacion"] = $notify;
                $message["notificacionFamiliar"] = $notifyFamiliar;

                return response()->json($message);

            }   

        } catch (\Exception $exception) {
            DB::rollBack();

            //return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception' => $exception->getMessage()];
            $message["success"] = false;
            $message["mensaje"] = 'Hubo un error, intente nuevamente.';
            $message["exception"] = $exception->getMessage();

            return response()->json($message);

        }
        
    }

    /* FUNCIONALIDAD ENVIAR NOTIFICACION A FAMILIARES */
    private function fnCrearNotificacionFamiliar($idPersona,$incidente,$plataforma){
      $familiares = $this->fnObtenerFamiliares($idPersona);
      $tokenFamiliar = $this->fnObtenerTokenFamiliar($familiares);
      $notificacion = $this->send_notificacion_movil($tokenFamiliar,$incidente,$plataforma);
      return $notificacion;
    }

    //FUNCION QUE PERMITE OBTENER LOS FAMILIARES SEGUN EL CODIGO DE PERSONA
    private function fnObtenerFamiliares($idPersona){
      $familiares = Familiar::where('persona_id',$idPersona)->get();
      return $familiares;
    }

    //FUNCIONALIDAD QUE PERMITE OBTENER LOS TOKEN DE LOS FAMILIARES QUE ESTEN REGISTRADOS 
    //EN A APLICACION
    private function fnObtenerTokenFamiliar($familiares){
      $tokenFamiliar = [];
      foreach($familiares as $familiar){
        $persona = Persona::select('id')->where('telefono',$familiar->telefono)->first();
        if($persona){
          $user = User::select('token')->where('id',$persona['id'])->first();
          if($user){
            $tokenFamiliar[] = $user->token; 
          }
        }
      }
      return $tokenFamiliar;
    }

    /* FUNCIONALIDAD PARA MANDAR NOTIFICACIONES */
    public function send_notificacion_movil($tokens, $incidente,$plataforma)
    {
        //key de firebase
        if (!defined('FIREBASE_API_KEY')) define('FIREBASE_API_KEY', 'AAAAvWiPr60:APA91bGhcS8uSNjl4bAkmFA9Ovg_ZbRNuv1hV6PBQvxnuNg93jiLP5HGBpQIK2Gqiv8SHqfzibs94Xc46rH2RT33djVhwv1AE8KiHY5Quj-Pc54fB3sWkEVvgLZlBKFQ9wV9DPojtSAW');

        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
        'Authorization: key=' . FIREBASE_API_KEY,
        'Content-Type: application/json'
        );

        //Aqui van los token del alcalde o colaboradores a quien le va a allegar la notificacion
        $registrationIDs = $tokens;
        //array( "cqdA2yE-rEE:APA91bFg5neRdtPM-G-yxdpxoZgjbgR_1UlkPPP25Mhva6T8RCZx4NuIqc4Sj1FYWMs2YO76ek5xrCJu_b3db_X96Ij7Lzpt8vBxdHyH5A-Kyg5oyMcZY9dCUJxtWBgo-CinxmpE8b_3tJdnqz8CXvJ9punJPDPjgg");

        $priority = "normal";
        $res = array();
        $res['data']['title'] = $incidente->TipoIncidente->descripcion;//"INUNDACION " ;//ESTE ES EL TITULO DE LA OTIFICACION
        $res['data']['message'] =  $incidente->direccion; //DESCIPCION DE LA NOTIFICACION
        $res['data']['timestamp'] = date('Y-m-d H:i a');
        $res['data']['plataforma'] = $plataforma;  //plataforma -> siempre sera eda
        $res['data']['incidencia'] = $incidente->id;  //id del incidente registrado

        $fields = array(    'registration_ids'  => $registrationIDs,
        'priority'  => $priority,
        'data' => $res
        );

        //echo json_encode($fields);

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($fields) );

        $result = curl_exec($ch);
        if(curl_errno($ch)){ echo 'Curl error: ' . curl_error($ch); }
        curl_close($ch);
        return $result;
    }

    /** -- api -- */
    // Registrar Material multimedia de un incidente
    // JJDCH 29062018
    public function nuevoRegistroMediaIncidente(Request $request)
    {
        DB::beginTransaction();

        try {
            // Obtengo todos los valores
            $data = $request->all();            

            $incidente = Incidente::find($data["id_incidente"]);

            foreach ($data['media'] as $media) {
                if (isset($media['incidente_media_name']) && isset($media['media_file'])) {
                    $this->setMediaFile($incidente, $media);    
                }
            }

            DB::commit();

            $message["success"] = true;
            $message["mensaje"] = "Material multimedia del incidente registrado correctamente"; 

            return response()->json($message);

        } catch (\Exception $exception) {
            DB::rollBack();

            $message["success"] = false;
            $message["mensaje"] = 'Hubo un error, intente nuevamente.';
            $message["exception"] = $exception->getMessage();

            return response()->json($message);

        }
    }

    /** -- api -- */
    // Listar Incidencias
    // JJDCH 30062018
    public function getIncidentes(Request $request)
    {
        $data = $request->all();
        if(!is_null($data) && count($data) > 0 && !is_null($data['fecha_inicio']) && !is_null($data["fecha_final"]) && !is_null($data["estados"])) {
            $fecha_inicio = Carbon::parse($data["fecha_inicio"]);
            $fecha_final = Carbon::parse($data["fecha_final"]);
            $estados = (strlen($data['estados']) > 0) ? explode("-", $data['estados']) : $data["estados"];
            $incidente = Incidente::whereBetween("fecha", [$fecha_inicio, $fecha_final])->whereIn("estado_incidente_id", $estados)->get();
        }else if(!is_null($data) && count($data) > 0 && !is_null($data['fecha_inicio']) && !is_null($data["fecha_final"]) && is_null($data["estados"])){
            $fecha_inicio = Carbon::parse($data["fecha_inicio"]);
            $fecha_final = Carbon::parse($data["fecha_final"]);
            $incidente = Incidente::whereBetween("fecha", [$fecha_inicio, $fecha_final])->get();
        }else if(!is_null($data) && count($data) > 0 && is_null($data["fecha_inicio"]) && is_null($data["fecha_final"]) && !is_null($data["estados"])){
            $estados = (strlen($data['estados']) > 0) ? explode("-", $data['estados']) : $data["estados"];
            $incidente = Incidente::whereIn("estado_incidente_id", $estados)->get();
        }else {
            $fecha_actual = Carbon::now()->toDateString();
            //$incidente = Incidente::where("fecha", $fecha_actual)->get();

            $ndias_antiguedad = Configuracion::where("nombre", "numero_dias_ver_incidentes")->first();
            $fecha_pasada = Carbon::now()->subDays($ndias_antiguedad->valor)->toDateString();
            $incidente = Incidente::FilterFechaRango($fecha_pasada,$fecha_actual)->get();
        }
        
        // Objetos
        $persona = Persona::get();

        if($incidente != null && count($incidente) > 0)
        {
            // Devolución de campos
            foreach ($incidente as $data) {

                $persona = Persona::find($data["persona_id"]);
                $tipo_incidente = TipoIncidente::find($data["tipo_incidente_id"]);
                $media_incidente = IncidenteMedia::where("incidente_id", $data["id"])->get();
                $atencion_incidente = AtencionIncidente::where("incidente_id", $data["id"])->get();              
                $estado_incidente = EstadoIncidente::find($data["estado_incidente_id"]);
                $Urbanizacion = Urbanizacion::find($data["urbanizacion_id"]);
                $territoriovecinal = TerritorioVecinal::find($Urbanizacion["territorio_vecinal_id"]);
                $polyline = Polyline::where("incidente_id", $data["id"])->get();

                if ($data["tipo_incidente_id"]==1)
                {
                    $detalle_incidencia = Inundacion::where("incidente_id", $data["id"])->first();
                    $nivel_agua = NivelAgua::find($detalle_incidencia['nivel_agua_id']);
                    $detalle_incidencia['nivel_agua'] = $nivel_agua->descripcion;  
                }
                elseif ($data["tipo_incidente_id"]==2)
                {
                    $detalle_incidencia = CalleObstaculo::where("incidente_id", $data["id"])->first();
                    $tipo_obstaculo = TipoObstaculo::find($detalle_incidencia['tipo_obstaculo_id']);
                    $detalle_incidencia['tipo_obstaculo'] = $tipo_obstaculo->descripcion;  
                }


                $incidencia["data"] = $data;
                $incidencia["data"]["estado_incidente_descripcion"] = $estado_incidente->descripcion;
                $incidencia["data"]["estado_incidente_color"] = $estado_incidente->color;
                                
                if($tipo_incidente != null){
                    $incidencia["data"]["tipo_incidente"] = $tipo_incidente->descripcion;
                }                

                $incidencia["data"]["urbanizacion_nombre"] = $Urbanizacion->descripcion;
                $incidencia["data"]["territorio_vecinal_nombre"] = $territoriovecinal->descripcion;
                $incidencia["ciudadano"] = $persona;
                $incidencia["detalle_incidente"] = $detalle_incidencia;
                $incidencia["media"] = $media_incidente;  
                $incidencia["atencion"] = $atencion_incidente;

                for ($i=0; $i < count($incidencia["atencion"]) ; $i++) 
                { 
                    $persona_atencion = Persona::find($incidencia["atencion"][$i]["persona_id"]);
                    $persona_atencion_incidente = $persona_atencion->ape_paterno." ".$persona_atencion->ape_materno." ".$persona_atencion->nombres;

                    $incidencia["atencion"][$i]["persona_atencion"] = $persona_atencion_incidente;
                }
                $incidencia["polilyne"] = $polyline;
                
                $incidencias["incidencia"][] = $incidencia;
            }        

            return response()->json($incidencias);
        }
        else
        {
            $incidencia = $incidente;
            return response()->json($incidencia);
        } 
    }

    /** -- api -- */
    // Listar Incidencias por ID
    // JJDCH 01072018
    public function getIncidentesById($id)
    {
        // Objetos
        $incidente = Incidente::find($id);
        $persona = Persona::get();

        // Devolución de campos
        $data = $incidente;

        if ($data != null){

            $persona = Persona::find($data["persona_id"]);
            $tipo_incidente = TipoIncidente::find($data["tipo_incidente_id"]);
            $media_incidente = IncidenteMedia::where("incidente_id", $data["id"])->get();;
            $atencion_incidente = AtencionIncidente::where("incidente_id", $data["id"])->get();
            $estado_incidente = EstadoIncidente::find($data["estado_incidente_id"]);
            $Urbanizacion = Urbanizacion::find($data["urbanizacion_id"]);
            $territoriovecinal = TerritorioVecinal::find($Urbanizacion["territorio_vecinal_id"]);
            $polyline = Polyline::where("incidente_id", $data["id"])->get();

            if ($data["tipo_incidente_id"]==1)
            {
                $detalle_incidencia = Inundacion::where("incidente_id", $data["id"])->first();
                $nivel_agua = NivelAgua::find($detalle_incidencia['nivel_agua_id']);
                $detalle_incidencia['nivel_agua'] = $nivel_agua->descripcion;  
            }
            elseif ($data["tipo_incidente_id"]==2)
            {
                $detalle_incidencia = CalleObstaculo::where("incidente_id", $data["id"])->first();
                $tipo_obstaculo = TipoObstaculo::find($detalle_incidencia['tipo_obstaculo_id']);
                $detalle_incidencia['tipo_obstaculo'] = $tipo_obstaculo->descripcion;  
            }

            
            $incidencia["incidencia"]["data"] = $data;
            $incidencia["incidencia"]["data"]["estado_incidente_descripcion"] = $estado_incidente->descripcion;
            $incidencia["incidencia"]["data"]["estado_incidente_color"] = $estado_incidente->color;

            
            if($tipo_incidente != null){
                $incidencia["incidencia"]["data"]["tipo_incidente"] = $tipo_incidente->descripcion;
            } 
            
            $incidencia["incidencia"]["data"]["urbanizacion_nombre"] = $Urbanizacion->descripcion;
            $incidencia["incidencia"]["data"]["territorio_vecinal_nombre"] = $territoriovecinal->descripcion;
            $incidencia["incidencia"]["ciudadano"] = $persona;
            $incidencia["incidencia"]["detalle_incidente"] = $detalle_incidencia;
            $incidencia["incidencia"]["media"] = $media_incidente;
            $incidencia["incidencia"]["atencion"] = $atencion_incidente;

            for ($i=0; $i < count($incidencia["incidencia"]["atencion"]) ; $i++) 
            { 
                $persona_atencion = Persona::find($incidencia["incidencia"]["atencion"][$i]["persona_id"]);
                $persona_atencion_incidente = $persona_atencion->ape_paterno." ".$persona_atencion->ape_materno." ".$persona_atencion->nombres;

                $incidencia["incidencia"]["atencion"][$i]["persona_atencion"] = $persona_atencion_incidente;
            } 
            $incidencia["incidencia"]["polilyne"] = $polyline;

        }
        else
        {
            $incidencia = $data;
        }

        return response()->json($incidencia);

    }

    /** -- api -- */
    // Listar Incidencias por Ciudadano
    // JJDCH 01072018
    public function getIncidentesByCiudadano($id)
    {

        // Objetos
        $incidente = Incidente::where("persona_id", $id)->get();
        $persona = Persona::get();

        if (count($incidente)>0)
        {
            // Devolución de campos
            foreach ($incidente as $data) {

                $persona = Persona::find($data["persona_id"]);
                $tipo_incidente = TipoIncidente::find($data["tipo_incidente_id"]);
                $media_incidente = IncidenteMedia::where("incidente_id", $data["id"])->get();
                $atencion_incidente = AtencionIncidente::where("incidente_id", $data["id"])->get();
                $estado_incidente = EstadoIncidente::find($data["estado_incidente_id"]);
                $Urbanizacion = Urbanizacion::find($data["urbanizacion_id"]);
                $territoriovecinal = TerritorioVecinal::find($Urbanizacion["territorio_vecinal_id"]);
                $polyline = Polyline::where("incidente_id", $data["id"])->get();

                if ($data["tipo_incidente_id"]==1)
                {
                    $detalle_incidencia = Inundacion::where("incidente_id", $data["id"])->first();
                    $nivel_agua = NivelAgua::find($detalle_incidencia['nivel_agua_id']);
                    $detalle_incidencia['nivel_agua'] = $nivel_agua->descripcion;  
                }
                elseif ($data["tipo_incidente_id"]==2)
                {
                    $detalle_incidencia = CalleObstaculo::where("incidente_id", $data["id"])->first();
                    $tipo_obstaculo = TipoObstaculo::find($detalle_incidencia['tipo_obstaculo_id']);
                    $detalle_incidencia['tipo_obstaculo'] = $tipo_obstaculo->descripcion;  
                }


                $incidencia["data"] = $data;
                $incidencia["data"]["estado_incidente_descripcion"] = $estado_incidente->descripcion;
                $incidencia["data"]["estado_incidente_color"] = $estado_incidente->color;
                
                if($tipo_incidente != null){
                    $incidencia["data"]["tipo_incidente"] = $tipo_incidente->descripcion;
                }             
                
                $incidencia["data"]["urbanizacion_nombre"] = $Urbanizacion->descripcion;
                $incidencia["data"]["territorio_vecinal_nombre"] = $territoriovecinal->descripcion;
                $incidencia["ciudadano"] = $persona;
                $incidencia["detalle_incidente"] = $detalle_incidencia;
                $incidencia["media"] = $media_incidente;
                $incidencia["atencion"] = $atencion_incidente;

                for ($i=0; $i < count($incidencia["atencion"]) ; $i++) 
                { 
                    $persona_atencion = Persona::find($incidencia["atencion"][$i]["persona_id"]);
                    $persona_atencion_incidente = $persona_atencion->ape_paterno." ".$persona_atencion->ape_materno." ".$persona_atencion->nombres;

                    $incidencia["atencion"][$i]["persona_atencion"] = $persona_atencion_incidente;
                }
                $incidencia["polilyne"] = $polyline;

                $incidencias["incidencia"][] = $incidencia;
            }        

            return response()->json($incidencias);
        }
        else
        {
            return response()->json($incidente);
        }        

    }

    /** -- api -- */
    // Listar Incidencias por estado
    // JJDCH 03072018
    public function getIncidentesByEstado($id)
    {
        // Objetos        
        $estados = explode("-", $id);
        $incidente = Incidente::whereIn('estado_incidente_id', $estados)->get();
        $persona = Persona::get();

        if (count($incidente)>0)
        {
            // Devolución de campos
            foreach ($incidente as $data) {

                $persona = Persona::find($data["persona_id"]);
                $tipo_incidente = TipoIncidente::find($data["tipo_incidente_id"]);
                $media_incidente = IncidenteMedia::where("incidente_id", $data["id"])->get();
                $atencion_incidente = AtencionIncidente::where("incidente_id", $data["id"])->get();
                $estado_incidente = EstadoIncidente::find($data["estado_incidente_id"]);
                $Urbanizacion = Urbanizacion::find($data["urbanizacion_id"]);
                $territoriovecinal = TerritorioVecinal::find($Urbanizacion["territorio_vecinal_id"]);
                $polyline = Polyline::where("incidente_id", $data["id"])->get();

                if ($data["tipo_incidente_id"]==1)
                {
                    $detalle_incidencia = Inundacion::where("incidente_id", $data["id"])->first();
                    $nivel_agua = NivelAgua::find($detalle_incidencia['nivel_agua_id']);
                    $detalle_incidencia['nivel_agua'] = $nivel_agua->descripcion;  
                }
                elseif ($data["tipo_incidente_id"]==2)
                {
                    $detalle_incidencia = CalleObstaculo::where("incidente_id", $data["id"])->first();
                    $tipo_obstaculo = TipoObstaculo::find($detalle_incidencia['tipo_obstaculo_id']);
                    $detalle_incidencia['tipo_obstaculo'] = $tipo_obstaculo->descripcion;  
                }


                $incidencia["data"] = $data;
                $incidencia["data"]["estado_incidente_descripcion"] = $estado_incidente->descripcion;
                $incidencia["data"]["estado_incidente_color"] = $estado_incidente->color;

                if($tipo_incidente != null){
                    $incidencia["data"]["tipo_incidente"] = $tipo_incidente->descripcion;
                }             
                
                $incidencia["data"]["urbanizacion_nombre"] = $Urbanizacion->descripcion;
                $incidencia["data"]["territorio_vecinal_nombre"] = $territoriovecinal->descripcion;
                $incidencia["ciudadano"] = $persona;
                $incidencia["detalle_incidente"] = $detalle_incidencia;
                $incidencia["media"] = $media_incidente;
                $incidencia["atencion"] = $atencion_incidente;

                for ($i=0; $i < count($incidencia["atencion"]) ; $i++) 
                { 
                    $persona_atencion = Persona::find($incidencia["atencion"][$i]["persona_id"]);
                    $persona_atencion_incidente = $persona_atencion->ape_paterno." ".$persona_atencion->ape_materno." ".$persona_atencion->nombres;

                    $incidencia["atencion"][$i]["persona_atencion"] = $persona_atencion_incidente;
                }
                $incidencia["polilyne"] = $polyline;

                $incidencias["incidencia"][] = $incidencia;
            }        

            return response()->json($incidencias);
        }
        else
        {
            return response()->json($incidente);
        } 
    }

    /** -- api -- */
    // Listar incidencias por estado y fechas
    // JJDCH 12102018
    public function getIncidentesByEstadoFechas($id, $fecha_inicio = null, $fecha_final = null)
    {
        if(is_null($fecha_inicio) && is_null($fecha_final)) {
            $ndias_antiguedad = Configuracion::where("nombre", "numero_dias_ver_incidentes")->first();
            $fecha_pasada = Carbon::now()->subDays($ndias_antiguedad->valor)->toDateString();
            $fecha_actual = Carbon::now()->toDateString();
            
            $estados = explode("-", $id);
            $incidente = Incidente::whereIn('estado_incidente_id', $estados)
                ->FilterFechaRango($fecha_pasada,$fecha_actual)
                ->get();

        }else {
            $estados = explode("-", $id);
            $incidente = Incidente::whereIn('estado_incidente_id', $estados)
                ->FilterFechaRango($fecha_inicio,$fecha_final)
                ->get();

        }

        // Objetos        
        $persona = Persona::get();

        if (count($incidente)>0)
        {
            // Devolución de campos
            foreach ($incidente as $data) {

                $persona = Persona::find($data["persona_id"]);
                $tipo_incidente = TipoIncidente::find($data["tipo_incidente_id"]);
                $media_incidente = IncidenteMedia::where("incidente_id", $data["id"])->get();
                $atencion_incidente = AtencionIncidente::where("incidente_id", $data["id"])->get();
                $estado_incidente = EstadoIncidente::find($data["estado_incidente_id"]);
                $Urbanizacion = Urbanizacion::find($data["urbanizacion_id"]);
                $territoriovecinal = TerritorioVecinal::find($Urbanizacion["territorio_vecinal_id"]);
                $polyline = Polyline::where("incidente_id", $data["id"])->get();

                if ($data["tipo_incidente_id"]==1)
                {
                    $detalle_incidencia = Inundacion::where("incidente_id", $data["id"])->first();
                    $nivel_agua = NivelAgua::find($detalle_incidencia['nivel_agua_id']);
                    $detalle_incidencia['nivel_agua'] = $nivel_agua->descripcion;  
                }
                elseif ($data["tipo_incidente_id"]==2)
                {
                    $detalle_incidencia = CalleObstaculo::where("incidente_id", $data["id"])->first();
                    $tipo_obstaculo = TipoObstaculo::find($detalle_incidencia['tipo_obstaculo_id']);
                    $detalle_incidencia['tipo_obstaculo'] = $tipo_obstaculo->descripcion;  
                }


                $incidencia["data"] = $data;
                $incidencia["data"]["estado_incidente_descripcion"] = $estado_incidente->descripcion;
                $incidencia["data"]["estado_incidente_color"] = $estado_incidente->color;

                if($tipo_incidente != null){
                    $incidencia["data"]["tipo_incidente"] = $tipo_incidente->descripcion;
                }             
                
                $incidencia["data"]["urbanizacion_nombre"] = $Urbanizacion->descripcion;
                $incidencia["data"]["territorio_vecinal_nombre"] = $territoriovecinal->descripcion;
                $incidencia["ciudadano"] = $persona;
                $incidencia["detalle_incidente"] = $detalle_incidencia;
                $incidencia["media"] = $media_incidente;
                $incidencia["atencion"] = $atencion_incidente;

                for ($i=0; $i < count($incidencia["atencion"]) ; $i++) 
                { 
                    $persona_atencion = Persona::find($incidencia["atencion"][$i]["persona_id"]);
                    $persona_atencion_incidente = $persona_atencion->ape_paterno." ".$persona_atencion->ape_materno." ".$persona_atencion->nombres;

                    $incidencia["atencion"][$i]["persona_atencion"] = $persona_atencion_incidente;
                }
                $incidencia["polilyne"] = $polyline;

                $incidencias["incidencia"][] = $incidencia;
            }        

            return response()->json($incidencias);
        }
        else
        {
            return response()->json($incidente);
        } 
    }


    /** -- api -- */
    // Listar Incidencias sin confirmar por Alcalde o comite
    // JJDCH 04072018
    public function getIncidentesSinConfirmarByAlcalde($id)
    {
        // Objeto Personas 4 = Alacalde // 5 = Comite Vecinal
        $alcaldes = DB::table('persona')
                        ->where('id', $id)
                        ->whereIn('tipo_persona_id', [4,5])->get();


        if(count($alcaldes)>0)
        {
            foreach ($alcaldes as $alcalde) {

                // En función al tipo de persona Alcalde o Comite buscamos el territorio vencinal
                // Al que pertenece

                if ($alcalde->tipo_persona_id == 4) {
                    $alcaldecomite = AlcaldeVecinal::where("persona_id", $alcalde->id)->first();
                }
                elseif ($alcalde->tipo_persona_id == 5) {
                    $alcaldecomite = ComiteGestion::where("persona_id", $alcalde->id)->first();
                }

                // Validamos que el alcalde o comite tenga asignado un territorio vecinal
                if (count($alcaldecomite)>0) 
                {
                    // Obtenemos las incidentes del territorio vecinal obtenido
                    $incidentes = Urbanizacion::where("territorio_vecinal_id",$alcaldecomite->territorio_vecinal_id)->get();

                    // Ordenamos en un arregle las incidentes
                    $urbs = [];
                    foreach ($incidentes as $urb) 
                    {   
                        array_push($urbs,$urb->id);
                    }

                    // Listamos los incidentes sin confirman de las urbanizacines de un territorio vecinaL
                    $incidente = Incidente::with('urbanizacion')
                                    ->Where('estado_incidente_id',1)
                                    ->whereIn('urbanizacion_id',$urbs)
                                    ->get();

                           

                    if (count($incidente)>0)
                    {
                        // Devolución de campos
                        foreach ($incidente as $data) {

                            $persona = Persona::find($data->persona_id);
                            $tipo_incidente = TipoIncidente::find($data->tipo_incidente_id);
                            $media_incidente = IncidenteMedia::where("incidente_id", $data->id)->get();
                            $atencion_incidente = AtencionIncidente::where("incidente_id", $data->id)->get();
                            $estado_incidente = EstadoIncidente::find($data->estado_incidente_id);
                            $Urbanizacion = Urbanizacion::find($data->urbanizacion_id);
                            $territoriovecinal = TerritorioVecinal::find($Urbanizacion["territorio_vecinal_id"]);
                            
                            $polyline = Polyline::where("incidente_id", $data->id)->get();

                            if ($data->tipo_incidente_id==1)
                            {
                                $detalle_incidencia = Inundacion::where("incidente_id", $data->id)->first();
                                $nivel_agua = NivelAgua::find($detalle_incidencia['nivel_agua_id']);
                                $detalle_incidencia['nivel_agua'] = $nivel_agua->descripcion;  
                            }
                            elseif ($data->tipo_incidente_id==2)
                            {
                                $detalle_incidencia = CalleObstaculo::where("incidente_id", $data->id)->first();
                                $tipo_obstaculo = TipoObstaculo::find($detalle_incidencia['tipo_obstaculo_id']);
                                $detalle_incidencia['tipo_obstaculo'] = $tipo_obstaculo->descripcion;  
                            }


                            $incidencia["data"] = $data;
                            $incidencia["data"]->estado_incidente_descripcion = $estado_incidente->descripcion;
                            $incidencia["data"]->estado_incidente_color = $estado_incidente->color;

                            if($tipo_incidente != null){
                                $incidencia["data"]->tipo_incidente = $tipo_incidente->descripcion;
                            }             
                            
                            $incidencia["data"]->urbanizacion_nombre = $Urbanizacion->descripcion;
                            $incidencia["data"]->territorio_vecinal_nombre = $territoriovecinal->descripcion;
                            $incidencia["ciudadano"] = $persona;
                            $incidencia["detalle_incidente"] = $detalle_incidencia;
                            $incidencia["media"] = $media_incidente;
                            $incidencia["atencion"] = $atencion_incidente;

                            for ($i=0; $i < count($incidencia["atencion"]) ; $i++) 
                            { 
                                $persona_atencion = Persona::find($incidencia["atencion"][$i]["persona_id"]);
                                $persona_atencion_incidente = $persona_atencion->ape_paterno." ".$persona_atencion->ape_materno." ".$persona_atencion->nombres;

                                $incidencia["atencion"][$i]["persona_atencion"] = $persona_atencion_incidente;
                            }
                            $incidencia["polilyne"] = $polyline;

                            $incidencias["incidencia"][] = $incidencia;
                        }
                        return response()->json($incidencias);
                    }
                    else
                    {
                        return ['success' => false, 'message' => 'No existen incidentes'];
                    } 
                }
                else
                {
                    return ['success' => false, 'message' => 'El usuario no tiene asignado un Territorio Vecinal']; 
                }                                                  
            }       
        }
        else
        {
            return ['success' => false, 'message' => 'El usuario no es Alcalde ni pertenece al Comité de Gestión de Territorio Vecinal'];    
        }            
    }


    /** -- api -- */
    // Actualizar estado de Incidente 
    // JJDCH 04072018
    public function updateIncidenteAtencion(Request $request)
    {
        DB::beginTransaction();

        try {
            
            $data = $request->all();
            $incidente = Incidente::find($data["id"]);

            // Asigno nuevos valores
            $incidente->persona_id_validador = $data["persona_id_validator"];
            $incidente->estado_incidente_id = $data["estado_incidente_id"];

            // Guardo cambios
            $incidente->save();

            // Registramos el puntaje correspondiete a la validación de un incidente
            // Obtenemos valores de Actividad Puntuacion
            $actividad_puntuacion = ActividadPuntuacion::where('estado_incidente_id',$data["estado_incidente_id"])
                                                        ->where('descripcion','Incidente Reportado')->first();

            // Registro la puntuación de la persona
            PuntuacionPersona::create([
                "numero_incidente"          => $incidente->id,
                "actividad_puntuacion_id"   => $actividad_puntuacion->id,
                "persona_id"                => $incidente->persona_id
            ]);

            $puntuacion_persona = PuntuacionPersona::where("persona_id", $incidente->persona_id)->get();
                    
            // Variable que guarda la puntuación por persona
            $puntos_persona = 0;

            // Obtenemos los puntos del usuario
            foreach ($puntuacion_persona as $puntos) {
                $actividad_puntuacion = ActividadPuntuacion::where("id",$puntos->actividad_puntuacion_id)->first();
                $puntos_persona += $actividad_puntuacion->puntaje;
            }

            // Actualizamos el nivel_ciudadano_id por persona
            // de acuerdo a la puntuación
            $persona = $incidente->persona;
            if($puntos_persona > 0 && $puntos_persona <= 100) {
                $persona->nivel_ciudadano_id = 1;
                $persona->save();
            }else if($puntos_persona >= 101 && $puntos_persona <= 200) {
                $persona->nivel_ciudadano_id = 2;
                $persona->save();
            }else if($puntos_persona >= 201 && $puntos_persona <= 300) {
                $persona->nivel_ciudadano_id = 3;
                $persona->save();
            }else if($puntos_persona >= 301 && $puntos_persona <= 400) {
                $persona->nivel_ciudadano_id = 4;
                $persona->save();
            }


            DB::commit();

            // Obtenemos la token del ciudadano para notificarle
            $ciudadano = User::where('persona_id',$incidente->persona_id)->first();
            $token[] = $ciudadano->token;

            // Obtenemos la data del incidente
            $incidentenotify = Incidente::with(['TipoIncidente','EstadoIncidente'])
                            ->where('id',$incidente->id)
                            ->first();

            // Seteamos campos del incidente para que se muestre correctamente en la notificación
            $incidentenotify->id = 0;
            $incidentenotify->TipoIncidente->descripcion = "Tú incidente ". $incidentenotify->TipoIncidente->descripcion . " fue informada como ".$incidentenotify->EstadoIncidente->descripcion; 

            // Enviamos notificacion
            $notify = $this->send_notificacion_movil($token,$incidentenotify,'otros'); 


            return ['success' => true, 'message' => 'Incidencia actualizada corrrectamente'];

            // Verificamos si tenemos 3 registros como falso positivo de un ciudadano
            $incidentes_falsos_positivos = Incidente::where("persona_id", $incidente->persona_id)
                ->where("estado_incidente_id", 3)->get()->count();

            if($incidentes_falsos_positivos == 3) {
                // Actualizamos el estado a inactivo al usuario de tipo ciudadano
                // Verificamos si la persona es de tipo ciudadano
                if($incidente->persona->tipo_persona_id == 2) {
                    $incidente->persona->state = 'Inactivo';
                    $incidente->persona->save();

                    $incidente->persona->user->state = 'Inactivo';
                    $incidente->persona->user->save();
                }
            }

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception'=>$exception->getMessage()];

        }
    }


    /** -- api -- */
    // Listar Incidencias verificadas por Alcalde o comite
    // JJDCH 04072018
    public function getIncidentesValidadasByAlcalde($id)
    {
        // Objeto Personas 4 = Alacalde // 5 = Comite Vecinal
        $alcaldes = DB::table('persona')
                        ->where('id', $id)
                        ->whereIn('tipo_persona_id', [4,5])->get();

        if(count($alcaldes)>0)
        {
            foreach ($alcaldes as $alcalde) {

                // En función al tipo de persona Alcalde o Comite buscamos el territorio vencinal
                // Al que pertenece

                if ($alcalde->tipo_persona_id == 4) {
                    $alcaldecomite = AlcaldeVecinal::where("persona_id", $alcalde->id)->first();
                }
                elseif ($alcalde->tipo_persona_id == 5) {
                    $alcaldecomite = ComiteGestion::where("persona_id", $alcalde->id)->first();
                }

                // Validamos que el alcalde o comite tenga asignado un territorio vecinal
                if (count($alcaldecomite)>0) 
                {
                    // Obtenemos las incidentes del territorio vecinal obtenido
                    $incidentes = Urbanizacion::where("territorio_vecinal_id",$alcaldecomite->territorio_vecinal_id)->get();

                    // Ordenamos en un arregle las incidentes
                    $urbs = [];
                    foreach ($incidentes as $urb) 
                    {   
                        array_push($urbs,$urb->id);
                    }

                    // Listamos los incidentes sin confirman de las urbanizacines de un territorio vecinaL
                    $incidente = Incidente::with('urbanizacion')
                                    ->WhereIn('estado_incidente_id',[2,4])
                                    ->whereIn('urbanizacion_id',$urbs)
                                    ->get();
                        

                    if (count($incidente)>0)
                    {
                        // Devolución de campos
                        foreach ($incidente as $data) {

                            $persona = Persona::find($data->persona_id);
                            $tipo_incidente = TipoIncidente::find($data->tipo_incidente_id);
                            $media_incidente = IncidenteMedia::where("incidente_id", $data->id)->get();
                            $atencion_incidente = AtencionIncidente::where("incidente_id", $data->id)->get();
                            $estado_incidente = EstadoIncidente::find($data->estado_incidente_id);
                            $Urbanizacion = Urbanizacion::find($data->urbanizacion_id);
                            $territoriovecinal = TerritorioVecinal::find($Urbanizacion["territorio_vecinal_id"]);
                            $polyline = Polyline::where("incidente_id", $data->id)->get();

                            if ($data->tipo_incidente_id==1)
                            {
                                $detalle_incidencia = Inundacion::where("incidente_id", $data->id)->first();
                                $nivel_agua = NivelAgua::find($detalle_incidencia['nivel_agua_id']);
                                $detalle_incidencia['nivel_agua'] = $nivel_agua->descripcion;  
                            }
                            elseif ($data->tipo_incidente_id==2)
                            {
                                $detalle_incidencia = CalleObstaculo::where("incidente_id", $data->id)->first();
                                $tipo_obstaculo = TipoObstaculo::find($detalle_incidencia['tipo_obstaculo_id']);
                                $detalle_incidencia['tipo_obstaculo'] = $tipo_obstaculo->descripcion;  
                            }


                            $incidencia["data"] = $data;
                            $incidencia["data"]->estado_incidente_descripcion = $estado_incidente->descripcion;
                            $incidencia["data"]->estado_incidente_color = $estado_incidente->color;

                            if($tipo_incidente != null){
                                $incidencia["data"]->tipo_incidente = $tipo_incidente->descripcion;
                            }             
                            
                            $incidencia["data"]->urbanizacion_nombre = $Urbanizacion->descripcion;
                            $incidencia["data"]->territorio_vecinal_nombre = $territoriovecinal->descripcion;
                            $incidencia["ciudadano"] = $persona;
                            $incidencia["detalle_incidente"] = $detalle_incidencia;
                            $incidencia["media"] = $media_incidente;
                            $incidencia["atencion"] = $atencion_incidente;

                            for ($i=0; $i < count($incidencia["atencion"]) ; $i++) 
                            { 
                                $persona_atencion = Persona::find($incidencia["atencion"][$i]["persona_id"]);
                                $persona_atencion_incidente = $persona_atencion->ape_paterno." ".$persona_atencion->ape_materno." ".$persona_atencion->nombres;

                                $incidencia["atencion"][$i]["persona_atencion"] = $persona_atencion_incidente;
                            }
                            $incidencia["polilyne"] = $polyline;
                            
                            $incidencias["incidencia"][] = $incidencia;
                        }        

                        return response()->json($incidencias);
                    }
                    else
                    {
                        return response()->json($incidente);
                    }   
                }       
                else
                {
                    return ['success' => false, 'message' => 'El usuario no tiene asignado un Territorio Vecinal']; 
                }  
            }       
        }
        else
        {
            return ['success' => false, 'message' => 'El usuario no es Alcalde ni pertenece al comite de gestión vecinal'];    
        }        
    }

    /** -- api -- */
    // Registrar Lineas en incidencias Polyline
    // JJDCH 06092018
    public function nuevoRegistroIncidenciaPolyline(Request $request)
    {
        DB::beginTransaction();

        try {

            // Obtengo todos los valores
            $data = $request->all();  

            //  Registro las coordenadas
            Polyline::create($data);

            DB::commit();

            $message["success"] = true;
            $message["mensaje"] = "Linea y detalle registrado correctamente";

            return response()->json($message);

        } catch (\Exception $exception) {
            DB::rollBack();

            //return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception' => $exception->getMessage()];
            $message["success"] = false;
            $message["mensaje"] = 'Hubo un error, intente nuevamente.';
            $message["exception"] = $exception->getMessage();

            return response()->json($message);

        } 
    }


    /** -- api -- */
    // Registrar Lineas en incidencias Polyline
    // JJDCH 06092018
    public function eliminarRegistroIncidenciaPolyline(Request $request)
    {
        DB::beginTransaction();

        try {

            // Obtengo los datos de la persona
            $Polyline_id = $request->input("id"); 

            // Elimino Linea
            $Polyline = Polyline::find($Polyline_id);
            if($Polyline)
            {
                $Polyline->delete();  
            }            

            DB::commit();

            $message["success"] = true;
            $message["mensaje"] = "Linea eliminada correctamente"; 

            return response()->json($message);

        } catch (\Exception $exception) {
            DB::rollBack();

            //return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception' => $exception->getMessage()];
            $message["success"] = false;
            $message["mensaje"] = 'Hubo un error, intente nuevamente.';
            $message["exception"] = $exception->getMessage();

            return response()->json($message);

        } 
    }

    /** -- api -- */
    // Obtener coordenadas de urbanización de acuerdo a coordenadas incidente e ID urbanización
    // JCRN 05102018
    // Actualizado, las coordenadas deben ser de la urbanizacion mas no del territorio vecinal
    // JJDCH 08102018
    // Actualizado, cliente solicito se elimine esta validación.
    // JJDCH 22102018
    public function getCoordenadasUrbanizacion($urbanizacion_id, $latitude, $longitude)
    {    
        /*$result = DB::table("urbanizacion as u")
            ->join("territorio_vecinal as tv", "tv.id", "=", "u.territorio_vecinal_id")
            ->select("tv.coordenadas")
            ->where("u.id", $urbanizacion_id)
            ->first();
            
        $result = DB::table("urbanizacion")
            ->select("coordenadas")
            ->where("id", $urbanizacion_id)
            ->first();

        // $punto = $latitude." ".$longitude;
        $punto = array("x" => $latitude, "y" => $longitude);
        $coordenadas = $result->coordenadas;

        return $this->puntoEnPoligono($punto, $coordenadas);*/
        return ["status" => "inside", "message" => "Punto señalado dentro de coordenadas"];
    }

    /** -- api -- */
    // Listar Incidencias Cabecera
    // JCRN 13112018
    public function getIncidentesCab(Request $request) {
        $data = $request->all();
        $incidente = null;
        if(!isset($data["fecha_inicio"]) 
            && !isset($data["fecha_final"]) 
            && isset($data["estados"]) 
            && !is_null($data["estados"])) {
            
                $fecha_actual = Carbon::now()->toDateString();
                $ndias_antiguedad = Configuracion::where("nombre", "numero_dias_ver_incidentes")->first();
                $fecha_pasada = Carbon::now()->subDays($ndias_antiguedad->valor)->toDateString();

                $estados = (strlen($data['estados']) > 0) ? explode("-", $data['estados']) : $data["estados"];
                $incidente = Incidente::with("estadoIncidente", "polylines")->FilterFechaRango($fecha_pasada,$fecha_actual)->whereIn("estado_incidente_id", $estados)->get();

        }else if(!isset($data["fecha_inicio"])
            && !isset($data["fecha_final"])
            && !isset($data["estados"])){

                $fecha_actual = Carbon::now()->toDateString();
                $ndias_antiguedad = Configuracion::where("nombre", "numero_dias_ver_incidentes")->first();
                $fecha_pasada = Carbon::now()->subDays($ndias_antiguedad->valor)->toDateString();
                $incidente = Incidente::with("estadoIncidente", "polylines")->FilterFechaRango($fecha_pasada,$fecha_actual)->get();

        }else if(isset($data["fecha_inicio"]) 
            && isset($data["fecha_final"]) 
            && !isset($data["estados"])){
            
                $fecha_inicio = Carbon::parse($data["fecha_inicio"]);
                $fecha_final = Carbon::parse($data["fecha_final"]);
                $incidente = Incidente::with("estadoIncidente", "polylines")->whereBetween("fecha", [$fecha_inicio, $fecha_final])->get();

        }else if(isset($data["fecha_inicio"])
            && isset($data["fecha_final"])
            && isset($data["estados"])){
            
                $fecha_inicio = Carbon::parse($data["fecha_inicio"]);
                $fecha_final = Carbon::parse($data["fecha_final"]);
                $estados = (strlen($data['estados']) > 0) ? explode("-", $data['estados']) : $data["estados"];
                $incidente = Incidente::with("estadoIncidente", "polylines")->whereBetween("fecha", [$fecha_inicio, $fecha_final])->whereIn("estado_incidente_id", $estados)->get();

        }else {

            $fecha_actual = Carbon::now()->toDateString();
            $ndias_antiguedad = Configuracion::where("nombre", "numero_dias_ver_incidentes")->first();
            $fecha_pasada = Carbon::now()->subDays($ndias_antiguedad->valor)->toDateString();
            $incidente = Incidente::with("estadoIncidente", "polylines")->FilterFechaRango($fecha_pasada,$fecha_actual)->get();
        }

        return $incidente;
    }

    /** -- api -- */
    // Listar Incidencias Detalle
    // JCRN 14112018
    public function getIncidentesDet($id) {       
        // return $data;
        //$incidente = $this->getIncidentesCab($request);
        $incidente = Incidente::where("id", $id)->get();
        $incidencia = array();
        $incidencias = array();
        if(!is_null($incidente) && count($incidente) > 0) {
            foreach($incidente as $inc) {
                $territorioVecinal = $inc->urbanizacion->territorioVecinal;
                $detalle_incidencia = null;
                if(isset($inc["tipo_incidente_id"]) && $inc["tipo_incidente_id"] == 1) {
                    $inundacion = $inc->inundacion;
                    $detalle_incidencia["id"] = $inundacion->id;
                    $detalle_incidencia["tipo_inundacion"] = $inundacion->tipo_inundacion;
                    $detalle_incidencia["incidente_id"] = $inundacion->incidente_id;
                    $detalle_incidencia["nivel_agua_id"] = $inundacion->nivel_agua_id;
                    $detalle_incidencia["created_at"] = $inundacion->created_at->toDateTimeString();
                    $detalle_incidencia["updated_at"] = $inundacion->updated_at->toDateTimeString();
                    $detalle_incidencia["nivel_agua"] = $inundacion->nivelAgua->descripcion;
                }elseif(isset($inc["tipo_incidente_id"]) && $inc["tipo_incidente_id"] == 2) {
                    $calleObstaculo = $inc->calleObstaculo;
                    $detalle_incidencia["id"] = $calleObstaculo->id;
                    $detalle_incidencia["incidente_id"] = $calleObstaculo->incidente_id;
                    $detalle_incidencia["tipo_obstaculo_id"] = $calleObstaculo->tipo_obstaculo_id;
                    $detalle_incidencia["created_at"] = $calleObstaculo->created_at->toDateTimeString();
                    $detalle_incidencia["updated_at"] = $calleObstaculo->updated_at->toDateTimeString();
                    $detalle_incidencia["tipo_obstaculo"] = $calleObstaculo->tipoObstaculo->descripcion;
                }

                $incidencia["data"]["id"] = $inc->id;
                $incidencia["data"]["fecha"] = $inc->fecha;
                $incidencia["data"]["descripcion"] = $inc->descripcion;
                $incidencia["data"]["direccion"] = $inc->direccion;
                $incidencia["data"]["latitud"] = $inc->latitud;
                $incidencia["data"]["longitud"] = $inc->longitud;
                $incidencia["data"]["imagen"] = $inc->imagen;
                $incidencia["data"]["urbanizacion_id"] = $inc->urbanizacion_id;
                $incidencia["data"]["persona_id"] = $inc->persona_id;
                $incidencia["data"]["persona_id_validador"] = $inc->persona_id_validador;
                $incidencia["data"]["estado_incidente_id"] = $inc->estado_incidente_id;
                $incidencia["data"]["tipo_incidente_id"] = $inc->tipo_incidente_id;
                $incidencia["data"]["created_at"] = $inc->created_at->toDateTimeString();
                $incidencia["data"]["updated_at"] = $inc->updated_at->toDateTimeString();
                $incidencia["data"]["estado_incidente_descripcion"] = $inc->estadoIncidente->descripcion;
                $incidencia["data"]["estado_incidente_color"] = $inc->estadoIncidente->color;
                $incidencia["data"]["tipo_incidente"] = $inc->tipoIncidente->descripcion;
                $incidencia["data"]["urbanizacion_nombre"] = $inc->urbanizacion->descripcion;
                $incidencia["data"]["territorio_vecinal_nombre"] = $inc->urbanizacion->territorioVecinal->descripcion;
                $incidencia["data"]["src_imagen"] = $inc->src_imagen;
                $incidencia["data"]["hora"] = $inc->hora;

                if(!is_null($inc->tipoIncidente)) {
                    $incidencia["data"]["tipo_incidente"] = $inc->tipoIncidente->descripcion;
                }

                $incidencia["data"]["urbanizacion_nombre"] = $inc->urbanizacion->descripcion;
                $incidencia["data"]["territorio_vecinal_nombre"] = $territorioVecinal->descripcion;
                $incidencia["ciudadano"] = $inc->persona;

                $incidencia["detalle_incidente"] = $detalle_incidencia;
                $incidencia["media"] = $inc->incidentesmedia;
                $incidencia["atencion"] = $inc->atencionIncidente;

                for($i = 0; $i < count($incidencia["atencion"]); $i++) {
                    $persona_atencion = Persona::find($incidencia["atencion"][$i]["persona_id"]);
                    $persona_atencion_incidente = $persona_atencion->ape_paterno." ".$persona_atencion->ape_materno." ".$persona_atencion->nombres;
                    $incidencia["atencion"][$i]["persona_atencion"] = $persona_atencion_incidente;
                }
                $incidencia["polilyne"] = $inc->polylines;
                //$incidencias["incidencia"][] = $incidencia;
                $incidencias["incidencia"] = $incidencia;
            }
            return response()->json($incidencias);
        }else {
            $incidencia = $incidente;
            return response()->json($incidencia);
        }
    }

    /** -- api -- */
    // Listar Incidencias Por Ciudadano Cabecera
    // JCRN 14112018
    public function getIncidentesByCiudadanoCab($id){
        $incidente = null;
        if(!is_null($id) && (int)$id != 0) {
            $incidente = Incidente::with("estadoIncidente", "polylines", "tipoIncidente")->where("persona_id", $id)->get();
        }
        return $incidente;
    }

    /** -- api -- */
    // Listar Incidencias Por Ciudadano Detalle
    // JCRN 14112018
    public function getIncidentesByCiudadanoDet($id) {
        //$incidente = $this->getIncidentesByCiudadanoCab($id);
        $incidente = Incidente::where("id", $id)->get();
        $incidencia = array();
        $incidencias = array();
        if(!is_null($incidente) && count($incidente) > 0) {
            foreach($incidente as $inc) {
                $territorioVecinal = $inc->urbanizacion->territorioVecinal;
                $detalle_incidencia = null;
                if(isset($inc["tipo_incidente_id"]) && $inc["tipo_incidente_id"] == 1) {
                    $inundacion = $inc->inundacion;
                    $detalle_incidencia["id"] = $inundacion->id;
                    $detalle_incidencia["tipo_inundacion"] = $inundacion->tipo_inundacion;
                    $detalle_incidencia["incidente_id"] = $inundacion->incidente_id;
                    $detalle_incidencia["nivel_agua_id"] = $inundacion->nivel_agua_id;
                    $detalle_incidencia["created_at"] = $inundacion->created_at->toDateTimeString();
                    $detalle_incidencia["updated_at"] = $inundacion->updated_at->toDateTimeString();
                    $detalle_incidencia["nivel_agua"] = $inundacion->nivelAgua->descripcion;
                }elseif(isset($inc["tipo_incidente_id"]) && $inc["tipo_incidente_id"] == 2) {
                    $calleObstaculo = $inc->calleObstaculo;
                    $detalle_incidencia["id"] = $calleObstaculo->id;
                    $detalle_incidencia["incidente_id"] = $calleObstaculo->incidente_id;
                    $detalle_incidencia["tipo_obstaculo_id"] = $calleObstaculo->tipo_obstaculo_id;
                    $detalle_incidencia["created_at"] = $calleObstaculo->created_at->toDateTimeString();
                    $detalle_incidencia["updated_at"] = $calleObstaculo->updated_at->toDateTimeString();
                    $detalle_incidencia["tipo_obstaculo"] = $calleObstaculo->tipoObstaculo->descripcion;
                }

                $incidencia["data"]["id"] = $inc->id;
                $incidencia["data"]["fecha"] = $inc->fecha;
                $incidencia["data"]["descripcion"] = $inc->descripcion;
                $incidencia["data"]["direccion"] = $inc->direccion;
                $incidencia["data"]["latitud"] = $inc->latitud;
                $incidencia["data"]["longitud"] = $inc->longitud;
                $incidencia["data"]["imagen"] = $inc->imagen;
                $incidencia["data"]["urbanizacion_id"] = $inc->urbanizacion_id;
                $incidencia["data"]["persona_id"] = $inc->persona_id;
                $incidencia["data"]["persona_id_validador"] = $inc->persona_id_validador;
                $incidencia["data"]["estado_incidente_id"] = $inc->estado_incidente_id;
                $incidencia["data"]["tipo_incidente_id"] = $inc->tipo_incidente_id;
                $incidencia["data"]["created_at"] = $inc->created_at->toDateTimeString();
                $incidencia["data"]["updated_at"] = $inc->updated_at->toDateTimeString();
                $incidencia["data"]["estado_incidente_descripcion"] = $inc->estadoIncidente->descripcion;
                $incidencia["data"]["estado_incidente_color"] = $inc->estadoIncidente->color;
                $incidencia["data"]["tipo_incidente"] = $inc->tipoIncidente->descripcion;
                $incidencia["data"]["urbanizacion_nombre"] = $inc->urbanizacion->descripcion;
                $incidencia["data"]["territorio_vecinal_nombre"] = $inc->urbanizacion->territorioVecinal->descripcion;
                $incidencia["data"]["src_imagen"] = $inc->src_imagen;
                $incidencia["data"]["hora"] = $inc->hora;

                if(!is_null($inc->tipoIncidente)) {
                    $incidencia["data"]["tipo_incidente"] = $inc->tipoIncidente->descripcion;
                }

                $incidencia["data"]["urbanizacion_nombre"] = $inc->urbanizacion->descripcion;
                $incidencia["data"]["territorio_vecinal_nombre"] = $territorioVecinal->descripcion;
                $incidencia["ciudadano"] = $inc->persona;

                $incidencia["detalle_incidente"] = $detalle_incidencia;
                $incidencia["media"] = $inc->incidentesmedia;
                $incidencia["atencion"] = $inc->atencionIncidente;

                for($i = 0; $i < count($incidencia["atencion"]); $i++) {
                    $persona_atencion = Persona::find($incidencia["atencion"][$i]["persona_id"]);
                    $persona_atencion_incidente = $persona_atencion->ape_paterno." ".$persona_atencion->ape_materno." ".$persona_atencion->nombres;
                    $incidencia["atencion"][$i]["persona_atencion"] = $persona_atencion_incidente;
                }
                $incidencia["polilyne"] = $inc->polylines;
                $incidencias["incidencia"][] = $incidencia;
            }
            return response()->json($incidencias);
        }else {
            $incidencia = $incidente;
            return response()->json($incidencia);
        }
    }

    /** -- api -- */
    // Listar Incidencias por Estado y Fechas
    // JCRN 14112018
    public function getIncidentesByEstadoFechasCab(Request $request) {
        $incidente = null;
        $data = $request->all();
        $return = "";
        if(isset($data["fecha_inicio"]) && is_null($data["fecha_inicio"]) 
            && isset($data["fecha_final"]) && is_null($data["fecha_final"]) 
            && isset($data["estados"]) && !is_null($data["estados"])) {

            //$fecha_pasada = Carbon::now()->subDays(7)->toDateString();
            $ndias_antiguedad = Configuracion::where("nombre", "numero_dias_ver_incidentes")->first();
            $fecha_pasada = Carbon::now()->subDays($ndias_antiguedad->valor)->toDateString();

            $fecha_actual = Carbon::now()->toDateString();
            $estados = $data["estados"];
            $idestados = explode("-", $estados);
           
            $incidente = Incidente::with("estadoIncidente", "polylines")
                ->whereIn('estado_incidente_id', $idestados)
                ->FilterFechaRango($fecha_pasada,$fecha_actual)
                ->get();
            // $return = "a";

        }else if(isset($data["fecha_inicio"]) && !is_null($data["fecha_inicio"])
            && isset($data["fecha_final"]) && !is_null($data["fecha_final"])
            && !isset($data["estados"])) {
            
            $fecha_inicio = $data["fecha_inicio"];
            $fecha_final = $data["fecha_final"];
            $incidente = Incidente::with("estadoIncidente", "polylines")
                ->FilterFechaRango($fecha_inicio,$fecha_final)
                ->get();

            // $return = "b";

        }else if(isset($data["fecha_inicio"]) && is_null($data["fecha_inicio"]) 
            && isset($data["fecha_final"]) && is_null($data["fecha_final"]) 
            && isset($data["estados"]) && is_null($data["estados"])) {
            
            //$fecha_pasada = Carbon::now()->subDays(7)->toDateString();
            $ndias_antiguedad = Configuracion::where("nombre", "numero_dias_ver_incidentes")->first();
            $fecha_pasada = Carbon::now()->subDays($ndias_antiguedad->valor)->toDateString();
            $fecha_actual = Carbon::now()->toDateString();
            
            $incidente = Incidente::with("estadoIncidente", "polylines")
                ->FilterFechaRango($fecha_pasada,$fecha_actual)
                ->get();

            // $return = "c";

        }else if(!isset($data["fecha_inicio"]) && !isset($data["fecha_final"]) && !isset($data["estados"])) {
            
            //$fecha_pasada = Carbon::now()->subDays(7)->toDateString();
            $ndias_antiguedad = Configuracion::where("nombre", "numero_dias_ver_incidentes")->first();
            $fecha_pasada = Carbon::now()->subDays($ndias_antiguedad->valor)->toDateString();
            $fecha_actual = Carbon::now()->toDateString();
            
            $incidente = Incidente::with("estadoIncidente", "polylines")
                ->FilterFechaRango($fecha_pasada,$fecha_actual)
                ->get();

            // $return = "d";

        }else if(isset($data["fecha_inicio"]) && !is_null($data["fecha_inicio"])
            && isset($data["fecha_final"]) && !is_null($data["fecha_final"])
            && isset($data["estados"]) && !is_null($data["estados"])) {

            $estados = $data["estados"];
            $fecha_inicio = $data["fecha_inicio"];
            $fecha_final = $data["fecha_final"];
            $idestados = explode("-", $estados);
            
            $incidente = Incidente::with("estadoIncidente", "polylines")
                ->whereIn('estado_incidente_id', $idestados)
                ->FilterFechaRango($fecha_inicio,$fecha_final)
                ->get();

            // $return = "e";
            
        }else if(!isset($data["fecha_inicio"]) && !isset($data["fecha_final"]) 
            && isset($data["estados"]) && !is_null($data["estados"])) {

            //$fecha_pasada = Carbon::now()->subDays(7)->toDateString();
            $ndias_antiguedad = Configuracion::where("nombre", "numero_dias_ver_incidentes")->first();
            $fecha_pasada = Carbon::now()->subDays($ndias_antiguedad->valor)->toDateString();
            $fecha_actual = Carbon::now()->toDateString();
            $estados = $data["estados"];
            $idestados = explode("-", $estados);
            
            $incidente = Incidente::with("estadoIncidente", "polylines")
                ->whereIn('estado_incidente_id', $idestados)
                ->FilterFechaRango($fecha_pasada,$fecha_actual)
                ->get();

            // $return = "f";

        }else {

            //$fecha_pasada = Carbon::now()->subDays(7)->toDateString();
            $ndias_antiguedad = Configuracion::where("nombre", "numero_dias_ver_incidentes")->first();
            $fecha_pasada = Carbon::now()->subDays($ndias_antiguedad->valor)->toDateString();
            $fecha_actual = Carbon::now()->toDateString();
            
            $incidente = Incidente::with("estadoIncidente", "polylines")
                ->FilterFechaRango($fecha_pasada,$fecha_actual)
                ->get();

            // $return = "g";
        }
        return $incidente;
        // return $return;
    }

    /** -- api -- */
    // Listar Incidencias Por Estado y Fechas Detalle
    // JCRN 14112018
    public function getIncidentesByEstadoFechasDet(Request $request) {
        $incidente = $this->getIncidentesByEstadoFechasCab($request);
        $incidencia = array();
        $incidencias = array();
        if(!is_null($incidente) && count($incidente) > 0) {
            foreach($incidente as $inc) {
                $territorioVecinal = $inc->urbanizacion->territorioVecinal;
                $detalle_incidencia = null;
                if(isset($inc["tipo_incidente_id"]) && $inc["tipo_incidente_id"] == 1) {
                    $inundacion = $inc->inundacion;
                    $detalle_incidencia["id"] = $inundacion->id;
                    $detalle_incidencia["tipo_inundacion"] = $inundacion->tipo_inundacion;
                    $detalle_incidencia["incidente_id"] = $inundacion->incidente_id;
                    $detalle_incidencia["nivel_agua_id"] = $inundacion->nivel_agua_id;
                    $detalle_incidencia["created_at"] = $inundacion->created_at->toDateTimeString();
                    $detalle_incidencia["updated_at"] = $inundacion->updated_at->toDateTimeString();
                    $detalle_incidencia["nivel_agua"] = $inundacion->nivelAgua->descripcion;
                }elseif(isset($inc["tipo_incidente_id"]) && $inc["tipo_incidente_id"] == 2) {
                    $calleObstaculo = $inc->calleObstaculo;
                    $detalle_incidencia["id"] = $calleObstaculo->id;
                    $detalle_incidencia["incidente_id"] = $calleObstaculo->incidente_id;
                    $detalle_incidencia["tipo_obstaculo_id"] = $calleObstaculo->tipo_obstaculo_id;
                    $detalle_incidencia["created_at"] = $calleObstaculo->created_at->toDateTimeString();
                    $detalle_incidencia["updated_at"] = $calleObstaculo->updated_at->toDateTimeString();
                    $detalle_incidencia["tipo_obstaculo"] = $calleObstaculo->tipoObstaculo->descripcion;
                }

                $incidencia["data"]["id"] = $inc->id;
                $incidencia["data"]["fecha"] = $inc->fecha;
                $incidencia["data"]["descripcion"] = $inc->descripcion;
                $incidencia["data"]["direccion"] = $inc->direccion;
                $incidencia["data"]["latitud"] = $inc->latitud;
                $incidencia["data"]["longitud"] = $inc->longitud;
                $incidencia["data"]["imagen"] = $inc->imagen;
                $incidencia["data"]["urbanizacion_id"] = $inc->urbanizacion_id;
                $incidencia["data"]["persona_id"] = $inc->persona_id;
                $incidencia["data"]["persona_id_validador"] = $inc->persona_id_validador;
                $incidencia["data"]["estado_incidente_id"] = $inc->estado_incidente_id;
                $incidencia["data"]["tipo_incidente_id"] = $inc->tipo_incidente_id;
                $incidencia["data"]["created_at"] = $inc->created_at->toDateTimeString();
                $incidencia["data"]["updated_at"] = $inc->updated_at->toDateTimeString();
                $incidencia["data"]["estado_incidente_descripcion"] = $inc->estadoIncidente->descripcion;
                $incidencia["data"]["estado_incidente_color"] = $inc->estadoIncidente->color;
                $incidencia["data"]["tipo_incidente"] = $inc->tipoIncidente->descripcion;
                $incidencia["data"]["urbanizacion_nombre"] = $inc->urbanizacion->descripcion;
                $incidencia["data"]["territorio_vecinal_nombre"] = $inc->urbanizacion->territorioVecinal->descripcion;
                $incidencia["data"]["src_imagen"] = $inc->src_imagen;
                $incidencia["data"]["hora"] = $inc->hora;

                if(!is_null($inc->tipoIncidente)) {
                    $incidencia["data"]["tipo_incidente"] = $inc->tipoIncidente->descripcion;
                }

                $incidencia["data"]["urbanizacion_nombre"] = $inc->urbanizacion->descripcion;
                $incidencia["data"]["territorio_vecinal_nombre"] = $territorioVecinal->descripcion;
                $incidencia["ciudadano"] = $inc->persona;

                $incidencia["detalle_incidente"] = $detalle_incidencia;
                $incidencia["media"] = $inc->incidentesmedia;
                $incidencia["atencion"] = $inc->atencionIncidente;

                for($i = 0; $i < count($incidencia["atencion"]); $i++) {
                    $persona_atencion = Persona::find($incidencia["atencion"][$i]["persona_id"]);
                    $persona_atencion_incidente = $persona_atencion->ape_paterno." ".$persona_atencion->ape_materno." ".$persona_atencion->nombres;
                    $incidencia["atencion"][$i]["persona_atencion"] = $persona_atencion_incidente;
                }
                $incidencia["polilyne"] = $inc->polylines;
                $incidencias["incidencia"][] = $incidencia;
            }
            return response()->json($incidencias);
        }else {
            $incidencia = $incidente;
            return response()->json($incidencia);
        }
    }

    /** -- api -- */
    // Listar Incidencias Sin Confirmar por alcalde  vecinal
    // JCRN 14112018
    public function getIncidentesSinConfirmarByAlcaldeDet($id) {

        // JJDCH 25062019 - Optimizando servicio
        $incidente = null;

        if(!is_null($id) && (int)$id != 0) {
            
            // JJDCH 25062019 obtendo las urbanizaciones asignadas a un comite de gestion o alcalde vecinal
            $urbanizacionav = AlcaldeVecinal::select("urbanizacion.id")
                                            ->join("territorio_vecinal", 'alcaldes_vecinales.territorio_vecinal_id', '=', 'territorio_vecinal.id')
                                            ->join("urbanizacion", "territorio_vecinal.id", '=', 'urbanizacion.territorio_vecinal_id')
                                            ->where('alcaldes_vecinales.persona_id',$id);
            $urbanizacion = ComiteGestion::select("urbanizacion.id")
                                            ->join("territorio_vecinal", 'comites_gestion.territorio_vecinal_id', '=', 'territorio_vecinal.id')
                                            ->join("urbanizacion", "territorio_vecinal.id", '=', 'urbanizacion.territorio_vecinal_id')
                                            ->where('comites_gestion.persona_id',$id)
                                            ->union($urbanizacionav)
                                            ->get();  

            // Ordenamos en un arregle las urbanizaciones
            /*$urbs = [];
            foreach ($urbanizacion as $urb) 
            {   
                array_push($urbs,$urb->id);
            }*/

            $incidente = Incidente::with("estadoIncidente", "polylines", "tipoIncidente")
                                    ->Where('estado_incidente_id',1)
                                    ->whereIn('urbanizacion_id',$urbanizacion)
                                    ->get();
        }

        return $incidente; 

        /*$alcaldes = Persona::where("id", $id)->whereIn("tipo_persona_id", [4, 5])->get();
        if(count($alcaldes) > 0) {
            foreach($alcaldes as $alcalde) {
                // En función al tipo de persona Alcalde o Comite buscamos el territorio vecinal
                // Al que pertenece
                if ($alcalde->tipo_persona_id == 4) {
                    $alcaldecomite = AlcaldeVecinal::where("persona_id", $alcalde->id)->first();
                }
                elseif ($alcalde->tipo_persona_id == 5) {
                    $alcaldecomite = ComiteGestion::where("persona_id", $alcalde->id)->first();
                }

                // Validamos que el alcalde o comite tenga asignado un territorio vecinal
                if(count($alcaldecomite) > 0) {
                    // Obtenemos las incidentes del territorio vecinal obtenido
                    $incidentes = Urbanizacion::where("territorio_vecinal_id",$alcaldecomite->territorio_vecinal_id)->get();

                    // Ordenamos en un arregle las incidentes
                    $urbs = [];
                    foreach ($incidentes as $urb) 
                    {   
                        array_push($urbs,$urb->id);
                    }

                    // Listamos los incidentes sin confirman de las urbanizacines de un territorio vecinaL
                    $incidente = Incidente::with( "estadoIncidente")
                        ->Where('estado_incidente_id',1)
                        ->whereIn('urbanizacion_id',$urbs)
                        ->get();

                    if(!is_null($incidente) && count($incidente) > 0) {
                        foreach($incidente as $inc) {
                            $territorioVecinal = $inc->urbanizacion->territorioVecinal;
                            $detalle_incidencia = null;
                            if(isset($inc["tipo_incidente_id"]) && $inc["tipo_incidente_id"] == 1) {
                                $inundacion = $inc->inundacion;
                                $detalle_incidencia["id"] = $inundacion->id;
                                $detalle_incidencia["tipo_inundacion"] = $inundacion->tipo_inundacion;
                                $detalle_incidencia["incidente_id"] = $inundacion->incidente_id;
                                $detalle_incidencia["nivel_agua_id"] = $inundacion->nivel_agua_id;
                                $detalle_incidencia["created_at"] = $inundacion->created_at->toDateTimeString();
                                $detalle_incidencia["updated_at"] = $inundacion->updated_at->toDateTimeString();
                                $detalle_incidencia["nivel_agua"] = $inundacion->nivelAgua->descripcion;
                            }elseif(isset($inc["tipo_incidente_id"]) && $inc["tipo_incidente_id"] == 2) {
                                $calleObstaculo = $inc->calleObstaculo;
                                $detalle_incidencia["id"] = $calleObstaculo->id;
                                $detalle_incidencia["incidente_id"] = $calleObstaculo->incidente_id;
                                $detalle_incidencia["tipo_obstaculo_id"] = $calleObstaculo->tipo_obstaculo_id;
                                $detalle_incidencia["created_at"] = $calleObstaculo->created_at->toDateTimeString();
                                $detalle_incidencia["updated_at"] = $calleObstaculo->updated_at->toDateTimeString();
                                $detalle_incidencia["tipo_obstaculo"] = $calleObstaculo->tipoObstaculo->descripcion;
                            }
            
                            $incidencia["data"]["id"] = $inc->id;
                            $incidencia["data"]["fecha"] = $inc->fecha;
                            $incidencia["data"]["descripcion"] = $inc->descripcion;
                            $incidencia["data"]["direccion"] = $inc->direccion;
                            $incidencia["data"]["latitud"] = $inc->latitud;
                            $incidencia["data"]["longitud"] = $inc->longitud;
                            $incidencia["data"]["imagen"] = $inc->imagen;
                            $incidencia["data"]["urbanizacion_id"] = $inc->urbanizacion_id;
                            $incidencia["data"]["persona_id"] = $inc->persona_id;
                            $incidencia["data"]["persona_id_validador"] = $inc->persona_id_validador;
                            $incidencia["data"]["estado_incidente_id"] = $inc->estado_incidente_id;
                            $incidencia["data"]["tipo_incidente_id"] = $inc->tipo_incidente_id;
                            $incidencia["data"]["created_at"] = $inc->created_at->toDateTimeString();
                            $incidencia["data"]["updated_at"] = $inc->updated_at->toDateTimeString();
                            $incidencia["data"]["estado_incidente_descripcion"] = $inc->estadoIncidente->descripcion;
                            $incidencia["data"]["estado_incidente_color"] = $inc->estadoIncidente->color;
                            //$incidencia["data"]["tipo_incidente"] = $inc->tipoIncidente->descripcion;
                            //$incidencia["data"]["urbanizacion_nombre"] = $inc->urbanizacion->descripcion;
                            //$incidencia["data"]["territorio_vecinal_nombre"] = $inc->urbanizacion->territorioVecinal->descripcion;
                            $incidencia["data"]["src_imagen"] = $inc->src_imagen;
                            $incidencia["data"]["hora"] = $inc->hora;
            
                            if(!is_null($inc->tipoIncidente)) {
                               $incidencia["data"]["tipo_incidente"] = $inc->tipoIncidente->descripcion;
                            }
            
                            $incidencia["data"]["urbanizacion_nombre"] = $inc->urbanizacion->descripcion;
                            $incidencia["data"]["territorio_vecinal_nombre"] = $territorioVecinal->descripcion;
                            $incidencia["ciudadano"] = $inc->persona;
            
                            $incidencia["detalle_incidente"] = $detalle_incidencia;
                            $incidencia["media"] = $inc->incidentesmedia;
                            $incidencia["atencion"] = $inc->atencionIncidente;
            
                            for($i = 0; $i < count($incidencia["atencion"]); $i++) {
                                $persona_atencion = Persona::find($incidencia["atencion"][$i]["persona_id"]); 
                                $persona_atencion_incidente = $persona_atencion->ape_paterno." ".$persona_atencion->ape_materno." ".$persona_atencion->nombres;
                                $incidencia["atencion"][$i]["persona_atencion"] = $persona_atencion_incidente;
                            }
                            $incidencia["polilyne"] = $inc->polylines;
                            $incidencias["incidencia"][] = $incidencia;
                        }
                        return response()->json($incidencias);
                    }else {
                        // $incidencia = $incidente;
                        // return response()->json($incidencia);
                        return ['success' => false, 'message' => 'No existen incidentes'];
                    }
                }else {
                    return ['success' => false, 'message' => 'No existen incidentes'];
                }
            }
        }else {
            return ['success' => false, 'message' => 'El usuario no es Alcalde ni pertenece al Comité de Gestión de Territorio Vecinal'];
        }*/
    }

    /** -- api -- */
    // Listar Incidencias verificadas por Alcalde o comite
    // JCRN 14112018
    public function getIncidentesValidadasByAlcaldeDet($id)
    {
        // Objeto Personas 4 = Alacalde // 5 = Comite Vecinal
        // $alcaldes = DB::table('persona')
        //                 ->where('id', $id)
        //                 ->whereIn('tipo_persona_id', [4,5])->get();

        // JJDCH 25062019 - Optimizando servicio
        $incidente = null;

        if(!is_null($id) && (int)$id != 0) {
            $incidente = Incidente::with("estadoIncidente", "polylines", "tipoIncidente")
                                    ->where("persona_id_validador", $id)
                                    ->WhereIn('estado_incidente_id',[2,4])
                                    ->get();
        }
        return $incidente;    


        /*$alcaldes = Persona::where("id", $id)->whereIn("tipo_persona_id", [4, 5])->get();

        if(count($alcaldes)>0)
        {
            foreach ($alcaldes as $alcalde) {

                // En función al tipo de persona Alcalde o Comite buscamos el territorio vencinal
                // Al que pertenece

                if ($alcalde->tipo_persona_id == 4) {
                    $alcaldecomite = AlcaldeVecinal::where("persona_id", $alcalde->id)->first();
                }
                elseif ($alcalde->tipo_persona_id == 5) {
                    $alcaldecomite = ComiteGestion::where("persona_id", $alcalde->id)->first();
                }

                // Validamos que el alcalde o comite tenga asignado un territorio vecinal
                if (count($alcaldecomite)>0) 
                {
                    // Obtenemos las incidentes del territorio vecinal obtenido
                    $incidentes = Urbanizacion::where("territorio_vecinal_id",$alcaldecomite->territorio_vecinal_id)->get();

                    // Ordenamos en un arregle las incidentes
                    $urbs = [];
                    foreach ($incidentes as $urb) 
                    {   
                        array_push($urbs,$urb->id);
                    }

                    // Listamos los incidentes sin confirman de las urbanizacines de un territorio vecinaL
                    // $incidente = Incidente::with('urbanizacion')
                    //                 ->WhereIn('estado_incidente_id',[2,4])
                    //                 ->whereIn('urbanizacion_id',$urbs)
                    //                 ->get();

                    $incidente = Incidente::with("estadoIncidente")
                        ->WhereIn('estado_incidente_id',[2,4])
                        ->whereIn('urbanizacion_id',$urbs)
                        ->get();
                        
                    if(!is_null($incidente) && count($incidente) > 0) {
                        foreach($incidente as $inc) {
                            $territorioVecinal = $inc->urbanizacion->territorioVecinal;
                            $detalle_incidencia = null;
                            if(isset($inc["tipo_incidente_id"]) && $inc["tipo_incidente_id"] == 1) {
                                $inundacion = $inc->inundacion;
                                $detalle_incidencia["id"] = $inundacion->id;
                                $detalle_incidencia["tipo_inundacion"] = $inundacion->tipo_inundacion;
                                $detalle_incidencia["incidente_id"] = $inundacion->incidente_id;
                                $detalle_incidencia["nivel_agua_id"] = $inundacion->nivel_agua_id;
                                $detalle_incidencia["created_at"] = $inundacion->created_at->toDateTimeString();
                                $detalle_incidencia["updated_at"] = $inundacion->updated_at->toDateTimeString();
                                $detalle_incidencia["nivel_agua"] = $inundacion->nivelAgua->descripcion;
                            }elseif(isset($inc["tipo_incidente_id"]) && $inc["tipo_incidente_id"] == 2) {
                                $calleObstaculo = $inc->calleObstaculo;
                                $detalle_incidencia["id"] = $calleObstaculo->id;
                                $detalle_incidencia["incidente_id"] = $calleObstaculo->incidente_id;
                                $detalle_incidencia["tipo_obstaculo_id"] = $calleObstaculo->tipo_obstaculo_id;
                                $detalle_incidencia["created_at"] = $calleObstaculo->created_at->toDateTimeString();
                                $detalle_incidencia["updated_at"] = $calleObstaculo->updated_at->toDateTimeString();
                                $detalle_incidencia["tipo_obstaculo"] = $calleObstaculo->tipoObstaculo->descripcion;
                            }
                                        
                            $incidencia["data"]["id"] = $inc->id;
                            $incidencia["data"]["fecha"] = $inc->fecha;
                            $incidencia["data"]["descripcion"] = $inc->descripcion;
                            $incidencia["data"]["direccion"] = $inc->direccion;
                            $incidencia["data"]["latitud"] = $inc->latitud;
                            $incidencia["data"]["longitud"] = $inc->longitud;
                            $incidencia["data"]["imagen"] = $inc->imagen;
                            $incidencia["data"]["urbanizacion_id"] = $inc->urbanizacion_id;
                            $incidencia["data"]["persona_id"] = $inc->persona_id;
                            $incidencia["data"]["persona_id_validador"] = $inc->persona_id_validador;
                            $incidencia["data"]["estado_incidente_id"] = $inc->estado_incidente_id;
                            $incidencia["data"]["tipo_incidente_id"] = $inc->tipo_incidente_id;
                            $incidencia["data"]["created_at"] = $inc->created_at->toDateTimeString();
                            $incidencia["data"]["updated_at"] = $inc->updated_at->toDateTimeString();
                            $incidencia["data"]["estado_incidente_descripcion"] = $inc->estadoIncidente->descripcion;
                            $incidencia["data"]["estado_incidente_color"] = $inc->estadoIncidente->color;
                            // $incidencia["data"]["tipo_incidente"] = $inc->tipoIncidente->descripcion;
                            // $incidencia["data"]["urbanizacion_nombre"] = $inc->urbanizacion->descripcion;
                            // $incidencia["data"]["territorio_vecinal_nombre"] = $inc->urbanizacion->territorioVecinal->descripcion;
                            $incidencia["data"]["src_imagen"] = $inc->src_imagen;
                            $incidencia["data"]["hora"] = $inc->hora;
            
                            if(!is_null($inc->tipoIncidente)) {
                                $incidencia["data"]["tipo_incidente"] = $inc->tipoIncidente->descripcion;
                            }
            
                            $incidencia["data"]["urbanizacion_nombre"] = $inc->urbanizacion->descripcion;
                            $incidencia["data"]["territorio_vecinal_nombre"] = $territorioVecinal->descripcion;
                            $incidencia["ciudadano"] = $inc->persona;
            
                            $incidencia["detalle_incidente"] = $detalle_incidencia;
                            $incidencia["media"] = $inc->incidentesmedia;
                            $incidencia["atencion"] = $inc->atencionIncidente;
            
                            for($i = 0; $i < count($incidencia["atencion"]); $i++) {
                                $persona_atencion = Persona::find($incidencia["atencion"][$i]["persona_id"]);
                                $persona_atencion_incidente = $persona_atencion->ape_paterno." ".$persona_atencion->ape_materno." ".$persona_atencion->nombres;
                                $incidencia["atencion"][$i]["persona_atencion"] = $persona_atencion_incidente;
                            }
                            $incidencia["polilyne"] = $inc->polylines;
                            $incidencias["incidencia"][] = $incidencia;
                        }
                        return response()->json($incidencias);
                    }else {
                        return response()->json($incidente);
                    }
                }       
                else
                {
                    return ['success' => false, 'message' => 'El usuario no tiene asignado un Territorio Vecinal']; 
                }  
            }       
        }
        else
        {
            return ['success' => false, 'message' => 'El usuario no es Alcalde ni pertenece al comite de gestión vecinal'];    
        }*/        
    }


    /** -- api -- */
    // Listar Incidencias Por Estado y Fechas
    // JCRN 14112018

    function puntoEnPoligono($point, $poligono, $pointVertex = true) {
        // Transformar la cadena de coordenadas en matrices, con valores "x" e "y"
        $vertices = array();
        $puntos_poligono = explode(";", $poligono);
        foreach($puntos_poligono as $vertex) {
            $vertices[] = $this->pointStringToCoordinates($vertex);
        }

        // Verificar si el punto se encuentra exactamente en un vértice
        if($pointVertex and $this->pointOnVertex($point, $vertices) == true) {
            return "vertice";
        }

        // Verificar si el punto está dentro del polígono o en el borde
        $intersections = 0;
        $vertices_count = count($vertices);

        for($i=1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i-1];
            $vertex2 = $vertices[$i];

            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Checar si el punto está en un segmento horizontal
                return "boundary";
            }

            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) { 
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x']; 
                if ($xinters == $point['x']) { // Checar si el punto está en un segmento (otro que horizontal)
                    return "boundary";
                }
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
                    $intersections++; 
                }
            }
        }

        // Si el número de intersecciones es impar el punto está dentro del polígono
        if($intersections % 2 != 0) {
            return ["status" => "inside", "message" => "Punto señalado dentro de coordenadas"];
        }else {
            return ["status" => "outside", "message" => "Punto señalado fuera de coordenadas"];
        }
    }

    function pointOnVertex($point, $vertices) {
        foreach($vertices as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }
    }

    function pointStringToCoordinates($pointString) {
        $coordinates = explode(",", trim($pointString));
        return array("x" => $coordinates[0], "y" => $coordinates[1]);
    }

  // //EXPORTACIONES DE ATENCIONES DE INCIDENTES A EXCEL
  public function exportarAtenciones(Request $request){
    $fecha = $request->get('fecha');
    $estadoIncidente = $request->get('estadoIncidente');
    $incidentes = $this->fnObtenerDataAtencionIncidentes($fecha,$estadoIncidente);
    // dd($incidentes);
    $fechaArchivo = now();
    $filename = "atencionincidentes-{$fechaArchivo}";
    Excel::create($filename, function($excel) use($incidentes,$fechaArchivo,$fecha,$estadoIncidente){
      $excel->sheet("Atencion_incidentes", function($sheet) use($incidentes,$fechaArchivo,$fecha,$estadoIncidente){
        $sheet->mergeCells("A1:E1");
        $sheet->cell("A1", function($cell) {
            $cell->setValue("Listado de atención de incidentes");
            $cell->setAlignment("center");
            $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
        });
        $sheet->mergeCells("A2:E2");
        $sheet->cell("A2", function($cell) use($fechaArchivo) {
            $cell->setValue("Fecha : {$fechaArchivo}");
            $cell->setAlignment("center");
            $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
        });
        $sheet->cell("A3", function($cell) {
          $cell->setValue("FILTROS");
          $cell->setAlignment("center");
          $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
        });
        $sheet->mergeCells("B3:C3");
        $sheet->cell("B3", function($cell) use($fecha) {
          $cell->setValue("Fecha : {$fecha}");
          $cell->setAlignment("center");
          $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
        });
        $sheet->mergeCells("D3:E3");
        $sheet->cell("D3", function($cell) use($estadoIncidente) {
          $cell->setValue("Estado : {$estadoIncidente}");
          $cell->setAlignment("center");
          $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
        });
        $sheet->cell("A4", function($cell) {$cell->setValue("Fecha"); $cell->setAlignment("center"); });
        $sheet->cell("B4", function($cell) {$cell->setValue("Territorio Vecinal"); $cell->setAlignment("center"); });
        $sheet->cell("C4", function($cell) {$cell->setValue("Urbanización"); $cell->setAlignment("center"); });
        $sheet->cell("D4", function($cell) {$cell->setValue("Desripción"); $cell->setAlignment("center"); });
        $sheet->cell("E4", function($cell) {$cell->setValue("Estado"); $cell->setAlignment("center"); });
        if(!empty($incidentes)) {
          foreach($incidentes as $key => $value) {
            $i = $key + 5;
            $sheet->cell('A'.$i, $value->fecha);
            $sheet->cell('B'.$i, $this->fnObtenerTV($value->urbanizacion_id));
            $sheet->cell('C'.$i, $this->fnObtenerUrbanizacion($value->urbanizacion_id));
            $sheet->cell('D'.$i, $value->descripcion);
            $sheet->cell('E'.$i, $this->fnObtenerNombreEstadoIncidente($value->estado_incidente_id));
          }
        }
      });
    })->export('xlsx');
  }

  /**
   * Obtiene la data de la atencion de incidentes en base a los filtros ingresados
   *
   * @param string $fecha
   * @param int $estadoIncidente
   * @return array
   */
  private function fnObtenerDataAtencionIncidentes($fecha,$estadoIncidente){
    return Incidente::with(['estadoIncidente', 'polylines', 
                            'persona','urbanizacion',
                            'urbanizacion.territorioVecinal', 'tipoIncidente', 
                            'inundacion', 'inundacion.nivelAgua',
                            'calleObstaculo', 'calleObstaculo.tipoObstaculo', 
                            'atencionIncidente', 'coordinaciones', 'incidentesmedia'])
                            ->whereIn('estado_incidente_id', [1,2,4,5])
                            ->filterFecha($fecha)
                            ->filterEstado($estadoIncidente)
                            ->orderBy("created_at", "DESC")
                            ->get();
  }

  /**
   * Obtiene el nombre asociado a un codigo de incidente
   *
   * @param int $estadoIncidente
   * @return string
   */
  private function fnObtenerNombreEstadoIncidente($estadoIncidente){
    $nombres = ['Sin Confirmar','Confirmado','Falso Positivo','Atendido'];
    return $nombres[$estadoIncidente-1];
  }

  /**
   * Obtiene la descripcion del territorio vecinal
   *
   * @param int $codigoTV
   * @return string
   */
  private function fnObtenerTV($codigoUrb){
    $urb = Urbanizacion::findOrFail($codigoUrb);
    $tv = $urb->territorioVecinal;
    return $tv->descripcion;
  }

  /**
   * Obtiene la descripcion de la urbanizacion
   *
   * @return void
   */
  private function fnObtenerUrbanizacion($codigoUrb){
    $urb = Urbanizacion::select('descripcion')->where('id',$codigoUrb)->first();
    return $urb->descripcion;
  }

}
