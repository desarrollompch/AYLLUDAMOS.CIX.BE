<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlcaldeVecinalRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\AlcaldeVecinal;
use Illuminate\Support\Facades\DB;
use Session;

class AlcaldeVecinalController extends Controller
{
    /* FUNCIONALIDADES PARA MEJORAR LA INTERACTIVIDAD */
    private function fnSetPaginaActual(){
      $paginaActual = Session::get('paginaActualAVec');
      if($paginaActual==null)
        Session::put('paginaActualAVec',1);
    }
      
    public function getPageSession(){
      $this->fnSetPaginaActual();
      $paginaSession = Session::get('paginaActualAVec');
      return ['success'=>true , 'paginaActual'=>$paginaSession];
    }  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AlcaldeVecinal $alcaldeVecinal,$validacion)
    {
      $this->authorize('view', [$alcaldeVecinal, 'AlcaldeVecinal']);
      if($validacion==0)
        Session::put('paginaActualAVec',1);
      if($validacion==1)
        $this->fnSetPaginaActual();
      return view('alcalde-vecinal.index');
    }


    public function all(AlcaldeVecinal $alcaldeVecinal)
    {
        $this->authorize('view', [$alcaldeVecinal, 'AlcaldeVecinal']);

        $alcaldesVecinales = AlcaldeVecinal::with(['persona', 'territorioVecinal'])->get();

        $currentPage = LengthAwarePaginator::resolveCurrentPage()-1;
        $collection = new Collection($alcaldesVecinales);
        $perPage = 10;
        $resultadosPorPagina = $collection->slice($currentPage * $perPage, $perPage)->all();
        $paginadoResultados= new LengthAwarePaginator($resultadosPorPagina, count($collection), $perPage);

        $pagination = [
            'total'             => $paginadoResultados->total(),
            'current_page'      => $paginadoResultados->currentPage(),
            'per_pague'         => $paginadoResultados->perPage(),
            'last_page'         => $paginadoResultados->lastPage(),
            'from'              => $paginadoResultados->firstItem(),
            'to'                => $paginadoResultados->lastItem()
        ];

        return ['success'=>true , 'alcaldesVecinales'=>$paginadoResultados, "pagination" => $pagination];


        // return ['success'=>true , 'alcaldesVecinales'=>$alcaldesVecinales];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(AlcaldeVecinal $alcaldeVecinal)
    {
        $this->authorize('create', [$alcaldeVecinal, 'AlcaldeVecinal']);

        return view('alcalde-vecinal.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AlcaldeVecinal $alcaldeVecinal, AlcaldeVecinalRequest $request)
    {
        $this->authorize('create', [$alcaldeVecinal, 'AlcaldeVecinal']);

        DB::beginTransaction();

        try {
            $data = $request->all();

            AlcaldeVecinal::create($data);

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
    public function show(AlcaldeVecinal $alcaldeVecinal)
    {
        $this->authorize('view', [$alcaldeVecinal, 'AlcaldeVecinal']);

        $alcaldeVecinal->persona;
        return ['sucess'=>true, 'alcaldeVecinal'=>$alcaldeVecinal];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AlcaldeVecinal $alcaldeVecinal,$alcaldeVecinalId,$pagina)
    {
        $this->authorize('update', [$alcaldeVecinal, 'AlcaldeVecinal']);
        Session::put('paginaActualAVec',$pagina);
        return view('alcalde-vecinal.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(AlcaldeVecinal $alcaldeVecinal, AlcaldeVecinalRequest $request)
    {
        $this->authorize('update', [$alcaldeVecinal, 'AlcaldeVecinal']);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $alcaldeVecinal->update($data);

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
    public function destroy(AlcaldeVecinal $alcaldeVecinal)
    {
        $this->authorize('delete', [$alcaldeVecinal, 'AlcaldeVecinal']);

        DB::beginTransaction();

        try {

            $alcaldeVecinal->delete();

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.'];

        }
    }
    
    
}
