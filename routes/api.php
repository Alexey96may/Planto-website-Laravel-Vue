<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StripeWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Полный путь будет: https://твой-сайт.onrender.com/api/stripe/webhook
|
*/
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);