<?php

namespace Qionar\PayGateway\Contracts;

interface WithPaymentMethods
{

    public function paymentMethods(): array;

}
