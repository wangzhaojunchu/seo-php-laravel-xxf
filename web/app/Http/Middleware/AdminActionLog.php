<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminActionLog
{
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);
        $response = $next($request);

        try {
            // Only record admin area actions when session indicates admin
            if ($request->is('admin*') && $request->session()->get('is_admin')) {
                $dir = base_path('data') . DIRECTORY_SEPARATOR . 'action';
                if (!is_dir($dir)) {
                    @mkdir($dir, 0755, true);
                }

                $date = date('Y-m-d');
                $file = $dir . DIRECTORY_SEPARATOR . "action-{$date}.log";

                $time = date('Y-m-d H:i:s');
                $ip = $request->ip() ?: '-';
                $method = $request->method();
                $uri = $request->getRequestUri();
                $status = method_exists($response, 'getStatusCode') ? $response->getStatusCode() : '-';
                $duration = round((microtime(true) - $start) * 1000, 2) . 'ms';
                $user = $request->session()->get('admin_user') ?? $request->session()->get('user') ?? 'admin';

                $input = $request->except(['password', 'password_confirmation', '_token']);

                // Build a concise human-friendly action description
                $action = '';
                try {
                    // IP rules
                    if (stripos($uri, '/admin/access/ip') !== false && in_array($method, ['POST', 'PUT'])) {
                        $allow = isset($input['allow_ips']) ? trim($input['allow_ips']) : '';
                        $deny = isset($input['deny_ips']) ? trim($input['deny_ips']) : '';
                        $ac = $allow === '' ? 0 : count(array_filter(array_map('trim', preg_split('/\r?\n/', $allow))));
                        $dc = $deny === '' ? 0 : count(array_filter(array_map('trim', preg_split('/\r?\n/', $deny))));
                        $action = "Saved IP rules (allow={$ac}, deny={$dc})";
                    }

                    // UA rules
                    if ($action === '' && stripos($uri, '/admin/access/ua') !== false && in_array($method, ['POST', 'PUT'])) {
                        $allow = isset($input['allow_uas']) ? trim($input['allow_uas']) : '';
                        $deny = isset($input['deny_uas']) ? trim($input['deny_uas']) : '';
                        $ac = $allow === '' ? 0 : count(array_filter(array_map('trim', preg_split('/\r?\n/', $allow))));
                        $dc = $deny === '' ? 0 : count(array_filter(array_map('trim', preg_split('/\r?\n/', $deny))));
                        $action = "Saved UA rules (allow={$ac}, deny={$dc})";
                    }

                    // Password
                    if ($action === '' && stripos($uri, '/admin/password') !== false && in_array($method, ['POST'])) {
                        $action = 'Updated admin password';
                    }

                    // Sites
                    if ($action === '' && stripos($uri, '/admin/sites') !== false && in_array($method, ['POST', 'PUT'])) {
                        $sites = isset($input['sites']) ? trim($input['sites']) : '';
                        $count = $sites === '' ? 0 : count(array_filter(array_map('trim', preg_split('/\r?\n/', $sites))));
                        $action = "Saved sites ({$count} entries)";
                    }

                    // Models
                    if ($action === '' && stripos($uri, '/admin/models') !== false && in_array($method, ['POST', 'PUT'])) {
                        $models = isset($input['models']) ? trim($input['models']) : '';
                        $count = $models === '' ? 0 : count(array_filter(array_map('trim', preg_split('/\r?\n/', $models))));
                        $action = "Saved models ({$count} entries)";
                    }

                    // Repair actions
                    if ($action === '' && stripos($uri, '/admin/repair') !== false && isset($input['action'])) {
                        $action = 'Executed repair: ' . (string)$input['action'];
                    }

                    // Generate fake logs
                    if ($action === '' && stripos($uri, '/admin/generate_fake_logs') !== false) {
                        $d = $input['date'] ?? date('Y-m-d');
                        $c = isset($input['count']) ? (int)$input['count'] : 0;
                        $action = "Generated fake logs for {$d} (count={$c})";
                    }

                    // Content generation (simple)
                    if ($action === '' && (stripos($uri, '/admin/content/ai') !== false || stripos($uri, '/admin/content/ai/generate') !== false) && isset($input['topic'])) {
                        $t = trim((string)$input['topic']);
                        $action = 'Generated AI article: ' . ($t === '' ? '(no title)' : ($t));
                    }

                    // Fallback: for non-GET requests, summarize keys; for GETs, mark visit
                    if ($action === '') {
                        if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE']) && is_array($input) && count($input) > 0) {
                            $keys = implode(',', array_keys($input));
                            $action = strtoupper($method) . ' ' . $uri . ' (keys: ' . $keys . ')';
                        } else {
                            $action = ($method === 'GET' ? 'Visited ' : $method . ' ') . $uri;
                        }
                    }
                } catch (\Throwable $e) {
                    $action = ($method === 'GET' ? 'Visited ' : $method . ' ') . $uri;
                }

                $obj = [
                    'datetime' => $time,
                    'user' => $user,
                    'ip' => $ip,
                    'method' => $method,
                    'uri' => $uri,
                    'status' => $status,
                    'duration' => $duration,
                    'action' => $action,
                    'payload' => $input,
                ];

                $line = @json_encode($obj, JSON_UNESCAPED_UNICODE) . PHP_EOL;
                @file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
            }
        } catch (\Throwable $e) {
            // swallow errors
        }

        return $response;
    }
}
