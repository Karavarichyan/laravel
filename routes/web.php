<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BlogController; // Подключаем BlogController
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Панель управления
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Маршруты для профиля
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| API маршруты
|--------------------------------------------------------------------------
*/

// CRUD для блогов
Route::prefix('api')->middleware('auth:sanctum')->group(function () {
    Route::get('/blogs', [BlogController::class, 'index']); // Получить список блогов
    Route::post('/blogs', [BlogController::class, 'store']); // Создать новый блог
    Route::put('/blogs/{blog}', [BlogController::class, 'update']); // Обновить существующий блог
    Route::delete('/blogs/{blog}', [BlogController::class, 'destroy']); // Удалить блог
});

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
|
| Обрабатывает все несуществующие маршруты.
|
*/

Route::fallback(function () {
    return Inertia::render('Error/404', [
        'message' => 'Страница не найдена.',
    ]);
});


Route::get('/blogs', function () {
    return Inertia::render('BlogList');
})->middleware(['auth']);

require __DIR__.'/auth.php';
