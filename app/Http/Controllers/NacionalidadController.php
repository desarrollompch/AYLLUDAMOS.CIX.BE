<?php

namespace App\Http\Controllers;

use App\Http\Requests\NacionalidadRequest;
use App\Permiso;
use App\Nacionalidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class NacionalidadController extends Controller
{
    /* FUNCIONALIDADES PARA MEJORAR LA INTERACTIVIDAD */
    private function fnSetPaginaActual(){
      $paginaActual = Session::get('paginaActualNac');
      if($paginaActual==null)
        Session::put('paginaActualNac',1);
    }
  
    public function getPageSession(){
      $this->fnSetPaginaActual();
      $paginaSession = Session::get('paginaActualNac');
      return ['success'=>true , 'paginaActual'=>$paginaSession];
    }  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Nacionalidad $nacionalidad,$validacion)
    {
        $this->authorize('view', [$nacionalidad, 'Nacionalidad']);
        if($validacion==0)
          Session::put('paginaActualNac',1);
        
        if($validacion==1)
          $this->fnSetPaginaActual();
        return view('nacionalidad.index');
    }


    public function all(Nacionalidad $nacionalidad)
    {
        $this->authorize('view', [$nacionalidad, 'Nacionalidad']);

        $nacionalidades = Nacionalidad::all();


        return ['success' => true, 'nacionalidades' => $nacionalidades];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Nacionalidad $nacionalidad)
    {
        $this->authorize('create', [$nacionalidad, 'Nacionalidad']);

        return view('nacionalidad.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Nacionalidad $nacionalidad,NacionalidadRequest $request)
    {
        $this->authorize('create', [$nacionalidad, 'Nacionalidad']);

        DB::beginTransaction();

        try {
            $data = $request->all();

            $nacionalidad = Nacionalidad::create($data);

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::nacionalidadlBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception' => $exception->getMessage()];

        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Nacionalidad $nacionalidad)
    {
        $this->authorize('view', [$nacionalidad, 'Nacionalidad']);

        return ['sucess' => true, 'nacionalidad' => $nacionalidad];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Nacionalidad $nacionalidad,$nacionalidadId,$pagina)
    {
        $this->authorize('update', [$nacionalidad, 'Nacionalidad']);
        Session::put('paginaActualNac',$pagina);
        return view('nacionalidad.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Nacionalidad $nacionalidad, NacionalidadRequest $request)
    {
        $this->authorize('update', [$nacionalidad, 'Nacionalidad']);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $nacionalidad->update($data);

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::nacionalidadlBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception' => $exception->getMessage()];

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nacionalidad $nacionalidad)
    {
        $this->authorize('delete', [$nacionalidad, 'Nacionalidad']);

        DB::beginTransaction();

        try {
            $nacionalidad->delete();

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::nacionalidadlBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception'=>$exception->getMessage()];

        }
    }

    //Api
    public function getNacionalidad()
    {
        $nacionalidades = Nacionalidad::all();
        return response()->json($nacionalidades); 
    }


}
