<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActividadPuntuacionRequest;
use Illuminate\Http\Request;
use App\ActividadPuntuacion;
use Illuminate\Support\Facades\DB;
use Session;

class ActividadPuntuacionController extends Controller
{
      /* FUNCIONALIDADES PARA MEJORAR LA INTERACTIVIDAD */
      private function fnSetPaginaActual(){
        $paginaActual = Session::get('paginaActualActP');
        if($paginaActual==null)
          Session::put('paginaActualActP',1);
      }
    
      public function getPageSession(){
        $this->fnSetPaginaActual();
        $paginaSession = Session::get('paginaActualActP');
        return ['success'=>true , 'paginaActual'=>$paginaSession];
      } 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ActividadPuntuacion $actividadPuntuacion,$validacion)
    {
        $this->authorize('view', [$actividadPuntuacion, 'ActividadPuntuacion']);
        if($validacion==0)
          Session::put('paginaActualActP',1);
      
        if($validacion==1)
          $this->fnSetPaginaActual();
        return view('actividad-puntuacion.index');
    }


    public function all(ActividadPuntuacion $actividadPuntuacion)
    {
        $this->authorize('view', [$actividadPuntuacion, 'ActividadPuntuacion']);

        $actividadesPuntuacion = ActividadPuntuacion::with(['estadoIncidente'])->get();


        return ['success'=>true , 'actividadesPuntuacion'=>$actividadesPuntuacion];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ActividadPuntuacion $actividadPuntuacion)
    {
        $this->authorize('create', [$actividadPuntuacion, 'ActividadPuntuacion']);

        return view('actividad-puntuacion.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ActividadPuntuacion $actividadPuntuacion, ActividadPuntuacionRequest $request)
    {
        $this->authorize('create', [$actividadPuntuacion, 'ActividadPuntuacion']);

        DB::beginTransaction();

        try {
            $data = $request->all();

            ActividadPuntuacion::create($data);

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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(ActividadPuntuacion $actividadPuntuacion)
    {
        $this->authorize('view', [$actividadPuntuacion, 'ActividadPuntuacion']);

        return ['sucess'=>true, 'actividadPuntuacion'=>$actividadPuntuacion];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ActividadPuntuacion $actividadPuntuacion,$actividadId,$pagina)
    {
        $this->authorize('update', [$actividadPuntuacion, 'ActividadPuntuacion']);
        Session::put('paginaActualActP',$pagina);
        return view('actividad-puntuacion.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ActividadPuntuacion $actividadPuntuacion, ActividadPuntuacionRequest $request)
    {
        $this->authorize('update', [$actividadPuntuacion, 'ActividadPuntuacion']);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $actividadPuntuacion->update($data);

            DB::commit();

            return ['success' => true];

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
    public function destroy(ActividadPuntuacion $actividadPuntuacion)
    {
        $this->authorize('delete', [$actividadPuntuacion, 'ActividadPuntuacion']);

        DB::beginTransaction();

        try {

            $actividadPuntuacion->delete();

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.'];

        }
    }
    
    
}
