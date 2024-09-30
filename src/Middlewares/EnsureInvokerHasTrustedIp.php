<?php

namespace Qionar\PayGateway\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInvokerHasTrustedIp
{
    public function handle(Request $request, Closure $next, string $ips): Response
    {

        $this->trustedIpRanges = explode('|', $ips);

        $clientIp = $request->ip();

        if (! $this->isIpAllowed($clientIp)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }

    protected function isIpAllowed(string $ip): bool
    {
        foreach ($this->trustedIpRanges as $range) {
            if ($this->ipInRange($ip, $range)) {
                return true;
            }
        }

        return false;
    }

    protected function ipInRange(string $ip, string $range): bool
    {
        [$subnet, $bits] = explode('/', $range);

        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        $subnet &= $mask;

        return ($ip & $mask) === $subnet;
    }
}
