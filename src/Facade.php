<?php

namespace Qionar\PayGateway;

use Qionar\PayGateway\DTOs\Contracts\RequiredDeactivateProfileData;
use Qionar\PayGateway\DTOs\Contracts\RequiredDebitTransferData;
use Qionar\PayGateway\DTOs\DeactivateProfileResultDTO;
use Qionar\PayGateway\DTOs\DebitTransferResultDTO;
use Qionar\PayGateway\DTOs\FastPaymentResultDTO;
use Qionar\PayGateway\Strategies\Contracts\HasAllowedToDeactivateCustomerProfile;
use Qionar\PayGateway\Strategies\Contracts\HasAllowedToDebitTransfer;
use Qionar\PayGateway\Strategies\Contracts\HasFastPayments;
use Qionar\PayGateway\Strategies\Contracts\PaymentProvider;
use Qionar\PayGateway\DTOs\Contracts\RequiredPaymentData;

class Facade implements PaymentProvider, HasFastPayments, HasAllowedToDebitTransfer, HasAllowedToDeactivateCustomerProfile
{

    public function __construct(
        private ?PaymentProvider $paymentProvider = null
    )
    {}

    public function processFastPayment(RequiredPaymentData $data): FastPaymentResultDTO
    {
        if(!($this->paymentProvider instanceof HasFastPayments)) {
            throw new \Exception('Payment provider is not instance of HasFastPayments');
        }

        return $this->paymentProvider->processFastPayment($data);
    }

    public function processDebitTransfer(RequiredDebitTransferData $data) : DebitTransferResultDTO
    {
        if(!($this->paymentProvider instanceof HasAllowedToDebitTransfer)) {
            throw new \Exception('Payment provider is not instance of HasAllowedToDebitTransfer');
        }

        return $this->paymentProvider->processDebitTransfer($data);
    }

    public function processDeactivateCustomerProfile(RequiredDeactivateProfileData $data) : DeactivateProfileResultDTO
    {
        if(!($this->paymentProvider instanceof HasAllowedToDeactivateCustomerProfile)) {
            throw new \Exception('Payment provider is not instance of HasAllowedToDeactivateCustomerProfile');
        }

        return $this->paymentProvider->processDeactivateCustomerProfile($data);
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
