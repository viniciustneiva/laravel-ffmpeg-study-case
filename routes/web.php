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

Route::get('/', [VideoController::class, 'index' ]);

Route::post('/upload', [VideoController::class, 'uploadVideo' ]);

Route::get('/remove/{id}', [VideoController::class, 'delete' ]);

Route::get('/test/{id}', [VideoController::class, 'test' ]);


Route::post('/convert', [VideoController::class, 'convert']);

