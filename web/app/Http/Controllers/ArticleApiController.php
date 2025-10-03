<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleApiController extends Controller
{
    public function receive(Request $request)
    {
        // Load API config
        $cfgFile = base_path('data') . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'config.json';
        $cfg = [
            'enabled' => false,
            'endpoint' => '/api/articles',
            'key' => null,
        ];
        if (file_exists($cfgFile)) {
            $raw = file_get_contents($cfgFile);
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $cfg = array_merge($cfg, $decoded);
            }
        }

        if (empty($cfg['enabled'])) {
            return response()->json(['error' => 'API disabled'], 503);
        }

        // Simple auth: X-API-Key header or key field in payload
        $provided = $request->header('X-API-Key') ?: $request->input('key');
        if (empty($cfg['key']) || empty($provided) || !hash_equals((string)$cfg['key'], (string)$provided)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Optional IP whitelist
        if (!empty($cfg['allowed_ips']) && is_array($cfg['allowed_ips'])) {
            $ip = $request->ip();
            if (!in_array($ip, $cfg['allowed_ips'], true)) {
                return response()->json(['error' => 'IP not allowed'], 403);
            }
        }

    // Validate payload
    $data = $request->only(['title', 'content', 'source', 'publish', 'summary']);
        $title = isset($data['title']) ? trim((string)$data['title']) : '';
        $content = isset($data['content']) ? (string)$data['content'] : '';
    $summary = isset($data['summary']) ? (string)$data['summary'] : '';
        $source = isset($data['source']) ? (string)$data['source'] : 'api';
        $publish = isset($data['publish']) ? filter_var($data['publish'], FILTER_VALIDATE_BOOLEAN) : false;

        if ($title === '' || $content === '') {
            return response()->json(['error' => 'title and content are required'], 400);
        }

        // Prepare directories (use existing data/article structure)
        $base = base_path('data') . DIRECTORY_SEPARATOR . 'article';
        $publishedDir = $base . DIRECTORY_SEPARATOR . 'published';
        $unpublishedDir = $base . DIRECTORY_SEPARATOR . 'unpublished';
    if (!is_dir($publishedDir)) { @mkdir($publishedDir, 0755, true); }
    if (!is_dir($unpublishedDir)) { @mkdir($unpublishedDir, 0755, true); }

        $id = (string) Str::uuid();
        $now = date('Y-m-d H:i:s');
        $status = $publish ? 'published' : 'unpublished';

        $article = [
            'id' => $id,
            'title' => $title,
            'summary' => $summary,
            'content' => $content,
            'source' => $source,
            'status' => $status,
            'created_at' => $now,
        ];
        if ($publish) {
            $article['published_at'] = $now;
        }

        $targetDir = $publish ? $publishedDir : $unpublishedDir;
    $filePath = $targetDir . DIRECTORY_SEPARATOR . $id . '.json';
    if (!is_dir($targetDir)) { @mkdir($targetDir, 0755, true); }
    file_put_contents($filePath, json_encode($article, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return response()->json(['success' => true, 'id' => $id, 'status' => $status], 201);
    }
}
