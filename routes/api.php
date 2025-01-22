<?php

use App\Http\Controllers\IntegracaoArqSignFeegowController;
use App\Http\Middleware\HmacAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/arqsign/webhook', IntegracaoArqSignFeegowController::class)
->middleware(HmacAuth::class)
->name('arqsign.webhook');
