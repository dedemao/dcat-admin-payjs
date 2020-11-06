<?php
namespace Dedemao\Payjs\Facades;

use Illuminate\Support\Facades\Facade;

class PayjsService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Dedemao\Payjs\Services\PayjsService::class;
    }
}
