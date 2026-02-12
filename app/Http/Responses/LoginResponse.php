<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request): JsonResponse|RedirectResponse
    {
        $user = auth()->user();

        // Redirect admin users to admin dashboard
        if ($user && $user->is_admin) {
            return redirect()->intended('/admin/dashboard');
        }

        // Redirect regular users to home page
        return redirect()->intended('/');
    }
}