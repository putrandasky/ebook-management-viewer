<?php

use App\Http\Controllers;
use App\Http\Controllers\Admin\ChapterController;
use App\Http\Controllers\Admin\EbookController;
use App\Http\Controllers\Admin\HotspotlinkController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\UserController;
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
Route::get('/reader', [Controllers\Reader\EbookReaderController::class, 'show']);
Route::post('/generate-shareable-link-crop-image', [Controllers\Reader\EbookReaderController::class, 'generateShareableCropImage']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users/auth', AuthController::class);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::post('/user/register', [UserController::class, 'create']);
    Route::delete('/user/{user_id}', [UserController::class, 'delete']);
    Route::get('/users', [UserController::class, 'index']);

});

Route::group([
    'middleware' => 'auth:sanctum',
], function ($router) {
    Route::group(['prefix' => 'admin'], function () {
        Route::get('ebooks', [EbookController::class, 'index']);
        Route::post('ebook', [EbookController::class, 'create']);
        Route::get('ebook/{slug}', [EbookController::class, 'show']);
        Route::put('ebook/{ebook_id}', [EbookController::class, 'update']);
        Route::delete('ebook/{ebook_id}', [EbookController::class, 'delete']);
        Route::get('ebook/{slug}/{chapter_id}', [ChapterController::class, 'show']);
        Route::post('chapter/reorder', [ChapterController::class, 'reorder']);
        Route::post('chapter/create-folder', [ChapterController::class, 'createFolder']);
        Route::post('chapter/create-file/{slug}', [ChapterController::class, 'createFile']);
        Route::put('chapter/{chapter_id}', [ChapterController::class, 'update']);
        Route::delete('chapter/{chapter_id}', [ChapterController::class, 'deleteChapter']);
        Route::post('page/create-file/{chapter_id}', [PageController::class, 'createFile']);
        Route::post('page/reorder', [PageController::class, 'reorder']);
        Route::get('page/{page_id}', [PageController::class, 'showDetail']);
        Route::patch('page/{page_id}', [PageController::class, 'updateDetail']);
        Route::delete('page/{page_id}', [PageController::class, 'deletePage']);
        Route::post('hotspotlinks/{page_id}', [PageController::class, 'createHotspotlink']);
        Route::delete('hotspotlinks/{hotspotlink_id}', [HotspotlinkController::class, 'deleteHotspotlink']);

    });

});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
