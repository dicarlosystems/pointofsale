<?php

Route::group(['middleware' => ['web', 'lookup:user', 'auth:user'], 'namespace' => 'Modules\PointOfSale\Http\Controllers'], function()
{
    Route::resource('pointofsale', 'PointOfSaleController');
    Route::post('pointofsale/bulk', 'PointOfSaleController@bulk');
    Route::get('api/pointofsale', 'PointOfSaleController@datatable');
});

Route::group(['middleware' => 'api', 'namespace' => 'Modules\PointOfSale\Http\ApiControllers', 'prefix' => 'api/v1'], function()
{
    Route::resource('pointofsale', 'PointOfSaleApiController');
});
