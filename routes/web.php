<?php

use App\Http\Controllers\ActividadController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\PinController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::post('pin', [PinController::class, 'store'])->name('pin.store');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('eventos', EventoController::class)
        ->except('show')
        ->parameters(['eventos' => 'evento']);

    Route::resource('actividades', ActividadController::class)
        ->only(['store', 'update', 'destroy'])
        ->parameters(['actividades' => 'actividad']);

    Route::resource('cursos', CursoController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['cursos' => 'curso']);
});

Route::middleware('pin')->group(function () {
    Route::get('calendario', [CalendarioController::class, 'index'])->name('calendario');
    Route::get('cronograma', [CalendarioController::class, 'cronograma'])->name('cronograma');
    Route::get('eventos/{evento}', [EventoController::class, 'show'])->name('eventos.show');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
