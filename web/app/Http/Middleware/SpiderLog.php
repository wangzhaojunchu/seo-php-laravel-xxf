<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SpiderLog
{
    protected $bots = [
        'Googlebot', 'Bingbot', 'Slurp', 'DuckDuckBot', 'Baiduspider', 'Yandex', 'Sogou', 'Exabot', 'facebot', 'twitterbot'
    ];

    public function handle(Request $request, Closure $next)
    {
        try {
            $ua = $request->header('User-Agent', '');
            foreach ($this->bots as $bot) {
                if ($ua !== '' && stripos($ua, $bot) !== false) {
                    $logDir = storage_path('logs');
                    if (!is_dir($logDir)) { @mkdir($logDir, 0755, true); }
                    $date = date('Y-m-d');
                    $file = $logDir . DIRECTORY_SEPARATOR . "spider-{$date}.log";
                    $entry = sprintf("%s\t%s\t%s\t%s\t%s\n", date('Y-m-d H:i:s'), $request->ip(), $bot, $request->method(), $request->fullUrl());
                    file_put_contents($file, $entry, FILE_APPEND | LOCK_EX);
                    break;
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return $next($request);
    }
}
