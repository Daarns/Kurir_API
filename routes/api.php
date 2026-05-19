<?php

use App\Http\Controllers\Api\KurirController;
use Illuminate\Support\Facades\Route;

Route::apiResource('kurir', KurirController::class);
