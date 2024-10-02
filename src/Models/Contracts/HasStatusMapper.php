<?php

namespace Qionar\PayGateway\Models\Contracts;

interface HasStatusMapper
{

    public static function mapStatus(mixed $status): mixed;

}