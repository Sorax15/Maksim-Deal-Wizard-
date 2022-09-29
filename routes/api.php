<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\DealController;

use App\Http\Middleware\SalesPersonAuthMiddleware;
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


$router->group([SalesPersonAuthMiddleware::class], function($router) {
    $router->get('/getSalesPeopleWidget', 'ApiController@getSalespeopleWidget');
});

//Tracking from client
Route::post('/tracking-client', 'DealController@trackingClient');

//Offer logix zip lookup
Route::post('/offerlogix-zip-lookup', 'DealController@offerlogixZipLookup');
Route::post('/offerlogix-calculate', 'DealController@offerlogixCalculate');
Route::post('/offerlogix-save', 'DealController@offerlogixSave');

