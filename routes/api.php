<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\BorrowController;

Route::post('/items', [ItemController::class, 'store']);
Route::post('/borrow', [BorrowController::class, 'borrow']);
Route::post('/return/{record}', [BorrowController::class, 'returnItem']);

