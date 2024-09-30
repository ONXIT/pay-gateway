<?php

namespace Qionar\PayGateway\Models;

use Illuminate\Database\Eloquent\Model;
use Qionar\PayGateway\Enums\PaymentProvider;
use Qionar\PayGateway\Enums\TransactionStatus;
use Qionar\PayGateway\Models\Contracts\HasFindMethod;

class Transaction extends Model implements HasFindMethod
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

}
