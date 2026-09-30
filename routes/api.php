<?php

use App\Http\Controllers\Api\BasketController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'index']);
Route::post('/basket/total', [BasketController::class, 'total']);
