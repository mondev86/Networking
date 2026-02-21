<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NetworkDeviceController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketResponseController;
use App\Http\Controllers\DeviceController;  // ← AGREGAR
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TwoFactorChallengeController;
use App\Http\Controllers\Settings\TwoFactorAuthenticationController;
use Inertia\Inertia;

// Ruta de bienvenida
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tickets
    Route::resource('tickets', TicketController::class);

    // Ticket Responses
    Route::post('tickets/{ticket}/responses', [TicketResponseController::class, 'store'])
        ->name('tickets.responses.store');
    Route::delete('ticket-responses/{response}', [TicketResponseController::class, 'destroy'])
        ->name('tickets.responses.destroy');

    // ========== DISPOSITIVOS DE RED (NetworkDevice) ==========
    Route::middleware(['role:admin,superadmin'])->group(function () {
        Route::prefix('devices')->name('devices.')->group(function () {
            // Solo lectura para network devices
            Route::get('/', [NetworkDeviceController::class, 'index'])->name('index');
            Route::get('/create', [NetworkDeviceController::class, 'create'])->name('create');
            Route::post('/', [NetworkDeviceController::class, 'store'])->name('store');
            Route::get('/{device}', [NetworkDeviceController::class, 'show'])->name('show');
            Route::get('/{device}/edit', [NetworkDeviceController::class, 'edit'])->name('edit');
            Route::put('/{device}', [NetworkDeviceController::class, 'update'])->name('update');
            Route::delete('/{device}', [NetworkDeviceController::class, 'destroy'])->name('destroy');
            Route::get('/available', [NetworkDeviceController::class, 'available'])->name('available');
        });
    });

    // ========== DISPOSITIVOS INFORMATICOS (Device - Impresoras, Monitores, etc) ==========
    Route::middleware(['role:admin,superadmin'])->group(function () {
        Route::prefix('equipment')->name('equipment.')->group(function () {
            // CRUD básico
            Route::get('/', [DeviceController::class, 'index'])->name('index');
            Route::get('/create', [DeviceController::class, 'create'])->name('create');
            Route::post('/', [DeviceController::class, 'store'])->name('store');
            Route::get('/{device}', [DeviceController::class, 'show'])->name('show');
            Route::get('/{device}/edit', [DeviceController::class, 'edit'])->name('edit');
            Route::put('/{device}', [DeviceController::class, 'update'])->name('update');
            Route::delete('/{device}', [DeviceController::class, 'destroy'])->name('destroy');

            // Asignación
            Route::post('/{device}/assign', [DeviceController::class, 'assign'])->name('assign');
            Route::post('/{device}/unassign', [DeviceController::class, 'unassign'])->name('unassign');

            // Por usuario/equipo
            Route::get('/user/{user}', [DeviceController::class, 'userDevices'])->name('user');
            Route::get('/team/{team}', [DeviceController::class, 'teamDevices'])->name('team');
             Route::get('/equipment/{equipment}/assignment-targets', [DeviceController::class, 'assignmentTargets'])
    ->name('equipment.assignment-targets');

            // API endpoints
            Route::get('/{device}/assignment-targets', [DeviceController::class, 'getAssignmentTargets']);
            Route::get('/{device}/history', [DeviceController::class, 'getHistory']);
        });
    });

    // ========== GESTIÓN DE USUARIOS (Solo Superadmin) ==========
    Route::middleware(['role:superadmin'])->group(function () {
        Route::resource('users', UserController::class);
    });

});


// Two Factor Challenge (Auth)
Route::middleware('guest')->group(function () {
    Route::get('/two-factor-challenge', [TwoFactorChallengeController::class, 'create'])
        ->name('two-factor.login');

    Route::post('/two-factor-challenge', [TwoFactorChallengeController::class, 'store']);
});

// Two Factor Challenge - SIN middleware guest para permitir usuarios autenticados
Route::get('/two-factor-challenge', [TwoFactorChallengeController::class, 'create'])
    ->name('two-factor.login');

// Two Factor Settings - SIN password.confirm
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/settings/two-factor', [TwoFactorAuthenticationController::class, 'show'])
        ->name('two-factor.show');
});

require __DIR__.'/auth.php';
