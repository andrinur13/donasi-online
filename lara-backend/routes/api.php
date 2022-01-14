<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('campaigns', [CampaignController::class, 'getAllCamapigns']);
Route::get('campaigns/{id}', [CampaignController::class, 'getDetailCampaigns']);

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);


Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('funding', [TransactionController::class, 'funding']);
    Route::post('myfunding', [TransactionController::class, 'myfunding']);
});
