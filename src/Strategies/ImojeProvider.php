<?php

namespace Qionar\PayGateway\Strategies;

use Illuminate\Support\Facades\Http;
use Qionar\PayGateway\Strategies\Contracts\HasFastPayments;
use Qionar\PayGateway\Strategies\Contracts\PaymentProvider;
use Qionar\PayGateway\DTOs\Contracts\RequiredPaymentData;
use Qionar\PayGateway\DTOs\ImojeFastPaymentDataDTO;

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

    private string $webhookUrl;

    private bool $enforceWebhook;

    public function __construct()
    {
        $this->baseUrl = config('pay-gateway.imoje.baseUrl');
        $this->serviceId = config('pay-gateway.imoje.client_id');
        $this->merchantId = config('pay-gateway.imoje.merchant_id');
        $this->merchantKey = config('pay-gateway.imoje.merchant_key');
        $this->authToken = config('pay-gateway.imoje.auth_token');
        $this->defaultLanguage = config('pay-gateway.imoje.default_language');
        $this->defaultCurrency = config('pay-gateway.imoje.default_currency');

        $this->webhookUrl = config('pay-gateway.imoje.webhook.uri');
        $this->enforceWebhook = config('pay-gateway.imoje.webhook.enabled');
    }


    public function processFastPayment(RequiredPaymentData $data): array
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
                'orderID' => $data->getInternalOrderId(),
            ];
        }

        if($this->enforceWebhook) {
            $payload['notificationUrl'] = $this->webhookUrl;
        }

        $response = $this
            ->buildDefaultRequest()
            ->post(self::FAST_PAYMENT_URI, [
                'serviceId' => $this->serviceId,
                'currency' => $this->defaultCurrency,
                ...$payload,
            ]);

        return $response->json();
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
            ->baseUrl($this->baseUrl);
    }

}
