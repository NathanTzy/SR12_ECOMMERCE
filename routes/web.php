<?php

use App\Http\Controllers\FrontEnd\AlamatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\FrontEnd\FrontEndController;
use App\Http\Controllers\FrontEnd\PaymentProofController;
use App\Http\Controllers\FrontEnd\TrackPesananController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Models\PaymentProofDetail;

/*
|--------------------------------------------------------------------------
| Public & Auth Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('pages.backend.auth.auth-login'));
Route::get('/login', [AuthController::class, 'loginForm'])->name('loginForm');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Frontend - Akses untuk Semua Role yang Authenticated
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:distributor|agen|subAgen|marketer|reseller'])->group(function () {
    Route::get('/index', [FrontEndController::class, 'index'])->name('frontend.index');
    Route::get('/kategori/{slug}', [FrontEndController::class, 'kategori'])->name('kategori.show');
    Route::get('/categories', [FrontEndController::class, 'allCategories'])->name('frontend.all-categories');
    Route::get('/produk/{id}', [FrontEndController::class, 'detail'])->name('produk.detail');
    Route::resource('alamat', AlamatController::class)->except(['show']);
    Route::resource('cart', \App\Http\Controllers\FrontEnd\CartController::class)->except(['show', 'edit', 'create']);
    Route::post('/checkout/bukti-tf', [PaymentProofController::class, 'store'])->name('payment-proof.store');
    Route::resource('track-pesanan', TrackPesananController::class);
    Route::get('/item/{item}/riwayat', [ItemController::class, 'riwayat'])->name('item.riwayat');
    Route::patch('/track-pesanan/{id}/cancel', [TrackPesananController::class, 'cancel'])->name('track-pesanan.cancel');
});

/*
|--------------------------------------------------------------------------
| Backend - Khusus Distributor
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:distributor'])->group(function () {
    Route::get('/dashboard', fn() => view('pages.backend.dashboard'))->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('category', CategoryController::class)->except('show');
    Route::resource('item', ItemController::class)->except('show');
    Route::resource('payment', PaymentController::class)->except('show');
    Route::resource('discount', DiscountController::class)->except('show');
    Route::resource('pesanan', PaymentProofController::class);
    Route::put('/payment-detail/{id}/status', [PaymentProofController::class, 'updateStatus'])->name('paymentDetail.updateStatus');
    Route::patch('/pesanan/acc-batal/{id}', [PaymentProofController::class, 'accBatal'])->name('pesanan.acc-batal');
    Route::patch('/pesanan/tolak-batal/{id}', [PaymentProofController::class, 'tolakBatal'])->name('pesanan.tolak-batal');
});
