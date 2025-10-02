<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IpAccessControl
{
    public function handle(Request $request, Closure $next)
    {
        // skip admin area
        if ($request->is('admin*') || $request->session()->get('is_admin')) {
            return $next($request);
        }

        $dir = base_path('data') . DIRECTORY_SEPARATOR . 'access';
        $file = $dir . DIRECTORY_SEPARATOR . 'ip_control.json';
        $cfg = ['default' => 'allow', 'allow' => [], 'deny' => []];
        if (file_exists($file)) {
            $cfg = json_decode(file_get_contents($file), true) ?: $cfg;
        }

        $ip = $request->ip();
        if (!$ip) return $next($request);

        // helper to test an ip against patterns
        $matchAny = function(array $patterns) use ($ip) {
            foreach ($patterns as $p) {
                $p = trim($p);
                if ($p === '') continue;
                if ($this->ipMatches($ip, $p)) return true;
            }
            return false;
        };

        $whitelist = $cfg['allow'] ?? [];
        $blacklist = $cfg['deny'] ?? [];

        // If the whitelist is configured, only allow IPs in it
        if (!empty($whitelist)) {
            if ($matchAny($whitelist)) {
                return $next($request);
            }
            return response('Access denied (not in whitelist)', 403);
        }

        // If the blacklist is configured, deny any IP in it
        if (!empty($blacklist)) {
            if ($matchAny($blacklist)) {
                return response('Access denied (IP blacklisted)', 403);
            }
        }

        // If no whitelist is set, and the IP is not in the blacklist (or no blacklist is set), allow access.
        return $next($request);
    }

    protected function ipMatches(string $ip, string $pattern): bool
    {
        // CIDR
        if (strpos($pattern, '/') !== false) {
            list($subnet, $mask) = explode('/', $pattern, 2);
            if (filter_var($subnet, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && is_numeric($mask)) {
                $ip_long = ip2long($ip);
                $sub_long = ip2long($subnet);
                $mask = (int)$mask;
                if ($mask < 0 || $mask > 32) return false;
                $mask_long = ~((1 << (32 - $mask)) - 1);
                return (($ip_long & $mask_long) === ($sub_long & $mask_long));
            }
        }

        // range start-end
        if (strpos($pattern, '-') !== false) {
            list($start, $end) = explode('-', $pattern, 2);
            $start = trim($start); $end = trim($end);
            if (filter_var($start, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && filter_var($end, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $ip_long = ip2long($ip);
                $s = ip2long($start);
                $e = ip2long($end);
                if ($s <= $e) {
                    return ($ip_long >= $s && $ip_long <= $e);
                } else {
                    return ($ip_long >= $e && $ip_long <= $s);
                }
            }
        }

        // single IP
        if (filter_var($pattern, FILTER_VALIDATE_IP)) {
            return $ip === $pattern;
        }

        return false;
    }
}
