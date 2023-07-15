<?php

use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::pattern('id', '\d+');

Route::controller(VideoController::class)->group(function() {
    Route::get('/', 'index');
    Route::post('/upload', 'uploadVideo');
    Route::get('/remove/{id}', 'delete');
    Route::get('/test/{id}','test');
    Route::post('/convert','convert');
    Route::get('/export/{id}', 'export');
});

