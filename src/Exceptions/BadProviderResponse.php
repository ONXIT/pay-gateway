<?php

namespace Qionar\PayGateway\Exceptions;

use Throwable;

class BadProviderResponse extends \Exception
{

    public function __construct(public mixed $providerResponse, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}