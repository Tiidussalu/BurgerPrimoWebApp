<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        $users = User::select('id', 'name', 'email', 'is_admin', 'is_courier', 'created_at')
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
        ]);
    }

    public function toggleCourier(User $user)
    {
        $user->update(['is_courier' => !$user->is_courier]);

        return redirect()->back()->with('success', $user->is_courier
            ? "{$user->name} on nüüd kuller."
            : "{$user->name} pole enam kuller.");
    }
}
