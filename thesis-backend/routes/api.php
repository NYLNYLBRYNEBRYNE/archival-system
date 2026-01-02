<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArchiveController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/archives', [ArchiveController::class, 'index']);
Route::post('/archives', [ArchiveController::class, 'store']); // This handles the upload