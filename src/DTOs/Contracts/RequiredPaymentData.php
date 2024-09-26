<?php

namespace Qionar\PayGateway\Contracts;

interface RequiredPaymentData
{

    public function getFirstName(): string;

    public function getInternalOrderId(): string | int;

    public function getLastName(): string;

    public function getEmail(): string;

    public function getAmount(): int;

}
