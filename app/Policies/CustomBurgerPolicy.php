<?php

namespace App\Policies;

use App\Models\CustomBurger;
use App\Models\User;

class CustomBurgerPolicy
{
    public function view(User $user, CustomBurger $burger): bool
    {
        return $user->id === $burger->user_id;
    }

    public function update(User $user, CustomBurger $burger): bool
    {
        return $user->id === $burger->user_id;
    }

    public function delete(User $user, CustomBurger $burger): bool
    {
        return $user->id === $burger->user_id;
    }
}