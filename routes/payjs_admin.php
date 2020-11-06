<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'        => config('admin.route.prefix').'/payjs',
    'namespace' => 'Dedemao\\Payjs\\Http\\Controllers\\Admin',
    'middleware' => config('admin.route.middleware'),
], function () {
    Route::resources([
        'index' => PayjsController::class,
    ]);
    Route::resources([
        'order' => OrderController::class,
    ]);
});
