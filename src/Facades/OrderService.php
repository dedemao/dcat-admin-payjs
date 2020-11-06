<?php
namespace Dedemao\Payjs\Facades;

use Illuminate\Support\Facades\Facade;

class OrderService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Dedemao\Payjs\Services\OrderService::class;
    }
}
