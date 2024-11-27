<?php

namespace Qionar\PayGateway\DTOs;

class DeactivateProfileResultDTO
{

    public function __construct(
        private readonly string $paymentId,
        private readonly bool $isActive,
    ) {}


    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function getPaymentId(): string
    {
        return $this->paymentId;
    }
}
