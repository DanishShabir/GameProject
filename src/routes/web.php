<?php

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

use Illuminate\Support\Facades\Route;

Route::get('/docs', function () {
    return view('docs.doc');
});

Route::prefix('payouts')->group(function(){
    Route::post('/', 'Payouts\PayoutTestController@store');
    Route::get('/', function () {
        return view('payouts.payout');
    });
});


/*
 * WEBHOOKS
 */
Route::prefix('webhooks')->group(function(){
    Route::post('zing', 'Webhooks\ZingWebhook@store');
    Route::post('paytriot', 'Webhooks\PaytriotWebhook@store')->name('paytriot.webhook');
    Route::get('zing', function() {
        return "success";
    });
});

Route::get('/paytriot', 'PaytriotPaymentController@test');

// Health Check for Zabbix
Route::get('/health', 'HealthController@healthCheck');
Route::get('deposits/download', 'Payments\Red6six\DepositAdminController@downloadCsv');
