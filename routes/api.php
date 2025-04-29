<?php

use App\Http\Controllers\Api\ChecklistApiController;
use App\Http\Controllers\Api\ChecklistItemApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
 
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});

Route::group([
    'middleware' => 'api',
], function ($router) {

    Route::group([
        'prefix' => 'checklist'
    ], function ($router) {
        Route::get('/', [ChecklistApiController::class, 'index']);
        Route::post('/', [ChecklistApiController::class, 'store']);
        Route::put('/{id}', [ChecklistApiController::class, 'update']);
        Route::delete('/{id}', [ChecklistApiController::class, 'destroy']);
    });

    Route::prefix('checklist/{checklist}')->group(function () {
        Route::get('item', [ChecklistItemApiController::class, 'index']);
        Route::post('item', [ChecklistItemApiController::class, 'store']);
        Route::get('item/{item}', [ChecklistItemApiController::class, 'show']);
        Route::put('item/rename/{item}', [ChecklistItemApiController::class, 'rename']);
        Route::put('item/{item}', [ChecklistItemApiController::class, 'updateStatus']);
        Route::delete('item/{item}', [ChecklistItemApiController::class, 'destroy']);
    });
    
});