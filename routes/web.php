<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Aplicação Laravel funcionando!',
        'status' => 'online'
    ]);
});