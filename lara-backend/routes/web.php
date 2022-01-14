<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\TransactionController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [AuthController::class, 'showFormLogin'])->name('login');
Route::get('login', [AuthController::class, 'showFormLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showFormRegister'])->name('register');
Route::post('register', [AuthController::class, 'register']);


Route::group(['middleware' => 'auth'], function () {
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::group(['middleware' => ['role:admin']], function() {
        Route::prefix('dashboard')->group(function() {
            Route::get('/campaign', [CampaignController::class, 'index']);
            Route::post('/campaign/store', [CampaignController::class, 'store']);
            Route::get('/campaign/create', [CampaignController::class, 'create']);
            Route::get('/campaign/edit/{id}', [CampaignController::class, 'edit']);
            Route::post('/campaign/update/{id}', [CampaignController::class, 'update']);
            Route::get('/campaign/delete/{id}', [CampaignController::class, 'delete']);

            Route::get('/campaign/report', [CampaignController::class, 'report']);

            Route::get('/transactions', [TransactionController::class, 'index']);
            Route::get('/transactions/approve/{id}', [TransactionController::class, 'approve']);

        });
    });
});
