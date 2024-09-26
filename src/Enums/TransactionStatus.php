<?php

namespace Qionar\PayGateway\Enums;

use Qionar\Pay\Gateway\Enums\Traits\ToArray;

enum TransactionStatus: string
{
    use ToArray;

    case PENDING = 'PENDING';

    case CANCELLED = 'CANCELLED';

    case SUCCESS = 'SUCCESS';

    case FAILED = 'FAILED';
}
