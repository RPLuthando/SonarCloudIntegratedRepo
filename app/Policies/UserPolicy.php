<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    
    public function view(User $user, User $model)
    {
        //
    }

   
    public function create(User $user)
    {  
        return in_array($user->email,
        ['admin@admin.com',]);
    }

    
    public function update(User $user, User $model)
    {
        //
    }

  
    public function delete(User $user, User $model)
    {
        //
        return in_array($user->email,
        ['admin@admin.com',]);
    }

    
    public function restore(User $user, User $model)
    {
        //
    }

    
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
