<?php

namespace Qionar\PayGateway\DTOs;

use Qionar\PayGateway\Contracts\RequiredPaymentData;

class PaymentDataDTO implements RequiredPaymentData
{

    public function __construct(
        private readonly string $firstName,
        private readonly string $lastName,
        private readonly string $email,
        private readonly int    $amount,
        private readonly string|int $internalOrderId,
    )
    {
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getInternalOrderId(): string|int
    {
        return $this->internalOrderId;
    }
}
