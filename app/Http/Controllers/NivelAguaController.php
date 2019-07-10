<?php

namespace App\Http\Controllers;

use App\Http\Requests\NivelAguaRequest;
use Illuminate\Http\Request;
use App\NivelAgua;
use Illuminate\Support\Facades\DB;
use Session;

class NivelAguaController extends Controller
{
  /* FUNCIONALIDADES PARA MEJORAR LA INTERACTIVIDAD */
  private function fnSetPaginaActual(){
    $paginaActual = Session::get('paginaActualNivA');
    if($paginaActual==null)
      Session::put('paginaActualNivA',1);
  }

  public function getPageSession(){
    $this->fnSetPaginaActual();
    $paginaSession = Session::get('paginaActualNivA');
    return ['success'=>true , 'paginaActual'=>$paginaSession];
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(NivelAgua $nivelAgua,$validacion)
    {
        $this->authorize('view', [$nivelAgua, 'NivelAgua']);
        if($validacion==0)
          Session::put('paginaActualNivA',1);
      
        if($validacion==1)
          $this->fnSetPaginaActual();
        return view('nivel-agua.index');
    }


    public function all(NivelAgua $nivelAgua)
    {
        $this->authorize('view', [$nivelAgua, 'NivelAgua']);

        $nivelesAgua = NivelAgua::all();


        return ['success'=>true , 'nivelesAgua'=>$nivelesAgua];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(NivelAgua $nivelAgua)
    {
        $this->authorize('create', [$nivelAgua, 'NivelAgua']);

        return view('nivel-agua.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(NivelAgua $nivelAgua, NivelAguaRequest $request)
    {
        $this->authorize('create', [$nivelAgua, 'NivelAgua']);

        DB::beginTransaction();

        try {
            $data = $request->all();

            $nivelAgua = NivelAgua::create($data);

            if(isset($data['imagen_file']) && isset($data['imagen_file']['value']))
            {
                $this->setImagenFile($nivelAgua, $data);
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
    public function show(NivelAgua $nivelAgua)
    {
        $this->authorize('view', [$nivelAgua, 'NivelAgua']);

        return ['sucess'=>true, 'nivelAgua'=>$nivelAgua];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(NivelAgua $nivelAgua,$nivelaguaId,$pagina)
    {
        $this->authorize('update', [$nivelAgua, 'NivelAgua']);
        Session::put('paginaActualNivA',$pagina);
        return view('nivel-agua.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(NivelAgua $nivelAgua, NivelAguaRequest $request)
    {
        $this->authorize('update', [$nivelAgua, 'NivelAgua']);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $nivelAgua->update($data);

            if(isset($data['imagen_file']) && isset($data['imagen_file']['value']))
            {
                $this->setImagenFile($nivelAgua, $data);
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
    public function destroy(NivelAgua $nivelAgua)
    {
        $this->authorize('delete', [$nivelAgua, 'NivelAgua']);

        DB::beginTransaction();

        try {

            $nivelAgua->delete();

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.'];

        }
    }


    public function setImagenFile($nivelAgua, $data){

        $file = base64_decode($data['imagen_file']['value']);
        $extension = explode('.', $data['imagen_file']['filename']);
        $index = count($extension);
        $fileName = seoUrl($nivelAgua->descripcion . '_imagen_' . substr(md5(uniqid(rand(), true)), 0, 6)) . '.' . $extension[$index - 1];

        \Storage::disk('public')->put('/images/nivel-agua/' . $fileName, $file);

        $exists = \Storage::disk('public')->exists('images/nivel-agua/' . $nivelAgua->imagen);

        if ($exists && !empty($nivelAgua->imagen)) {
            \Storage::disk('public')->delete('images/nivel-agua/' . $nivelAgua->imagen);
        }

        $nivelAgua->imagen = $fileName;

        $nivelAgua->save();

    }

    /** -- api -- */
    // Listar Niveles de Agua
    // JJDCH 29062018
    public function getNivelAgua(){

        $nivel_agua = NivelAgua::get();
        return response()->json($nivel_agua);

    }
    
    
}
