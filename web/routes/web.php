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

// Setup route will be rendered by MiddwareInit on first run. Provide a route to view it directly for testing.
Route::get('/setup', [AuthController::class, 'setup'])->name('setup');

// Login form
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');

// Login submit
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin dashboard (protected) and sub-pages
Route::get('/admin', [AdminController::class, 'dashboard'])->middleware('admin.auth')->name('admin.dashboard');

Route::get('/admin/settings', [AdminController::class, 'settings'])->middleware('admin.auth')->name('admin.settings');

Route::get('/admin/password', [AdminController::class, 'passwordForm'])->middleware('admin.auth')->name('admin.password');

Route::post('/admin/password', [AdminController::class, 'updatePassword'])->middleware('admin.auth')->name('admin.password.update');

Route::get('/admin/logs', [AdminController::class, 'logs'])->middleware('admin.auth')->name('admin.logs');

Route::get('/admin/spiders', [AdminController::class, 'spiders'])->middleware('admin.auth')->name('admin.spiders');

Route::get('/admin/repair', [AdminController::class, 'repair'])->middleware('admin.auth')->name('admin.repair');

Route::post('/admin/repair', [AdminController::class, 'runRepair'])->middleware('admin.auth');

// Generate fake logs for testing
Route::post('/admin/generate-fake-logs', [AdminController::class, 'generateFakeLogs'])->middleware('admin.auth')->name('admin.generate_fake_logs');

// 站群管理 - 网站管理
Route::get('/admin/sites', [AdminController::class, 'sites'])->middleware('admin.auth')->name('admin.sites');

Route::post('/admin/sites', [AdminController::class, 'saveSites'])->middleware('admin.auth')->name('admin.sites.save');

// 站群管理 - 模型管理
Route::get('/admin/models', [AdminController::class, 'models'])->middleware('admin.auth')->name('admin.models');

Route::post('/admin/models', [AdminController::class, 'saveModels'])->middleware('admin.auth')->name('admin.models.save');

// 访问控制 - IP 控制
Route::get('/admin/access/ip', [AdminController::class, 'accessIp'])->middleware('admin.auth')->name('admin.access.ip');

Route::post('/admin/access/ip', [AdminController::class, 'saveAccessIp'])->middleware('admin.auth')->name('admin.access.ip.save');

// 访问控制 - UA 控制
Route::get('/admin/access/ua', [AdminController::class, 'accessUa'])->middleware('admin.auth')->name('admin.access.ua');

Route::post('/admin/access/ua', [AdminController::class, 'saveAccessUa'])->middleware('admin.auth')->name('admin.access.ua.save');

