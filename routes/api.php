<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => []], function () {
    Route::apiResource('document-type', 'DocumentTypeController');
    Route::apiResource('role', 'RoleController');
    Route::apiResource('client', 'ClientController');
    Route::apiResource('order', 'OrderController');
    Route::apiResource('order-details', 'OrderDetailController');
    Route::apiResource('product', 'ProductController');
    Route::apiResource('supplier', 'SupplierController');
    Route::apiResource('pre-sale-report', 'PreSaleReportController');
    Route::apiResource('pre-sale-report-detail', 'PreSaleReportDetailController');
    Route::apiResource('user', 'UserController');
    Route::post('save-row', 'PreSaleReportController@saveRow');
    Route::post('completed', 'PreSaleReportController@completedRow');
    Route::post('delivery', 'PreSaleReportController@delivery');
    Route::get('report', 'PreSaleReportController@report');
    Route::get('report-quality', 'OrderFeedbackController@reportQuality');
    Route::post('production', 'PreSaleReportController@production');
});

Route::get('send-email', 'EmailController@send');
Route::get('pdf/{id}', 'OrderController@pdf');

Route::post('get-order', 'OrderController@getOrder');
Route::apiResource('order-feedback', 'OrderFeedbackController');