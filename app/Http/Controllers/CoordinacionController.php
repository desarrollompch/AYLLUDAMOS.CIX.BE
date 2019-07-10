<?php

namespace App\Http\Controllers;

use App\Http\Requests\CoordinacionRequest;
use Illuminate\Http\Request;
use App\Coordinacion;
use Illuminate\Support\Facades\DB;
use Session;

class CoordinacionController extends Controller
{
      /* FUNCIONALIDADES PARA MEJORAR LA INTERACTIVIDAD */
    private function fnSetPaginaActual(){
      $paginaActual = Session::get('paginaActualCoord');
      if($paginaActual==null)
        Session::put('paginaActualCoord',1);
    }
    
    public function getPageSession(){
      $this->fnSetPaginaActual();
      $paginaSession = Session::get('paginaActualCoord');
      return ['success'=>true , 'paginaActual'=>$paginaSession];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Coordinacion $coordinacion,$validacion)
    {
        $this->authorize('view', [$coordinacion, 'Coordinacion']);
        if($validacion==0)
          Session::put('paginaActualCoord',1);
      
        if($validacion==1)
          $this->fnSetPaginaActual();
        return view('coordinacion.index');
    }


    public function all(Coordinacion $coordinacion)
    {
        $this->authorize('view', [$coordinacion, 'Coordinacion']);

        $coordinacions = Coordinacion::all();


        return ['success'=>true , 'coordinacions'=>$coordinacions];
    }

    public function list(Coordinacion $coordinacion)
    {
        $coordinacions = Coordinacion::all();


        return ['success'=>true , 'coordinacions'=>$coordinacions];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Coordinacion $coordinacion)
    {
        $this->authorize('create', [$coordinacion, 'Coordinacion']);

        return view('coordinacion.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Coordinacion $coordinacion, CoordinacionRequest $request)
    {
        $this->authorize('create', [$coordinacion, 'Coordinacion']);

        DB::beginTransaction();

        try {
            $data = $request->all();

            Coordinacion::create($data);

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
    public function show(Coordinacion $coordinacion)
    {
        $this->authorize('view', [$coordinacion, 'Coordinacion']);

        return ['sucess'=>true, 'coordinacion'=>$coordinacion];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Coordinacion $coordinacion,$id,$pagina)
    {
        $this->authorize('update', [$coordinacion, 'Coordinacion']);
        Session::put('paginaActualCoord',$pagina);
        return view('coordinacion.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Coordinacion $coordinacion, CoordinacionRequest $request)
    {
        $this->authorize('update', [$coordinacion, 'Coordinacion']);
        DB::beginTransaction();

        try {
            $data = $request->all();
            $coordinacion->update($data);

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
    public function destroy(Coordinacion $coordinacion)
    {
        $this->authorize('delete', [$coordinacion, 'Coordinacion']);

        DB::beginTransaction();

        try {

            $coordinacion->delete();

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.'];

        }
    }

    /** -- api -- */
    public function getCoordinacion()
    {
        $coordinacion = Coordinacion::get();
        return response()->json($coordinacion);

    }
    
    
}
