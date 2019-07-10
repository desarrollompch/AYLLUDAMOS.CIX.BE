<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\TipoPersona;    
use App\NivelCiudadano;
use App\EstadoPersona;
use App\Urbanizacion;
use App\Rol;
use App\Persona;
use App\User;
use App\PuntuacionPersona;
use App\ActividadPuntuacion;
use App\Incidente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecuperarContrasena;

class UsuarioController extends Controller
{
    public function logueo(Request $request)
    {
        $email = $request->input("email");
        $password = $request->input("password");
        $token = $request->input("token");

        $return = $this->validarCredenciales($email, $password);

        if($return){

            //Como el usuario ya se encuentra validado obtengo su información en base a su email
            $user = User::where("email", $email)->first();
            $persona = Persona::find($user->persona_id);
            if ($persona) 
            {
                //$urbanizacion = Urbanizacion::find($persona->urbanizacion_id);
                $urbanizacion = Urbanizacion::with(['TerritorioVecinal'])->where('urbanizacion.id',$persona->urbanizacion_id)->first();
                
                //$tipopersona = TipoPersona::find($persona->tipo_persona_id);
                $tipopersona = $user->roles()->first();

                $nivel_ciudadano = NivelCiudadano::find($persona->nivel_ciudadano_id);
                $puntuacion_persona = PuntuacionPersona::where("persona_id", $persona->id)->get();
                $incidencia = Incidente::where('persona_id',$persona->id)->get();
                
                // Seteo contadores
                $puntos_persona = 0;
                $i_registradas = 0;
                $i_validadas = 0;
                $i_atendidas = 0;

                // Obtenemos los puntos del usuario
                foreach ($puntuacion_persona as $puntos) {
                    $actividad_puntuacion = ActividadPuntuacion::where("id",$puntos->actividad_puntuacion_id)->first();
                    $puntos_persona += $actividad_puntuacion->puntaje;
                }

                // Obtenemos las incidencias registradas
                foreach ($incidencia as $item) 
                {
                    $i_registradas += 1; 
                    
                    if($item->estado_incidente_id == 2)
                    { 
                        $i_validadas += 1; 
                    }
                    if($item->estado_incidente_id == 4)
                    { 
                        $i_atendidas += 1; 
                    }
                }

                DB::beginTransaction();

                try {

                    // Asigno nuevos valores
                    $user->token = $token;

                    // Guardo cambios
                    $user->save();

                    DB::commit();  

                    $data["mensaje_token"] = "Token actualizada correctamente";                  

                } catch (\Exception $exception) {
                    DB::rollBack();
                    $data["mensaje_token"] = "Token con error ".$exception->getMessage(); 
                }

               
                $data["status"] = true;
                $data["mensaje"] = "Usuario correctamente autenticado";
                $data["user_id"] = $user->id;
                //$data["user_name"] = $user->name;
                //$data["user_last_name"] = $user->last_name;
                $data["user_state"] = $persona->state;
                $data["user_email"] = $user->email;
                $data["user_token"] = $user->token;
                $data["user_persona_id"] = $user->persona_id;
                $data["user_persona_nombres"] = $user->persona->nombres;
                $data["user_persona_ape_paterno"] = $user->persona->ape_paterno;
                $data["user_persona_ape_materno"] = $user->persona->ape_materno;
                $data["user_persona_direccion"] = $persona->direccion;
                $data["user_persona_dni"] = $persona->dni;
                $data["user_persona_telefono"] = $persona->telefono;
                $data["user_urbanizacion_id"] = $urbanizacion->id;
                $data["user_urbanizacion_name"] = $urbanizacion->descripcion;
                $data["user_tipo_persona_id"] = $tipopersona->id;
                $data["user_tipo_persona_name"] = $tipopersona->descripcion;
                $data["user_nivel_ciudadano_name"] = $nivel_ciudadano->descripcion;
                $data["user_nivel_ciudadano_icono"] = $nivel_ciudadano->icono;
                $data["user_nivel_ciudadano_icono_src"] = $nivel_ciudadano->src_icono;
                //$data["user_puntuacion_persona"] = $puntuacion_persona;
                $data["user_puntuacion_persona_puntos"] = $puntos_persona;
                $data["user_incidencias_registradas"] = $i_registradas;
                $data["user_incidencias_atendidas"] = $i_atendidas;
                $data["user_incidencias_no_atendidas"] = $i_validadas;
                $data["user_territoriovecinal_id"] = $urbanizacion->TerritorioVecinal->id;
                $data["user_territoriovecinal_name"] = $urbanizacion->TerritorioVecinal->descripcion;


            }else {
                $data["status"] = false;
                $data["mensaje"] = "No se encontró persona con los datos ingresados";
            }         
            

        }else {
            $data["status"] = false;
            $data["mensaje"] = "No se encontró registro con los datos ingresados";
        }
        return response()->json($data);
    }

    public function nuevoRegistroCiudadano(Request $request)
    {
        // JJDCH 21062019
        // Agregamos filtros para validar que los campos sean enviados
        $ape_paterno    = $request->input("ape_paterno");
        $ape_materno    = $request->input("ape_materno");
        $nombres        = $request->input("nombres");
        $dni            = $request->input("dni");
        $telefono       = $request->input("telefono");
        $email          = $request->input("email");
        $direccion      = $request->input("direccion");
        $password       = $request->input("password");
        $urbanizacion   = $request->input("id_urbanizacion");
        $token          = $request->input('token');

        if (!isset($ape_paterno) || empty($ape_paterno)) {
            $data["status"] = false;
            $data["mensaje"] = "Error: El apellido paterno es un campo obligatorio";
            return response()->json($data);
        } elseif (!isset($ape_materno) || empty($ape_materno)) {
            $data["status"] = false;
            $data["mensaje"] = "Error: El apellido materno es un campo obligatorio";
            return response()->json($data);
        } elseif (!isset($nombres) || empty($nombres)) {
            $data["status"] = false;
            $data["mensaje"] = "Error: El nombre es un campo obligatorio";
            return response()->json($data);
        } elseif (!isset($dni) || empty($dni) || strlen($dni) < 8) {
            $data["status"] = false;
            $data["mensaje"] = "Error: El DNI es un campo obligatorio";
            return response()->json($data);
        } elseif (!isset($telefono) || empty($telefono)  || strlen($telefono) < 9) {
            $data["status"] = false;
            $data["mensaje"] = "Error: El teléfono es un campo obligatorio";
            return response()->json($data);
        } elseif (!isset($email) || empty($email)) {
            $data["status"] = false;
            $data["mensaje"] = "Error: El email es un campo obligatorio";
            return response()->json($data);
        } elseif (!isset($direccion) || empty($direccion)) {
            $data["status"] = false;
            $data["mensaje"] = "Error: La dirección es un campo obligatorio";
            return response()->json($data);
        } elseif (!isset($password) || empty($password)) {
            $data["status"] = false;
            $data["mensaje"] = "Error: El password es un campo obligatorio";
            return response()->json($data);
        } elseif (!isset($urbanizacion) || empty($urbanizacion) || $urbanizacion == 0) {
            $data["status"] = false;
            $data["mensaje"] = "Error: La urbanización es un campo obligatorio";
            return response()->json($data);
        } elseif (!isset($token) || empty($token)) {
            $data["status"] = false;
            $data["mensaje"] = "Error: El token es un campo obligatorio";
            return response()->json($data);
        }

        // JJDCH 25062018
        // tipo persona - Ciudadano
        // Este campo permite saber el tipo de persona que es y con el cual podrá acceder a la aplicación como ciudadano - alcalce vecinal o jefe zonal, que son muy distintos a los roles del back end.

        // Verificamos si existe un ciudadano con el mismo dni, telefono o correo
        $registro = Persona::whereRaw('mail = "'.$request->input("email").'"')
                    ->orWhere('dni', '=', ''.$request->input("dni").'')
                    ->orWhere('telefono', '=', ''.$request->input("telefono").'')
                    ->first();
       
        if(count($registro)>0)
        {
            if ($request->input("dni")==$registro->dni) 
            {
                $data["status"] = false;
                $data["mensaje"] = "Ya existe una persona registrada con este DNI ".$request->input("dni");

                return response()->json($data);
            } 
            
            if ($request->input("email")==$registro->mail) 
            {
                $data["status"] = false;
                $data["mensaje"] = "Ya existe una persona registrada con este correo ".$request->input("email");

                return response()->json($data);
            }
            
            if ($request->input("telefono")==$registro->telefono) 
            {
                $data["status"] = false;
                $data["mensaje"] = "Ya existe una persona registrada con este teléfono ".$request->input("telefono");

                return response()->json($data);
            }
        }
        else 
        {
            DB::beginTransaction();

            try {
                
                // Tipo de persona Ciudadano = 2
                $tipo_persona = TipoPersona::find(2);

                // nivel ciudadano
                $nivel_ciudadano = NivelCiudadano::find(1);

                // estado persona
                $estado_persona = EstadoPersona::find(2);

                // urbanizaciom
                // $urbanizacion = Urbanizacion::find(1);
                // Parametro ya se recibe desde la llamada id_urbanizacion

                // JJDCH 25062018
                // rol que tiene esta persona Ciudadano 
                $rol = Rol::find(1);                

                $persona = new Persona();
                $persona->ape_paterno           = $ape_paterno;
                $persona->ape_materno           = $ape_materno;
                $persona->nombres               = $nombres;
                $persona->dni                   = $dni;
                $persona->telefono              = $telefono;
                $persona->mail                  = $email;
                $persona->direccion             = $direccion;
                $persona->state                 = $estado_persona->descripcion;
                $persona->tipo_persona_id       = $tipo_persona->id;
                $persona->nivel_ciudadano_id    = $nivel_ciudadano->id;
                $persona->estado_persona_id     = $estado_persona->id;
                $persona->urbanizacion_id       = $urbanizacion;
                $persona->rol_id                = $rol->id;
                
                // Registrando un ciudadano con estado inactivo ya que debe ser validado
                $persona->save();

                // Registrando un usuario
                User::create([
                    //'name'          => $persona->nombres,
                    'email'         => $persona->mail,
                    'password'      => $password,
                    //"last_name"     => $persona->nombres,
                    "admin"         => 0,
                    "state"         => 'Activo',
                    "persona_id"    => $persona->id,
                    "rol_id"        => $rol->id,
                    "token"         => $token
                ]);

                $user = User::where("persona_id", $persona->id)->first();

                //Asignación de rol ciudadano al usuario registrado.
                $user->roles()->attach($rol->id);

                //Asignación de puntuacion persona
                //La puntuación para el registro se realiza cuando un ciudadano es validado.               
                
                //Datos para devolver mensaje
                $data["status"] = true;
                $data["mensaje"] = "Ciudadano registrado con éxito";
                $data["persona_id"] = $persona->id;
                $data["user_id"] = $user->id;
                $data["persona_nombres"] = $persona->nombres;
                $data["user_state"] = $persona->state;

                DB::commit();

                return response()->json($data);

            } catch (\Exception $exception) {
                DB::rollBack();

                return ['status' => false, 'mensaje' => 'Hubo un error, intente nuevamente.', 'exception' => $exception->getMessage(),'persona_id'=>'', 'user_id'=>'','persona_nombres'=>''];    
            }
        }
    }

    public function actualizarPerfil(Request $request)
    {
        $telefono = $request->input("telefono");
        $email = $request->input("email");
        $nombres = $request->input("nombres");
        $ape_paterno = $request->input("ape_paterno");
        $ape_materno = $request->input("ape_materno");
        $direccion = $request->input("direccion");
        $urbanizacion_id = $request->input("urbanizacion_id");

        $valor = $this->validarCorreoSistema();

        if($valor) {
            // existe
        }else {
            $data["status"] = false;
            $data["mensaje"] = "El email ingresado no existe en el sistema";
        }
    }

    private function validarCorreoSistema($email)
    {
        $user = User::where("email", $email)->first();
        return $user;
    }

    private function validarCredenciales($email, $password)
    {
        $valor = false;

        // Valida si existe un correo en el sistema
        $user = $this->validarCorreoSistema($email);
        
        // Comprueba si existe el usuario
        if($user) {
            $validCredentials = Hash::check($password, $user->getAuthPassword());
            if($validCredentials) {
                $valor = true;
            }
        }
        
        return $valor;
    }

    /** -- api -- */
    // Actualizar contraseña
    // JJDCH 26062018

    public function udpContrasenaById(Request $request){

        // Obtengo los valores que actualizare
        $id = $request->input("id_persona");  
        $password = $request->input("password");
        $new_password = $request->input("new_password");

        // Obtengo Datos de Usuario
        $user = User::where("persona_id", $id)->first();

        // Comprueba si existe el usuario
        if($user) {
            $validCredentials = Hash::check($password, $user->getAuthPassword());
            if($validCredentials) {
                $user->password =  $new_password;
                $user->save();
                $data["status"] = true;
                $data["mensaje"] = "Contraseña actualizada con éxito";
            }
            else
            {
                $data["status"] = false;
                $data["mensaje"] = "La contraseña ingresada es incorrecta";
            }
        }
        else
        {
            $data["status"] = false;
            $data["mensaje"] = "No se encontró registro con los datos ingresados";
        }

        return response()->json($data); 

    }

    /** -- api -- */
    // Estado de un determinado Ciudadano
    // JJDCH 29062018

    public function getEstadoUserbyId(Request $request)
    {

        // Obtengo los datos del ciudadano a validar
        $id = $request->input("id_persona");  

        //Obtengo los datos del ciudadano por el id
        $persona = Persona::find($id);

        //Obtengo los datos del nivel de ciudadano y puntaje
        $nivel_ciudadano = NivelCiudadano::find($persona->nivel_ciudadano_id);
        $puntuacion_persona = PuntuacionPersona::where("persona_id", $persona->id)->get();
        $incidencia = Incidente::where('persona_id',$persona->id)->get();

        // Seteo contadores
        $puntos_persona = 0;
        $i_registradas = 0;
        $i_validadas = 0;
        $i_atendidas = 0;

        foreach ($puntuacion_persona as $puntos) {
            # code...
            $actividad_puntuacion = ActividadPuntuacion::where("id",$puntos->actividad_puntuacion_id)->first();
            $puntos_persona += $actividad_puntuacion->puntaje;
        } 

        // Obtenemos las incidencias registradas
        foreach ($incidencia as $item) 
        {
            $i_registradas += 1; 
            
            if($item->estado_incidente_id == 2)
            { 
                $i_validadas += 1; 
            }
            if($item->estado_incidente_id == 4)
            { 
                $i_atendidas += 1; 
            }
        }     


        $data["user_state"] = $persona->state;
        $data["user_persona_id"] = $persona->id;
        $data["user_persona_nombres"] = $persona->nombres;
        $data["user_persona_ape_paterno"] = $persona->ape_paterno;
        $data["user_persona_ape_materno"] = $persona->ape_materno;
        $data["user_nivel_ciudadano_name"] = $nivel_ciudadano->descripcion;
        $data["user_nivel_ciudadano_icono"] = $nivel_ciudadano->icono;
        $data["user_nivel_ciudadano_icono_src"] = $nivel_ciudadano->src_icono;  
        $data["user_puntuacion_persona_puntos"] = $puntos_persona;
        $data["user_incidencias_registradas"] = $i_registradas;
        $data["user_incidencias_atendidas"] = $i_atendidas;
        $data["user_incidencias_no_atendidas"] = $i_validadas;
        


        return response()->json($data); 

     }

    /** -- api -- */
    // Recuperar contraseña de un usuario
    // JJDCH 15072018

    public function getContrasenabyemail(Request $request)
    {

        // Obtengo el correo del usuario que desea recuperar su contraseña
        $email = $request->input("correo");  

        if (!empty($email)) 
        {
            // Valida si existe un correo en el sistema
            $user = $this->validarCorreoSistema($email);
            
            // Comprueba si existe el usuario
            if($user) 
            {
                DB::beginTransaction();

                try {

                    $new_password = rand();
                    $user->password = $new_password;
                    $user->save();

                    DB::commit();

                    $this->enviarcorreo_newclave($email,$new_password);

                    $data["status"] = true;
                    $data["message"] = "Contraseña actualizada con éxito, se enviará un correo con la nueva clave";
                    //$data["clave"] = $new_password;
                    
                } catch (\Exception $exception) {
                    DB::rollBack();

                    return ['status' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception' => $exception->getMessage()];
                }                
            }
            else
            {
                $data["status"] = false;
                $data["message"] = "No existe una cuenta asociada al correo ".$email;
            }
        } 
        else 
        {
            $data["status"] = false;
            $data["message"] = "No existe un correo para poder validar";
        }

        return response()->json($data); 

    }

    public function enviarcorreo_newclave($email,$clave)
    {
        // Obtengo los datos del usuario
        $persona = Persona::where('mail', $email)->first();

        // Enviar correo
        Mail::to($email)
            ->bcc("juanjiner@gmail.com")
            ->send(new RecuperarContrasena($persona->ape_paterno.' '.$persona->ape_materno.', '.$persona->nombres, $clave));
    }

}
