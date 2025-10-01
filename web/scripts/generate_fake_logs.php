<?php
// Usage: php generate_fake_logs.php [YYYY-MM-DD] [count]
$date = $argv[1] ?? date('Y-m-d');
$count = isset($argv[2]) ? max(1, min(10000, (int)$argv[2])) : 300;
$root = __DIR__ . DIRECTORY_SEPARATOR . '..';
$logDir = $root . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}
$accessFile = $logDir . DIRECTORY_SEPARATOR . "access-{$date}.log";
$spiderFile = $logDir . DIRECTORY_SEPARATOR . "spider-{$date}.log";
$ips = ['192.168.1.10','192.168.1.11','203.0.113.5','198.51.100.23','8.8.8.8','203.0.113.9','198.51.100.77'];
$methods = ['GET','POST','HEAD'];
$urls = ['/','/about','/products','/search?q=php','/robots.txt','/sitemap.xml','/contact','/blog/post-1','/api/data'];
$bots = ['Googlebot','Bingbot','Baiduspider','YandexBot','DuckDuckBot','AhrefsBot','SemrushBot'];

$afh = fopen($accessFile, 'a');
$sph = fopen($spiderFile, 'a');
$writtenAccess = 0;
$writtenSpider = 0;
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
        $writtenAccess++;
    }
    fclose($afh);
}
if ($sph) {
    $spar = max(1, intval($count / 4));
    for ($i=0;$i<$spar;$i++){
        $ts = strtotime($date) + rand(0, 86400 - 1);
        $t = date('Y-m-d H:i:s', $ts);
        $ip = $ips[array_rand($ips)];
        $bot = $bots[array_rand($bots)];
        $m = $methods[array_rand($methods)];
        $url = $urls[array_rand($urls)];
        fwrite($sph, implode("\t", [$t,$ip,$bot,$m,$url]) . "\n");
        $writtenSpider++;
    }
    fclose($sph);
}

echo "Wrote {$writtenAccess} access entries to {$accessFile}\n";
echo "Wrote {$writtenSpider} spider entries to {$spiderFile}\n";
return 0;
