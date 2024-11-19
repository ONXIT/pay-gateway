<?php

namespace Qionar\PayGateway\Strategies;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Qionar\PayGateway\DTOs\Contracts\RequiredDeactivateProfileData;
use Qionar\PayGateway\DTOs\Contracts\RequiredDebitTransferData;
use Qionar\PayGateway\DTOs\DeactivateProfileResultDTO;
use Qionar\PayGateway\DTOs\DebitTransferResultDTO;
use Qionar\PayGateway\Exceptions\BadProviderResponse;
use Qionar\PayGateway\Strategies\Contracts\HasAllowedToDeactivateCustomerProfile;
use Qionar\PayGateway\Strategies\Contracts\HasAllowedToDebitTransfer;
use Qionar\PayGateway\Strategies\Contracts\HasFastPayments;
use Qionar\PayGateway\Strategies\Contracts\PaymentProvider;
use Qionar\PayGateway\DTOs\Contracts\RequiredPaymentData;
use Qionar\PayGateway\DTOs\ImojeFastPaymentDataDTO;
use Qionar\PayGateway\DTOs\FastPaymentResultDTO;
use Qionar\PayGateway\Strategies\Contracts\HasSubscriptions;

class ImojeProvider implements PaymentProvider, HasFastPayments, HasSubscriptions, HasAllowedToDeactivateCustomerProfile, HasAllowedToDebitTransfer
{

    public const FAST_PAYMENT_URI = '/payment';
    public const DEBT_TRANSFER_URI = '/transaction/profile';
    public const DEACTIVATE_CUSTOMER_PROFILE_URI = '/profile/deactivate';

    private string $baseUrl;
    private string $serviceId;
    private string $merchantId;
    private string $merchantKey;
    private string $authToken;
    private string $defaultLanguage;
    private string $defaultCurrency;

    private string $webhookUri;

    private bool $enforceWebhook;

    private ?string $successReturnUri;
    private ?string $failureReturnUri;

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
        if (!$data instanceof ImojeFastPaymentDataDTO) {
            throw new InvalidArgumentException('Argument should be instance of `ImojeFastPaymentDataDTO`');
        }

        $response = $this->processPayment($data);

        return new FastPaymentResultDTO(
            url: $response->json('payment.url'),
            providerData: $response->json(),
            internalOrderId: $response->json('payment.orderId'),
            customerPaymentToken: $response->json('paymentProfile.id')
        );
    }

    public function processDebitTransfer(RequiredDebitTransferData $data): DebitTransferResultDTO
    {
        $response = $this
            ->buildDefaultRequest()
            ->post(self::DEBT_TRANSFER_URI, [
                'serviceId' => $this->serviceId,
                'paymentProfileId' => $data->getPaymentProfileId(),
                'amount' => $data->getAmount(),
                'currency' => $this->defaultCurrency,
                'orderId' => $data->getOrderId(),
                'title' => $data->getTitle(),
                'notificationUrl' => $this->webhookUri,
            ]);

        return new DebitTransferResultDTO(
            transactionId: $response->json('transaction.id'),
            paymentId: $response->json('transaction.payment.id'),
            paymentStatus: $response->json('transaction.payment.status'),
            cardStatusCode: $response->json('transaction.statusCode'),
        );
    }

    public function processDeactivateCustomerProfile(RequiredDeactivateProfileData $data): DeactivateProfileResultDTO
    {
        //$data->getPaymentProfileId
        $response = $this
            ->buildDefaultRequest()
            ->post(self::DEACTIVATE_CUSTOMER_PROFILE_URI, [
                'paymentProfileId' => $data->getPaymentProfileId(),
            ]);

        return new DeactivateProfileResultDTO(
            paymentId: $response->json('paymentProfile.id'),
            isActive: $response->json('paymentProfile.isActive'),
        );

    }


    public function charge(
        string $internalOrderId,
        string $customerPaymentToken,
        int $amount,
        string $currency,
        string $title = ''
    ) {
        $response = $this
            ->buildDefaultRequest()
            ->post(self::FAST_PAYMENT_URI, [
                'serviceId' => $this->serviceId,
                'paymentProfileId' => $customerPaymentToken,
                'amount' => $amount,
                'orderId' => $internalOrderId,
                'title' => $title
            ]);

        return new FastPaymentResultDTO(
            url: $response->json('payment.url'),
            providerData: $response->json(),
            internalOrderId: $response->json('payment.orderId'),
            customerPaymentToken: $response->json('paymentProfile.id')
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

    protected function processPayment(ImojeFastPaymentDataDTO $data)
    {
        $payload = $data->toPayload([
            'language' => $this->defaultLanguage
        ]);

        if ($this->enforceWebhook) {
            $payload['notificationUrl'] = $payload['notificationUrl'] ?? url($this->webhookUri);
        }

        if ($this->successReturnUri) {
            $payload['successReturnUrl'] = $payload['successReturnUrl'] ?? url($this->successReturnUri);
        }

        if ($this->failureReturnUri) {
            $payload['failureReturnUrl'] = $payload['failureReturnUrl'] ?? url($this->failureReturnUri);
        }

        $response = $this
            ->buildDefaultRequest()
            ->post(self::FAST_PAYMENT_URI, [
                'serviceId' => $this->serviceId,
                'currency' => $this->defaultCurrency,
                ...$payload,
            ]);

        if (!$response->successful()) {
            throw new BadProviderResponse($response);
        }

        return $response;
    }

    protected function buildDefaultRequest()
    {
        return Http::asJson()
            ->withToken($this->authToken, 'Bearer')
            ->baseUrl($this->baseUrl . '/' . $this->merchantId);
    }
}
