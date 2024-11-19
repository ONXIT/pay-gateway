<?php

namespace Qionar\PayGateway\DTOs;

class DebitTransferResultDTO
{

    public function __construct(
        private readonly string $transactionId,
        private readonly string $paymentId,
        private readonly string $paymentStatus,
        private readonly string $cardStatusCode,
    ) {}

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    public function getPaymentStatus(): string
    {
        return $this->paymentStatus;
    }

    public function getCardStatusCode(): string
    {
        return $this->cardStatusCode;
    }
}
