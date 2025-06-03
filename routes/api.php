<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\CallbackController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\GenresController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserContentItemController;
use App\Http\Controllers\WatchHistoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('test' , [TestController::class, 'index']);

Route::patch('callback/update-video-quality', [CallbackController::class, 'updateVideoQuality'])
    ->name('callback.update-video-quality');

Route::get('/movies/{uuid}', [MovieController::class, 'showByUuid']);
Route::get('/series/{uuid}', [SeriesController::class, 'showByUuid']);
Route::apiResource('movies', MovieController::class);
Route::get('/series/{series}/seasons/{season}/episodes', [SeriesController::class, 'getEpisodesBySeason']);
Route::get('/series/{series}/episodes', [SeriesController::class, 'getEpisodesBySeries']);
Route::apiResource('series', SeriesController::class);

Route::get('/films', [FilmController::class, 'index']);

Route::get('/genres', [GenresController::class, 'index']);
Route::get('/plans', [PlanController::class, 'index']);
Route::get('/subscriptions/user/{uuid}', [SubscriptionController::class, 'showByUserUuid']);

Route::get('/episodes/{uuid}', [EpisodeController::class, 'showByUuid']);

Route::get('/payments', [PaymentController::class, 'createPayment']);
Route::get('/payment/return', [PaymentController::class, 'paymentReturn']);

Route::get('/content/user-content-item', [UserContentItemController::class, 'showByUserUuid']);
Route::post('/content/user-content-item', [UserContentItemController::class, 'store']);
Route::delete('/content/user-content-item', [UserContentItemController::class, 'destroy']);

Route::get('/watch-history', [WatchHistoryController::class, 'showByUserUuid']);
Route::post('/watch-history', [WatchHistoryController::class, 'update']);

