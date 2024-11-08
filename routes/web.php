<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;


// Route::get('/', function () {
//     return redirect()->route('login');
// });

// Route::get('/login', [LoginController::class, 'index'])->name('login');
// Route::post('/login', [LoginController::class, 'authenticate']);
// Route::get('/auth/register', [RegisterController::class, 'index']);
// Route::post('/auth/`register', [RegisterController::class, 'store']);

Auth::routes();

// Menu Utama
Route::get('/', function () {
    return view('index');
});
// Menu Utama

// user
Route::middleware('auth')->group(function () {
    // Permission
    Route::controller(PermissionController::class)->prefix('permission')->group(function () {
        Route::get('', 'index')->name('permission.index');
        Route::get('data', 'data')->name('permission.data');
        Route::post('store', 'store')->name('permission.store');
        Route::put('update', 'update')->name('permission.update');
        Route::delete('destroy', 'destroy')->name('permission.destroy');
    });
    // Role
    Route::controller(RoleController::class)->prefix('role')->group(function () {
        Route::get('', 'index')->name('role.index');
        Route::get('data', 'data')->name('role.data');
        Route::post('store', 'store')->name('role.store');
        Route::put('update', 'update')->name('role.update');
        Route::delete('destroy', 'destroy')->name('role.destroy');

        Route::post('assign-permission', 'assignPermission')->name('role.assignPermission');
    });
    // Berita
    Route::controller(BeritaController::class)->prefix('berita')->group(function () {
        Route::get('', 'index')->name('berita.index');
        Route::get('data', 'data')->name('berita.data');
        Route::post('store', 'store')->name('berita.store');
        Route::put('update', 'update')->name('berita.update');
        Route::delete('destroy', 'destroy')->name('berita.destroy');
    });
    // Foto
    Route::controller(FotoController::class)->prefix('foto')->group(function () {
        Route::get('', 'index')->name('foto.index');
        Route::get('data', 'data')->name('foto.data');
        Route::post('store', 'store')->name('foto.store');
        Route::put('update', 'update')->name('foto.update');
        Route::delete('destroy', 'destroy')->name('foto.destroy');
    });
    // Video
    Route::controller(VideoController::class)->prefix('video')->group(function () {
        Route::get('', 'index')->name('video.index');
        Route::get('data', 'data')->name('video.data');
        Route::post('store', 'store')->name('video.store');
        Route::put('update', 'update')->name('video.update');
        Route::delete('destroy', 'destroy')->name('video.destroy');
    });
    // User
    Route::controller(UserController::class)->prefix('user')->group(function () {
        Route::get('', 'index')->name('user.index');
        Route::get('data', 'data')->name('user.data');
        Route::post('store', 'store')->name('user.store');
        Route::put('update', 'update')->name('user.update');
        Route::delete('destroy', 'destroy')->name('user.destroy');
    });
});
// user

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
