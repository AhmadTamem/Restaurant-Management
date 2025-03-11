<?php

namespace App\Policies;

use App\Models\User;

class Menupolicy
{
    public function create(User $user)
    {
        return $user->role === 'manager' || $user->role === 'admin';
    }
    public function update(User $user)
    {
        return $user->role === 'manager' || $user->role === 'admin';
    }
    public function delete(User $user)
    {
        return $user->role === 'manager' || $user->role === 'admin';
    }
}