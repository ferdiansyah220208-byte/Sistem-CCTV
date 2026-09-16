<?php

use App\Http\Controllers\Admin\BuildingController;
use App\Http\Controllers\Admin\CameraController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FloorController;
use App\Http\Controllers\Admin\LayoutController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Public\HomeController;


// ============ PUBLIC ============
// Route::get('/', [HomeController::class, 'index'])->name('home');
// Route::get('/buildings', [HomeController::class, 'buildings'])->name('public.buildings');
// Route::get('/buildings/{building}', [HomeController::class, 'floors'])->name('public.floors');
// Route::get('/floors/{floor}', [HomeController::class, 'rooms'])->name('public.rooms');
// Route::get('/rooms/{room}', [HomeController::class, 'detail'])->name('public.room.detail');
// Route::get('/search', [HomeController::class, 'search'])->name('public.search');

Route::get('/', function () {
    return view('welcome');
})->name('home');

// ============ AUTH ============
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
     ->middleware('auth')->name('logout');

// ============ ADMIN ============
Route::middleware(['auth', 'admin'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD
    Route::resource('buildings', BuildingController::class);
    Route::resource('floors', FloorController::class);
    Route::resource('layouts', LayoutController::class);
    Route::resource('rooms', RoomController::class);
    Route::resource('cameras', CameraController::class);

    // Custom endpoints
    Route::patch('rooms/{room}/polygon', [RoomController::class, 'updatePolygon'])
         ->name('rooms.update-polygon');
    Route::patch('cameras/{camera}/status', [CameraController::class, 'updateStatus'])
         ->name('cameras.update-status');
});