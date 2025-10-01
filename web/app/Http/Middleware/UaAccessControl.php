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
        $uaList = [];
        if (file_exists($file)) {
            $uaList = json_decode(file_get_contents($file), true) ?: [];
        }

        if (empty($uaList)) return $next($request);

        $ua = $request->header('User-Agent', '');
        foreach ($uaList as $bot) {
            if ($bot && stripos($ua, $bot) !== false) {
                // blocked or allowed? we'll treat UA list as allowed bots to track; if you want to block, invert logic
                // For now, deny bots that match list (treat as block list)
                return response('Access denied (UA block)', 403);
            }
        }
        return $next($request);
    }
}
