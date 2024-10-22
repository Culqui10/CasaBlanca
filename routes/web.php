<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoriasController;
use App\Http\Controllers\Admin\ProductosController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::get('/admin', [AdminController::class, 'index'])->name('admin.index')->middleware('auth:sanctum');
Route::resource('/', CategoriasController::class)->names('admin.categorias');
Route::resource('/', ProductosController::class)->names('admin.productos');
Route::get('categoriabyfamilia/{id}', [ProductosController::class, 'categoriabyfamilia'])->name('admin.categoriabyfamilia');


