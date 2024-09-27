<?php

namespace Qionar\PayGateway\Models;

use Illuminate\Database\Eloquent\Model;
use Qionar\PayGateway\Enums\PaymentProvider;
use Qionar\PayGateway\Enums\TransactionStatus;

class Transaction extends Model
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

    static public function findByUuid(string $uuid)
    {
        return self::where('uuid', $uuid)->first();
    }
}
