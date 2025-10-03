<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

// Allow customizing the admin URL prefix via data/admin/config.json (key: "prefix") or env ADMIN_PATH
$adminPrefix = 'admin';
$adminCfgFile = base_path('data') . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'config.json';
if (getenv('ADMIN_PATH')) {
    $adminPrefix = trim(getenv('ADMIN_PATH')) ?: $adminPrefix;
} elseif (env('ADMIN_PATH')) {
    $adminPrefix = trim(env('ADMIN_PATH')) ?: $adminPrefix;
} elseif (file_exists($adminCfgFile)) {
    $raw = @file_get_contents($adminCfgFile);
    $decoded = @json_decode($raw, true) ?: [];
    if (!empty($decoded['prefix'])) {
        $adminPrefix = trim($decoded['prefix']);
    }
}

// Setup route will be rendered by MiddwareInit on first run. Provide a route to view it directly for testing.
Route::get('/setup', [AuthController::class, 'setup'])->name('setup');

// Login form
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');

// Login submit
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin dashboard (protected) and sub-pages
Route::get("/{$adminPrefix}", [AdminController::class, 'dashboard'])->middleware('admin.auth')->name('admin.dashboard');

Route::get("/{$adminPrefix}/settings", [AdminController::class, 'settings'])->middleware('admin.auth')->name('admin.settings');

// Save admin config (prefix)
Route::post("/{$adminPrefix}/settings", [AdminController::class, 'saveAdminConfig'])->middleware('admin.auth')->name('admin.settings.save');

Route::get("/{$adminPrefix}/password", [AdminController::class, 'passwordForm'])->middleware('admin.auth')->name('admin.password');

Route::post("/{$adminPrefix}/password", [AdminController::class, 'updatePassword'])->middleware('admin.auth')->name('admin.password.update');

Route::get("/{$adminPrefix}/logs", [AdminController::class, 'logs'])->middleware('admin.auth')->name('admin.logs');

Route::get("/{$adminPrefix}/operation-logs", [AdminController::class, 'operationLogs'])->middleware('admin.auth')->name('admin.operation_logs');

Route::get("/{$adminPrefix}/spiders", [AdminController::class, 'spiders'])->middleware('admin.auth')->name('admin.spiders');

Route::get("/{$adminPrefix}/repair", [AdminController::class, 'repair'])->middleware('admin.auth')->name('admin.repair');

Route::post("/{$adminPrefix}/repair", [AdminController::class, 'runRepair'])->middleware('admin.auth');

// Generate fake logs for testing
Route::post("/{$adminPrefix}/generate-fake-logs", [AdminController::class, 'generateFakeLogs'])->middleware('admin.auth')->name('admin.generate_fake_logs');

// 站群管理 - 网站管理
Route::get("/{$adminPrefix}/sites", [AdminController::class, 'sites'])->middleware('admin.auth')->name('admin.sites');

Route::post("/{$adminPrefix}/sites", [AdminController::class, 'saveSites'])->middleware('admin.auth')->name('admin.sites.save');
// Delete a group by name (AJAX)
Route::post("/{$adminPrefix}/sites/delete", [AdminController::class, 'deleteSite'])->middleware('admin.auth')->name('admin.sites.delete');
Route::post("/{$adminPrefix}/sites/restore", [AdminController::class, 'restoreSite'])->middleware('admin.auth')->name('admin.sites.restore');

// JSON endpoint for models list used by admin UI
Route::get("/{$adminPrefix}/models/list", [AdminController::class, 'modelsList'])->middleware('admin.auth')->name('admin.models.list');
// List site backups and accept JSON site saves (AJAX)
Route::get("/{$adminPrefix}/sites/backups", [AdminController::class, 'sitesBackups'])->middleware('admin.auth')->name('admin.sites.backups');
Route::get("/{$adminPrefix}/sites/json", [AdminController::class, 'sitesJson'])->middleware('admin.auth')->name('admin.sites.json');
Route::post("/{$adminPrefix}/sites/json", [AdminController::class, 'saveSitesJson'])->middleware('admin.auth')->name('admin.sites.json');

// 站群管理 - 模型管理
Route::get("/{$adminPrefix}/models", [AdminController::class, 'models'])->middleware('admin.auth')->name('admin.models');

Route::post("/{$adminPrefix}/models", [AdminController::class, 'saveModels'])->middleware('admin.auth')->name('admin.models.save');
// Delete a model by key (AJAX)
Route::post("/{$adminPrefix}/models/delete", [AdminController::class, 'deleteModel'])->middleware('admin.auth')->name('admin.models.delete');

// 描述管理 (每个分组的描述模板，支持变量如 {keyword}, {tips}, {ip}, {time})
Route::get("/{$adminPrefix}/sites/descriptions", [AdminController::class, 'showDescriptions'])->middleware('admin.auth')->name('admin.sites.descriptions');
Route::get("/{$adminPrefix}/sites/descriptions/json", [AdminController::class, 'descriptionsJson'])->middleware('admin.auth')->name('admin.sites.descriptions.json');
Route::post("/{$adminPrefix}/sites/descriptions/save", [AdminController::class, 'saveDescription'])->middleware('admin.auth')->name('admin.sites.descriptions.save');
Route::post("/{$adminPrefix}/sites/descriptions/delete", [AdminController::class, 'deleteDescription'])->middleware('admin.auth')->name('admin.sites.descriptions.delete');

// 访问控制 - IP 控制
Route::get("/{$adminPrefix}/access/ip", [AdminController::class, 'accessIp'])->middleware('admin.auth')->name('admin.access.ip');

Route::post("/{$adminPrefix}/access/ip", [AdminController::class, 'saveAccessIp'])->middleware('admin.auth')->name('admin.access.ip.save');

// 访问控制 - UA 控制
Route::get("/{$adminPrefix}/access/ua", [AdminController::class, 'accessUa'])->middleware('admin.auth')->name('admin.access.ua');

Route::post("/{$adminPrefix}/access/ua", [AdminController::class, 'saveAccessUa'])->middleware('admin.auth')->name('admin.access.ua.save');

// 内容管理
Route::get("/{$adminPrefix}/content/ai", [AdminController::class, 'showContentAi'])->middleware('admin.auth')->name('admin.content.ai');
Route::post("/{$adminPrefix}/content/ai", [AdminController::class, 'generateAiArticle'])->middleware('admin.auth')->name('admin.content.ai.generate');
Route::get("/{$adminPrefix}/content/manage", [AdminController::class, 'showContentManage'])->middleware('admin.auth')->name('admin.content.manage');
// Content resources (keywords/columns/tips/suffixes) management page
Route::get("/{$adminPrefix}/content/resources", [AdminController::class, 'showContentResources'])->middleware('admin.auth')->name('admin.content.resources');
Route::post("/{$adminPrefix}/content/resources/add", [AdminController::class, 'addContentResource'])->middleware('admin.auth')->name('admin.content.resources.add');
Route::get("/{$adminPrefix}/content/collection", [AdminController::class, 'showContentCollection'])->middleware('admin.auth')->name('admin.content.collection');

// Content file operations: list, upload, delete (per group)
Route::get("/{$adminPrefix}/content/files", [AdminController::class, 'contentFilesList'])->middleware('admin.auth')->name('admin.content.files');
Route::post("/{$adminPrefix}/content/upload", [AdminController::class, 'uploadContentFile'])->middleware('admin.auth')->name('admin.content.upload');
Route::post("/{$adminPrefix}/content/delete-file", [AdminController::class, 'deleteContentFile'])->middleware('admin.auth')->name('admin.content.delete_file');

// API 管理
Route::get("/{$adminPrefix}/api", [AdminController::class, 'api'])->middleware('admin.auth')->name('admin.api');
Route::post("/{$adminPrefix}/api", [AdminController::class, 'saveApiConfig'])->middleware('admin.auth')->name('admin.api.save');

// Public API endpoint to receive articles
use App\Http\Controllers\ArticleApiController;
Route::post('/api/articles', [ArticleApiController::class, 'receive']);

