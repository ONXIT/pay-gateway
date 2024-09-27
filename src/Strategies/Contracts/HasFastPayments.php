<?php

namespace Qionar\PayGateway\Strategies\Contracts;

use Qionar\PayGateway\DTOs\Contracts\RequiredPaymentData;
use Qionar\PayGateway\DTOs\FastPaymentResultDTO;

interface HasFastPayments
{

    public function processFastPayment(RequiredPaymentData $data): FastPaymentResultDTO;

}
