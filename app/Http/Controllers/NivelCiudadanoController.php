<?php

namespace App\Http\Controllers;

use App\Http\Requests\NivelCiudadanoRequest;
use App\NivelCiudadano;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class NivelCiudadanoController extends Controller
{
      /* FUNCIONALIDADES PARA MEJORAR LA INTERACTIVIDAD */
    private function fnSetPaginaActual(){
      $paginaActual = Session::get('paginaActualNCiu');
      if($paginaActual==null)
        Session::put('paginaActualNCiu',1);
    }
    
    public function getPageSession(){
      $this->fnSetPaginaActual();
      $paginaSession = Session::get('paginaActualNCiu');
      return ['success'=>true , 'paginaActual'=>$paginaSession];
    }  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(NivelCiudadano $nivelCiudadano,$validacion)
    {
        $this->authorize('view', [$nivelCiudadano, 'NivelCiudadano']);
        if($validacion==0)
          Session::put('paginaActualNCiu',1);
      
        if($validacion==1)
          $this->fnSetPaginaActual();
        return view('nivel-ciudadano.index');
    }


    public function all(NivelCiudadano $nivelCiudadano)
    {
        $this->authorize('view', [$nivelCiudadano, 'NivelCiudadano']);

        $nivelesCiudadanos = NivelCiudadano::all();

        return ['success'=>true , 'nivelesCiudadanos'=>$nivelesCiudadanos];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(NivelCiudadano $nivelCiudadano)
    {
        $this->authorize('create', [$nivelCiudadano, 'NivelCiudadano']);

        return view('nivel-ciudadano.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(NivelCiudadano $nivelCiudadano,NivelCiudadanoRequest $request)
    {
        $this->authorize('create', [$nivelCiudadano, 'NivelCiudadano']);

        DB::beginTransaction();

        try {
            $data = $request->all();

            $nivelCiudadano = NivelCiudadano::create($data);

            if(isset($data['icono_file']) && isset($data['icono_file']['value']))
            {
                $this->setIconoFile($nivelCiudadano, $data);
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
    public function show(NivelCiudadano $nivelCiudadano)
    {
        $this->authorize('view', [$nivelCiudadano, 'NivelCiudadano']);

        return ['sucess'=>true, 'nivelCiudadano'=>$nivelCiudadano];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(NivelCiudadano $nivelCiudadano,$nivelciudadanoId,$pagina)
    {
        $this->authorize('update', [$nivelCiudadano, 'NivelCiudadano']);
        Session::put('paginaActualNCiu',$pagina);
        return view('nivel-ciudadano.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(NivelCiudadano $nivelCiudadano, NivelCiudadanoRequest $request)
    {
        $this->authorize('update', [$nivelCiudadano, 'NivelCiudadano']);

        DB::beginTransaction();

        try {
            $data = $request->all();

            $nivelCiudadano->update($data);

            if(isset($data['icono_file']) && isset($data['icono_file']['value']))
            {
                $this->setIconoFile($nivelCiudadano, $data);
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
    public function destroy(NivelCiudadano $nivelCiudadano)
    {
        $this->authorize('delete', [$nivelCiudadano, 'NivelCiudadano']);

        DB::beginTransaction();

        try {

            $exists = \Storage::disk('public')->exists('files/images/makes/logos/' . $nivelCiudadano->icono);

            if ($exists && !empty($nivelCiudadano->icono)) {
                \Storage::disk('public')->delete('files/images/makes/logos/' . $nivelCiudadano->icono);
            }

            $nivelCiudadano->delete();

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'exception'=>$exception->getMessage()];

        }
    }

    public function setIconoFile($nivelCiudadano, $data){

            $file = base64_decode($data['icono_file']['value']);
            $extension = explode('.', $data['icono_file']['filename']);
            $index = count($extension);
            $fileName = seoUrl($nivelCiudadano->descripcion . '_icono_' . substr(md5(uniqid(rand(), true)), 0, 6)) . '.' . $extension[$index - 1];

            \Storage::disk('public')->put('/images/niveles-iconos/' . $fileName, $file);

            $exists = \Storage::disk('public')->exists('images/niveles-iconos/' . $nivelCiudadano->icono);

            if ($exists && !empty($nivelCiudadano->icono)) {
                \Storage::disk('public')->delete('images/niveles-iconos/' . $nivelCiudadano->icono);
            }

            $nivelCiudadano->icono = $fileName;

            $nivelCiudadano->save();

    }
}
