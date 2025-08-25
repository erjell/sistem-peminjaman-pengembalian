<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\BorrowController;

Route::get('/', function () {
    return view('/auth/login');

});
// return View::make('/auth/login', ['name' => 'James']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

      Route::get('/barang-masuk', [barangController::class, 'create'])->name('barang.create');
      Route::get('/mobile/barang-masuk', [barangController::class, 'createMobile'])->name('barang.mobile.create');
      Route::post('/barang-masuk', [barangController::class, 'store'])->name('barang.store');

      Route::get('/items', [ItemController::class, 'index'])->name('items.index');
      Route::post('/items', [ItemController::class, 'store'])->name('items.store');

      Route::get('/borrow', [BorrowController::class, 'create'])->name('borrow.create');
      Route::post('/borrow', [BorrowController::class, 'borrow'])->name('borrow.store');
      Route::get('/borrow-records', [BorrowController::class, 'index'])->name('borrow.index');
      Route::post('/return/{record}', [BorrowController::class, 'returnItem'])->name('borrow.return');
  });




require __DIR__.'/auth.php';
