<?php

namespace Qionar\PayGateway\Strategies\Contracts;

interface HasFastPayments
{

    public function processFastPayment(RequiredPaymentData $data): array;

}
