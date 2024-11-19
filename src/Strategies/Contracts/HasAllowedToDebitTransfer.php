<?php

namespace Qionar\PayGateway\Strategies\Contracts;

use Qionar\PayGateway\DTOs\Contracts\RequiredDebitTransferData;
use Qionar\PayGateway\DTOs\DebitTransferResultDTO;

interface HasAllowedToDebitTransfer
{
    public function processDebitTransfer(RequiredDebitTransferData $data): DebitTransferResultDTO;
}
