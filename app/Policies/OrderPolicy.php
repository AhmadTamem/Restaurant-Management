<?php

namespace App\Policies;

use App\Models\User;

class OrderPolicy
{
    public function create(User $user)
    {
        return $user->role === 'user' || $user->role === 'admin';
    }
   public function update(User $user): bool
   {
       return $user->role === 'captain' || $user->role === 'admin';
   }
   public function index(User $user): bool
   {
       return $user->role === 'captain' || $user->role === 'admin';
   }
}
