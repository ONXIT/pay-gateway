<?php

namespace Qionar\PayGateway\DTOs;

class FastPaymentResultDTO
{

    public function __construct(
        private readonly string $url,
        private readonly array $providerData,
        private readonly string $internalOrderId,
        private readonly string $customerPaymentToken
    ) {}

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getProviderData(): array
    {
        return $this->providerData;
    }

    public function getInternalOrderId(): string
    {
        return $this->internalOrderId;
    }

    public function getCustomerPaymentToken(): string
    {
        return $this->customerPaymentToken;
    }
}
