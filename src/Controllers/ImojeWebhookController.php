<?php

namespace Qionar\PayGateway\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Qionar\PayGateway\Controllers\Contracts\HandleWebhookCallbacks;
use Qionar\PayGateway\Enums\TransactionStatus;
use Qionar\PayGateway\Middlewares\EnsureInvokerHasTrustedIp;
use Qionar\PayGateway\Models\Contracts\HasFindMethod;
use Qionar\PayGateway\Models\Transaction;

class ImojeWebhookController extends Controller implements HandleWebhookCallbacks
{

    public function __construct()
    {
        $trustedIps = config('pay-gateway.drivers.imoje.webhook.trusted_ip_ranges');

        $this->middleware(
            EnsureInvokerHasTrustedIp::class .
            ':' .
            implode('|', $trustedIps)
        );
    }

    public function handle(Request $request): \Illuminate\Http\JsonResponse
    {
        Log::info('New transaction callback received.', [
            'ip' => $request->ip(),
            'payload' => $request->all()
        ]);

        $modelClass = config('pay-gateway.model');

        if(!in_array(HasFindMethod::class, class_implements($modelClass))) {
            throw new \Exception('Model class must implement HasFindMethod to use default webhook handler');
        }

        $transaction = $modelClass::findByInternalOrderId(
            $request->input('transaction.orderId')
        );

        if(!$transaction) {
            return response()->json([
               'message' => 'There is no transaction with specified id'
            ], 404);
        }

        $status = $request->input('transaction.status');

        $transaction->update([
            'status' => $this->mapStatus($status),
            'provider_data' => [
                ...$transaction->provider_data,
                'status_callback' => $request->all()
            ]
        ]);

        return response()->json([
            'message' => 'OK'
        ]);
    }

    protected function mapStatus(string $status): TransactionStatus
    {
        return match ($status) {
            'rejected', 'error' => TransactionStatus::FAILED,
            'cancelled' => TransactionStatus::CANCELLED,
            'settled' => TransactionStatus::SUCCESS,
            default => TransactionStatus::PENDING
        };
    }

}
