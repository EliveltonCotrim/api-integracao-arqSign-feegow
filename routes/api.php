<?php

use App\Http\Controllers\IntegracaoArqSignFeegowController;
use App\Http\Middleware\HmacAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/arqsign/webhook', IntegracaoArqSignFeegowController::class)
    ->middleware(HmacAuth::class)
    ->name('arqsign.webhook');

