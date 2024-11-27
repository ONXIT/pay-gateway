<?php

namespace Qionar\PayGateway\DTOs;

use Qionar\PayGateway\DTOs\Contracts\RequiredDeactivateProfileData;
use Qionar\PayGateway\DTOs\Contracts\RequiredPaymentData;

class ImojeCancelSubscriptionDataDTO implements RequiredDeactivateProfileData
{

    public function __construct(

        protected readonly string $paymentProfileId,
    ) {
    }

    public function getPaymentProfileId(): string
    {
        return $this->paymentProfileId;
    }
}
