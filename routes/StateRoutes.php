<?php

use App\Http\Controllers\StateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('states', StateController::class)->except(['create','edit','store','update','destroy']);
