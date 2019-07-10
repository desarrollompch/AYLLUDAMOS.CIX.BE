<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoIncidenteRequest;
use Illuminate\Http\Request;
use App\TipoIncidente;
use Illuminate\Support\Facades\DB;
use Session;

class TipoIncidenteController extends Controller
{
      /* FUNCIONALIDADES PARA MEJORAR LA INTERACTIVIDAD */
      private function fnSetPaginaActual(){
        $paginaActual = Session::get('paginaActualTipI');
        if($paginaActual==null)
          Session::put('paginaActualTipI',1);
      }
    
      public function getPageSession(){
        $this->fnSetPaginaActual();
        $paginaSession = Session::get('paginaActualTipI');
        return ['success'=>true , 'paginaActual'=>$paginaSession];
      } 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TipoIncidente $tipoIncidente,$validacion)
    {
        $this->authorize('view', [$tipoIncidente, 'TipoIncidente']);
        if($validacion==0)
          Session::put('paginaActualTipI',1);
      
        if($validacion==1)
          $this->fnSetPaginaActual();
        return view('tipo-incidente.index');
    }


    public function all(TipoIncidente $tipoIncidente)
    {
        $this->authorize('view', [$tipoIncidente, 'TipoIncidente']);

        $tiposIncidentes = TipoIncidente::all();


        return ['success'=>true , 'tiposIncidentes'=>$tiposIncidentes];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(TipoIncidente $tipoIncidente)
    {
        $this->authorize('create', [$tipoIncidente, 'TipoIncidente']);

        return view('tipo-incidente.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoIncidente $tipoIncidente, TipoIncidenteRequest $request)
    {
        $this->authorize('create', [$tipoIncidente, 'TipoIncidente']);

        DB::beginTransaction();

        try {
            $data = $request->all();

            TipoIncidente::create($data);

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
    public function show(TipoIncidente $tipoIncidente)
    {
        $this->authorize('view', [$tipoIncidente, 'TipoIncidente']);

        return ['sucess'=>true, 'tipoIncidente'=>$tipoIncidente];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoIncidente $tipoIncidente,$tipoincidenteId,$pagina)
    {
        $this->authorize('update', [$tipoIncidente, 'TipoIncidente']);
        Session::put('paginaActualTipI',$pagina);
        return view('tipo-incidente.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(TipoIncidente $tipoIncidente, TipoIncidenteRequest $request)
    {
        $this->authorize('update', [$tipoIncidente, 'TipoIncidente']);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $tipoIncidente->update($data);

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
    public function destroy(TipoIncidente $tipoIncidente)
    {
        $this->authorize('delete', [$tipoIncidente, 'TipoIncidente']);

        DB::beginTransaction();

        try {

            $tipoIncidente->delete();

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.'];

        }
    }
    
    
}
