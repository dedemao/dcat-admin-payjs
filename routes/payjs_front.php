<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'        => 'pay',
    'namespace'     => 'Dedemao\\Payjs\\Http\\Controllers\\Front',
], function () {
    Route::get('index', 'PayController@index');
    Route::get('jsapi', 'PayController@jsapi');
    Route::get('getQrcode', 'PayController@getQrcode');
    Route::get('checkOrder', 'PayController@checkOrder');
    Route::get('test', 'PayController@test');
    Route::post('notify', 'PayController@notify');
    Route::get('response', 'PayController@response');
});

