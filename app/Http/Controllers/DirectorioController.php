<?php

namespace App\Http\Controllers;

use App\Http\Requests\DirectorioRequest;
use Illuminate\Http\Request;
use App\Directorio;
use Illuminate\Support\Facades\DB;
use Session;

class DirectorioController extends Controller
{
      /* FUNCIONALIDADES PARA MEJORAR LA INTERACTIVIDAD */
    private function fnSetPaginaActual(){
      $paginaActual = Session::get('paginaActualDire');
      if($paginaActual==null)
        Session::put('paginaActualDire',1);
    }
    
    public function getPageSession(){
      $this->fnSetPaginaActual();
      $paginaSession = Session::get('paginaActualDire');
      return ['success'=>true , 'paginaActual'=>$paginaSession];
    }  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Directorio $directorio)
    {
        $this->authorize('view', [$directorio, 'Directorio']);
        // if($validacion==0)
        //   Session::put('paginaActualDire',1);
      
        // if($validacion==1)
        //   $this->fnSetPaginaActual();
        return view('directorio.index');
    }


    public function all(Directorio $directorio)
    {
        $this->authorize('view', [$directorio, 'Directorio']);
      
        $directorios = Directorio::all();


        return ['success'=>true , 'directorios'=>$directorios];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Directorio $directorio)
    {
        $this->authorize('create', [$directorio, 'Directorio']);

        return view('directorio.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Directorio $directorio, DirectorioRequest $request)
    {
      $this->authorize('create', [$directorio, 'Directorio']);
      DB::beginTransaction();
      try {
        $data = $request->all();
        Directorio::create($data);
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
    public function show(Directorio $directorio)
    {
        $this->authorize('view', [$directorio, 'Directorio']);

        return ['sucess'=>true, 'directorio'=>$directorio];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Directorio $directorio,$directorioId,$pagina)
    {
        $this->authorize('update', [$directorio, 'Directorio']);
        Session::put('paginaActualDire',$pagina);
        return view('directorio.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Directorio $directorio, DirectorioRequest $request)
    {
        $this->authorize('update', [$directorio, 'Directorio']);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $directorio->update($data);

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
    public function destroy(Directorio $directorio)
    {
        $this->authorize('update', [$directorio, 'Directorio']);

        DB::beginTransaction();

        try {

            $directorio->delete();

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.'];

        }
    }

    /** -- api -- */
    public function getDirectorio()
    {
        $directorio = Directorio::get();
        return response()->json($directorio);

    }
    
    
}
