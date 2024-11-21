<?php

use App\Http\Controllers\Admin\AccountStatusController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoriasController;
use App\Http\Controllers\Admin\ConsumptionsController;
use App\Http\Controllers\Admin\FotosController;
use App\Http\Controllers\Admin\MenusController;
use App\Http\Controllers\Admin\PaymentMethodsController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\PensionersController;
use App\Http\Controllers\Admin\ProductosController;
use App\Http\Controllers\Admin\TypeFoodsController;

use Illuminate\Support\Facades\Route;
use Laravel\Jetstream\Rules\Role;

Route::resource('/categoria', CategoriasController::class)->names('admin.categorias');
Route::resource('/producto', ProductosController::class)->names('admin.productos');
Route::get('categoriabyfamilia/{id}', [CategoriasController::class, 'categoriabyfamilia'])->name('admin.categoriabyfamilia');
Route::resource('foto', FotosController::class)->names('admin.foto');
Route::resource('/paymentmethod',  PaymentMethodsController::class)->names('admin.paymentmethods');
Route::resource('/typefood', TypeFoodsController::class)->names('admin.typefoods');
Route::resource('/menu', MenusController::class)->names('admin.menus');
Route::resource('/pensioner', PensionersController::class)->names('admin.pensioners');
Route::resource('/payment', PaymentsController::class)->names('admin.payments');
Route::get('/pensioners/search', [PensionersController::class, 'search'])->name('pensioners.search');
Route::resource('/accountstatus', AccountStatusController::class)->names('admin.accountstatus');
Route::get('accountstatus', [AccountstatusController::class, 'filter'])->name('admin.accountstatus.index');
Route::resource('/consumption', ConsumptionsController::class)->names('admin.consumptions');

?>