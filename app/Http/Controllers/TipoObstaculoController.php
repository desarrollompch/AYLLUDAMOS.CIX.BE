<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoObstaculoRequest;
use Illuminate\Http\Request;
use App\TipoObstaculo;
use Illuminate\Support\Facades\DB;
use Session;

class TipoObstaculoController extends Controller
{
      /* FUNCIONALIDADES PARA MEJORAR LA INTERACTIVIDAD */
  private function fnSetPaginaActual(){
    $paginaActual = Session::get('paginaActualTipOb');
    if($paginaActual==null)
      Session::put('paginaActualTipOb',1);
  }
    
  public function getPageSession(){
    $this->fnSetPaginaActual();
    $paginaSession = Session::get('paginaActualTipOb');
    return ['success'=>true , 'paginaActual'=>$paginaSession];
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TipoObstaculo $tipoObstaculo,$validacion)
    {
        $this->authorize('view', [$tipoObstaculo, 'TipoObstaculo']);
        if($validacion==0)
          Session::put('paginaActualTipOb',1);
      
        if($validacion==1)
          $this->fnSetPaginaActual();
        return view('tipo-obstaculo..index');
    }


    public function all(TipoObstaculo $tipoObstaculo)
    {
        $this->authorize('view', [$tipoObstaculo, 'TipoObstaculo']);

        $tiposObstaculos = TipoObstaculo::all();


        return ['success'=>true , 'tiposObstaculos'=>$tiposObstaculos];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(TipoObstaculo $tipoObstaculo)
    {
        $this->authorize('create', [$tipoObstaculo, 'TipoObstaculo']);

        return view('tipo-obstaculo..create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoObstaculo $tipoObstaculo,TipoObstaculoRequest $request)
    {
        $this->authorize('create', [$tipoObstaculo, 'TipoObstaculo']);

        DB::beginTransaction();

        try {
            $data = $request->all();

            $tipoObstaculo = TipoObstaculo::create($data);

            if(isset($data['imagen_file']) && isset($data['imagen_file']['value']))
            {
                $this->setImagenFile($tipoObstaculo, $data);
            }

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
    public function show(TipoObstaculo $tipoObstaculo)
    {
        $this->authorize('view', [$tipoObstaculo, 'TipoObstaculo']);

        return ['sucess'=>true, 'tipoObstaculo'=>$tipoObstaculo];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoObstaculo $tipoObstaculo,$tipoobstaculoId,$pagina)
    {
        $this->authorize('update', [$tipoObstaculo, 'TipoObstaculo']);
        Session::put('paginaActualTipOb',$pagina);
        return view('tipo-obstaculo..edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(TipoObstaculo $tipoObstaculo, TipoObstaculoRequest $request)
    {
        $this->authorize('update', [$tipoObstaculo, 'TipoObstaculo']);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $tipoObstaculo->update($data);

            if(isset($data['imagen_file']) && isset($data['imagen_file']['value']))
            {
                $this->setImagenFile($tipoObstaculo, $data);
            }

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
    public function destroy(TipoObstaculo $tipoObstaculo)
    {
        $this->authorize('delete', [$tipoObstaculo, 'TipoObstaculo']);

        DB::beginTransaction();

        try {

            $tipoObstaculo->delete();

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.'];

        }
    }

    public function setImagenFile($tipoObstaculo, $data){

        $file = base64_decode($data['imagen_file']['value']);
        $extension = explode('.', $data['imagen_file']['filename']);
        $index = count($extension);
        $fileName = seoUrl($tipoObstaculo->descripcion . '_imagen_' . substr(md5(uniqid(rand(), true)), 0, 6)) . '.' . $extension[$index - 1];

        \Storage::disk('public')->put('/images/tipo-obstaculo/' . $fileName, $file);

        $exists = \Storage::disk('public')->exists('images/tipo-obstaculo/' . $tipoObstaculo->imagen);

        if ($exists && !empty($tipoObstaculo->imagen)) {
            \Storage::disk('public')->delete('images/tipo-obstaculo/' . $tipoObstaculo->imagen);
        }

        $tipoObstaculo->imagen = $fileName;

        $tipoObstaculo->save();

    }


    /** -- api -- */
    // Listar Tipos de Obstaculizacion
    // JJDCH 29062018
    public function getTipoObstaculo(){

        $tipo_obstaculo = TipoObstaculo::get();
        return response()->json($tipo_obstaculo);

    }
    
    
}
