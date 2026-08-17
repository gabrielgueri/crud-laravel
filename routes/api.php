<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Laravel API running']);
});

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
