<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DealController;

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

Route::get('/', function () {
    return 'Build-A-Brand Widgets v1.0';
});

// Main Nav Page
Route::get('/welcome', 'App\Http\Controllers\DealController@getWelcomePage');

// Sales Person Steps
Route::get('/start-sales-person', 'App\Http\Controllers\DealController@getStartSalesPersonFlow');
Route::get('/selected-sales-person', 'App\Http\Controllers\DealController@selectedSalesPerson');
Route::get('/skip-sales-person', 'App\Http\Controllers\DealController@skipSalesPersonStep');
Route::get('/salesperson-detail', 'App\Http\Controllers\DealController@getSalespersonDetail');

// Vehicle Selection Page
Route::get('/vehicle-select', 'App\Http\Controllers\DealController@getVehiclePage');
Route::get('/vehicle-detail', 'App\Http\Controllers\DealController@getVehicleDetailPage');
Route::post('/vehicle-detail', 'App\Http\Controllers\DealController@submitVehicle');
Route::get('/vehicle-detail2', 'App\Http\Controllers\DealController@getVehicleDetailPage2');

Route::post('/vehicle-filter', 'App\Http\Controllers\DealController@filterVehicle');
Route::post('/get-vehicles-html', 'App\Http\Controllers\DealController@getVehiclesHtml');

//VDP
Route::get('/remove-trade', 'App\Http\Controllers\DealController@removeTrade');



// Mobile Sales Person
Route::get('/mobile/sales-people', 'App\Http\Controllers\DealController@getMobileSalesPeople');
Route::get('/mobile/sales-person', 'App\Http\Controllers\DealController@getMobileSalesPerson');

// Contact Information Step
Route::get('/contact-information', 'App\Http\Controllers\DealController@getContactInformation');
Route::post('/contact-information', 'App\Http\Controllers\DealController@submitContactInformation');

// Schedule Appointment
Route::get('/schedule-appointment', 'App\Http\Controllers\DealController@getAppointment');
Route::post('/schedule-appointment', 'App\Http\Controllers\DealController@submitAppointment');

// Pre-Approved Step
Route::get('/pre-approved', 'App\Http\Controllers\DealController@getPreApprovedBasic');
Route::post('/pre-approved', 'App\Http\Controllers\DealController@submitPreApprovedBasic');
Route::get('/pre-approved/employee', 'App\Http\Controllers\DealController@getPreApprovedEmployee');
Route::post('/pre-approved/employee', 'App\Http\Controllers\DealController@submitPreApprovedEmployee');

// Value Trade Step
Route::get('/value-trade', 'App\Http\Controllers\DealController@getValueTrade');
Route::post('/value-trade', 'App\Http\Controllers\DealController@submitValueTrade');

// Payments Page
Route::get('/payments', 'App\Http\Controllers\DealController@getPaymentsPage');
Route::post('/payments', 'App\Http\Controllers\DealController@submitPaymentsPage');

// Summary
Route::get('/summary', 'App\Http\Controllers\DealController@getSummary');
Route::post('/send-summary', 'App\Http\Controllers\DealController@submitSummary');

Route::get('/test', 'App\Http\Controllers\DealController@test');

//Tracking from client
Route::post('/tracking-client', 'App\Http\Controllers\DealController@trackingClient');

Route::post('/salesperson-question', 'App\Http\Controllers\DealController@salespersonQuestion');

