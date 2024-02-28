<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('users', UserController::class)->except(['create','edit']);

Route::prefix('users/{user}')->group(function (){
    Route::resource('addresses', AddressController::class)->except(['create','edit']);
});
