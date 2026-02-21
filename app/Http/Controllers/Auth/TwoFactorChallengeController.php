<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TwoFactorChallengeController extends Controller
{
    public function create(Request $request): Response|\Illuminate\Http\RedirectResponse
    {
        // Si no hay sesión de 2FA y no está autenticado, redirigir a login
        if (!$request->session()->has('login.two_factor_id') && !auth()->check()) {
            return redirect()->route('login');
        }

        return Inertia::render('auth/TwoFactorChallenge');
    }
}
