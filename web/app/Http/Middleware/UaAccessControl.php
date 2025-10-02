<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UaAccessControl
{
    public function handle(Request $request, Closure $next)
    {
        // skip admin area
        if ($request->is('admin*') || $request->session()->get('is_admin')) {
            return $next($request);
        }

        $dir = base_path('data') . DIRECTORY_SEPARATOR . 'access';
        $file = $dir . DIRECTORY_SEPARATOR . 'ua_control.json';
        $cfg = ['allow' => [], 'deny' => []];
        if (file_exists($file)) {
            $cfg = json_decode(file_get_contents($file), true) ?: $cfg;
        }

        $ua = $request->header('User-Agent', '');

        // Helper to check if UA string contains any of the patterns
        $matchAny = function(array $patterns) use ($ua) {
            if (empty($ua)) return false;
            foreach ($patterns as $p) {
                $p = trim($p);
                if ($p === '') continue;
                if (stripos($ua, $p) !== false) return true;
            }
            return false;
        };

        $whitelist = $cfg['allow'] ?? [];
        $blacklist = $cfg['deny'] ?? [];

        // If the whitelist is configured, only allow UAs in it
        if (!empty($whitelist)) {
            if ($matchAny($whitelist)) {
                return $next($request);
            }
            return response('Access denied (UA not in whitelist)', 403);
        }

        // If the blacklist is configured, deny any UA in it
        if (!empty($blacklist)) {
            if ($matchAny($blacklist)) {
                return response('Access denied (UA blacklisted)', 403);
            }
        }

        // If no whitelist is set, and the UA is not in the blacklist, allow access.
        return $next($request);
    }
}
