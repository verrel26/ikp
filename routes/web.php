<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// Home
// Route::middleware('guest')->get('/', function () {
//     return view('welcome');
// });
Route::get('/', [HomeController::class, 'index']);
Route::get('/files', [HomeController::class, 'files'])->name('files');

Route::get('/detailHome/{id}', [HomeController::class, 'detailHome'])->name('detailHome');
Route::get('/list', [HomeController::class, 'list'])->name('list');
Route::post('/shareFile', [HomeController::class, 'shareFile'])->name('shareFile');
Route::get('/downloadFile/{id}', [HomeController::class, 'downloadFile'])->name('downloadFile');
Route::post('/reqDownload/{id}', [HomeController::class, 'reqDownload'])->name('reqDownload');


// Login
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// Admin
Route::middleware('auth')->group(function () {

    // Home
    Route::get('/home', [DashboardController::class, 'home']);

    // User
    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('', 'index')->name('user.index');
        Route::get('data', 'data')->name('user.data');
        Route::post('store', 'store')->name('user.store');
        Route::put('update', 'update')->name('user.update');
        Route::delete('delete', 'delete')->name('user.delete');
    });
    // Media
    Route::controller(MediaController::class)->prefix('medias')->group(function () {
        Route::get('', 'index')->name('media.index');
        Route::get('data', 'data')->name('media.data');
        Route::post('store', 'store')->name('media.store');
        Route::put('update', 'update')->name('media.update');
        Route::delete('delete', 'delete')->name('media.delete');
        Route::get('{id}/detail', 'detail')->name('media.detail');
        Route::get('{id}/ddownload', 'ddownload')->name('media.ddownload');
        Route::post('{id}/reqDownload', 'reqDownload')->name('media.reqDownload');
        Route::post('{id}/approve', 'approve')->name('media.approve');
        Route::post('{id}/decline', 'decline')->name('media.decline');
        Route::post('{id}/setPublic', 'setPublic')->name('media.setPublic');
    });
    // Permission
    Route::controller(PermissionController::class)->prefix('permissions')->group(function () {
        Route::get('', 'index')->name('permission.index');
        Route::get('data', 'data')->name('permission.data');
        Route::post('store', 'store')->name('permission.store');
        Route::put('update/{id}', 'update')->name('permission.update');
        Route::delete('delete', 'delete')->name('permission.delete');
    });
});
