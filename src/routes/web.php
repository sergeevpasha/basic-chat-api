<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'status' => 'ok',
    ]);
});

Route::fallback(function () {
    return response()->json([
        'status' => 'Not Found',
    ], 404);
});
