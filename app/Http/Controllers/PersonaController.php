<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonaRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Faker\Provider\Person;
use Illuminate\Http\Request;
use App\Persona;
use App\User;
use App\Rol;
use App\PuntuacionPersona;
use App\ActividadPuntuacion;
use App\TipoPersona;
use App\EstadoPersona;
use Illuminate\Support\Facades\DB;
use Excel;
use Session;

class PersonaController extends Controller
{
      /* FUNCIONALIDADES PARA MEJORAR LA INTERACTIVIDAD */
      private function fnSetPaginaActual(){
        $paginaActual = Session::get('paginaActualPer');
        if($paginaActual==null)
          Session::put('paginaActualPer',1);
      }
    
      public function getPageSession(){
        $this->fnSetPaginaActual();
        $paginaSession = Session::get('paginaActualPer');
        return ['success'=>true , 'paginaActual'=>$paginaSession];
      }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Persona $persona,$validacion)
    {
        $this->authorize('view', [$persona, 'Persona']);
        if($validacion==0)
          Session::put('paginaActualPer',1);
        if($validacion==1)
          $this->fnSetPaginaActual();
        return view('persona.index');
    }

    public function inactivePerson(Persona $persona)
    {
        $this->authorize('active', [$persona, 'Persona']);

        return view('persona.inactive');
    }

    public function allestadoPersona(Persona $persona){

        $this->authorize('view', [$persona, 'Persona']);

        $estadoPersona = EstadoPersona::all();

        return ["success" => true, "estadopersona" => $estadoPersona];
    }


    public function all(Persona $persona,Request $request)
    {
      $nombre = $request->get('nombre');
      $tipopersona = $request->get('tipopersona');
      $dni = $request->get('dni');

      $this->authorize('view', [$persona, 'Persona']);
      $personas = Persona::with(['tipopersona','user','user.roles'])
                          ->filterNombre($nombre)
                          ->filterTipoPersona($tipopersona)
                          ->filterDni($dni)
                          ->get();  
        
      $currentPage = LengthAwarePaginator::resolveCurrentPage()-1;
      $collection = new Collection($personas);
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

      return ['success'=>true , 'personas'=>$paginadoResultados, "pagination" => $pagination];
    }

    public function inactivePersons(Persona $persona)
    {
        // JJDCH 24062019 Actualizo consulta de ciudadanos que deben activarse
        $this->authorize('active', [$persona, 'Persona']);
        $personas = Persona::with(['tipopersona','user','user.roles'])
                              ->where('persona.state','Inactivo')
                              ->get();

        return ['success'=>true , 'personas'=>$personas];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Persona $persona)
    {
        $this->authorize('create', [$persona, 'Persona']);

        return view('persona.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Persona $persona,PersonaRequest $request)
    {
        $this->authorize('create', [$persona, 'Persona']);

        DB::beginTransaction();

        try {
            $data = $request->all();

            $persona = Persona::create($data);

            // Registrando un usuario
            $user = User::create([
                        //'name'          => $data['nombres'],
                        'email'         => $data['mail'],
                        'password'      => bcrypt($data['dni']),
                        //"last_name"     => $data['nombres'],
                        "admin"         => 0,
                        "state"         => $data['state'],
                        "persona_id"    => $persona->id,
                        "rol_id"        => $persona->rol_id,
                        "token"         => ''
                    ]);
            //Asignación de rol ciudadano al usuario registrado.
            $user->roles()->attach($persona->rol_id);

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception'=>$exception->getMessage()];
        }
    }

    /* GENERACION DE EXCEL */
public function exportarAExcel(Persona $persona, Request $request)
{
  $nombre = $request->get('nombre');
  $tipopersona = $request->get('tipopersona');
  $dni = $request->get('dni');

  $personas = Persona::with(['tipopersona'])
                      ->filterNombre($nombre)
                      ->filterTipoPersona($tipopersona)
                      ->filterDni($dni)
                      ->get();  

  $fecha = now();
  $filename = "personas-{$fecha}";

  Excel::create($filename, function($excel) use($personas,$nombre,$tipopersona,$dni,$fecha){
    $excel->sheet("Personas", function($sheet) use($personas,$nombre,$tipopersona,$dni,$fecha){
      $sheet->mergeCells("A1:G1");
      $sheet->cell("A1", function($cell) {
          $cell->setValue("Listado de personas");
          $cell->setAlignment("center");
          $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
      });
      $sheet->mergeCells("A2:G2");
      $sheet->cell("A2", function($cell) use($fecha) {
          $cell->setValue("Fecha : $fecha");
          $cell->setAlignment("center");
          $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
      });
      $sheet->cell("A3", function($cell) {
        $cell->setValue("FILTROS");
        $cell->setAlignment("center");
        $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
      });
      $sheet->mergeCells("B3:C3");
      $sheet->cell("B3", function($cell) use($nombre) {
        $cell->setValue("Nombre : {$nombre}");
        $cell->setAlignment("center");
        $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
      });
      $sheet->mergeCells("D3:E3");
      $sheet->cell("D3", function($cell) use($tipopersona) {
        $cell->setValue("Tipo persona : {$this->fnObtenerTipoPersona($tipopersona)}");
        $cell->setAlignment("center");
        $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
      });
      $sheet->mergeCells("F3:G3");
      $sheet->cell("F3", function($cell) use($dni) {
        $cell->setValue("Dni : {$dni}");
        $cell->setAlignment("center");
        $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
      });
      $sheet->cell("A4", function($cell) {$cell->setValue("Dni"); $cell->setAlignment("center"); });
      $sheet->cell("B4", function($cell) {$cell->setValue("Nombre"); $cell->setAlignment("center"); });
      $sheet->cell("C4", function($cell) {$cell->setValue("Apellidos"); $cell->setAlignment("center"); });
      $sheet->cell("D4", function($cell) {$cell->setValue("Tipo persona"); $cell->setAlignment("center"); });
      if(!empty($personas)) {
        foreach($personas as $key => $value) {
          $i = $key + 5;
          $sheet->cell('A'.$i, $value->dni);
          $sheet->cell('B'.$i, "{$value->nombres}");
          $sheet->cell('C'.$i, "{$value->ape_paterno} {$value->ape_materno}");
          $sheet->cell('D'.$i, $value->tipopersona->descripcion);
        }
      }
    });
  })->download('xls');
}

private function fnObtenerTipoPersona($tipopersona){
  if(!$tipopersona)
    return $tipopersona;
  $tipopersona = TipoPersona::findOrFail($tipopersona);
  return $tipopersona->descripcion;
}

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Persona $persona)
    {
        $this->authorize('view', [$persona, 'Persona']);

        return ['sucess'=>true, 'persona'=>$persona];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Persona $persona,$personaId,$pagina)
    {
        $this->authorize('view', [$persona, 'Persona']);
        Session::put('paginaActualPer',$pagina);
        return view('persona.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Persona $persona, PersonaRequest $request)
    {
        $this->authorize('update', [$persona, 'Persona']);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $persona->update($data);

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception'=>$exception->getMessage()];

        }
    }

    public function active_persona(Persona $persona, Request $request)
    {
        $this->authorize('update', [$persona, 'Persona']);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $persona->update($data);

            // Obtenemos el estado asignado a la persona
            $estado_persona = ($data['state']=='Activo')? 2 : 1;

            // Obtenemos valores de Actividad Puntuacion
            $actividad_puntuacion = ActividadPuntuacion::where('estado_incidente_id',$estado_persona)
                                                        ->where('descripcion','Registro de Usuario')->first();

            // Registro la puntuación de la persona
            PuntuacionPersona::create([
                "numero_incidente"          => 0,
                "actividad_puntuacion_id"   => $actividad_puntuacion->id,
                "persona_id"                => $data['id']
            ]);


            DB::commit();

            // Obtenemos la token del ciudadano para notificarle
            $ciudadano = User::where('persona_id',$data['id'])->first();
            $token[] = $ciudadano->token;

            // Title de la Notificacion
            $title = "Validación de Ciudadano";

            // Message de la Notificación
            $estado = ($data['state']=='Activo')? 'ya puede registrar incidencias' : 'verifique que sus datos estén correctos';
            $message = "Estimado ciudadano su cuenta ha sido validada como ".$data['state'].", ".$estado;
            
            // Valores adicionales de notificacion.
            $plataforma = "Otros";
            $id_incidente = 0; 

            // Enviamos notificacion
            $notify = $this->send_notificacion_movil($token, $title, $message, $plataforma, $id_incidente);

            return ['success' => true, 'message' => "Ciudadano ".$persona->nombres.", ".$persona->ape_paterno." ".$persona->ape_materno." validado correctamente"];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception'=>$exception->getMessage()];

        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Persona $persona)
    {
        $this->authorize('delete', [$persona, 'Persona']);

        DB::beginTransaction();

        try {

            $user = User::where('persona_id',$persona->id)->first();
            if($user)
            {
                $user->delete();
            }
            
            $persona->delete();

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.'];

        }
    }

    public function search(Request $request)
    {
        $q = $request->get('q');
        $filter = $request->get('filter');

        $personas = Persona::whereRaw('CONCAT(nombres," ", ape_paterno, " ", ape_materno) like "%'.$q.'%"')
                    ->orWhere('dni', 'like', '%'.$q.'%')
                    ->filter($filter)
                    ->get();

        return $personas;

    }

    //PERMITE TRAER TODOS LOS TIPOS DE PERSONAS
    public function getTipoPersona(){
      $tipoPersona = TipoPersona::select('id','descripcion')->get();
      return ['sucess'=>true, 'tipopersona'=>$tipoPersona];
    }

    /** -- api -- */
    public function getPersonas()
    {
        $personas = Persona::get();
        return response()->json($personas);
    }

    public function getPersonaById($id)
    {
        $persona = Persona::find($id);
        return response()->json($persona);
    }

    public function udpPersonaById(Request $request)
    {
        // Obtengo los valores que actualizare
        $id = $request->input("id_persona");
        $ape_paterno = $request->input("ape_paterno");
        $ape_materno = $request->input("ape_materno");
        $nombres = $request->input("nombres");
        $direccion = $request->input("direccion");
        $urbanizacion = $request->input("id_urbanizacion");

        // Obtengo Persona
        $persona = Persona::find($id);

        //Actualizo datos de la persona
        $persona->ape_paterno = $ape_paterno;
        $persona->ape_materno = $ape_materno;
        $persona->nombres = $nombres;
        $persona->direccion = $direccion;
        $persona->urbanizacion_id = $urbanizacion;
        
        // Registrando datos de persona
        $persona->save();

        $data["status"] = true;
        $data["mensaje"] = "Datos actualizados con éxito";
        $data["persona"] = $persona;

        return response()->json($data);       
    }

    // Envío de notificación aplicación
    // 21-07-2018 JJDCH

    public function send_notificacion_movil($tokens, $title, $message, $plataforma, $id_incidente)
    {
        //key de firebase
        define('FIREBASE_API_KEY', 'AAAAvWiPr60:APA91bGhcS8uSNjl4bAkmFA9Ovg_ZbRNuv1hV6PBQvxnuNg93jiLP5HGBpQIK2Gqiv8SHqfzibs94Xc46rH2RT33djVhwv1AE8KiHY5Quj-Pc54fB3sWkEVvgLZlBKFQ9wV9DPojtSAW');

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
        //$res['data']['title'] = $incidente->TipoIncidente->descripcion;//"INUNDACION " ;//ESTE ES EL TITULO DE LA OTIFICACION
        $res['data']['title'] = $title;//"INUNDACION " ;//ESTE ES EL TITULO DE LA OTIFICACION
        //$res['data']['message'] =  $incidente->direccion; //DESCIPCION DE LA NOTIFICACION
        $res['data']['message'] = $message;
        $res['data']['timestamp'] = date('Y-m-d H:i a');
        $res['data']['plataforma'] = $plataforma;  //plataforma -> siempre sera eda
        //$res['data']['incidencia'] = $incidente->id;  //id del incidente registrado
        $res['data']['incidencia'] = $id_incidente;

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


}
