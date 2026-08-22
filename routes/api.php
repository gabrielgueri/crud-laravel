<?php

use App\Http\Controllers\LaptopReservationController;
use Illuminate\Support\Facades\Route;

Route::apiResource('reservations', LaptopReservationController::class);



