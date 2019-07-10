<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfiguracionRequest;
use Illuminate\Http\Request;
use App\Configuracion;
use Illuminate\Support\Facades\DB;
use Session;

class ConfiguracionController extends Controller
{
      /* FUNCIONALIDADES PARA MEJORAR LA INTERACTIVIDAD */
    private function fnSetPaginaActual(){
      $paginaActual = Session::get('paginaActualConfig');
      if($paginaActual==null)
        Session::put('paginaActualConfig',1);
    }
    
    public function getPageSession(){
      $this->fnSetPaginaActual();
      $paginaSession = Session::get('paginaActualConfig');
      return ['success'=>true , 'paginaActual'=>$paginaSession];
    } 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Configuracion $configuracion,$validacion)
    {
        $this->authorize('view', [$configuracion, 'Configuracion']);
        if($validacion==0)
          Session::put('paginaActualConfig',1);
      
        if($validacion==1)
          $this->fnSetPaginaActual();
        return view('configuracion.index');
    }


    public function all(Configuracion $configuracion)
    {
        $this->authorize('view', [$configuracion, 'Configuracion']);

        $configuracions = Configuracion::all();


        return ['success'=>true , 'configuracions'=>$configuracions];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Configuracion $configuracion)
    {
        $this->authorize('create', [$configuracion, 'Configuracion']);

        return view('configuracion.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Configuracion $configuracion, ConfiguracionRequest $request)
    {
        $this->authorize('create', [$configuracion, 'Configuracion']);

        DB::beginTransaction();

        try {
            $data = $request->all();

            Configuracion::create($data);

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
    public function show(Configuracion $configuracion)
    {
        $this->authorize('view', [$configuracion, 'Configuracion']);

        return ['sucess'=>true, 'configuracion'=>$configuracion];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Configuracion $configuracion,$configuracionId,$pagina)
    {
        $this->authorize('update', [$configuracion, 'Configuracion']);
        Session::put('paginaActualConfig',$pagina);
        return view('configuracion.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Configuracion $configuracion, ConfiguracionRequest $request)
    {
        $this->authorize('update', [$configuracion, 'Configuracion']);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $configuracion->update($data);

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
    public function destroy(Configuracion $configuracion)
    {
        $this->authorize('update', [$configuracion, 'Configuracion']);

        DB::beginTransaction();

        try {

            $configuracion->delete();

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.'];

        }
    }

    /** -- api -- */
    public function getConfiguracion()
    {
        $configuracion = Configuracion::get();
        return response()->json($configuracion);

    }
    
    
}
