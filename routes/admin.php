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
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\TypeFoodsController;

use Illuminate\Support\Facades\Route;
use Laravel\Jetstream\Rules\Role;


Route::resource('/paymentmethod',  PaymentMethodsController::class)->names('admin.paymentmethods');
Route::resource('/typefood', TypeFoodsController::class)->names('admin.typefoods');
Route::resource('/menu', MenusController::class)->names('admin.menus');
Route::resource('/pensioner', PensionersController::class)->names('admin.pensioners');
Route::resource('/payment', PaymentsController::class)->names('admin.payments');
Route::get('/pensioners/search', [PensionersController::class, 'search'])->name('pensioners.search');
Route::resource('/accountstatus', AccountStatusController::class)->names('admin.accountstatus');
Route::get('accountstatus', [AccountstatusController::class, 'filter'])->name('admin.accountstatus.index');
Route::resource('/consumption', ConsumptionsController::class)->names('admin.consumptions');
Route::get('/menus/filter', [MenusController::class, 'filterMenus'])->name('menus.filterMenus');
Route::get('/menus/get-price/{menuId}', [MenusController::class, 'getMenuPrice'])->name('menus.getMenuPrice');
Route::get('/reports', [ReportsController::class, 'index'])->name('admin.reports.index');
Route::get('/reports/generate', [ReportsController::class, 'generate'])->name('admin.reports.generate');
Route::get('/admin/graficos/pensioners', [AdminController::class, 'getPensionersByAnio'])->name('admin.graficos.pensioners');
Route::get('/admin/graficos/menus', [AdminController::class, 'getMenusByTipoComida'])->name('admin.graficos.menus');


?>