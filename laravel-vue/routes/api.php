<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiProxyController;

Route::any('/{any}', [ApiProxyController::class, 'proxy'])
    ->where('any', '.*');