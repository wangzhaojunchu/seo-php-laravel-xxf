<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AccessLog
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            $isAdmin = $request->session()->get('is_admin', false);
            if (!$isAdmin) {
                $logDir = storage_path('logs');
                if (!is_dir($logDir)) { @mkdir($logDir, 0755, true); }
                $date = date('Y-m-d');
                $file = $logDir . DIRECTORY_SEPARATOR . "access-{$date}.log";
                $status = method_exists($response, 'getStatusCode') ? $response->getStatusCode() : (property_exists($response, 'status') ? $response->status : 0);
                $entry = sprintf("%s\t%s\t%s\t%s\t%s\n", date('Y-m-d H:i:s'), $request->ip(), $request->method(), $status, $request->fullUrl());
                file_put_contents($file, $entry, FILE_APPEND | LOCK_EX);
            }
        } catch (\Throwable $e) {
            // ignore logging errors
        }

        return $response;
    }
}
