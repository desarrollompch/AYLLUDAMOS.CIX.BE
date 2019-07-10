<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Familiar;
use App\FamiliarUbicacion;
use App\BotonPanico;
use App\BotonPanicoUser;
use App\Persona;
use App\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FamiliarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /** -- api -- */
    // Registro de familiares por persona
    // JJDCH 30062018
    public function nuevoRegistroFamiliar(Request $request){

        // Obtengo los datos de la persona
        $persona_id = $request->input("id_persona");
        $nombres = $request->input("nombres");
        $telefono = $request->input("telefono");  

       // Registrando un usuario
        Familiar::create([
            'nombres'     => $nombres,
            'telefono'    => $telefono,
            'persona_id'  => $persona_id
        ]);

        $data["status"] = true;
        $data["mensaje"] = "Familiar registrado con éxito";

        return response()->json($data);
    }

    /** -- api -- */
    // Listado de familiares por persona
    // JJDCH 29062018

    public function getFamiliaresbyPersona(Request $request){

        // Obtengo los datos de la persona
        $persona_id = $request->input("id_persona");  

        // Obtengo Datos de los familiares
        $familiar = Familiar::where("persona_id", $persona_id)->get();

        return response()->json($familiar); 

    }

    /** -- api -- */
    // Eliminar familiar por ID
    // JJDCH 29062018
    public function delFamiliarbyId(Request $request){

        // Obtengo los datos de la persona
        $familiar_id = $request->input("id_familiar"); 

        // Elimino Familiar
        $familiar = Familiar::find($familiar_id);
        $familiar->delete();

        $data["status"] = true;
        $data["mensaje"] = "Familiar eliminado correctamente";
        
        return response()->json($data);
    }

    /** -- api -- */
    // Listar Ubicacion familiar por ID
    // JJDCH 02072018
    public function getUbicacionFamiliarbyId($id)
    {
        // Obtengo Datos de ubicación de los familiares
        $familiarubicacion = FamiliarUbicacion::where("familiar_id", $id)->get();

        return response()->json($familiarubicacion); 
    }

    /** -- api -- */
    // Registrar Ubicación de un familiar
    // JJDCH 02072018
    public function nuevoRegistroUbicacionFamiliar(Request $request)
    {
        DB::beginTransaction();

        try {
            
            $data = $request->all();
            // Obtenemos los familiares que han agregado al usuario como familiar, para que puedan  ver su ubicación
            $familiares = Familiar::where("telefono", $data['telefono'])->get();            

            // Datos del usuario que esta registrando su ubicación
            $persona = Persona::where("telefono", $data['telefono'])->first();
            $usuario = $persona->nombres." ".$persona->ape_paterno." ".$persona->ape_materno;

            //Notifico mediante la app a sus familiares

            $mensaje = "El usuario ".$usuario." envio su ubicación http://maps.google.com/maps?q=".$data['latitude'].",".$data['longitude']."";
            $dataEmail["title"] = "Estado actual de ubicación del usuario";
            $dataEmail["message"] = $mensaje;      
            $notifyFamiliar = $this->fnCrearNotificacionFamiliar($persona->id,$dataEmail,'Otros');   

            if (count($familiares)>0) {
                foreach ($familiares as $familiar) {

                    // Obtengo el id de la persona
                    $id_familiar = $familiar['id'];
                    $data['familiar_id'] = $id_familiar;

                    // Registro la ubicación del familiar                
                    FamiliarUbicacion::create($data);
                    DB::commit();                    
                }                        

                return ['success' => true, 'message' => "Ubicación registrada correctamente", "notifyFamiliar" => $notifyFamiliar];
            }
            else
            {
                return ['success' => false, 'message' => "El teléfono no se encuentra asociado a un familiar"];
            }               

        } catch (\Exception $exception) {
            DB::rollBack();
            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception'=>$exception->getMessage()];
        }
    }

    /** -- api -- */
    // Listar ubicaciones registradas por telefono
    // JJDCH 05092018
    public function getUbicacionbyTelefono($telefono)
    {
        // Obtengo Datos de ubicación de una persona en función a su numero de telefono.
        $ubicacion = Familiar::with('FamiliarUbicacion')->where("telefono", $telefono)->get();
        return response()->json($ubicacion); 
    }

    /** -- api -- */
    // Eliminar ubicaciones registradas por familiar
    // JCRN 05102018
    public function eliminarUbicacionesFamiliar($id)
    {
        DB::beginTransaction();

        try {
            DB::table("familiar_ubicacion")->where("id", $id)->delete();

            DB::commit();

            return ["success" => true, "message" => "Ubicaciones eliminadas correctamente"];
        }catch (\Exception $exception) {
            DB::rollBack();
            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception'=>$exception->getMessage()];
        }
    }

    /** -- api -- */
    // Eliminar ubicaciones de manera masiva
    // JCRN 12112018
    public function eliminarUbicacionesMasivo($ids)
    {
        DB::beginTransaction();

        try {
            $ids = trim($ids);
            $listado_ids = explode("-", $ids);
            $result = false;
            foreach($listado_ids as $item) {
                DB::table("familiar_ubicacion")->where("id", $item)->delete();
                DB::commit();
                $result = true;
            }

            if($result) {
                return ["success" => true, "message" => "Ubicaciones eliminadas correctamente"];
            }else {
                return ["success" => false, "message" => "No se realizó la operación correctamente"];
            }

        }catch (\Exception $exception) {
            DB::rollBack();
            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception'=>$exception->getMessage()];
        }
    }

    /** -- api -- */
    // Vista que mostrará todas las ubicaciones
    // JCRN 13102018
    public function listarUbicaciones() {
        return view("ubicacion.index");
    }

    /** -- api -- */
    // Listar ubicaciones
    // JCRN 13102018
    public function ubicacionesAll(Request $request) {
        $fecha_inicio = $request->get("fecha_inicio");
        $fecha_final =  $request->get("fecha_final");

        if(is_null($fecha_inicio) && is_null($fecha_final)) {
            $fecha_actual = Carbon::now()->toDateString();
            $ubicaciones = FamiliarUbicacion::with("familiar")
                ->where(DB::raw("DATE(fecha)"), $fecha_actual)
                ->get();
        }else {
            $parts_date_inicio = explode("/", $fecha_inicio);
            $parts_date_final = explode("/", $fecha_final);

            $date_inicio = $parts_date_inicio[2]."-".$parts_date_inicio[1]."-".$parts_date_inicio[0];
            $date_final = $parts_date_final[2]."-".$parts_date_final[1]."-".$parts_date_final[0];

            $ubicaciones = FamiliarUbicacion::with("familiar")
                ->whereBetween(DB::raw("DATE(fecha)"), [$date_inicio, $date_final])
                ->get();
        }

        $listado = [];
        foreach($ubicaciones as $ubicacion) {
            $nombres = (is_null($ubicacion->familiar) ? "USER" : "USER".(string)$ubicacion->familiar->id);
            $telefono = (is_null($ubicacion->familiar) ? "" : $ubicacion->familiar->telefono);
            $item = array(
                "id" => $ubicacion->id,
                "familiar_id" => $ubicacion->familiar_id,
                "latitude" => $ubicacion->latitude,
                "longitude" => $ubicacion->longitude,
                "descripcion" => $ubicacion->descripcion,
                "nombres" => $nombres,
                "telefono" => $telefono,
                "fecha" => Carbon::parse($ubicacion->fecha)->format('d/m/Y h:m:s a'),
                "descripcion" => $ubicacion->descripcion
            );

            $listado[] = $item;
        }

        return response()->json($listado);
    }

    /** -- api -- */
    // Notificar no registro de ubicación
    // JCRN 17102018
    // public function notificarNoUbicacion($telefono, $registroUbicacion) {
    //     if($registroUbicacion == false) {
    //         $persona = Persona::where("telefono", $telefono)->first();
    //         $usuario = $persona->nombres." ".$persona->ape_paterno." ".$persona->ape_materno;
    //         $familiares = Familiar::where("telefono", $telefono)->get();
    //         $dataEmail["title"] = "Estado actual de ubicación de usuario";
    //         $dataEmail["message"] = "El usuario ".$usuario." no está enviando su ubicación";
    //         $notifyFamiliar = $this->fnCrearNotificacionFamiliar($persona->id,$dataEmail,'Otros');  
            
    //         foreach ($familiares as $familiar){
    //             $emails[] = $familiar->persona->mail;
    //         }

    //         Mail::send('emails.notify.familiar', ['data' => $dataEmail, 'titulo'=>'Notificación de ubicación'], function ($m) use ($emails) {
    //             $m->from('aylludame@app.com', 'Administrador');
    //             $m->subject('Notificación de ubicación / '.date('d-m-y'));
    //             foreach ($emails as $email) {
    //                 $m->to($email);
    //             }
    //         });

    //         $message["success"] = true;
    //         $message["mensaje"] = "Los familiares han sido notificiados";
    //         $message["notificacionFamiliar"] = $notifyFamiliar;

    //         return response()->json($message);
    //     }else {
    //         $message["success"] = false;
    //         $message["mensaje"] = "Sin notificaciones";
    //         return response()->json($message);
    //     }
    // }

    /** -- api -- */
    // Notificar no registro de ubicación
    // JCRN 18102018
    public function notificarUbicacionAutomatica() {
        $hora_actual = Carbon::now()->toTimeString();
        $filas = BotonPanicoUser::where("accion", "ACTIVO")->get();
        $resultados = [];
        foreach($filas as $fila) {
            $frecuencia = (int)$fila->tiempo;
            $telefono = $fila->telefono;
            $user_id = $fila->user_id;
            $user = User::with("persona")->select("persona_id")->where("id", $user_id)->first();
            // $familiares = Familiar::where("persona_id", $user->persona_id)->get()->pluck("id")->toArray();
            $familiares = Familiar::where("telefono", $telefono)->get()->pluck("id")->toArray();
            if(!is_null($familiares) && count($familiares) > 0) {
                $idFamiliar = $familiares[0];
                $ultimaUbicacion = FamiliarUbicacion::where("familiar_id", $idFamiliar)
                    ->orderBy("created_at", "DESC")
                    ->first();
                $fecha_registrada = Carbon::parse($ultimaUbicacion->fecha)->addMinute($frecuencia);
                $hora_registrada = $fecha_registrada->toTimeString();
                if($hora_actual > $hora_registrada) {
                    $dia_registrado = $fecha_registrada->toDateString();
                    $usuario = $user->persona->nombres." ".$user->persona->ape_paterno." ".$user->persona->ape_materno;
                    $mensaje = "El usuario ".$usuario." no está enviando su ubicación\nSu última úbicación fué el día ".$dia_registrado." a las ".$hora_registrada;
                    $dataEmail["title"] = "Estado actual de ubicación del usuario";
                    $dataEmail["message"] = $mensaje;                    
                    $notifyFamiliar = $this->fnCrearNotificacionFamiliar($user->persona_id,$dataEmail,'Otros');  
                    
                    foreach ($familiares as $familiar){
                        $emails[] = $familiar->persona->mail;
                    }

                    Mail::send('emails.notify.familiar', ['data' => $dataEmail, 'titulo'=>'Notificación de ubicación'], function ($m) use ($emails) {
                        $m->from('aylludame@app.com', 'Administrador');
                        $m->subject('Notificación de ubicación / '.date('d-m-y'));
                        foreach ($emails as $email) {
                            $m->to($email);
                        }
                    });

                    $message["success"] = true;
                    $message["message"] = "Los familiares han sido notificiados";
                    $message["notificacionFamiliar"] = $notifyFamiliar;

                    return response()->json($message);
                }else {
                    $message["success"] = false;
                    $message["message"] = "Sin notificaciones";
                    return response()->json($message);
                }
            }else {
                $message["success"] = false;
                $message["message"] = "Sin notificaciones";
                return response()->json($message);
            }
        }
    }

    /** -- api -- */
    // Registro de ubicación automático
    // JCRN 15102018
    // public function registroUbicacionFamiliarAutomatico(Request $request) 
    // {
    //     DB::beginTransaction();

    //     try {
            
    //         $data = $request->all();
    //         $minutos = $data["minutos"];
            
    //         $persona = Persona::where("telefono", $data["telefono"])->first();
    //         $user_id = $persona->user->id;

    //         $config_boton_panico_user = BotonPanicoUser::where("user_id", $user_id)->get(); 
    //         $config_minutos = $config_boton_panico_user->tiempo;

    //         $familiares = Familiar::where("telefono", $data['telefono'])->get();

    //         if($config_minutos >= $minutos) {
    //             // Registro de ubicación
    //             if(count($familiares) > 0) {
    //                 foreach ($familiares as $familiar) {

    //                     // Obtengo el id de la persona
    //                     $id_familiar = $familiar['id'];
    //                     $data['familiar_id'] = $id_familiar;

    //                     // Registro la ubicación del familiar                
    //                     FamiliarUbicacion::create($data);
    //                     DB::commit();
    //                 }   

    //                 return ['success' => true, 'data' => "Ubicación registrada correctamente"];
    //             }
    //         }else {
    //             $usuario = $persona->nombres." ".$persona->ape_paterno." ".$persona->ape_materno;
    //             $data["title"] = "Estado actual de ubicación de usuario";
    //             $data["message"] = "El usuario ".$usuario." no está enviando su ubicación";
    //             $notifyFamiliar = $this->fnCrearNotificacionFamiliar($persona->id,$data,'Otros');  
                
    //             foreach ($familiares as $familiar){
    //                 $emails[] = $familiar->persona->mail;
    //             }

    //             Mail::send('emails.notify.familiar', ['data' => $data, 'titulo'=>'Notificación de ubicación'], function ($m) use ($emails) {
    //                 $m->from('aylludame@app.com', 'Administrador');
    //                 $m->subject('Notificación de ubicación / '.date('d-m-y'));
    //                 foreach ($emails as $email) {
    //                     $m->to($email);
    //                 }
    //             });

    //             $message["success"] = true;
    //             $message["mensaje"] = "Los familiares han sido notificiados";

    //             return response()->json($message);
    //         }          

    //     } catch (\Exception $exception) {
    //         DB::rollBack();
    //         return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception'=>$exception->getMessage()];
    //     }
    // }

    /* FUNCIONALIDAD ENVIAR NOTIFICACION A FAMILIARES */
    private function fnCrearNotificacionFamiliar($idPersona,$data,$plataforma){
        $familiares = $this->fnObtenerFamiliares($idPersona);
        $tokenFamiliar = $this->fnObtenerTokenFamiliar($familiares);
        $notificacion = $this->send_notificacion_movil($tokenFamiliar,$data,$plataforma);
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
    public function send_notificacion_movil($tokens, $data,$plataforma)
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
        $res['data']['title'] = $data['title'];//"INUNDACION " ;//ESTE ES EL TITULO DE LA OTIFICACION
        $res['data']['message'] =  $data['message']; //DESCIPCION DE LA NOTIFICACION
        $res['data']['timestamp'] = date('Y-m-d H:i a');
        $res['data']['plataforma'] = $plataforma;  //plataforma -> siempre sera eda
        $res['data']['incidencia'] = 0;  //id del incidente registrado

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
