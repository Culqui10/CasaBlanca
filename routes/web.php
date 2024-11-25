<?php

use App\Http\Controllers\Admin\AccountStatusController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoriasController;
use App\Http\Controllers\Admin\PaymentMethodsController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\PensionersController;
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
    return redirect('/login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/admin', [AdminController::class,'index'])->name('admin.index');
});




