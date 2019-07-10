<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use Excel;
use Session;

class UserController extends Controller
{
  /* FUNCIONALIDADES PARA MEJORAR LA INTERACTIVIDAD */
  private function fnSetPaginaActual(){
    $paginaActual = Session::get('paginaActualU');
    if($paginaActual==null)
      Session::put('paginaActualU',1);
  }

  public function getPageSession(){
    $this->fnSetPaginaActual();
    $paginaSession = Session::get('paginaActualU');
    return ['success'=>true , 'paginaActual'=>$paginaSession];
  }  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user,$validacion)
    {
      $this->authorize('view', [$user, 'User']);
      if($validacion==0)
        Session::put('paginaActualU',1);
        
      if($validacion==1)
        $this->fnSetPaginaActual();
        
      return view('user.index');
    }


    public function all(User $user, Request $request)
    {
        $dni = $request->get('dni');
        $nombres = $request->get('nombres');
        $apellidos = $request->get('apellidos');

        $this->authorize('view', [$user, 'User']);

        $users = User::select('users.name','users.id','users.email','persona.dni',
                              'users.password','users.last_name','users.admin',
                              'users.state','users.persona_id','users.rol_id',
                              'users.created_at','users.token')
                    ->with(['roles', 'persona'])
                    ->join('persona', 'persona.id', '=', 'users.persona_id')
                    ->filterDni($dni)
                    ->filterNombres($nombres)
                    ->filterApellidos($apellidos)
                    ->get();
        return ['success'=>true , 'users'=>$users,'dni'=>$dni,'nombres'=>$nombres,'apellidos'=>$apellidos];
    }

    //
/* GENERACION DE EXCEL */
public function exportarAExcel(User $user, Request $request)
{
  $dni = $request->get('dni');
  $nombres = $request->get('nombres');
  $apellidos = $request->get('apellidos');

  $users = User::select('users.name','users.id','users.email','persona.dni',
                        'users.password','users.last_name','users.admin',
                        'users.state','users.persona_id','users.rol_id',
                        'users.created_at','users.token')
              ->with(['roles', 'persona'])
              ->join('persona', 'persona.id', '=', 'users.persona_id')
              ->filterDni($dni)
              ->filterNombres($nombres)
              ->filterApellidos($apellidos)
              ->get();

  $fecha = now();
  $filename = "usuarios-{$fecha}";

  Excel::create($filename, function($excel) use($users,$dni,$nombres,$apellidos,$fecha){
    $excel->sheet("Usuarios", function($sheet) use($users,$dni,$nombres,$apellidos,$fecha){
      $sheet->mergeCells("A1:G1");
      $sheet->cell("A1", function($cell) {
          $cell->setValue("Listado de usuarios");
          $cell->setAlignment("center");
          $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
      });
      $sheet->mergeCells("A2:G2");
      $sheet->cell("A2", function($cell) use($fecha) {
          $cell->setValue("Fecha : $fecha");
          $cell->setAlignment("center");
          $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
      });
      $sheet->cell("A3", function($cell) {
        $cell->setValue("FILTROS");
        $cell->setAlignment("center");
        $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
      });
      $sheet->mergeCells("B3:C3");
      $sheet->cell("B3", function($cell) use($dni) {
        $cell->setValue("Dni : {$dni}");
        $cell->setAlignment("center");
        $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
      });
      $sheet->mergeCells("D3:E3");
      $sheet->cell("D3", function($cell) use($nombres) {
        $cell->setValue("Nombres : {$nombres}");
        $cell->setAlignment("center");
        $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
      });
      $sheet->mergeCells("F3:G3");
      $sheet->cell("F3", function($cell) use($apellidos) {
        $cell->setValue("Apellidos : {$apellidos}");
        $cell->setAlignment("center");
        $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
      });
      $sheet->cell("A4", function($cell) {$cell->setValue("Dni"); $cell->setAlignment("center"); });
      $sheet->cell("B4", function($cell) {$cell->setValue("Nombre"); $cell->setAlignment("center"); });
      $sheet->cell("C4", function($cell) {$cell->setValue("Apellidos"); $cell->setAlignment("center"); });
      $sheet->cell("D4", function($cell) {$cell->setValue("Usuario"); $cell->setAlignment("center"); });
      $sheet->cell("E4", function($cell) {$cell->setValue("Rol"); $cell->setAlignment("center"); });
      $sheet->cell("F4", function($cell) {$cell->setValue("Estado"); $cell->setAlignment("center"); });
      $sheet->cell("G4", function($cell) {$cell->setValue("Fecha registro"); $cell->setAlignment("center"); });
      if(!empty($users)) {
        foreach($users as $key => $value) {
          $i = $key + 5;
          $sheet->cell('A'.$i, $value->dni);
          $sheet->cell('B'.$i, "{$value->persona->nombres}");
          $sheet->cell('C'.$i, "{$value->persona->ape_paterno} {$value->persona->ape_materno}");
          $sheet->cell('D'.$i, $value->email);
          $sheet->cell('E'.$i, $this->fnObtenerRoles($value->roles));
          $sheet->cell("F".$i, $value->state);
          $sheet->cell("G".$i, $value->created_at);
        }
      }
    });
  })->download('xls');
}

public function getloader(){
  $ruta = asset('/img/loader.gif');
  return ['ruta' => $ruta];
}

  /**
   * Obtiene los datos en base a los filtros 
   *
   * @param request $request
   * @return User
   */
  private function fnObtenerUsuarios($request){
    $dni = $request->get('dni');
    $nombres = $request->get('nombres');
    $apellidos = $request->get('apellidos');

    $users = User::select('users.name','users.id','users.email','persona.dni',
                          'users.password','users.last_name','users.admin',
                          'users.state','users.persona_id','users.rol_id',
                          'users.created_at','users.token')
                ->with(['roles', 'persona'])
                ->join('persona', 'persona.id', '=', 'users.persona_id')
                ->filterDni($dni)
                ->filterNombres($nombres)
                ->filterApellidos($apellidos)
                ->get();
    return $users; 
  }

  /**
   * Obtiene un astring de roles en base a un array de objetos
   *
   * @return void
   */
  private function fnObtenerRoles($roles){
    $strRoles = '';
    foreach($roles as $rol){
      $strRoles = $strRoles." {$rol->descripcion}";
    }
    return $strRoles;
  }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        $this->authorize('create', [$user, 'User']);

        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(User $user,UserRequest $request)
    {
        $this->authorize('create', [$user, 'User']);

        DB::beginTransaction();

        try {
            $data = $request->all();

            $user = User::create($data);

            foreach ($data['roles'] as $datum)
            {
                $user->roles()->attach($datum['id']);
            }

            $linkToModel = '"<a href="'.route('user.edit', ['user'=>$user->id]).'">'.$user->email.'</a>"';

            activity()->log("Se creó un nuevo usuario {$linkToModel}");

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
    public function show(User $user)
    {
        $this->authorize('view', [$user, 'User']);

        $user->persona;
        $user->roles;

        return ['sucess'=>true, 'user'=>$user];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user,$userId,$pagina)
    {
      $this->authorize('update', [$user, 'User']);
      Session::put('paginaActualU',$pagina);
      return view('user.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, UserRequest $request)
    {
        $this->authorize('update', [$user, 'User']);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $user->update($data);

            $user->roles()->detach();

            foreach ($data['roles'] as $datum)
            {
                $user->roles()->attach($datum['id']);
            }

            $linkToModel = '"<a href="'.route('user.index', ['pagina'=>1]).'">'.$user->email.'</a>"';
            activity()->log("Se actualizó el usuario {$linkToModel}");

            DB::commit();

            return ['success' => true];
            return route('user.index',1);

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
    public function destroy(User $user)
    {
        $this->authorize('delete', [$user, 'User']);

        DB::beginTransaction();

        try {

            $user->roles()->detach();
            $user->delete();

            DB::commit();

            return ['success' => true];

        } catch (\Exception $exception) {
            DB::rollBack();

            return ['success' => false, 'message' => 'Hubo un error, intente nuevamente.', 'e'=>$exception];

        }
    }

    public function search(Request $request)
    {
        $q = $request->get('q');

        $users = User::whereRaw('email like "%'.$q.'%"')
            ->get();

        return $users;

    }

    /** -- api -- */
    public function getUsers()
    {
        $users = User::get();
        return response()->json($users);
    }

    public function getUserById($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }
}
