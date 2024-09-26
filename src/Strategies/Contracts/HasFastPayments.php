<?php

namespace Qionar\PayGateway\Contracts;

interface HasFastPayments
{

    public function processFastPayment(RequiredPaymentData $data): array;

}
