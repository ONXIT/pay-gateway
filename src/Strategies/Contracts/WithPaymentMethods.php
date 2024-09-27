<?php

namespace Qionar\PayGateway\Strategies\Contracts;

interface WithPaymentMethods
{

    public function paymentMethods(): array;

}
