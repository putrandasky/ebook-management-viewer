<?php

use App\Http\Controllers;
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
Route::get('/readme', function () {
    return view('readme');
});

Route::get('/shared/{imageName}', [Controllers\Reader\EbookReaderController::class, 'showCropImage']);
Route::get('/pdf/{pdfname}', [Controllers\Reader\EbookReaderController::class, 'showPdf']);
Route::post('/generatepdf/entire', [Controllers\Reader\EbookReaderController::class, 'generateEntirePdf']);
Route::post('/generatepdf/range', [Controllers\Reader\EbookReaderController::class, 'generateRangePdf']);

Route::group(['prefix' => 'admin'], function () {

    Route::get('/{vue_capture?}', function () {
        return View::make('apps.admin');
    })->where('vue_capture', '[\/\w\.-]*');
});
Route::group(['prefix' => 'reader'], function () {

    Route::get('/{vue_capture?}', function () {
        return View::make('apps.reader');
    })->where('vue_capture', '[\/\w\.-]*');
});

Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
