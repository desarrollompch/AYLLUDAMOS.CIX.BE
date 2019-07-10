<?php

namespace App\Http\Controllers;

use App\Http\Requests\EstadoIncidenteRequest;
use Illuminate\Http\Request;
use App\EstadoIncidente;
use Illuminate\Support\Facades\DB;
use Session;

class EstadoIncidenteController extends Controller
{
  /* FUNCIONALIDADES PARA MEJORAR LA INTERACTIVIDAD */
  private function fnSetPaginaActual(){
    $paginaActual = Session::get('paginaActualEstI');
    if($paginaActual==null)
      Session::put('paginaActualEstI',1);
  }
    
  public function getPageSession(){
    $this->fnSetPaginaActual();
    $paginaSession = Session::get('paginaActualEstI');
    return ['success'=>true , 'paginaActual'=>$paginaSession];
  }  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(EstadoIncidente $estadoIncidente,$validacion)
    {
        $this->authorize('view', [$estadoIncidente, 'EstadoIncidente']);
        if($validacion==0)
          Session::put('paginaActualEstI',1);
      
        if($validacion==1)
          $this->fnSetPaginaActual();
        return view('estado-incidente.index');
    }


    public function all(EstadoIncidente $estadoIncidente)
    {
        $this->authorize('view', [$estadoIncidente, 'EstadoIncidente']);

        $estadosIncidentes = EstadoIncidente::all();


        return ['success'=>true , 'estadosIncidentes'=>$estadosIncidentes];
    }

    public function list(EstadoIncidente $estadoIncidente)
    {      

        $estadosIncidentes = EstadoIncidente::all();


        return ['success'=>true , 'estadosIncidentes'=>$estadosIncidentes];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(EstadoIncidente $estadoIncidente)
    {
        $this->authorize('create', [$estadoIncidente, 'EstadoIncidente']);

        return view('estado-incidente.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(EstadoIncidente $estadoIncidente, EstadoIncidenteRequest $request)
    {
        $this->authorize('create', [$estadoIncidente, 'EstadoIncidente']);

        DB::beginTransaction();

        try {
            $data = $request->all();

            EstadoIncidente::create($data);

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
    public function show(EstadoIncidente $estadoIncidente)
    {
        $this->authorize('view', [$estadoIncidente, 'EstadoIncidente']);

        return ['sucess'=>true, 'estadoIncidente'=>$estadoIncidente];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EstadoIncidente $estadoIncidente,$estadoincidenteId,$pagina)
    {
        $this->authorize('update', [$estadoIncidente, 'EstadoIncidente']);
        Session::put('paginaActualEstI',$pagina);
        return view('estado-incidente.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(EstadoIncidente $estadoIncidente, EstadoIncidenteRequest $request)
    {
        $this->authorize('update', [$estadoIncidente, 'EstadoIncidente']);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $estadoIncidente->update($data);

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
    public function destroy(EstadoIncidente $estadoIncidente)
    {
        $this->authorize('delete', [$estadoIncidente, 'EstadoIncidente']);

        DB::beginTransaction();

        try {

            $estadoIncidente->delete();

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.'];

        }
    }
    
    
}
