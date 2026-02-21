<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Fortify\Features;

class TwoFactorAuthenticationController extends Controller
{
    public function show(Request $request): Response|RedirectResponse
    {
        $user = $request->user();

        // Retornar 403 si 2FA está deshabilitado completamente
        if (!Features::canManageTwoFactorAuthentication()) {
            abort(403);
        }

        // Verificar si requiere confirmación de contraseña
        $requiresPasswordConfirmation = Features::optionEnabled(
            Features::twoFactorAuthentication(),
            'confirmPassword'
        ) && !$request->session()->has('auth.password_confirmed_at');

        // Si requiere confirmación y no está confirmada, redirigir
        if ($requiresPasswordConfirmation) {
            return redirect()->route('password.confirm');
        }

        return Inertia::render('settings/TwoFactor', [
            'twoFactorEnabled' => (bool)($user->two_factor_enabled ?? false),
            'requiresConfirmation' => $requiresPasswordConfirmation,
        ]);
    }
}
