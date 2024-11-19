<?php

namespace Qionar\PayGateway\Strategies\Contracts;

use Qionar\PayGateway\DTOs\Contracts\RequiredDeactivateProfileData;
use Qionar\PayGateway\DTOs\DeactivateProfileResultDTO;
use Qionar\PayGateway\DTOs\DebitTransferResultDTO;

interface HasAllowedToDeactivateCustomerProfile
{
    public function processDeactivateCustomerProfile(RequiredDeactivateProfileData $data): DeactivateProfileResultDTO;
}
