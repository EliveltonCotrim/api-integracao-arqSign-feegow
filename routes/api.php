<?php

use App\Http\Controllers\ArqSignWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/arqsign/webhook', ArqSignWebhookController::class)->name('arqsign.webhook');
