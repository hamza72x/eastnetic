<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController as UserApiController;

Route::get('/users', [UserApiController::class, 'getUsers']);
