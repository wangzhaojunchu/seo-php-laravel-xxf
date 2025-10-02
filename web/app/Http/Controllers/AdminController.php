<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $serverInfo = [
            'php_version' => PHP_VERSION,
            'os' => php_uname(),
            'disk_free' => @disk_free_space(base_path()),
            'disk_total' => @disk_total_space(base_path()),
            'uptime' => trim(@shell_exec('uptime -p') ?: ''),
            'memory_usage' => memory_get_usage(true),
        ];
        $access = null;
        $spiders = null;
        $todayAccess = storage_path('logs') . DIRECTORY_SEPARATOR . 'access-' . date('Y-m-d') . '.log';
        $todaySpider = storage_path('logs') . DIRECTORY_SEPARATOR . 'spider-' . date('Y-m-d') . '.log';

        $accessFile = null;
        if (file_exists($todayAccess)) {
            $accessFile = $todayAccess;
        } else {
            $files = glob(storage_path('logs') . DIRECTORY_SEPARATOR . 'access-*.log');
            if (!empty($files)) {
                usort($files, function($a, $b) { return filemtime($b) <=> filemtime($a); });
                $accessFile = $files[0];
            }
        }

        $spiderFile = null;
        if (file_exists($todaySpider)) {
            $spiderFile = $todaySpider;
        } else {
            $sfiles = glob(storage_path('logs') . DIRECTORY_SEPARATOR . 'spider-*.log');
            if (!empty($sfiles)) {
                usort($sfiles, function($a, $b) { return filemtime($b) <=> filemtime($a); });
                $spiderFile = $sfiles[0];
            }
        }

        if ($accessFile && file_exists($accessFile)) {
            $access = implode("\n", array_slice(file($accessFile), -200));
        }
        if ($spiderFile && file_exists($spiderFile)) {
            $spiders = implode("\n", array_slice(file($spiderFile), -200));
        }
        return view('admin', ['page' => 'home', 'serverInfo' => $serverInfo, 'accessLogs' => $access, 'spiderLogs' => $spiders]);
    }

    public function settings()
    {
        return view('admin', ['page' => 'settings']);
    }

    public function passwordForm()
    {
        return view('admin', ['page' => 'password']);
    }

    public function updatePassword(Request $request)
    {
        $pwFile = base_path('data') . DIRECTORY_SEPARATOR . 'password.txt';
        $this->ensureDirectoryExists(dirname($pwFile));
        $new = (string)$request->input('new_password', '');
        if (trim($new) === '') {
            return redirect()->route('admin.password')->withErrors(['new_password' => 'New password cannot be empty']);
        }
        file_put_contents($pwFile, $new);
        $this->logOperation("Password updated");
        return redirect()->route('admin.password')->with('status', 'Password updated successfully');
    }

    public function operationLogs(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));
        $validator = Validator::make($request->all(), [
            'from' => 'nullable|date',
            'to' => 'nullable|date',
            'q' => 'nullable|string|max:256',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:10|max:200',
        ]);
        if ($validator->fails()) {
            return redirect()->route('admin.operation_logs')->withErrors($validator)->withInput();
        }
        
        $from = $request->query('from', $date);
        $to = $request->query('to', $date);
        $q = $request->query('q', null);
        $page = max(1, (int)$request->query('page', 1));
        $per = max(10, min(200, (int)$request->query('per_page', 20)));

        try {
            $periodStart = new \DateTime($from);
            $periodEnd = new \DateTime($to);
        } catch (\Exception $e) {
            $periodStart = new \DateTime($date);
            $periodEnd = new \DateTime($date);
        }
        if ($periodEnd < $periodStart) {
            $t = $periodStart; $periodStart = $periodEnd; $periodEnd = $t;
        }

        $download = $request->query('download', null);

        $entries = [];
        $d = clone $periodStart;
        while ($d <= $periodEnd) {
            $day = $d->format('Y-m-d');
            $file = base_path('data') . DIRECTORY_SEPARATOR . 'action' . DIRECTORY_SEPARATOR . "action-{$day}.log";
            if (file_exists($file)) {
                $lines = array_map('trim', file($file));
                foreach ($lines as $ln) {
                    if ($ln === '') continue;
                    $obj = json_decode($ln, true);
                    if (!$obj) continue;
                    $row = [
                        'datetime' => $obj['datetime'] ?? '',
                        'user' => $obj['user'] ?? '',
                        'ip' => $obj['ip'] ?? '',
                        'method' => $obj['method'] ?? '',
                        'uri' => $obj['uri'] ?? '',
                        'status' => $obj['status'] ?? '',
                        'duration' => $obj['duration'] ?? '',
                        'action' => $obj['action'] ?? '',
                        'payload' => isset($obj['payload']) ? (is_array($obj['payload']) ? json_encode($obj['payload'], JSON_UNESCAPED_UNICODE) : (string)$obj['payload']) : '',
                        'raw' => $ln,
                    ];
                    if ($q && stripos($row['uri'] . ' ' . $row['user'] . ' ' . $row['ip'] . ' ' . $row['payload'], $q) === false) continue;
                    $entries[] = $row;
                }
            }
            $d->modify('+1 day');
        }

        if ($download === 'action') {
            $csv = "datetime,user,ip,method,uri,status,duration,action,payload\n";
            foreach ($entries as $r) {
                $csv .= sprintf('"%s","%s","%s","%s","%s","%s","%s","%s","%s"\n', $r['datetime'], $r['user'], $r['ip'], $r['method'], $r['uri'], $r['status'], $r['duration'], addslashes($r['action'] ?? ''), addslashes($r['payload'] ?? ''));
            }
            return response($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=action-{$date}.csv",
            ]);
        }

        $total = count($entries);
        $pages = max(1, ceil($total / $per));
        $slice = array_slice(array_reverse($entries), ($page-1)*$per, $per);

        return view('admin', ['page' => 'operation_logs', 'logs' => $slice, 'total' => $total, 'currentPage' => $page, 'pages' => $pages, 'date' => $date, 'from' => $from, 'to' => $to, 'q' => $q, 'per' => $per]);
    }

    private function logOperation(string $message)
    {
        $file = storage_path('logs/operation-' . date('Y-m-d') . '.log');
        $ip = request()->ip();
        $line = date('Y-m-d H:i:s') . "\t" . $ip . "\t" . $message . "\n";
        file_put_contents($file, $line, FILE_APPEND);
    }

    public function logs(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));
        $validator = Validator::make($request->all(), [
            'from' => 'nullable|date',
            'to' => 'nullable|date',
            'ip' => 'nullable|string|max:128',
            'status' => 'nullable|string|max:16',
            'q' => 'nullable|string|max:256',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:10|max:200',
        ]);
        if ($validator->fails()) {
            return redirect()->route('admin.logs')->withErrors($validator)->withInput();
        }
        
        $from = $request->query('from', $date);
        $to = $request->query('to', $date);
        $ip = $request->query('ip', null);
        $status = $request->query('status', null);
        $q = $request->query('q', null);
        $page = max(1, (int)$request->query('page', 1));
        $per = max(10, min(200, (int)$request->query('per_page', 20)));
        $download = $request->query('download', null);

        try {
            $periodStart = new \DateTime($from);
            $periodEnd = new \DateTime($to);
        } catch (\Exception $e) {
            $periodStart = new \DateTime($date);
            $periodEnd = new \DateTime($date);
        }
        if ($periodEnd < $periodStart) {
            $t = $periodStart; $periodStart = $periodEnd; $periodEnd = $t;
        }

        $entries = [];
        $counts = [];
        $labels = [];
        $d = clone $periodStart;
        while ($d <= $periodEnd) {
            $day = $d->format('Y-m-d');
            $labels[] = $day;
            $counts[$day] = 0;
            $file = storage_path("logs/access-{$day}.log");
            if (file_exists($file)) {
                $lines = array_map('trim', file($file));
                foreach ($lines as $ln) {
                    if ($ln === '') continue;
                    $parts = preg_split('/\t+/', $ln);
                    $row = [
                        'datetime' => $parts[0] ?? '',
                        'ip' => $parts[1] ?? '',
                        'method' => $parts[2] ?? '',
                        'status' => $parts[3] ?? '',
                        'url' => $parts[4] ?? '',
                        'raw' => $ln,
                    ];
                    if ($ip && stripos($row['ip'], $ip) === false) continue;
                    if ($status && (string)$row['status'] !== (string)$status) continue;
                    if ($q && stripos($row['url'], $q) === false && stripos($row['ip'], $q) === false) continue;
                    $entries[] = $row;
                    $counts[$day]++;
                }
            }
            $d->modify('+1 day');
        }

        $filtered = $entries;

        if ($download === 'access') {
            $csv = "datetime,ip,method,status,url\n";
            foreach ($filtered as $r) {
                $csv .= sprintf('"%s","%s","%s","%s","%s"\n', $r['datetime'], $r['ip'], $r['method'], $r['status'], addslashes($r['url']));
            }
            return response($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=access-{$date}.csv",
            ]);
        }
        
        $total = count($filtered);
        $pages = max(1, ceil($total / $per));
        $slice = array_slice(array_reverse(array_values($filtered)), ($page-1)*$per, $per);

        $chartCounts = [];
        foreach ($labels as $lab) {
            $chartCounts[] = $counts[$lab] ?? 0;
        }
        return view('admin', ['page' => 'logs', 'logs' => $slice, 'total' => $total, 'currentPage' => $page, 'pages' => $pages, 'date' => $date, 'from' => $from, 'to' => $to, 'ip' => $ip, 'status' => $status, 'q' => $q, 'per' => $per, 'chartLabels' => $labels, 'chartCounts' => $chartCounts]);
    }

    public function spiders(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));
        $validator = Validator::make($request->all(), [
            'from' => 'nullable|date',
            'to' => 'nullable|date',
            'bot' => 'nullable|string|max:128',
            'ip' => 'nullable|string|max:128',
            'q' => 'nullable|string|max:256',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:10|max:200',
        ]);
        if ($validator->fails()) {
            return redirect()->route('admin.spiders')->withErrors($validator)->withInput();
        }

        $from = $request->query('from', $date);
        $to = $request->query('to', $date);
        $bot = $request->query('bot', null);
        $ip = $request->query('ip', null);
        $q = $request->query('q', null);
        $page = max(1, (int)$request->query('page', 1));
        $per = max(10, min(200, (int)$request->query('per_page', 20)));
        $download = $request->query('download', null);

        try {
            $periodStart = new \DateTime($from);
            $periodEnd = new \DateTime($to);
        } catch (\Exception $e) {
            $periodStart = new \DateTime($date);
            $periodEnd = new \DateTime($date);
        }
        if ($periodEnd < $periodStart) {
            $t = $periodStart; $periodStart = $periodEnd; $periodEnd = $t;
        }

        $entries = [];
        $counts = [];
        $labels = [];
        $d = clone $periodStart;
        while ($d <= $periodEnd) {
            $day = $d->format('Y-m-d');
            $labels[] = $day;
            $counts[$day] = 0;
            $file = storage_path("logs/spider-{$day}.log");
            if (file_exists($file)) {
                $lines = array_map('trim', file($file));
                foreach ($lines as $ln) {
                    if ($ln === '') continue;
                    $parts = preg_split('/\t+/', $ln);
                    $row = [
                        'datetime' => $parts[0] ?? '',
                        'ip' => $parts[1] ?? '',
                        'bot' => $parts[2] ?? '',
                        'method' => $parts[3] ?? '',
                        'url' => $parts[4] ?? '',
                        'raw' => $ln,
                    ];
                    if ($bot && stripos($row['bot'], $bot) === false) continue;
                    if ($ip && stripos($row['ip'], $ip) === false) continue;
                    if ($q && stripos($row['url'], $q) === false && stripos($row['ip'], $q) === false) continue;
                    $entries[] = $row;
                    $counts[$day]++;
                }
            }
            $d->modify('+1 day');
        }

        $filtered = $entries;

        if ($download === 'spider') {
            $csv = "datetime,ip,bot,method,url\n";
            foreach ($filtered as $r) {
                $csv .= sprintf('"%s","%s","%s","%s","%s"\n', $r['datetime'], $r['ip'], $r['bot'], $r['method'], addslashes($r['url']));
            }
            return response($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=spider-{$date}.csv",
            ]);
        }

        $total = count($filtered);
        $pages = max(1, ceil($total / $per));
        $slice = array_slice(array_reverse(array_values($filtered)), ($page-1)*$per, $per);

        $chartCounts = [];
        foreach ($labels as $lab) {
            $chartCounts[] = $counts[$lab] ?? 0;
        }

        return view('admin', ['page' => 'spiders', 'spiderLogs' => $slice, 'total' => $total, 'currentPage' => $page, 'pages' => $pages, 'date' => $date, 'from' => $from, 'to' => $to, 'bot' => $bot, 'ip' => $ip, 'q' => $q, 'per' => $per, 'chartLabels' => $labels, 'chartCounts' => $chartCounts]);
    }

    public function repair()
    {
        return view('admin', ['page' => 'repair']);
    }

    public function runRepair(Request $request)
    {
        $action = $request->input('action', '');
        $result = '';
        try {
            if ($action === 'cache-clear') {
                $result = shell_exec('php ' . base_path('artisan') . ' cache:clear 2>&1');
            } elseif ($action === 'config-cache') {
                $result = shell_exec('php ' . base_path('artisan') . ' config:cache 2>&1');
            } else {
                $result = 'No action specified';
            }
        } catch (\Throwable $e) {
            $result = 'Error: ' . $e->getMessage();
        }
        return view('admin', ['page' => 'repair', 'repairResult' => $result]);
    }

    public function api()
    {
        $file = base_path('data') . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'config.json';
        $this->ensureDirectoryExists(dirname($file));
        $cfg = [];
        if (file_exists($file)) {
            $txt = file_get_contents($file);
            $cfg = json_decode($txt, true) ?: [];
        }
        $json = json_encode($cfg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return view('admin', ['page' => 'api_management', 'apiConfigJson' => $json]);
    }

    public function saveApiConfig(Request $request)
    {
        $json = $request->input('config_json', '');
        $file = base_path('data') . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'config.json';
        $this->ensureDirectoryExists(dirname($file));

        // Basic validation: valid JSON and must contain 'endpoint' and 'key'
        $decoded = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->route('admin.api')->withErrors(['config_json' => 'Invalid JSON: ' . json_last_error_msg()])->withInput();
        }
        if (!isset($decoded['endpoint'])) {
            return redirect()->route('admin.api')->withErrors(['config_json' => 'JSON must contain at least "endpoint" field'])->withInput();
        }

        // Auto-generate a key if missing or empty
        if (empty($decoded['key'])) {
            try {
                $decoded['key'] = bin2hex(random_bytes(16));
            } catch (\Throwable $e) {
                $decoded['key'] = bin2hex(openssl_random_pseudo_bytes(16));
            }
        }

        // Ensure allowed_sources exists
        if (!isset($decoded['allowed_sources']) || !is_array($decoded['allowed_sources'])) {
            $decoded['allowed_sources'] = ['api'];
        }

        file_put_contents($file, json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return redirect()->route('admin.api')->with('status', 'API 配置已保存')->with('api_key', $decoded['key']);
    }

    public function generateFakeLogs(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $count = max(1, min(1000, (int)$request->input('count', 200)));
        $accessFile = storage_path("logs/access-{$date}.log");
        $spiderFile = storage_path("logs/spider-{$date}.log");
        $ips = ['192.168.1.10','192.168.1.11','203.0.113.5','198.51.100.23','8.8.8.8'];
        $methods = ['GET','POST','HEAD'];
        $urls = ['/','/about','/products','/search?q=php','/robots.txt','/sitemap.xml'];
        $bots = ['Googlebot','Bingbot','Baiduspider','YandexBot','DuckDuckBot','AhrefsBot'];
        $afh = fopen($accessFile, 'a');
        $sph = fopen($spiderFile, 'a');
        if ($afh) {
            for ($i=0;$i<$count;$i++){
                $ts = strtotime($date) + rand(0, 86400 - 1);
                $t = date('Y-m-d H:i:s', $ts);
                $ip = $ips[array_rand($ips)];
                $m = $methods[array_rand($methods)];
                $statusList = [200,200,200,301,404,500];
                $status = $statusList[array_rand($statusList)];
                $url = $urls[array_rand($urls)];
                fwrite($afh, implode("\t", [$t,$ip,$m,$status,$url]) . "\n");
            }
            fclose($afh);
        }
        if ($sph) {
            for ($i=0;$i<intval($count/4);$i++){
                $ts = strtotime($date) + rand(0, 86400 - 1);
                $t = date('Y-m-d H:i:s', $ts);
                $ip = $ips[array_rand($ips)];
                $bot = $bots[array_rand($bots)];
                $m = $methods[array_rand($methods)];
                $url = $urls[array_rand($urls)];
                fwrite($sph, implode("\t", [$t,$ip,$bot,$m,$url]) . "\n");
            }
            fclose($sph);
        }
        return redirect()->route('admin.repair')->with('status', 'Fake logs generated for ' . $date);
    }

    public function sites()
    {
        // Read groups from data/group/groups.json
        $file = base_path('data') . DIRECTORY_SEPARATOR . 'group' . DIRECTORY_SEPARATOR . 'groups.json';
        $this->ensureDirectoryExists(dirname($file));
        $groups = [];
        if (file_exists($file)) {
            $groups = json_decode(file_get_contents($file), true) ?: [];
        }
        return view('admin', ['page' => 'sites', 'groups' => $groups]);
    }

    public function saveSites(Request $request)
    {
        // Expect JSON payload of groups or form inputs
        $file = base_path('data') . DIRECTORY_SEPARATOR . 'group' . DIRECTORY_SEPARATOR . 'groups.json';
        $this->ensureDirectoryExists(dirname($file));

        // If a groups_json field is present, use it (from JS); otherwise, build from form fields
        $groupsJson = $request->input('groups_json', null);
        if ($groupsJson) {
            $decoded = json_decode($groupsJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return redirect()->route('admin.sites')->withErrors(['groups' => 'Invalid groups JSON']);
            }
            $groups = $decoded;
        } else {
            // Build a single group from form inputs
            $name = $request->input('group_name', 'default');
            $domainsRaw = $request->input('group_domains', '');
            $domains = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', trim($domainsRaw)))));
            $forceWww = $request->has('force_www');
            $forceMobile = $request->has('force_mobile');
            $opt = [
                'name' => $name,
                'domains' => $domains,
                'force_www' => $forceWww,
                'force_mobile' => $forceMobile,
                'model' => $request->input('group_model', ''),
                'template' => $request->input('group_template', ''),
                'created_at' => date('Y-m-d H:i:s')
            ];
            // Load existing groups and append
            $groups = [];
            if (file_exists($file)) {
                $groups = json_decode(file_get_contents($file), true) ?: [];
            }
            $groups[] = $opt;
        }

        file_put_contents($file, json_encode($groups, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return redirect()->route('admin.sites')->with('status', 'Groups saved');
    }

    // Delete a site/group by name (AJAX-friendly)
    public function deleteSite(Request $request)
    {
        $name = (string)$request->input('name', '');
        if ($name === '') {
            return response()->json(['ok' => false, 'message' => '缺少分组名'], 400);
        }
        $file = base_path('data') . DIRECTORY_SEPARATOR . 'group' . DIRECTORY_SEPARATOR . 'groups.json';
        $this->ensureDirectoryExists(dirname($file));
        $groups = [];
        if (file_exists($file)) {
            $groups = json_decode(file_get_contents($file), true) ?: [];
        }
        $found = null;
        $remaining = [];
        foreach ($groups as $g) {
            if (($g['name'] ?? '') === $name) {
                $found = $g;
            } else {
                $remaining[] = $g;
            }
        }
        if ($found === null) {
            return response()->json(['ok' => false, 'message' => '未找到匹配的分组'], 404);
        }
        // Create backup file for undo
        $backupDir = base_path('data') . DIRECTORY_SEPARATOR . 'group' . DIRECTORY_SEPARATOR . 'backups';
        $this->ensureDirectoryExists($backupDir);
        $slug = preg_replace('/[^a-z0-9_\-]/i', '_', strtolower($name));
        $backupFile = $backupDir . DIRECTORY_SEPARATOR . 'backup-' . date('Ymd-His') . '-' . $slug . '.json';
        file_put_contents($backupFile, json_encode($found, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Save remaining groups
        file_put_contents($file, json_encode(array_values($remaining), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return response()->json(['ok' => true, 'message' => '分组已删除', 'backup' => basename($backupFile)]);
    }

    // Restore a site/group from a backup file
    public function restoreSite(Request $request)
    {
        $backup = (string)$request->input('backup', '');
        if ($backup === '') {
            return response()->json(['ok' => false, 'message' => '缺少 backup 参数'], 400);
        }
        $backupFile = base_path('data') . DIRECTORY_SEPARATOR . 'group' . DIRECTORY_SEPARATOR . 'backups' . DIRECTORY_SEPARATOR . $backup;
        if (!file_exists($backupFile)) {
            return response()->json(['ok' => false, 'message' => '备份文件未找到'], 404);
        }
        $content = json_decode(file_get_contents($backupFile), true);
        if (!$content || !is_array($content)) {
            return response()->json(['ok' => false, 'message' => '备份数据无效'], 500);
        }
        $file = base_path('data') . DIRECTORY_SEPARATOR . 'group' . DIRECTORY_SEPARATOR . 'groups.json';
        $this->ensureDirectoryExists(dirname($file));
        $groups = [];
        if (file_exists($file)) {
            $groups = json_decode(file_get_contents($file), true) ?: [];
        }
        // Avoid duplicates by name
        foreach ($groups as $g) {
            if (($g['name'] ?? '') === ($content['name'] ?? '')) {
                return response()->json(['ok' => false, 'message' => '分组已存在，无法恢复'], 409);
            }
        }
        $groups[] = $content;
        file_put_contents($file, json_encode($groups, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        // Optionally remove the backup file
        @unlink($backupFile);
        return response()->json(['ok' => true, 'message' => '分组已恢复']);
    }

    // Return list of backup files for groups
    public function sitesBackups()
    {
        $backupDir = base_path('data') . DIRECTORY_SEPARATOR . 'group' . DIRECTORY_SEPARATOR . 'backups';
        $this->ensureDirectoryExists($backupDir);
        $files = glob($backupDir . DIRECTORY_SEPARATOR . 'backup-*.json') ?: [];
        usort($files, function($a, $b){ return filemtime($b) <=> filemtime($a); });
        $list = [];
        foreach ($files as $f) {
            $list[] = ['file' => basename($f), 'mtime' => date('Y-m-d H:i:s', filemtime($f))];
        }
        return response()->json($list);
    }

    // Return groups as JSON for front-end usage (safer than fetching raw data file)
    public function sitesJson()
    {
        $file = base_path('data') . DIRECTORY_SEPARATOR . 'group' . DIRECTORY_SEPARATOR . 'groups.json';
        $this->ensureDirectoryExists(dirname($file));
        $groups = [];
        if (file_exists($file)) {
            $groups = json_decode(file_get_contents($file), true) ?: [];
        }
        return response()->json(array_values($groups));
    }

    // Accept JSON payload to save a site (AJAX-friendly)
    public function saveSitesJson(Request $request)
    {
        $payload = $request->json()->all();
        if (!$payload || !is_array($payload)) {
            return response()->json(['ok' => false, 'message' => 'Invalid JSON payload'], 400);
        }
        // Expect either single group object or array
        $group = null;
        if (isset($payload['name'])) {
            $group = $payload;
        } elseif (isset($payload[0]) && is_array($payload[0])) {
            $group = $payload[0];
        }
        if (!$group || empty($group['name'])) {
            return response()->json(['ok' => false, 'message' => 'Missing group name'], 400);
        }

        $file = base_path('data') . DIRECTORY_SEPARATOR . 'group' . DIRECTORY_SEPARATOR . 'groups.json';
        $this->ensureDirectoryExists(dirname($file));
        $groups = [];
        if (file_exists($file)) {
            $groups = json_decode(file_get_contents($file), true) ?: [];
        }

        // If name exists, replace; otherwise append
        $replaced = false;
        foreach ($groups as $i => $g) {
            if (($g['name'] ?? '') === ($group['name'] ?? '')) {
                $groups[$i] = array_merge($g, $group);
                $replaced = true;
                break;
            }
        }
        if (!$replaced) {
            $group['created_at'] = date('Y-m-d H:i:s');
            $groups[] = $group;
        }

        // Write groups and return
        file_put_contents($file, json_encode(array_values($groups), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return response()->json(['ok' => true, 'message' => $replaced ? 'Updated' : 'Created']);
    }

    public function models()
    {
        $file = base_path('data') . DIRECTORY_SEPARATOR . 'access' . DIRECTORY_SEPARATOR . 'models.json';
        $this->ensureDirectoryExists(dirname($file));
        $models = [];
        if (file_exists($file)) {
            $models = json_decode(file_get_contents($file), true) ?: [];
        }
        return view('admin', ['page' => 'models', 'modelsList' => $models]);
    }

    // Return models as JSON for UI (AJAX)
    public function modelsList()
    {
        $file = base_path('data') . DIRECTORY_SEPARATOR . 'access' . DIRECTORY_SEPARATOR . 'models.json';
        $this->ensureDirectoryExists(dirname($file));
        $models = [];
        if (file_exists($file)) {
            $models = json_decode(file_get_contents($file), true) ?: [];
        }
        return response()->json($models);
    }

    public function saveModels(Request $request)
    {
        $file = base_path('data') . DIRECTORY_SEPARATOR . 'access' . DIRECTORY_SEPARATOR . 'models.json';
        $this->ensureDirectoryExists(dirname($file));

        $modelsJson = $request->input('models_json', null);
        if ($modelsJson) {
            $decoded = json_decode($modelsJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return redirect()->route('admin.models')->withErrors(['models' => 'Invalid JSON']);
            }
            // Merge/update logic: if existing file contains entries with same key, update them; otherwise append
            $newModels = $decoded;
            $existing = [];
            if (file_exists($file)) {
                $existing = json_decode(file_get_contents($file), true) ?: [];
            }
            // Build map by key for existing
            $map = [];
            foreach ($existing as $e) {
                if (!empty($e['key'])) $map[$e['key']] = $e;
            }
            foreach ($newModels as $nm) {
                // Normalize template: allow string (JSON or single template) or array
                if (isset($nm['template'])) {
                    if (is_string($nm['template'])) {
                        $tRaw = trim($nm['template']);
                        $decodedT = json_decode($tRaw, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decodedT)) {
                            $nm['template'] = $decodedT;
                        } elseif ($tRaw !== '') {
                            // treat as single-template shorthand -> map to 'page'
                            $nm['template'] = ['page' => $tRaw];
                        } else {
                            // empty string -> default mapping
                            $nm['template'] = ['page' => 'template.html', 'list' => 'list.html'];
                        }
                    } elseif (!is_array($nm['template'])) {
                        // force to array default
                        $nm['template'] = ['page' => 'template.html', 'list' => 'list.html'];
                    }
                } else {
                    $nm['template'] = ['page' => 'template.html', 'list' => 'list.html'];
                }

                $k = $nm['key'] ?? '';
                if ($k !== '' && isset($map[$k])) {
                    // Update fields (merge but ensure template is array)
                    $merged = array_merge($map[$k], $nm);
                    if (!is_array($merged['template'])) $merged['template'] = ['page' => 'template.html'];
                    $map[$k] = $merged;
                } else {
                    // New model, add to map with provided key or generated
                    if ($k === '') {
                        $k = preg_replace('/[^a-z0-9_\-]/i', '_', strtolower($nm['name'] ?? 'model'));
                        $nm['key'] = $k;
                    }
                    $map[$k] = $nm;
                }
            }
            // Convert map back to sequential array
            $models = array_values($map);
        } else {
            // Fallback: one-line-per-model name (legacy)
            // Support a single model form with multiline template mapping (model_template)
            $name = $request->input('model_name', '');
            $key = $request->input('model_key', '') ?: preg_replace('/[^a-z0-9_\-]/i', '_', strtolower($name ?: 'model'));
            $tmplTxt = $request->input('model_template', '');
            $mapping = [];
            if (trim($tmplTxt) !== '') {
                $lines = preg_split('/\r?\n/', trim($tmplTxt));
                foreach ($lines as $ln) {
                    $parts = preg_split('/=>/', $ln, 2);
                    if (count($parts) == 2) {
                        $k = trim($parts[0]);
                        $v = trim($parts[1]);
                        if ($k !== '') $mapping[$k] = $v;
                    }
                }
            }
            if (empty($mapping)) { $mapping = ['page' => 'template.html', 'list' => 'list.html']; }
            $models = [ ['name' => $name ?: 'model', 'key' => $key, 'template' => $mapping] ];
        }

        file_put_contents($file, json_encode($models, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return redirect()->route('admin.models')->with('status', 'Models saved');
    }

    public function accessIp()
    {
        $file = base_path('data/access/ip_control.json');
        $this->ensureDirectoryExists(dirname($file));
        $cfg = ['allow' => [], 'deny' => []];
        if (file_exists($file)) {
            $cfg = json_decode(file_get_contents($file), true) ?: $cfg;
        }
        return view('admin', ['page' => 'access_ip', 'ipAllow' => ($cfg['allow'] ?? []), 'ipDeny' => ($cfg['deny'] ?? [])]);
    }

    public function saveAccessIp(Request $request)
    {
        $file = base_path('data/access/ip_control.json');
        $this->ensureDirectoryExists(dirname($file));
        $allowRaw = $request->input('allow_ips', '');
        $denyRaw = $request->input('deny_ips', '');
        $allow = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', trim($allowRaw)))));
        $deny = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', trim($denyRaw)))));
        $cfg = ['allow' => $allow, 'deny' => $deny];
        file_put_contents($file, json_encode($cfg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return redirect()->route('admin.access.ip')->with('status', 'IP rules saved');
    }

    public function accessUa()
    {
        $file = base_path('data/access/ua_control.json');
        $this->ensureDirectoryExists(dirname($file));
        $cfg = ['allow' => [], 'deny' => []];
        if (file_exists($file)) {
            $cfg = json_decode(file_get_contents($file), true) ?: $cfg;
        }
        return view('admin', ['page' => 'access_ua', 'uaAllow' => ($cfg['allow'] ?? []), 'uaDeny' => ($cfg['deny'] ?? [])]);
    }

    public function saveAccessUa(Request $request)
    {
        $file = base_path('data/access/ua_control.json');
        $this->ensureDirectoryExists(dirname($file));
        $allowRaw = $request->input('allow_uas', '');
        $denyRaw = $request->input('deny_uas', '');
        $allow = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', trim($allowRaw)))));
        $deny = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', trim($denyRaw)))));
        $cfg = ['allow' => $allow, 'deny' => $deny];
        file_put_contents($file, json_encode($cfg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return redirect()->route('admin.access.ua')->with('status', 'UA rules saved');
    }

    // --- Content Management Methods ---

    public function showContentAi()
    {
        return view('admin', ['page' => 'content_ai']);
    }

    public function showContentCollection()
    {
        return view('admin', ['page' => 'content_collection']);
    }

    // Separate page for content resources management (keywords/columns/tips/suffixes)
    public function showContentResources(Request $request)
    {   
        $tab = $request->query('tab', 'keywords');
        $group = $request->query('group', 'default');
        $slug = $this->slugify($group ?: 'default');
        $dir = base_path('data') . DIRECTORY_SEPARATOR . 'group' . DIRECTORY_SEPARATOR . $slug . DIRECTORY_SEPARATOR . $tab;
        $this->ensureDirectoryExists($dir);
        $files = glob($dir . DIRECTORY_SEPARATOR . '*') ?: [];
        $list = [];
        foreach ($files as $f) {
            if (!is_file($f)) continue;
            $list[] = ['file' => basename($f), 'size' => filesize($f), 'mtime' => date('Y-m-d H:i:s', filemtime($f))];
        }

        // Render admin view with files list for server-side rendering
        return view('admin', [
            'page' => 'content_resources',
            'resource_tab' => $tab,
            'resource_group' => $group,
            'resource_files' => $list,
        ]);
    }

    // Add a simple content resource (text) via modal form (saves under data/group/{slug}/{type}/{name})
    public function addContentResource(Request $request)
    {
        $group = (string)$request->input('group', 'default');
        $type = (string)$request->input('type', 'keywords');
        $name = (string)$request->input('name', 'new.txt');
        $content = (string)$request->input('content', '');
        $allowed = ['keywords','columns','tips','suffixes'];
        if (!in_array($type, $allowed)) {
            return redirect()->back()->with('error', 'Invalid type');
        }
        $slug = $this->slugify($group ?: 'default');
        $dir = base_path('data') . DIRECTORY_SEPARATOR . 'group' . DIRECTORY_SEPARATOR . $slug . DIRECTORY_SEPARATOR . $type;
        $this->ensureDirectoryExists($dir);

        $safeName = preg_replace('/[^a-z0-9_\-\.]/i', '_', $name);
        $path = $dir . DIRECTORY_SEPARATOR . $safeName;
        file_put_contents($path, $content);

        return redirect()->route('admin.content.resources', ['tab' => $type, 'group' => $group])->with('status', '已添加');
    }

    public function generateAiArticle(Request $request)
    {
        $topic = $request->input('topic', 'Untitled');
        
        // ##################################################################
        // ### PLACEHOLDER - REAL AI API CALL REQUIRED ###
        // ##################################################################
        $fakeContent = "This is a simulated AI-generated article about `{$topic}`.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi.";
        // ##################################################################

        $this->saveArticle($topic, $fakeContent, 'AI');

        return redirect()->route('admin.content.manage')->with('status', 'Simulated article generated and saved as unpublished.');
    }

    public function showContentManage(Request $request)
    {
        // Read articles from data/article
        $publishedDir = base_path('data') . DIRECTORY_SEPARATOR . 'article' . DIRECTORY_SEPARATOR . 'published';
        $unpublishedDir = base_path('data') . DIRECTORY_SEPARATOR . 'article' . DIRECTORY_SEPARATOR . 'unpublished';
        $this->ensureDirectoryExists($publishedDir);
        $this->ensureDirectoryExists($unpublishedDir);

        $files = array_merge(glob($publishedDir . DIRECTORY_SEPARATOR . '*.json') ?: [], glob($unpublishedDir . DIRECTORY_SEPARATOR . '*.json') ?: []);
        $all = [];
        foreach ($files as $f) {
            $txt = @file_get_contents($f);
            $data = json_decode($txt, true);
            if (!$data) continue;
            $all[] = $data;
        }

        // Filtering
        $source = $request->query('source');
        $status = $request->query('status');
        $from = $request->query('from');
        $to = $request->query('to');

        $filtered = array_filter($all, function ($article) use ($source, $status, $from, $to) {
            if ($source && (!isset($article['source']) || $article['source'] !== $source)) return false;
            if ($status && (!isset($article['status']) || $article['status'] !== $status)) return false;
            if ($from && isset($article['created_at']) && strtotime($article['created_at']) < strtotime($from)) return false;
            if ($to && isset($article['created_at']) && strtotime($article['created_at']) > strtotime($to . ' 23:59:59')) return false;
            return true;
        });

        // Sort by created_at desc
        usort($filtered, function($a, $b) { return strtotime($b['created_at'] ?? 0) <=> strtotime($a['created_at'] ?? 0); });

        // Pagination
        $page = max(1, (int)$request->query('page', 1));
        $perPage = 10;
        $total = count($filtered);
        $pages = max(1, ceil($total / $perPage));
        $paginated = array_slice(array_values($filtered), ($page - 1) * $perPage, $perPage);

        return view('admin', [
            'page' => 'content_manage',
            'articles' => $paginated,
            'total' => $total,
            'currentPage' => $page,
            'pages' => $pages,
            'perPage' => $perPage,
            'f_source' => $source,
            'f_status' => $status,
            'f_from' => $from,
            'f_to' => $to
        ]);
    }

    // List files for a given group and type (keywords, columns, tips, suffixes)
    public function contentFilesList(Request $request)
    {
        $group = (string)$request->query('group', '');
        $type = (string)$request->query('type', 'keywords');
        $allowed = ['keywords','columns','tips','suffixes'];
        if (!in_array($type, $allowed)) {
            return response()->json(['ok' => false, 'message' => 'Invalid type'], 400);
        }

        // resolve group slug
        $slug = $this->slugify($group ?: 'default');
        $dir = base_path('data') . DIRECTORY_SEPARATOR . 'group' . DIRECTORY_SEPARATOR . $slug . DIRECTORY_SEPARATOR . $type;
        $this->ensureDirectoryExists($dir);
        $files = glob($dir . DIRECTORY_SEPARATOR . '*') ?: [];
        $list = [];
        foreach ($files as $f) {
            if (!is_file($f)) continue;
            $list[] = ['file' => basename($f), 'size' => filesize($f), 'mtime' => date('Y-m-d H:i:s', filemtime($f))];
        }
        usort($list, function($a,$b){ return $b['mtime'] <=> $a['mtime']; });
        return response()->json(['ok' => true, 'files' => $list]);
    }

    // Upload a plain text file for a given group and type
    public function uploadContentFile(Request $request)
    {
        $group = (string)$request->input('group', '');
        $type = (string)$request->input('type', 'keywords');
        $allowed = ['keywords','columns','tips','suffixes'];
        if (!in_array($type, $allowed)) {
            return response()->json(['ok' => false, 'message' => 'Invalid type'], 400);
        }

        if (!$request->hasFile('file')) {
            return response()->json(['ok' => false, 'message' => 'No file uploaded'], 400);
        }
        $file = $request->file('file');
        if (!$file->isValid()) {
            return response()->json(['ok' => false, 'message' => 'Upload error'], 400);
        }

        $slug = $this->slugify($group ?: 'default');
        $dir = base_path('data') . DIRECTORY_SEPARATOR . 'group' . DIRECTORY_SEPARATOR . $slug . DIRECTORY_SEPARATOR . $type;
        $this->ensureDirectoryExists($dir);

        // move uploaded file to dir with sanitized name
        $name = preg_replace('/[^a-z0-9_\-\.]/i', '_', $file->getClientOriginalName());
        $target = $dir . DIRECTORY_SEPARATOR . $name;
        $file->move($dir, $name);

        return response()->json(['ok' => true, 'message' => 'Uploaded', 'file' => basename($target)]);
    }

    // Delete a content file
    public function deleteContentFile(Request $request)
    {
        $group = (string)$request->input('group', '');
        $type = (string)$request->input('type', 'keywords');
        $file = (string)$request->input('file', '');
        $allowed = ['keywords','columns','tips','suffixes'];
        if (!in_array($type, $allowed) || $file === '') {
            return response()->json(['ok' => false, 'message' => 'Invalid parameters'], 400);
        }
        $slug = $this->slugify($group ?: 'default');
        $path = base_path('data') . DIRECTORY_SEPARATOR . 'group' . DIRECTORY_SEPARATOR . $slug . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . basename($file);
        if (!file_exists($path)) {
            return response()->json(['ok' => false, 'message' => 'File not found'], 404);
        }
        @unlink($path);
        return response()->json(['ok' => true, 'message' => 'Deleted']);
    }

    private function slugify($name)
    {
        $s = preg_replace('/[^a-z0-9_\-]/i', '_', trim(strtolower($name)));
        return $s ?: 'default';
    }

    private function saveArticle($title, $content, $source)
    {
        $dir = base_path('data/article/unpublished');
        $this->ensureDirectoryExists($dir);
        $id = Str::uuid();
        $article = [
            'id' => $id,
            'title' => $title,
            'content' => $content,
            'source' => $source,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        file_put_contents("$dir/{$id}.json", json_encode($article, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function getArticles()
    {
        $publishedDir = base_path('data/article/published');
        $unpublishedDir = base_path('data/article/unpublished');
        $this->ensureDirectoryExists($publishedDir);
        $this->ensureDirectoryExists($unpublishedDir);

        $articles = [];
        $publishedFiles = glob("$publishedDir/*.json");
        $unpublishedFiles = glob("$unpublishedDir/*.json");

        foreach ($publishedFiles as $file) {
            $data = json_decode(file_get_contents($file), true);
            if ($data) {
                $data['status'] = 'published';
                $articles[] = $data;
            }
        }
        foreach ($unpublishedFiles as $file) {
            $data = json_decode(file_get_contents($file), true);
            if ($data) {
                $data['status'] = 'unpublished';
                $articles[] = $data;
            }
        }

        usort($articles, fn($a, $b) => strtotime($b['created_at']) <=> strtotime($a['created_at']));

        return $articles;
    }

    private function ensureDirectoryExists($path)
    {
        if (!is_dir($path)) {
            @mkdir($path, 0755, true);
        }
    }
}
