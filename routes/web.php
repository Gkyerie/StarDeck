<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Catalog (admin & member)
    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
    Route::get('/catalog/{unit}', [CatalogController::class, 'show'])->name('catalog.show');

    // Member: own profile
    Route::get('/profile', [MemberController::class, 'myProfile'])->name('profile.show');
    Route::get('/profile/edit', [MemberController::class, 'editMyProfile'])->name('profile.edit');
    Route::put('/profile', [MemberController::class, 'updateMyProfile'])->name('profile.update');

    // Member: own loans
    Route::get('/my-loans', [LoanController::class, 'myLoans'])->name('loans.my');

    /*
    |----------------------------------------------------------------------
    | Admin-only Routes
    |----------------------------------------------------------------------
    */

    Route::middleware('admin')->group(function () {
        // Categories
        Route::resource('categories', CategoryController::class)->except(['show']);

        // Units
        Route::resource('units', UnitController::class);

        // Members
        Route::resource('members', MemberController::class);

        // Loans - borrowing
        Route::get('/loans/create', [LoanController::class, 'create'])->name('loans.create');
        Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');

        // Loans - active list
        Route::get('/loans/active', [LoanController::class, 'active'])->name('loans.active');

        // Loans - return
        Route::get('/loans/{loan}/return', [LoanController::class, 'showReturn'])->name('loans.return');
        Route::post('/loans/{loan}/return', [LoanController::class, 'processReturn'])->name('loans.process-return');

        // Loans - history
        Route::get('/members/{member}/history', [LoanController::class, 'history'])->name('loans.history');
        Route::get('/members/{member}/history/print', [LoanController::class, 'printHistory'])->name('loans.print-history');
    });
});
