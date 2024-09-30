<?php

namespace Qionar\PayGateway\Models\Contracts;

interface HasFindMethod
{
    static public function findByInternalOrderId(string $uuid): self;
}