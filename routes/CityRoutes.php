<?php

use App\Http\Controllers\CityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('cities', CityController::class)->except(['create','edit','store','update','destroy']);
