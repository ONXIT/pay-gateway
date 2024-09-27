<?php

namespace Qionar\PayGateway\Enums;

use Qionar\PayGateway\Enums\Traits\ToArray;

enum PaymentProvider: string
{

    use ToArray;

    case IMOJE = 'IMOJE';
}
