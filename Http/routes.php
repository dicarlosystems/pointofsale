<?php

Route::group(['middleware' => ['web', 'lookup:user', 'auth:user'], 'namespace' => 'Modules\PointOfSale\Http\ApiControllers', 'prefix' => 'api/v1'], function()
{
    Route::get('pointofsale/productsByBarcode', 'PointOfSaleApiController@productsByBarcode')->name('productsByBarcode');
});
