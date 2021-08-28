<?php

use App\Http\Controllers\API\V1\AuthController as AuthControllerV1;
use App\Http\Controllers\API\V1\PostController;
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

Route::middleware(['cors', 'json.response'])->group(function () {
    Route::prefix('v1')->group(function () {
        Route::post('/register', [AuthControllerV1::class, 'register']);
        Route::post('/login', [AuthControllerV1::class, 'login']);

        Route::middleware(['auth:api'])->group(function () {
            Route::post('/logout', [AuthControllerV1::class, 'logout']);
            Route::get('/profile', [AuthControllerV1::class, 'profile']);
            Route::post('/refreshToken', [AuthControllerV1::class, 'refreshToken']);
            Route::apiResource('/post', PostController::class);
        });
    });
});
