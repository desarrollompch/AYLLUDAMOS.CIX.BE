<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EnvioAutomatico;
use DB;

class EnvioAutomaticoController extends Controller
{
        /** -- api -- */
    // Registrar botón de pánico por parte del usuario 
    // JCRN 17102018
    public function registroEstadoPorUsuario(Request $request) {
        DB::beginTransaction();

        try {
            $data = $request->all();
            // $data["fecha"] = Carbon::now();
            $success = false;
            $mensaje = ($data["accion"] == "ACTIVO") ? "activado" : "desactivado"; 
            $success = ($data["accion"] == "ACTIVO") ? true : false;

            EnvioAutomatico::create($data);

            DB::commit();

            return ['success' => $success, "message" => "El envío automático ha sido ".$mensaje];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception'=>$exception->getMessage()];

        }
    }
}
