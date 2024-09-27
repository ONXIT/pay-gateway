<?php

namespace Qionar\PayGateway\Strategies\Contracts;

use Qionar\PayGateway\DTOs\Contracts\RequiredPaymentData;

interface HasFastPayments
{

    public function processFastPayment(RequiredPaymentData $data): array;

}
