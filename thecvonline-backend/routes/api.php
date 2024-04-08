<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailController;

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

    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('password');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password');

    Route::get('generateExcel', [EmailController::class, 'generateExcel'])->name('generate.excel');

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::prefix('email')->group(function(){
            Route::get('/', [EmailController::class, 'index'])->name('email.index');
            Route::post('create', [EmailController::class, 'store'])->name('email.create');
            Route::post('upload', [EmailController::class, 'upload'])->name('email.upload');
            Route::put('update/{id}', [EmailController::class, 'update'])->name('email.update');
            Route::delete('/{id}', [EmailController::class, 'destroy'])->name('email.delete');
            Route::get('counts', [EmailController::class, 'getCount'])->name('email.count');
            Route::get('get-static-data', [EmailController::class, 'getStaticData'])->name('get.static.data');
            Route::get('get-template', [EmailController::class, 'getTemplate'])->name('email.get.template');
            Route::post('set-template', [EmailController::class, 'setTemplate'])->name('email.set.template');
            Route::post('send-email', [EmailController::class, 'sendEmail'])->name('send.email');
        });

        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });


