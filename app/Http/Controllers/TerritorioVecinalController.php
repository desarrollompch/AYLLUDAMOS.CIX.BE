<?php

namespace App\Http\Controllers;

use App\Http\Requests\TerritorioVecinalRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\TerritorioVecinal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class TerritorioVecinalController extends Controller
{
    /* FUNCIONALIDADES PARA MEJORAR LA INTERACTIVIDAD */
    private function fnSetPaginaActual(){
      $paginaActual = Session::get('paginaActualTV');
      if($paginaActual==null)
        Session::put('paginaActualTV',1);
    }
  
    public function getPageSession(){
      $this->fnSetPaginaActual();
      $paginaSession = Session::get('paginaActualTV');
      return ['success'=>true , 'paginaActual'=>$paginaSession];
    }  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TerritorioVecinal $territorioVecinal,$validacion)
    {
      $this->authorize('view', [$territorioVecinal, 'TerritorioVecinal']);
      if($validacion==0)
        Session::put('paginaActualTV',1);
      if($validacion==1)
        $this->fnSetPaginaActual();
      return view('territorio-vecinal.index');
    }

    public function viewMap()
    {
        return view('territorio-vecinal.view-map');
    }


    public function all(TerritorioVecinal $territorioVecinal)
    {

        $this->authorize('view', [$territorioVecinal, 'TerritorioVecinal']);

        $territoriosVecinales = TerritorioVecinal::all();
    
        $currentPage = LengthAwarePaginator::resolveCurrentPage()-1;
        $collection = new Collection($territoriosVecinales);
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

        return ["success" => true, "result" => $paginadoResultados, "pagination" => $pagination];
    }

    public function allSinPaginado(TerritorioVecinal $territorioVecinal) {

        $this->authorize('view', [$territorioVecinal, 'TerritorioVecinal']);

        $territoriosVecinales = TerritorioVecinal::all();

        return ["success" => true, "territoriosVecinales" => $territoriosVecinales];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(TerritorioVecinal $territorioVecinal)
    {
        $this->authorize('create', [$territorioVecinal, 'TerritorioVecinal']);

        return view('territorio-vecinal.create');
    }

    public function storeAll(TerritorioVecinal $territorioVecinal,Request $request)
    {

        try {
            $this->authorize('create', [$territorioVecinal, 'TerritorioVecinal']);

            $data = $request->all();

            foreach ($data['body'] as $datum)
            {
                TerritorioVecinal::create([
                    'descripcion'       => $datum['Descripcion'],
                    'coordenadas'       => '',
                    'min_latitude'      => '',
                    'max_latitude'      => '',
                    'max_longitude'     => '',
                    'min_longitude'     => '',
                    'latitude'          => '',
                    'longitude'         => ''
                    ]);
            }

            return ['success' => true, "message" => 'Territorios vecinales creados con éxito'];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception'=> $exception];

        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(TerritorioVecinal $territorioVecinal,TerritorioVecinalRequest $request)
    {
        $this->authorize('create', [$territorioVecinal, 'TerritorioVecinal']);

        DB::beginTransaction();

        try {
            $data = $request->all();

            $latitudes = array();
            $longitudes = array();

            $coordinates = explode(';', $data['coordenadas']);

            foreach ($coordinates as $coordinate){
                $data_c = explode(',', $coordinate);
                $latitudes[] = $data_c[0];
                $longitudes[] = $data_c[1];
            }

            $data['max_latitude'] = max($latitudes);
            $data['min_latitude'] = min($latitudes);
            $data['max_longitude'] = max($longitudes);
            $data['min_longitude'] = min($longitudes);

            TerritorioVecinal::create($data);

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.'];

        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(TerritorioVecinal $territorioVecinal)
    {
        $this->authorize('view', [$territorioVecinal, 'TerritorioVecinal']);

        return ['sucess'=>true, 'territorioVecinal'=>$territorioVecinal];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(TerritorioVecinal $territorioVecinal,$territorioVecinalId,$pagina)
    {
        $this->authorize('update', [$territorioVecinal, 'TerritorioVecinal']);
        Session::put('paginaActualTV',$pagina);
        return view('territorio-vecinal.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(TerritorioVecinal $territorioVecinal, TerritorioVecinalRequest $request)
    {
        $this->authorize('update', [$territorioVecinal, 'TerritorioVecinal']);

        DB::beginTransaction();

        try {
            $data = $request->all();

            $latitudes = array();
            $longitudes = array();

            $coordinates = explode(';', $data['coordenadas']);

            foreach ($coordinates as $coordinate){
                $data_c = explode(',', $coordinate);
                $latitudes[] = $data_c[0];
                $longitudes[] = $data_c[1];
            }

            $data['max_latitude'] = max($latitudes);
            $data['min_latitude'] = min($latitudes);
            $data['max_longitude'] = max($longitudes);
            $data['min_longitude'] = min($longitudes);

            $territorioVecinal->update($data);

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.'];

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TerritorioVecinal $territorioVecinal)
    {
        $this->authorize('delete', [$territorioVecinal, 'TerritorioVecinal']);

        DB::beginTransaction();

        try {

            $territorioVecinal->delete();

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.'];

        }
    }
}
