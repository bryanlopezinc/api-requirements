<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

Route::get('/products', Controllers\FetchProductsController::class)->name('getAllProducts');
