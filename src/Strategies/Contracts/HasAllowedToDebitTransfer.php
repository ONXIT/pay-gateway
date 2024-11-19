<?php

namespace Qionar\PayGateway\Strategies\Contracts;

use Qionar\PayGateway\DTOs\DebitTransferResultDTO;

interface HasAllowedToDebitTransfer
{
    public function processDebitTransfer($data): DebitTransferResultDTO;
}
