<?php

namespace Qionar\PayGateway\Models;

use Illuminate\Database\Eloquent\Model;
use Qionar\PayGateway\Enums\PaymentProvider;
use Qionar\PayGateway\Enums\TransactionStatus;
use Qionar\PayGateway\Models\Contracts\HasFindMethod;
use Qionar\PayGateway\Models\Contracts\HasStatusMapper;

class Transaction extends Model implements HasFindMethod, HasStatusMapper
{
    protected $table = 'transactions';

    protected $fillable = [
        'provider_id',
        'provider',
        'status',
        'amount',
        'provider_data',
        'custom_properties',
        'uuid'
    ];

    protected $casts = [
        'provider' => PaymentProvider::class,
        'status' => TransactionStatus::class,
        'provider_data' => 'json',
        'custom_properties' => 'json'
    ];

    static public function findByInternalOrderId(string $uuid): self
    {
        return self::where('uuid', $uuid)->first();
    }

    public static function mapStatus(mixed $status): TransactionStatus
    {
        return match ($status) {
            'rejected', 'error' => TransactionStatus::FAILED,
            'cancelled' => TransactionStatus::CANCELLED,
            'settled' => TransactionStatus::SUCCESS,
            default => TransactionStatus::PENDING
        };
    }

}
