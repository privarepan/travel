<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class HmPay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'hmpay';
    }
}
