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

        $entries = [];
        $d = clone $periodStart;
        while ($d <= $periodEnd) {
            $day = $d->format('Y-m-d');
            $file = storage_path("logs/operation-{$day}.log");
            if (file_exists($file)) {
                $lines = array_map('trim', file($file));
                foreach ($lines as $ln) {
                    if ($ln === '') continue;
                    $parts = preg_split('/\t+/', $ln);
                    $row = [
                        'datetime' => $parts[0] ?? '',
                        'ip' => $parts[1] ?? '',
                        'message' => $parts[2] ?? '',
                        'raw' => $ln,
                    ];
                    if ($q && stripos($row['message'], $q) === false && stripos($row['ip'], $q) === false) continue;
                    $entries[] = $row;
                }
            }
            $d->modify('+1 day');
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
        $file = base_path('data/access/sites.json');
        $this->ensureDirectoryExists(dirname($file));
        $sites = [];
        if (file_exists($file)) {
            $sites = json_decode(file_get_contents($file), true) ?: [];
        }
        return view('admin', ['page' => 'sites', 'sitesList' => $sites]);
    }

    public function saveSites(Request $request)
    {
        $file = base_path('data/access/sites.json');
        $this->ensureDirectoryExists(dirname($file));
        $input = $request->input('sites', '');
        $sites = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', trim($input)))));
        file_put_contents($file, json_encode($sites, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return redirect()->route('admin.sites')->with('status', 'Sites saved');
    }

    public function models()
    {
        $file = base_path('data/access/models.json');
        $this->ensureDirectoryExists(dirname($file));
        $models = [];
        if (file_exists($file)) {
            $models = json_decode(file_get_contents($file), true) ?: [];
        }
        return view('admin', ['page' => 'models', 'modelsList' => $models]);
    }

    public function saveModels(Request $request)
    {
        $file = base_path('data/access/models.json');
        $this->ensureDirectoryExists(dirname($file));
        $input = $request->input('models', '');
        $models = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', trim($input)))));
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
        $allArticles = $this->getArticles();

        // Filtering
        $source = $request->query('source');
        $status = $request->query('status');
        $from = $request->query('from');
        $to = $request->query('to');

        $filtered = array_filter($allArticles, function ($article) use ($source, $status, $from, $to) {
            if ($source && $article['source'] !== $source) return false;
            if ($status && $article['status'] !== $status) return false;
            if ($from && strtotime($article['created_at']) < strtotime($from)) return false;
            if ($to && strtotime($article['created_at']) > strtotime($to . ' 23:59:59')) return false;
            return true;
        });

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
