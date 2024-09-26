<?php

use Qionar\PayGateway\Contracts\PaymentProvider;

function paymentProcessor(PaymentProvider|string $provider)
{
    if ($provider instanceof PaymentProvider) {
        return \Qionar\PayGateway\Facades\PaymentProcessor::withProvider($provider);
    }

    return \Qionar\PayGateway\Facades\PaymentProcessor::provider($provider);
}

