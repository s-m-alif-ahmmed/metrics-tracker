<?php
use AlifAhmmed\Http\Controllers\MetricsController;
use Illuminate\Support\Facades\Route;

Route::post('/track-metric', [MetricsController::class, 'store']);
