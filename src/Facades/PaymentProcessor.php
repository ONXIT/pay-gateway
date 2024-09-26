<?php

namespace Qionar\PayGateway\Facades;

use Illuminate\Support\Facades\Facade;

use Qionar\PayGateway\Facade as PayGateway;

class PaymentProcessor extends Facade
{

    protected static function getFacadeAccessor()
    {
        return PayGateway::class;
    }

}
