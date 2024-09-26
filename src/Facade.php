<?php

namespace Qionar\PayGateway;

use Qionar\PayGateway\Contracts\HasFastPayments;
use Qionar\PayGateway\Contracts\PaymentProvider;
use Qionar\PayGateway\Contracts\RequiredPaymentData;

class Facade implements PaymentProvider, HasFastPayments
{

    public function __construct(
        private ?PaymentProvider $paymentProvider = null
    )
    {}

    public function processFastPayment(RequiredPaymentData $data): array
    {
        if(!($this->paymentProvider instanceof HasFastPayments)) {
            throw new \Exception('Payment provider is not instance of HasFastPayments');
        }

        return $this->paymentProvider->processFastPayment($data);
    }

    public function getPaymentProvider(): PaymentProvider
    {
        return $this->paymentProvider;
    }

    public function withProvider(PaymentProvider $paymentProvider): Facade
    {
        $this->paymentProvider = $paymentProvider;

        return $this;
    }

    public static function provider(?string $key, array $options = [])
    {
        $key = $key ?? config('pay-gateway.default_driver');

        $providerReference = config("pay-gateway.drivers.{$key}.provider");

        return new Facade(new $providerReference($options));
    }

}
