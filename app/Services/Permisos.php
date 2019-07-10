<?php
/**
 * Created by PhpStorm.
 * User: icalvay
 * Date: 9/07/18
 * Time: 05:11
 */

namespace App\Services;


use App\Permiso;

class Permisos
{

    public function getMenu()
    {


        $menu = config('menu');

        $final_menu = [];

        foreach ($menu as $item)
        {

            $valid  = false;

            if (isset($item['routes']) && count($item['routes']) > 0){

                $routes = [];

                foreach ($item['routes'] as $route)
                {
                    $valid_route = $this->checkPermiso($route['model'], $route['action']);

                    if($valid_route){
                      if($route['route']=='user.index' || 
                         $route['route']=='rol.index' || 
                         $route['route']=='territorio-vecinal.index' ||
                         $route['route']=='urbanizacion.index' || 
                         $route['route']=='alcalde-vecinal.index' ||
                         $route['route']=='comite-gestion.index' || 
                         $route['route']=='tipo-persona.index' ||
                         $route['route']=='persona.index' || 
                         $route['route']=='incidente.index' ||
                         $route['route']=='incidente.attention' || 
                         $route['route']=='nacionalidad.index' || 
                         $route['route']=='nivel-ciudadano.index' || 
                         $route['route']=='actividad-puntuacion.index' ||
                         $route['route']=='tipo-incidente.index' || 
                         $route['route']=='estado-incidente.index' || 
                         $route['route']=='nivel-agua.index' || 
                         $route['route']=='tipo-obstaculo.index' || 
                         $route['route']=='coordinacion.index'){
                        $route['url'] = route($route['route'],0);
                      }else{
                        $route['url'] = route($route['route']);
                      }
                      $routes[] = $route;
                    }
                }

                if(count($routes) > 0){
                    $valid = true;
                    $item['routes'] = $routes;
                }

            }else{
                $valid = $this->checkPermiso($item['model'], $item['action']);
                $item['url'] = route($item['route'],0);
                // if($route['route']=='configuracion.index'){
                //   $item['url'] = route($item['route'],0);
                // }else{
                //   $item['url'] = route($item['route']);
                // }
            }

            if($valid){
                $final_menu[]  = $item;
            }

        }

        return $final_menu;

    }


    public function checkPermiso($model_name, $action)
    {
        $user = auth()->user();

        if($user->admin == 1)
        {
            return true;
        }

        $roles = $user->roles()->pluck('rol.id');
        $permission = Permiso::whereIn('rol_id', $roles)->where('model', $model_name)->get()->toArray();

        $key = array_search($action, array_column($permission, 'action'));

        if(is_numeric($key))
        {
            return true;
        }

        return false;
    }

}