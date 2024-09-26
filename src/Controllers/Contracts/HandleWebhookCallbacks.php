<?php

namespace Qionar\PayGateway\Controllers\Contracts;

use Illuminate\Http\Request;

interface HandleWebhookCallbacks
{
    public function handle(Request $request): mixed;
}
