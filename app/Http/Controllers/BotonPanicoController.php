<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BotonPanico;
use App\BotonPanicoUser;
use App\Persona;
use Carbon\Carbon;
use DB;

class BotonPanicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BotonPanico $botonpanico)
    {
      // $this->authorize('view', [$botonpanico, 'BotonPanico']);
      // $data = $this->getBotonPanico();
      // dd($data);
      // $botonpanico = $this->fnObtenerEstadoBotonPanico();
      return view('botonpanico.index');
    }

    /**
     *API :  Obtiene el ultimo estado registrado para el boton de panico
     *
     * @return BotonPanico
     */
    public function getBotonPanico(){
      $botonpanico = BotonPanico::orderBy('id','desc')->first();
      // return "aaaaaaa";
      return ['sucess'=>true, 'botonpanico'=>$botonpanico];
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
      DB::beginTransaction();
      try {
        $data = $request->all();
        BotonPanico::create([
          "nombre"=>$data['state']?'ACTIVO':'INACTIVO',
          "state"=>$data['state']?1:0,
          "fecha"=>Carbon::now()
        ]);
        DB::commit();
        return ['success' => true];
      } catch (\Exception $exception) {
        DB::rollBack();
        return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception'=>$exception->getMessage()];
      }
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
    // Comprobar estado de botón de pánico 
    // JCRN 15102018
    public function comprobarEstado() {
        $success = false;
        $result = BotonPanico::orderBy("id", "desc")->first();
        if(!is_null($result) || count($result) > 0) {
            $success = true;
        }

        $return = array("success" => $success, "result" => $result);
        return response()->json($return);
    }

    /** -- api -- */
    // Comprobar estado de botón de pánico de parte del usuario
    // JCRN 17102018
    public function comprobarEstadoPorUsuario($telefono) {
        $persona = Persona::where("telefono", $telefono)->first();
        $user_id = $persona->user->id;
        $boton_panico_user = BotonPanicoUser::select("id", "user_id", "accion", "tiempo")->where("user_id", $user_id)->first(); 
        return response()->json($boton_panico_user);
    }

    /** -- api -- */
    // Registrar botón de pánico por parte del usuario 
    // JCRN 15102018
    public function registroEstadoPorUsuario(Request $request) {
        DB::beginTransaction();

        try {
            $data = $request->all();
            $data["fecha"] = Carbon::now();
            $user_id = $data["user_id"];
            if($data["accion"] == "ACTIVO") {
                BotonPanicoUser::create($data);
                $mensaje = "La activación del botón de pánico ha sido registrado";
                $success = true;
            }else {
                $botonPanicoUser = BotonPanicoUser::where("user_id", $user_id)->first();
                $botonPanicoUser->accion = "INACTIVO";
                $botonPanicoUser->save();
                $mensaje = "El botón de pánico ha sido desactivado";
                $success = false;
            }
            
            DB::commit();

            return ['success' => $success, "message" => $mensaje];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception'=>$exception->getMessage()];

        }
    }
}
