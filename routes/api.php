<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TranslationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function (Request $request) {
    dd('test');
});

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/translations/export', [TranslationController::class, 'export']);
    Route::apiResource('translations', TranslationController::class);
});