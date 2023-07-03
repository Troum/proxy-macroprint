<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\SectionController;
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
Route::group(['prefix' => 'sections'], function () {
   Route::get('header', [SectionController::class, 'getHeader']);
   Route::get('footer', [SectionController::class, 'getFooter']);
});
Route::get('index', [PageController::class, 'indexPage']);
Route::get('requisites', [PageController::class, 'requisitesPage']);
Route::get('articles', [PageController::class, 'companyArticles']);
Route::get('requirements', [PageController::class, 'requirementsPage']);
Route::get('products/{slug}', [PageController::class, 'productPage']);
