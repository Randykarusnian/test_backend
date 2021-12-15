<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'soal1\LoginController@login');

Route::middleware(['jwt.verify'])->group(function() {
    Route::post('report-merchant', 'soal1\ReportController@reportMerchant');
    Route::post('report-outlet', 'soal1\ReportController@reportOutlet');
});

Route::post('deret', 'soal3\DeretController@deret');
Route::post('sort', 'soal4\SortController@sort');