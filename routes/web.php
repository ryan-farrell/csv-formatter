<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeownerCSVController;

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

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/csv-upload', function () {
    return view('csv-uploader');
})->name('csv-upload');

Route::post('/upload-csv', [HomeownerCSVController::class, 'csvUpload'])->name('upload.csv');

Route::get('/report-upload', function () {
    return view('csv-uploader');
})->name('report-uploaded');
