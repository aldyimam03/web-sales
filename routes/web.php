<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SaleController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [AuthController::class, 'loginPage'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('profile', [UserController::class, 'update'])->name('profile.update');

    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('permission:dashboard.view');

    Route::prefix('items')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('items.index')->middleware('permission:item.view');
        Route::get('/create', [ItemController::class, 'create'])->name('items.create')->middleware('permission:item.create');
        Route::post('/', [ItemController::class, 'store'])->name('items.store')->middleware('permission:item.create');
        Route::get('/{item}', [ItemController::class, 'show'])->name('items.show')->middleware('permission:item.view');
        Route::get('/{item}/edit', [ItemController::class, 'edit'])->name('items.edit')->middleware('permission:item.update');
        Route::put('/{item}', [ItemController::class, 'update'])->name('items.update')->middleware('permission:item.update');
        Route::delete('/{item}', [ItemController::class, 'destroy'])->name('items.destroy')->middleware('permission:item.delete');
    });

    Route::prefix('/users')->middleware('role:admin')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::prefix('/sales')->group(function () {
        Route::get('/', [SaleController::class, 'index'])->name('sales.index')->middleware('permission:sale.view');
        Route::get('/create', [SaleController::class, 'create'])->name('sales.create')->middleware('permission:sale.create');
        Route::post('/', [SaleController::class, 'store'])->name('sales.store')->middleware('permission:sale.create');
        Route::get('/{sale}', [SaleController::class, 'show'])->name('sales.show')->middleware('permission:sale.view');
        Route::get('/{sale}/edit', [SaleController::class, 'edit'])->name('sales.edit')->middleware('permission:sale.update');
        Route::put('/{sale}', [SaleController::class, 'update'])->name('sales.update')->middleware('permission:sale.update');
        Route::delete('/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy')->middleware('permission:sale.delete');
    });

    Route::prefix('/payments')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('payments.index')->middleware('permission:payment.view');
        Route::get('/create', [PaymentController::class, 'create'])->name('payments.create')->middleware('permission:payment.create');
        Route::post('/', [PaymentController::class, 'store'])->name('payments.store')->middleware('permission:payment.create');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('payments.show')->middleware('permission:payment.view');
        Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit')->middleware('permission:payment.update');
        Route::put('/{payment}', [PaymentController::class, 'update'])->name('payments.update')->middleware('permission:payment.update');
        Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy')->middleware('permission:payment.delete');
    });
});
