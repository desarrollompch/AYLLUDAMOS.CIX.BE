<?php

namespace App\Policies;

use App\Permiso;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ACLPolicy
{
    use HandlesAuthorization;

    public function view(User $user, $model, $model_name)
    {
        return $this->checkPermission($user, $model_name, 'view');
    }

    public function create(User $user, $model, $model_name)
    {
        return $this->checkPermission($user, $model_name, 'create');
    }


    public function update(User $user, $model, $model_name)
    {
        return $this->checkPermission($user, $model_name, 'update');
    }


    public function delete(User $user, $model, $model_name)
    {
        return $this->checkPermission($user, $model_name, 'delete');
    }

    public function attention(User $user, $model, $model_name)
    {
        return $this->checkPermission($user, $model_name, 'attention');
    }

    public function attentiondetalle(User $user, $model, $model_name) 
    {
        return $this->checkPermission($user, $model_name, 'attentiondetalle');
    }

    public function active(User $user, $model, $model_name)
    {
        return $this->checkPermission($user, $model_name, 'active');
    }


    public function checkPermission($user, $model_name, $action)
    {
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
