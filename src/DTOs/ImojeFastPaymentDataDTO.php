<?php

namespace Qionar\PayGateway\DTOs;

use Qionar\PayGateway\DTOs\Contracts\RequiredPaymentData;

class ImojeFastPaymentDataDTO extends PaymentDataDTO
{

    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        int    $amount,
        string|int $internalOrderId,

        protected readonly ?string $currency = null,
        protected readonly string $title = '',
        protected readonly array $visibleMethods = [],
        protected readonly ?string $preselectedMethodCode = null,
        protected readonly ?string $returnUrl = null,
        protected readonly ?string $successReturnUrl = null,
        protected readonly ?string $failureReturnUrl = null,
        protected readonly ?int $validTo = null,
        // Understand it as a webhook url where you'll receive transaction status.
        protected readonly ?string $notificationUrl = null,

        // Customer addition model
        protected readonly ?string $phone = null,
        protected readonly ?string $language = null,
    ) {
        parent::__construct($firstName, $lastName, $email, $amount, $internalOrderId);
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getVisibleMethods(): array
    {
        return $this->visibleMethods;
    }

    public function getPreselectedMethodCode(): ?string
    {
        return $this->preselectedMethodCode;
    }

    public function getReturnUrl(): ?string
    {
        return $this->returnUrl;
    }

    public function getSuccessReturnUrl(): ?string
    {
        return $this->successReturnUrl;
    }

    public function getFailureReturnUrl(): ?string
    {
        return $this->failureReturnUrl;
    }

    public function getValidTo(): ?int
    {
        return $this->validTo;
    }

    public function getNotificationUrl(): ?string
    {
        return $this->notificationUrl;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function toPayload(array $defaults = [])
    {
        return \Arr::whereNotNull([
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'orderId' => $this->getInternalOrderId(),
            'customer' => \Arr::whereNotNull([
                'firstName' => $this->getFirstName(),
                'lastName' => $this->getLastName(),
                'email' => $this->getEmail(),
                'phone' => $this->getPhone(),
                'locale' => $this->getLanguage() ?? $defaults['language'],
            ]),
            'title' => $this->getTitle(),
            'visibleMethod' => $this->getVisibleMethods(),
            'preselectedMethodCode' => $this->getPreselectedMethodCode(),
            'returnUrl' => $this->getReturnUrl(),
            'successReturnUrl' => $this->getSuccessReturnUrl(),
            'failureReturnUrl' => $this->getFailureReturnUrl(),
            'validTo' => $this->getValidTo(),
            'notificationUrl' => $this->getNotificationUrl(),
        ]);
    }
}
