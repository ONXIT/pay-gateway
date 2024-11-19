<?php

namespace Qionar\PayGateway\DTOs\Contracts;

interface RequiredDebitTransferData
{

    public function getPaymentProfileId(): string;

    public function getAmount(): int;

    public function getOrderId(): string|int;

    public function getTitle(): string;

}
