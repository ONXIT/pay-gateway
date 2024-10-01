<?php

namespace Qionar\PayGateway\Strategies;

use Illuminate\Support\Facades\Http;
use Qionar\PayGateway\Strategies\Contracts\HasFastPayments;
use Qionar\PayGateway\Strategies\Contracts\PaymentProvider;
use Qionar\PayGateway\DTOs\Contracts\RequiredPaymentData;
use Qionar\PayGateway\DTOs\ImojeFastPaymentDataDTO;
use Qionar\PayGateway\DTOs\FastPaymentResultDTO;

class ImojeProvider implements PaymentProvider, HasFastPayments
{

    public const FAST_PAYMENT_URI = '/payment';

    private string $baseUrl;
    private string $serviceId;
    private string $merchantId;
    private string $merchantKey;
    private string $authToken;
    private string $defaultLanguage;
    private string $defaultCurrency;

    private string $webhookUri;

    private bool $enforceWebhook;

    private string $successReturnUri;
    private string $failureReturnUri;

    public function __construct()
    {
        $this->baseUrl = config('pay-gateway.drivers.imoje.base_url');
        $this->serviceId = config('pay-gateway.drivers.imoje.service_id');
        $this->merchantId = config('pay-gateway.drivers.imoje.merchant_id');
        $this->merchantKey = config('pay-gateway.drivers.imoje.merchant_key');
        $this->authToken = config('pay-gateway.drivers.imoje.auth_token');
        $this->defaultLanguage = config('pay-gateway.drivers.imoje.default_language');
        $this->defaultCurrency = config('pay-gateway.drivers.imoje.default_currency');

        $this->webhookUri = config('pay-gateway.drivers.imoje.webhook.uri');
        $this->enforceWebhook = config('pay-gateway.drivers.imoje.webhook.enabled');
        $this->successReturnUri = config('pay-gateway.success_return_uri');
        $this->failureReturnUri = config('pay-gateway.failure_return_uri');
    }


    public function processFastPayment(RequiredPaymentData $data): FastPaymentResultDTO
    {
        if ($data instanceof ImojeFastPaymentDataDTO) {
            $payload = $data->toPayload([
                'language' => $this->defaultLanguage
            ]);
        } else {
            $payload = [
                'customer' => [
                    'firstName' => $data->getFirstName(),
                    'lastName' => $data->getLastName(),
                    'email' => $data->getEmail(),
                    'locale' => $this->defaultLanguage,
                ],
                'amount' => $data->getAmount(),
                'orderId' => $data->getInternalOrderId(),
            ];
        }

        if($this->enforceWebhook) {
            $payload['notificationUrl'] = $payload['notificationUrl'] ?? url($this->webhookUri);
        }

        if($this->successReturnUri) {
            $payload['successReturnUrl'] = $payload['successReturnUrl'] ?? url($this->successReturnUri);
        }

        if($this->failureReturnUri) {
            $payload['failureReturnUrl'] = $payload['failureReturnUrl'] ?? url($this->failureReturnUri);
        }

        $response = $this
            ->buildDefaultRequest()
            ->post(self::FAST_PAYMENT_URI, [
                'serviceId' => $this->serviceId,
                'currency' => $this->defaultCurrency,
                ...$payload,
            ]);

        return new FastPaymentResultDTO(
            url: $response->json('payment.url'),
            providerData: $response->json(),
            internalOrderId: $response->json('payment.orderId'),
        );
    }

    public function setLanguage(string $language): ImojeProvider
    {
        $this->defaultLanguage = $language;

        return $this;
    }

    public function setCurrency(string $defaultCurrency): ImojeProvider
    {
        $this->defaultCurrency = $defaultCurrency;

        return $this;
    }

    protected function buildDefaultRequest()
    {
        return Http::asJson()
            ->withToken($this->authToken, 'Bearer')
            ->baseUrl($this->baseUrl . '/' . $this->merchantId);
    }

}
