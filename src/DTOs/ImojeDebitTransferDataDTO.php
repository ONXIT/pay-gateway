<?php

namespace Qionar\PayGateway\DTOs;

use Qionar\PayGateway\DTOs\Contracts\RequiredDebitTransferData;
use Qionar\PayGateway\DTOs\Contracts\RequiredPaymentData;

class ImojeDebitTransferDataDTO implements RequiredDebitTransferData
{

    public function __construct(
        private readonly string $paymentProfileId,
        private readonly int $amount,
        private readonly string $orderId,
        private readonly string $title,

    ) {}

    public function getPaymentProfileId(): string
    {
        return $this->paymentProfileId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getOrderId(): string|int
    {
        return $this->orderId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
