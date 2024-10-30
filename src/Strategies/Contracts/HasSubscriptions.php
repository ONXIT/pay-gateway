<?php

namespace Qionar\PayGateway\Strategies\Contracts;

interface HasSubscriptions
{
    public function charge(
        string $internalOrderId,
        string $customerPaymentToken,
        int $amount,
        string $currency,
        string $title = ''
    );
}
