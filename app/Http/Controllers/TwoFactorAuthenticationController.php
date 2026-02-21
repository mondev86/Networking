<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class TwoFactorChallengeController extends Controller
{
    /**
     * Show the two factor challenge form.
     */
    public function create(Request $request): Response
    {
        // Verificar que hay un usuario pendiente de autenticación 2FA
        if (!$request->session()->has('login.two_factor_id')) {
            return redirect()->route('login');
        }

        return Inertia::render('auth/TwoFactorChallenge');
    }

    /**
     * Verify the two factor authentication code.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $userId = $request->session()->get('login.two_factor_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::findOrFail($userId);

        // Verificar el código 2FA (implementa tu lógica aquí)
        $isValid = $this->verifyTwoFactorCode($user, $request->code);

        if (!$isValid) {
            return back()->withErrors([
                'code' => 'The provided code is invalid.',
            ]);
        }

        // Limpiar la sesión temporal
        $request->session()->forget('login.two_factor_id');

        // Autenticar al usuario
        Auth::login($user, $request->session()->get('login.remember', false));
        $request->session()->forget('login.remember');

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Verify the two factor authentication code.
     */
    protected function verifyTwoFactorCode($user, string $code): bool
    {
        // Implementa tu lógica de verificación aquí
        // Ejemplo básico (debes usar una librería como Google2FA):
        if (!$user->two_factor_secret) {
            return false;
        }

        // Aquí iría la verificación real del código TOTP
        // Por ahora, un ejemplo simple:
        return strlen($code) === 6 && is_numeric($code);
    }
}
