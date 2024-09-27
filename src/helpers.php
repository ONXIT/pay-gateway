<?php

use Qionar\PayGateway\Contracts\PaymentProvider;

function paymentProcessor(PaymentProvider|string|null $provider = null): PaymentProvider
{
    if ($provider instanceof PaymentProvider) {
        return \Qionar\PayGateway\Facades\PaymentProcessor::withProvider($provider);
    }

    return \Qionar\PayGateway\Facades\PaymentProcessor::provider($provider);
}

