<?php

use App\Http\Controllers\CampaignController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('dashboard')->group(function() {
    Route::get('/campaign', [CampaignController::class, 'index']);
    Route::get('/campaign/store', [CampaignController::class, 'store']);
    Route::get('/campaign/edit/{id}', [CampaignController::class, 'edit']);
    Route::get('/campaign/update/{id}', [CampaignController::class, 'update']);
    Route::get('/campaign/delete/{id}', [CampaignController::class, 'update']);
});
