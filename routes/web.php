<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/chat', [ChatController::class, 'index'])->middleware(['auth', 'verified'])->name('chat');
Route::post('/chat', [ChatController::class, 'store'])->middleware(['auth', 'verified'])->name('chat.store');
Route::get('/chat/{chatSession}', [ChatController::class, 'show'])->middleware(['auth', 'verified'])->name('chat.show');
Route::put('/chat/{chatSession}', [ChatController::class, 'update'])->middleware(['auth', 'verified'])->name('chat.update');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
