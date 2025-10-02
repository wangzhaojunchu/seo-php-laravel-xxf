<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Dashboard</title>
    <style>
        .icon{width:16px;height:16px;display:inline-block;flex:0 0 16px}
        .section-toggle{display:flex;align-items:center;gap:8px;padding:6px 8px;border-radius:8px;cursor:pointer;color:var(--muted);margin-top:12px}
        .section-toggle:hover{background:rgba(0,0,0,0.02);color:var(--text)}
        .submenu.collapsed{display:none}
        .caret{margin-left:auto;transition:transform .15s}
        .caret.rotated{transform:rotate(90deg)}
        :root{--bg:#f6f8fa;--card:#ffffff;--accent:#2b6cb0;--muted:#6b7280;--text:#111;--sidebar:#fff}
        .theme-dark{--bg:#0b1220;--card:#0f1724;--accent:#60a5fa;--muted:#9aa6b2;--text:#e6eef8;--sidebar:#071026}
        .theme-light{--bg:#f6f8fa;--card:#ffffff;--accent:#2b6cb0;--muted:#6b7280;--text:#111;--sidebar:#ffffff}

        html,body{height:100%;margin:0;font-family:Inter,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:var(--bg);color:var(--text);transition:background .25s,color .25s}
        .wrap{display:flex;min-height:100vh}
    .sidebar{width:220px;background:var(--sidebar);border-right:1px solid rgba(0,0,0,0.07);padding:20px;transition:background .25s;flex:0 0 220px;min-width:220px}
        .brand{font-weight:700;margin-bottom:18px;font-size:18px}
        .nav{margin:12px 0}
        .nav a{display:block;padding:10px 12px;border-radius:8px;color:var(--text);text-decoration:none;margin-bottom:4px;transition:background .15s,color .15s}
        .nav a.active{background:var(--accent);color:#fff;font-weight:600}
        .submenu{margin-left:8px;padding-left:8px;border-left:1px dashed rgba(0,0,0,0.04)}
        .content{flex:1;padding:32px}
        .topbar{background:transparent}
    .panel{background:var(--card);border-radius:12px;padding:20px;box-shadow:0 8px 28px rgba(2,6,23,0.08);transition:background .25s,box-shadow .25s}
    .content-inner{overflow:auto}
    table{width:100%;border-collapse:collapse}
    table, table th, table td{border:1px solid rgba(0,0,0,0.06)}
    table th, table td{padding:8px}
        pre.log{background:rgba(2,6,23,0.6);color:#d6e6ff;padding:12px;border-radius:8px;overflow:auto;max-height:400px}
        .muted{color:var(--muted)}
        .btn{background:var(--accent);color:#fff;padding:8px 14px;border-radius:10px;border:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;height:38px;box-sizing:border-box;text-decoration:none;}
        form.inline{display:flex;gap:12px;align-items:center}
        form.inline > div{display:flex;flex-direction:column}
        label.small{display:block;font-size:13px;color:var(--muted);margin-bottom:6px}
        input[type="password"],input[type="text"]{padding:8px 12px;border:1px solid rgba(0,0,0,0.06);border-radius:8px;height:38px;box-sizing:border-box;background:transparent;color:var(--text)}
        @media (max-width:860px){.sidebar{display:none}.content{padding:16px}}
    .pager-btn{display:inline-flex;align-items:center;justify-content:center;padding:6px 10px;border-radius:6px;background:transparent;border:1px solid rgba(0,0,0,0.04);min-width:40px;height:34px;color:var(--text);text-decoration:none;font-size:13px}
        .pager-btn.disabled{opacity:0.45;pointer-events:none}
    table th, table td{font-size:13px}
    .muted{font-size:13px}
    .per-select{height:34px;border-radius:6px;padding:6px 8px;border:1px solid rgba(0,0,0,0.06);background:transparent;color:var(--text);font-size:13px}
    .bot-item{display:inline-flex;align-items:center;gap:8px;padding:6px 10px;background:rgba(0,0,0,0.04);border-radius:8px;font-size:13px;transition:background .2s,transform .1s;user-select:none}
    .bot-item:hover{background:rgba(0,0,0,0.07)}
    .bot-item .bot-action{text-decoration:none;font-weight:700;transition:transform .1s;display:inline-block}
    .bot-item .bot-action:hover{transform:scale(1.2)}
    .bot-item .bot-action:active{transform:scale(1.05)}
    .bot-icon{stroke:var(--muted);width:16px;height:16px}
    </style>
</head>
<body>
    <div class="wrap">
        <aside class="sidebar">
            <div class="brand">管理员面板</div>
            <nav class="nav">
                <a href="{{ route('admin.dashboard') }}" class="{{ ($page ?? '') === 'home' ? 'active' : '' }}">
                    <span class="icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 11.5L12 4l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V11.5z" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    管理员首页
                </a>

                <div class="section-toggle" data-section="core-settings" role="button" tabindex="0">
                    <span class="icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" stroke="currentColor" stroke-width="1.2"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a1 1 0 0 1-1.4 1.4l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.3V20a1 1 0 0 1-2 0v-.1a1.7 1.7 0 0 0-1-1.3 1.7 1.7 0 0 0-1.8.3l-.1.1A1 1 0 0 1 7.3 18l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.3-1H6a1 1 0 0 1 0-2h.1a1.7 1.7 0 0 0 1.3-1 1.7 1.7 0 0 0-.3-1.8L7 7.3A1 1 0 0 1 8.4 5.9l.1.1a1.7 1.7 0 0 0 1.8.3h.1A1.7 1.7 0 0 0 12 6.2V6a1 1 0 0 1 2 0v.2a1.7 1.7 0 0 0 1 1.3h.1a1.7 1.7 0 0 0 1.8-.3l.1-.1A1 1 0 0 1 18.6 7l-.1.1a1.7 1.7 0 0 0-.3 1.8c.2.5.6.9 1.1 1.1H19a1 1 0 0 1 0 2h-.1c-.5.2-.9.6-1.1 1.1z" stroke="currentColor" stroke-width="1"/></svg></span>
                    核心设置
                    <span class="caret" data-caret-for="core-settings">›</span>
                </div>
                <div class="submenu" data-section="core-settings">
                    <a href="{{ route('admin.settings') }}" class="{{ ($page ?? '') === 'settings' ? 'active' : '' }}"><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg></span>系统设置</a>
                    <a href="{{ route('admin.password') }}" class="{{ ($page ?? '') === 'password' ? 'active' : '' }}"><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l4-4 1-1 3 3-1 1-4 4-3 3H2z"/><path d="M15 4l-3 3 5 5 3-3-5-5z"/><path d="M9 15l-4.5 4.5"/></svg></span>修改密码</a>
                    <a href="{{ route('admin.logs') }}" class="{{ ($page ?? '') === 'logs' ? 'active' : '' }}"><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></span>操作日志</a>
                    <a href="{{ route('admin.repair') }}" class="{{ ($page ?? '') === 'repair' ? 'active' : '' }}"><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg></span>系统修复</a>
                </div>

                <div class="section-toggle" data-section="sites" role="button" tabindex="0">
                    <span class="icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 13h18M3 6h18M8 20h8" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    站群管理
                    <span class="caret" data-caret-for="sites">›</span>
                </div>
                <div class="submenu" data-section="sites">
                    <a href="{{ route('admin.sites') }}" class="{{ ($page ?? '') === 'sites' ? 'active' : '' }}"><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M20 17.58A5 5 0 0 0 15 8a5 5 0 0 0-9.35 2.12 5 5 0 0 0-1.05 9.88"/><line x1="3" y1="21" x2="21" y2="21"/></svg></span>网站管理</a>
                    <a href="{{ route('admin.models') }}" class="{{ ($page ?? '') === 'models' ? 'active' : '' }}"><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></span>模型管理</a>
                </div>

                <div class="section-toggle" data-section="content" role="button" tabindex="0">
                    <span class="icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="1.2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></span>
                    内容管理
                    <span class="caret" data-caret-for="content">›</span>
                </div>
                <div class="submenu" data-section="content">
                    <a href="{{ route('admin.content.ai') }}" class="{{ ($page ?? '') === 'content_ai' ? 'active' : '' }}"><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M17.5 19H9a7 7 0 1 1 0-14h8.5a5.5 5.5 0 1 1 0 11z"/></svg></span>AI 生成</a>
                    <a href="{{ route('admin.content.manage') }}" class="{{ ($page ?? '') === 'content_manage' ? 'active' : '' }}"><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/></svg></span>内容列表</a>
                    <a href="{{ route('admin.content.collection') }}" class="{{ ($page ?? '') === 'content_collection' ? 'active' : '' }}"><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M10 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/><path d="M12.5 19.5L10 22l-2.5-2.5"/><path d="M12.5 4.5L10 2 7.5 4.5"/><path d="M4.5 12.5L2 10l2.5-2.5"/><path d="M19.5 12.5L22 10l-2.5-2.5"/><circle cx="12" cy="12" r="8"/></svg></span>采集管理</a>
                </div>

                <div class="section-toggle" data-section="access" role="button" tabindex="0">
                    <span class="icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2v4M6 6v2a6 6 0 0 0 12 0V6" stroke="currentColor" stroke-width="1.2"/><rect x="3" y="10" width="18" height="11" rx="2" stroke="currentColor" stroke-width="1.2"/></svg></span>
                    访问控制
                    <span class="caret" data-caret-for="access">›</span>
                </div>
                <div class="submenu" data-section="access">
                    <a href="{{ route('admin.logs') }}" class="{{ ($page ?? '') === 'logs' ? 'active' : '' }}"><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg></span>访问记录</a>
                    <a href="{{ route('admin.spiders') }}" class="{{ ($page ?? '') === 'spiders' ? 'active' : '' }}"><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M10 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/><path d="M14 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/><path d="M18 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/><path d="M6 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/><path d="M15 21h-6"/><path d="M12 17v4"/><path d="M18.5 10.5l2.5-2.5"/><path d="M3 12.5l2.5 2.5"/><path d="M18.5 14.5l2.5 2.5"/><path d="M3 11.5l2.5-2.5"/><path d="M9.5 3.5l2.5 2.5"/><path d="M12 6V3"/></svg></span>蜘蛛记录</a>
                    <a href="{{ route('admin.access.ip') }}" class="{{ ($page ?? '') === 'access_ip' ? 'active' : '' }}"><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>访问IP控制</a>
                    <a href="{{ route('admin.access.ua') }}" class="{{ ($page ?? '') === 'access_ua' ? 'active' : '' }}"><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><polyline points="17 11 19 13 23 9"/></svg></span>访问UA控制</a>
                </div>
            </nav>
        </aside>
        <main class="content">
            <div class="topbar" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px">
                <div style="font-size:18px;font-weight:600">
                    @php
                        $titles = [
                            'home' => '管理员首页',
                            'settings' => '系统设置',
                            'password' => '修改密码',
                            'logs' => '访问日志',
                            'spiders' => '爬虫日志',
                            'repair' => '系统修复',
                            'sites' => '网站管理',
                            'models' => '模型管理',
                            'content_ai' => 'AI 生成设置',
                            'content_manage' => '内容列表',
                            'content_collection' => '采集管理',
                            'access_ip' => '访问 IP 控制',
                            'access_ua' => '访问 UA 控制',
                        ];
                    @endphp
                    {{ $titles[$page ?? 'home'] ?? ucfirst($page ?? 'Unknown') }}
                </div>
                <div style="display:flex;gap:12px;align-items:center">
                    <a class="btn" href="/" style="background:transparent;color:var(--muted);border:1px solid rgba(0,0,0,0.04);height:34px;padding:6px 10px">主页</a>
                    <div class="theme-toggle">
                        <label style="font-size:13px;color:var(--muted);margin-right:8px">主题</label>
                        <button id="themeBtn" class="btn" type="button">切换</button>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0">
                        @csrf
                        <button class="btn" type="submit">退出</button>
                    </form>
                </div>
            </div>
            <div class="panel">
                @if(($page ?? '') === 'home')
                    <h2>服务器信息</h2>
                    <table style="width:100%;border-collapse:collapse">
                        <tr><td class="muted" style="width:180px">PHP Version</td><td>{{ $serverInfo['php_version'] ?? 'n/a' }}</td></tr>
                        <tr><td class="muted">OS</td><td>{{ $serverInfo['os'] ?? 'n/a' }}</td></tr>
                        <tr><td class="muted">磁盘可用 / 总计</td><td>{{ isset($serverInfo['disk_free']) ? number_format($serverInfo['disk_free'] / 1024 / 1024, 2) . ' MB' : 'n/a' }} / {{ isset($serverInfo['disk_total']) ? number_format($serverInfo['disk_total'] / 1024 / 1024, 2) . ' MB' : 'n/a' }}</td></tr>
                        <tr><td class="muted">Uptime</td><td>{{ $serverInfo['uptime'] ?? 'n/a' }}</td></tr>
                        <tr><td class="muted">Memory (bytes)</td><td>{{ $serverInfo['memory_usage'] ?? 'n/a' }}</td></tr>
                    </table>

                    <div style="display:flex;gap:16px;margin-top:18px;flex-wrap:wrap">
                        <div style="flex:1;min-width:280px">
                            <h3>访问日志（最近 200 条）</h3>
                            @if(empty($accessLogs))
                                <p class="muted">暂无访问日志。</p>
                            @else
                                <pre class="log" style="max-height:220px">{{ $accessLogs }}</pre>
                            @endif
                        </div>

                        <div style="flex:1;min-width:280px">
                            <h3>爬虫日志（最近 200 条）</h3>
                            @if(empty($spiderLogs))
                                <p class="muted">暂无爬虫记录。</p>
                            @else
                                <pre class="log" style="max-height:220px">{{ $spiderLogs }}</pre>
                            @endif
                        </div>
                    </div>

                @elseif(($page ?? '') === 'settings')
                    <h2>系统设置</h2>
                    <p class="muted">系统设置占位。这里可以放置站点配置、环境检查、缓存设置等。</p>

                @elseif(($page ?? '') === 'sites')
                    <h2>网站管理</h2>
                    @if(session('status'))<div style="background:#ecffed;padding:8px;border-radius:6px;margin-bottom:12px">{{ session('status') }}</div>@endif
                    <form method="POST" action="{{ route('admin.sites.save') }}">
                        @csrf
                        <label class="small">每行一个域名或站点地址</label>
                        <textarea name="sites" style="width:100%;min-height:160px;border:1px solid rgba(0,0,0,0.06);padding:8px;border-radius:8px">{{ isset($sitesList) ? implode("\n", $sitesList) : '' }}</textarea>
                        <div style="margin-top:8px"><button class="btn" type="submit">保存</button></div>
                    </form>

                @elseif(($page ?? '') === 'models')
                    <h2>模型管理</h2>
                    @if(session('status'))<div style="background:#ecffed;padding:8px;border-radius:6px;margin-bottom:12px">{{ session('status') }}</div>@endif
                    <form method="POST" action="{{ route('admin.models.save') }}">
                        @csrf
                        <label class="small">每行一个模型键或名称</label>
                        <textarea name="models" style="width:100%;min-height:160px;border:1px solid rgba(0,0,0,0.06);padding:8px;border-radius:8px">{{ isset($modelsList) ? implode("\n", $modelsList) : '' }}</textarea>
                        <div style="margin-top:8px"><button class="btn" type="submit">保存</button></div>
                    </form>

                @elseif(($page ?? '') === 'access_ip')
                    <h2>访问 IP 控制</h2>
                    @if(session('status'))<div style="background:#ecffed;padding:8px;border-radius:6px;margin-bottom:12px">{{ session('status') }}</div>@endif
                    
                    <div class="muted" style="margin-bottom: 16px; padding: 12px; background: rgba(0,0,0,0.02); border-radius: 8px; line-height: 1.6;">
                        <strong>逻辑说明:</strong><br>
                        1. <strong>白名单优先:</strong> 如果“允许访问的IP”列表不为空，则只有列表中的IP可以访问。此模式下，黑名单将被忽略。<br>
                        2. <strong>黑名单模式:</strong> 如果“允许访问的IP”列表为空，则“禁止访问的IP”列表生效，列表中的IP将被阻止。<br>
                        3. <strong>全部允许:</strong> 如果两个列表都为空，则允许所有IP访问。
                    </div>

                    <form method="POST" action="{{ route('admin.access.ip.save') }}">
                        @csrf
                        <div style="margin-top:8px">
                            <label class="small">允许访问的IP (白名单) - 每行一个 (例如 192.168.1.10, 10.0.0.0/8, 或 192.168.1.10-192.168.1.13)</label>
                            <textarea name="allow_ips" style="width:100%;min-height:120px;border:1px solid rgba(0,0,0,0.06);padding:8px;border-radius:8px">{{ isset($ipAllow) ? implode("\n", $ipAllow) : '' }}</textarea>
                        </div>

                        <div style="margin-top:8px">
                            <label class="small">禁止访问的IP (黑名单) - 仅在上方白名单为空时生效</label>
                            <textarea name="deny_ips" style="width:100%;min-height:120px;border:1px solid rgba(0,0,0,0.06);padding:8px;border-radius:8px">{{ isset($ipDeny) ? implode("\n", $ipDeny) : '' }}</textarea>
                        </div>

                        <div style="margin-top:8px"><button class="btn" type="submit">保存</button></div>
                    </form>

                @elseif(($page ?? '') === 'access_ua')
                    <h2>访问 UA 控制</h2>
                    @if(session('status'))<div style="background:#ecffed;padding:8px;border-radius:6px;margin-bottom:12px">{{ session('status') }}</div>@endif

                    <div class="muted" style="margin-bottom: 16px; padding: 12px; background: rgba(0,0,0,0.02); border-radius: 8px; line-height: 1.6;">
                        <strong>逻辑说明:</strong><br>
                        1. <strong>白名单优先:</strong> 如果“允许访问的UA”列表不为空，则只有UA包含列表中任意关键词的请求才被允许。<br>
                        2. <strong>黑名单模式:</strong> 如果白名单为空，则UA包含“禁止访问的UA”列表中任意关键词的请求将被阻止。<br>
                        3. <strong>全部允许:</strong> 如果两个列表都为空，则允许所有请求。
                    </div>

                    @php
                    function get_bot_svg_icon($botName) {
                        $botName = strtolower($botName);
                        $svg_icon = '';

                        if (str_contains($botName, 'google')) {
                            $svg_icon = '<svg class="bot-icon" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="#34A853"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20z"/><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/></svg>';
                        } elseif (str_contains($botName, 'bing')) {
                            $svg_icon = '<svg class="bot-icon" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="#008373"><path d="M12 12l-8-4v10l8 4 8-4V8l-8 4z"/><path d="M12 12V2l8 4"/><path d="M4 8l8 4"/></svg>';
                        } elseif (str_contains($botName, 'baidu')) {
                            $svg_icon = '<svg class="bot-icon" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="#2932E1"><path d="M14.5 10.5c-1.5-1.5-3.5-1.5-5 0s-1.5 3.5 0 5c.5.5 1.2.8 1.9.9"/><path d="M12 12a4.5 4.5 0 0 0 4.5-4.5c0-2.5-2-4.5-4.5-4.5S7.5 5 7.5 7.5"/><path d="M14.5 10.5a2.5 2.5 0 0 1-2.5 2.5 2.5 2.5 0 0 1-2.5-2.5"/><path d="M5 18c-1.7-1.7-2-4.3-.8-6.5s3.3-3.8 5.8-3.8"/></svg>';
                        } else {
                            $svg_icon = '<svg class="bot-icon" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="currentColor"><path d="M15 9a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path d="M2.5 12.5a1 1 0 0 1 1-1h17a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1h-17a1 1 0 0 1-1-1v-6z"/><path d="M9 11.5V9.75M15 11.5V9.75M6.5 15.5h1M16.5 15.5h1" stroke-linecap="round"/></svg>';
                        }
                        return $svg_icon;
                    }
                    @endphp

                    <form method="POST" action="{{ route('admin.access.ua.save') }}">
                        @csrf
                        <div style="margin-top:8px">
                            <label class="small">允许访问的UA (白名单) - 每行一个, UA包含其一即可</label>
                            <textarea name="allow_uas" id="allow_uas_textarea" style="width:100%;min-height:120px;border:1px solid rgba(0,0,0,0.06);padding:8px;border-radius:8px">{{ isset($uaAllow) ? implode("\n", $uaAllow) : '' }}</textarea>
                        </div>

                        <div style="margin-top:8px">
                            <label class="small">禁止访问的UA (黑名单) - 仅在上方白名单为空时生效</label>
                            <textarea name="deny_uas" id="deny_uas_textarea" style="width:100%;min-height:120px;border:1px solid rgba(0,0,0,0.06);padding:8px;border-radius:8px">{{ isset($uaDeny) ? implode("\n", $uaDeny) : '' }}</textarea>
                        </div>

                        <div style="margin-top:16px;">
                            <label class="small">快速添加常见蜘蛛</label>
                            <div id="common-bots-list" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px;">
                                @php
                                    $commonBots = ['Googlebot', 'Bingbot', 'Baiduspider', 'YandexBot', 'AhrefsBot', 'SemrushBot', 'DotBot', 'MJ12bot', 'Sogou', 'Bytespider'];
                                @endphp
                                @foreach($commonBots as $bot)
                                    <div class="bot-item">
                                        {!! get_bot_svg_icon($bot) !!}
                                        <span class="bot-name">{{ $bot }}</span>
                                        <a href="#" class="bot-action" data-action="allow" data-bot="{{ $bot }}" style="color: #28a745;" title="添加到白名单">[+]</a>
                                        <a href="#" class="bot-action" data-action="deny" data-bot="{{ $bot }}" style="color: #dc3545;" title="添加到黑名单">[-]</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div style="margin-top:16px"><button class="btn" type="submit">保存</button></div>
                    </form>

                    <script>
                        (function() {
                            const allowTextarea = document.getElementById('allow_uas_textarea');
                            const denyTextarea = document.getElementById('deny_uas_textarea');
                            if (!allowTextarea || !denyTextarea) return;

                            document.querySelectorAll('#common-bots-list .bot-action').forEach(actionLink => {
                                actionLink.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    const botName = this.getAttribute('data-bot');
                                    const action = this.getAttribute('data-action');
                                    const targetTextarea = (action === 'allow') ? allowTextarea : denyTextarea;
                                    
                                    const currentValue = targetTextarea.value.trim();
                                    let lines = currentValue.split('\n').map(l => l.trim()).filter(l => l !== '');

                                    if (lines.includes(botName)) {
                                        lines = lines.filter(l => l !== botName);
                                        targetTextarea.value = lines.join('\n');
                                    } else {
                                        lines.push(botName);
                                        targetTextarea.value = lines.join('\n');
                                    }
                                });
                            });
                        })();
                    </script>

                @elseif(($page ?? '') === 'password')
                    <h2>修改密码</h2>
                    @if(session('status'))<div style="background:#ecffed;padding:8px;border-radius:6px;margin-bottom:12px">{{ session('status') }}</div>@endif
                    @if($errors->any())<div style="background:#fff6f6;padding:8px;border-radius:6px;margin-bottom:12px;color:#9b1c1c">{{ $errors->first() }}</div>@endif
                    <form method="POST" action="{{ route('admin.password.update') }}" class="inline">
                        @csrf
                        <div>
                            <label class="small">新密码</label>
                            <input type="password" name="new_password" required>
                        </div>
                        <div>
                            <button class="btn" type="submit">更新</button>
                        </div>
                    </form>

                @elseif(($page ?? '') === 'logs')
                    <h2>访问日志</h2>
                    <form method="GET" action="{{ route('admin.logs') }}" style="display:flex;gap:8px;align-items:center;margin-bottom:12px;flex-wrap:wrap">
                        <label class="small">从 <input type="date" name="from" value="{{ $from ?? ($date ?? date('Y-m-d')) }}"></label>
                        <label class="small">到 <input type="date" name="to" value="{{ $to ?? ($date ?? date('Y-m-d')) }}"></label>
                        <label class="small">IP <input type="text" name="ip" value="{{ $ip ?? '' }}" placeholder="部分或完整 IP"></label>
                        <label class="small">搜索 <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="URL 或 IP"></label>
                        <label class="small">每页 <input type="number" name="per_page" value="{{ $per ?? 50 }}" min="10" max="200" style="width:80px"></label>
                        <div style="margin-left:auto;display:flex;gap:8px">
                            <button class="btn" type="submit">筛选</button>
                            <a class="btn" href="{{ route('admin.logs') }}?from={{ $from ?? ($date ?? date('Y-m-d')) }}&to={{ $to ?? ($date ?? date('Y-m-d')) }}&download=access">下载 CSV</a>
                        </div>
                    </form>

                    @if($errors->any())
                        <div style="background:#fff6f6;padding:8px;border-radius:6px;margin-bottom:12px;color:#9b1c1c">{{ $errors->first() }}</div>
                    @endif
                    @if(isset($chartCounts) && is_array($chartCounts))
                        <div style="margin-bottom:12px;display:flex;align-items:center;gap:12px">
                            <div style="flex:1">
                                <canvas id="logs-chart" height="80" data-labels='{{ json_encode($chartLabels ?? []) }}' data-counts='{{ json_encode($chartCounts ?? []) }}'></canvas>
                            </div>
                            <div style="min-width:160px;text-align:right;color:var(--muted)">总访问：<strong>{{ $total ?? 0 }}</strong></div>
                        </div>
                    @endif

                    @if(empty($logs))
                        <p class="muted">没有匹配的日志。</p>
                    @else
                        <div class="content-inner">
                        <table>
                            <thead>
                                <tr style="text-align:left">
                                    <th style="width:64px">序号</th>
                                    <th>时间</th>
                                    <th>IP</th>
                                    <th>方法</th>
                                    <th>状态</th>
                                    <th>URL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $startIndex = (($currentPage ?? 1)-1) * ($per ?? 20); @endphp
                                @foreach($logs as $row)
                                    <tr>
                                        <td>{{ $loop->index + 1 + $startIndex }}</td>
                                        <td>{{ $row['datetime'] }}</td>
                                        <td>{{ $row['ip'] }}</td>
                                        <td>{{ $row['method'] }}</td>
                                        <td>{{ $row['status'] }}</td>
                                        <td>{{ $row['url'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>

                        <div style="margin-top:12px;display:flex;align-items:center;gap:8px">
                            <div class="muted">共 {{ $total }} 条结果</div>
                            <div style="flex:1;text-align:center">
                                @php
                                    $cp = $currentPage ?? 1;
                                    $maxLinks = 10;
                                    $start = max(1, $cp - intval($maxLinks/2));
                                    $end = min($pages, $start + $maxLinks - 1);
                                    $start = max(1, $end - $maxLinks + 1);
                                @endphp
                                <table style="margin:0 auto;border-collapse:collapse"><tr>
                                    <td style="padding:4px"><a class="pager-btn" href="?from={{ $from }}&to={{ $to }}&ip={{ $ip }}&q={{ $q }}&page=1&per_page={{ $per }}">« 首页</a></td>
                                    <td style="padding:4px">
                                        @if($cp>1)
                                            <a class="pager-btn" href="?from={{ $from }}&to={{ $to }}&ip={{ $ip }}&q={{ $q }}&page={{ $cp-1 }}&per_page={{ $per }}">‹ 上一页</a>
                                        @else
                                            <span class="pager-btn disabled">‹ 上一页</span>
                                        @endif
                                    </td>
                                    @for($p=$start;$p<=$end;$p++)
                                        <td style="padding:4px">
                                            <a class="pager-btn" href="?from={{ $from }}&to={{ $to }}&ip={{ $ip }}&q={{ $q }}&page={{ $p }}&per_page={{ $per }}" style="background:{{ $p == $cp ? 'rgba(0,0,0,0.06)' : 'transparent' }}">{{ $p }}</a>
                                        </td>
                                    @endfor
                                    <td style="padding:4px">
                                        @if($cp<$pages)
                                            <a class="pager-btn" href="?from={{ $from }}&to={{ $to }}&ip={{ $ip }}&q={{ $q }}&page={{ $cp+1 }}&per_page={{ $per }}">下一页 ›</a>
                                        @else
                                            <span class="pager-btn disabled">下一页 ›</span>
                                        @endif
                                    </td>
                                    <td style="padding:4px"><a class="pager-btn" href="?from={{ $from }}&to={{ $to }}&ip={{ $ip }}&q={{ $q }}&page={{ $pages }}&per_page={{ $per }}">尾页 »</a></td>
                                </tr></table>
                            </div>
                            <div style="width:160px;text-align:right;">
                                <form method="GET" style="display:inline-block">
                                    <input type="hidden" name="from" value="{{ $from }}">
                                    <input type="hidden" name="to" value="{{ $to }}">
                                    <input type="hidden" name="ip" value="{{ $ip ?? '' }}">
                                    <input type="hidden" name="q" value="{{ $q ?? '' }}">
                                    <input type="hidden" name="page" value="1">
                                    <select name="per_page" class="per-select" onchange="this.form.submit()">
                                        <option value="20" {{ ($per ?? 20) == 20 ? 'selected' : '' }}>每页 20</option>
                                        <option value="50" {{ ($per ?? 20) == 50 ? 'selected' : '' }}>每页 50</option>
                                        <option value="100" {{ ($per ?? 20) == 100 ? 'selected' : '' }}>每页 100</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    @endif

                @elseif(($page ?? '') === 'spiders')
                    <h2>蜘蛛记录</h2>
                    <form method="GET" action="{{ route('admin.spiders') }}" style="display:flex;gap:8px;align-items:center;margin-bottom:12px;flex-wrap:wrap">
                        <label class="small">从 <input type="date" name="from" value="{{ $from ?? ($date ?? date('Y-m-d')) }}"></label>
                        <label class="small">到 <input type="date" name="to" value="{{ $to ?? ($date ?? date('Y-m-d')) }}"></label>
                        <label class="small">IP <input type="text" name="ip" value="{{ $ip ?? '' }}" placeholder="部分或完整 IP"></label>
                        <label class="small">爬虫 <input type="text" name="bot" value="{{ $bot ?? '' }}" placeholder="部分 UA 名称"></label>
                        <label class="small">每页 <input type="number" name="per_page" value="{{ $per ?? 50 }}" min="10" max="200" style="width:80px"></label>
                        <div style="margin-left:auto;display:flex;gap:8px">
                            <button class="btn" type="submit">筛选</button>
                            <a class="btn" href="{{ route('admin.spiders') }}?from={{ $from ?? ($date ?? date('Y-m-d')) }}&to={{ $to ?? ($date ?? date('Y-m-d')) }}&download=spider">下载 CSV</a>
                        </div>
                    </form>

                    @if($errors->any())
                        <div style="background:#fff6f6;padding:8px;border-radius:6px;margin-bottom:12px;color:#9b1c1c">{{ $errors->first() }}</div>
                    @endif
                    @if(isset($chartCounts) && is_array($chartCounts))
                        <div style="margin-bottom:12px;display:flex;align-items:center;gap:12px">
                            <div style="flex:1">
                                <canvas id="spiders-chart" height="80" data-labels='{{ json_encode($chartLabels ?? []) }}' data-counts='{{ json_encode($chartCounts ?? []) }}'></canvas>
                            </div>
                            <div style="min-width:160px;text-align:right;color:var(--muted)">总爬虫记录：<strong>{{ $total ?? 0 }}</strong></div>
                        </div>
                    @endif

                    @if(empty($spiderLogs))
                        <p class="muted">没有匹配的爬虫记录。</p>
                    @else
                        <div class="content-inner">
                        <table>
                            <thead>
                                <tr style="text-align:left">
                                    <th style="width:64px">序号</th>
                                    <th>时间</th>
                                    <th>IP</th>
                                    <th>爬虫</th>
                                    <th>方法</th>
                                    <th>URL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $startIndex = (($currentPage ?? 1)-1) * ($per ?? 20); @endphp
                                @foreach($spiderLogs as $row)
                                    <tr>
                                        <td>{{ $loop->index + 1 + $startIndex }}</td>
                                        <td>{{ $row['datetime'] }}</td>
                                        <td>{{ $row['ip'] }}</td>
                                        <td>{{ $row['bot'] }}</td>
                                        <td>{{ $row['method'] }}</td>
                                        <td>{{ $row['url'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>

                        <div style="margin-top:12px;display:flex;align-items:center;gap:8px">
                            <div class="muted">共 {{ $total }} 条结果</div>
                            <div style="flex:1;text-align:center">
                                @php
                                    $cp = $currentPage ?? 1;
                                    $maxLinks = 10;
                                    $start = max(1, $cp - intval($maxLinks/2));
                                    $end = min($pages, $start + $maxLinks - 1);
                                    $start = max(1, $end - $maxLinks + 1);
                                @endphp
                                <table style="margin:0 auto;border-collapse:collapse"><tr>
                                    <td style="padding:4px"><a class="pager-btn" href="?from={{ $from }}&to={{ $to }}&bot={{ $bot }}&ip={{ $ip }}&page=1&per_page={{ $per }}">« 首页</a></td>
                                    <td style="padding:4px">
                                        @if($cp>1)
                                            <a class="pager-btn" href="?from={{ $from }}&to={{ $to }}&bot={{ $bot }}&ip={{ $ip }}&page={{ $cp-1 }}&per_page={{ $per }}">‹ 上一页</a>
                                        @else
                                            <span class="pager-btn disabled">‹ 上一页</span>
                                        @endif
                                    </td>
                                    @for($p=$start;$p<=$end;$p++)
                                        <td style="padding:4px">
                                            <a class="pager-btn" href="?from={{ $from }}&to={{ $to }}&bot={{ $bot }}&ip={{ $ip }}&page={{ $p }}&per_page={{ $per }}" style="background:{{ $p == $cp ? 'rgba(0,0,0,0.06)' : 'transparent' }}">{{ $p }}</a>
                                        </td>
                                    @endfor
                                    <td style="padding:4px">
                                        @if($cp<$pages)
                                            <a class="pager-btn" href="?from={{ $from }}&to={{ $to }}&bot={{ $bot }}&ip={{ $ip }}&page={{ $cp+1 }}&per_page={{ $per }}">下一页 ›</a>
                                        @else
                                            <span class="pager-btn disabled">下一页 ›</span>
                                        @endif
                                    </td>
                                    <td style="padding:4px"><a class="pager-btn" href="?from={{ $from }}&to={{ $to }}&bot={{ $bot }}&ip={{ $ip }}&page={{ $pages }}&per_page={{ $per }}">尾页 »</a></td>
                                </tr></table>
                            </div>
                            <div style="width:160px;text-align:right;">
                                <form method="GET" style="display:inline-block">
                                    <input type="hidden" name="from" value="{{ $from }}">
                                    <input type="hidden" name="to" value="{{ $to }}">
                                    <input type="hidden" name="ip" value="{{ $ip ?? '' }}">
                                    <input type="hidden" name="bot" value="{{ $bot ?? '' }}">
                                    <input type="hidden" name="q" value="{{ $q ?? '' }}">
                                    <input type="hidden" name="page" value="1">
                                    <select name="per_page" class="per-select" onchange="this.form.submit()">
                                        <option value="20" {{ ($per ?? 20) == 20 ? 'selected' : '' }}>每页 20</option>
                                        <option value="50" {{ ($per ?? 20) == 50 ? 'selected' : '' }}>每页 50</option>
                                        <option value="100" {{ ($per ?? 20) == 100 ? 'selected' : '' }}>每页 100</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    @endif

                @elseif(($page ?? '') === 'repair')
                    <h2>系统修复</h2>
                    <p class="muted">提供一组便利操作用于修复常见问题。</p>
                    <form method="POST" action="{{ route('admin.repair') }}">
                        @csrf
                        <div style="display:flex;gap:8px;flex-wrap:wrap">
                            <button class="btn" type="submit" name="action" value="cache-clear">清除缓存</button>
                            <button class="btn" type="submit" name="action" value="config-cache">重建配置缓存</button>
                        </div>
                    </form>
                    <hr style="margin:14px 0;border:none;border-top:1px solid rgba(0,0,0,0.06)">
                    <h3>生成测试日志</h3>
                    <p class="muted">在开发或调试时可以生成假访问记录和爬虫记录以测试日志查看与筛选功能。</p>
                    <form method="POST" action="{{ route('admin.generate_fake_logs') }}" class="inline" style="margin-top:8px">
                        @csrf
                        <div>
                            <label class="small">日期</label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="small">数量</label>
                            <input type="number" name="count" value="200" min="1" max="1000" style="width:110px">
                        </div>
                        <div>
                            <button class="btn" type="submit">生成假日志</button>
                        </div>
                    </form>
                    @if(!empty($repairResult))
                        <h3 style="margin-top:12px">结果</h3>
                        <pre style="background:#0f1724;color:#d6e6ff;padding:12px;border-radius:8px;white-space:pre-wrap;">{{ $repairResult }}</pre>
                    @endif

                @elseif(($page ?? '') === 'content_ai')
                    <h2>AI 生成设置</h2>
                    <p class="muted">配置用于生成文章的AI服务，并在此处生成新内容。</p>
                    
                    <h3 style="margin-top:24px;">生成新文章</h3>
                    <form method="POST" action="{{ route('admin.content.ai.generate') }}" id="ai-generation-form">
                        @csrf
                        <div style="margin-top:8px;">
                            <label class="small">文章主题 / 关键词</label>
                            <input type="text" name="topic" placeholder="例如：2025年最新的SEO技巧" style="width:100%;">
                        </div>
                        <div style="margin-top:16px;">
                            <button class="btn" type="submit">生成文章</button>
                        </div>
                    </form>

                    <hr style="margin:24px 0;border:none;border-top:1px solid rgba(0,0,0,0.06)">

                    <h3>AI 服务设置</h3>
                    <form method="POST" action="#">
                        @csrf
                        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                            <div>
                                <label class="small">AI 提供商</label>
                                <select name="ai_provider" id="ai_provider_select" class="per-select" style="width:100%;">
                                    <option value="openai">OpenAI</option>
                                    <option value="gemini">Google Gemini</option>
                                    <option value="azure">Azure OpenAI</option>
                                </select>
                            </div>
                            <div>
                                <label class="small">API 密钥 (API Key)</label>
                                <input type="password" name="api_key" value="" placeholder="请输入您的API密钥" style="width:100%;">
                            </div>
                            <div>
                                <label class="small">模型选择</label>
                                <select name="model" id="ai_model_select" class="per-select" style="width:100%;"></select>
                            </div>
                        </div>
                        <div style="margin-top:16px;">
                            <label class="small">系统指令 / 模板 (可用变量: {topic})</label>
                            <textarea name="default_prompt" style="width:100%;min-height:120px;border:1px solid rgba(0,0,0,0.06);padding:8px;border-radius:8px" placeholder="例如：请以 `{topic}` 为主题，撰写一篇关于SEO优化的文章，要求..."></textarea>
                        </div>
                        <div style="margin-top:16px"><button class="btn" type="submit">保存设置</button></div>
                    </form>

                    <script>
                        (function() {
                            const providerSelect = document.getElementById('ai_provider_select');
                            const modelSelect = document.getElementById('ai_model_select');

                            const modelsByProvider = {
                                openai: ['gpt-4-turbo', 'gpt-4o', 'gpt-4', 'gpt-3.5-turbo'],
                                gemini: ['gemini-1.5-pro-latest', 'gemini-1.5-flash-latest', 'gemini-1.0-pro'],
                                azure: ['YOUR-DEPLOYMENT-NAME']
                            };

                            function updateModels() {
                                const selectedProvider = providerSelect.value;
                                const models = modelsByProvider[selectedProvider] || [];
                                
                                // Clear current options
                                modelSelect.innerHTML = '';

                                // Add new options
                                models.forEach(model => {
                                    const option = document.createElement('option');
                                    option.value = model;
                                    option.textContent = model;
                                    modelSelect.appendChild(option);
                                });
                            }

                            // Initial population
                            updateModels();

                            // Update on change
                            providerSelect.addEventListener('change', updateModels);
                        })();
                    </script>

                @elseif(($page ?? '') === 'content_collection')
                    <h2>采集管理</h2>
                    <p class="muted">管理内容采集规则和外部API。</p>
                    
                    <h3 style="margin-top:24px;">采集规则</h3>
                    <form method="POST" action="#">
                        @csrf
                        <p class="muted">此部分用于配置定时或手动触发的采集任务。 (后端功能待实现)</p>
                        <!-- 采集规则配置 -->
                    </form>

                    <hr style="margin:24px 0;border:none;border-top:1px solid rgba(0,0,0,0.06)">

                    <h3>文章上传 API</h3>
                     <form method="POST" action="#">
                        @csrf
                        <div style="display:flex; align-items:center; gap:16px;">
                            <label class="small">API 状态</label>
                            <div>
                                <label class="switch">
                                  <input type="checkbox" name="api_enabled" checked>
                                  <span class="slider round"></span>
                                </label>
                            </div>
                            <span class="muted">开启后，允许通过API向系统上传文章。</span>
                        </div>
                        <div style="margin-top:16px;">
                            <label class="small">API 端点 URL</label>
                            <input type="text" readonly value="{{ url('/api/articles') }}" style="width:100%; background: rgba(0,0,0,0.02);">
                        </div>
                        <div style="margin-top:16px;">
                            <label class="small">API 密钥</label>
                            <div style="display:flex; gap:12px;">
                                <input type="text" readonly value="**************" style="width:100%; background: rgba(0,0,0,0.02);">
                                <button class="btn" type="button">显示/隐藏</button>
                                <button class="btn" type="button">重新生成</button>
                            </div>
                        </div>
                        <div style="margin-top:16px"><button class="btn" type="submit">保存 API 设置</button></div>
                    </form>

                @elseif(($page ?? '') === 'content_manage')
                    <h2>内容列表</h2>
                    <form method="GET" action="#" style="display:flex;gap:12px;align-items:center;margin-bottom:16px;flex-wrap:wrap">
                        <label class="small">从 <input type="date" name="from" value=""></label>
                        <label class="small">到 <input type="date" name="to" value=""></label>
                        <select name="source" class="per-select">
                            <option value="">所有来源</option>
                            <option value="ai">AI 生成</option>
                            <option value="crawled">采集</option>
                            <option value="api">API上传</option>
                        </select>
                        <select name="status" class="per-select">
                            <option value="">所有状态</option>
                            <option value="published">已发布</option>
                            <option value="unpublished">未发布</option>
                        </select>
                        <button class="btn" type="submit">筛选</button>
                    </form>

                    <div class="content-inner">
                        <table>
                            <thead>
                                <tr style="text-align:left">
                                    <th style="width:40px"><input type="checkbox"></th>
                                    <th>标题</th>
                                    <th>来源</th>
                                    <th>状态</th>
                                    <th>创建日期</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i=1; $i<=10; $i++)
                                <tr>
                                    <td><input type="checkbox"></td>
                                    <td>示例文章标题 {{ $i }} - 如何提升网站排名</td>
                                    <td><span style="background: #eef; padding: 2px 6px; border-radius: 4px; font-size: 12px;">AI 生成</span></td>
                                    <td><span style="color: #28a745;">已发布</span></td>
                                    <td>2025-10-0{{ $i }}</td>
                                    <td>
                                        <a href="#" style="color:var(--accent); font-size:13px;">编辑</a>
                                        <a href="#" style="color:#dc3545; font-size:13px; margin-left:8px;">删除</a>
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top:12px;display:flex;align-items:center;gap:8px">
                        <div class="muted">共 35 条结果</div>
                        <div style="flex:1;text-align:center">
                            <a class="pager-btn disabled" href="#">‹ 上一页</a>
                            <a class="pager-btn" href="#" style="background:rgba(0,0,0,0.06)">1</a>
                            <a class="pager-btn" href="#">2</a>
                            <a class="pager-btn" href="#">3</a>
                            <a class="pager-btn" href="#">4</a>
                            <a class="pager-btn" href="#">下一页 ›</a>
                        </div>
                    </div>

                @else
                    <h2>Unknow</h2>
                @endif
            </div>
        </main>
    </div>
    <script>
        (function(){
            const root = document.documentElement;
            const btn = document.getElementById('themeBtn');
            const saved = localStorage.getItem('admin-theme') || 'light';
            function apply(t){
                if(t === 'dark'){
                    root.classList.add('theme-dark');
                    root.classList.remove('theme-light');
                    btn.textContent = '夜间';
                } else {
                    root.classList.add('theme-light');
                    root.classList.remove('theme-dark');
                    btn.textContent = '白天';
                }
                localStorage.setItem('admin-theme', t);
            }
            apply(saved);
            btn.addEventListener('click', ()=>{
                apply((localStorage.getItem('admin-theme')==='dark') ? 'light' : 'dark');
            });
        })();
    </script>
    <script>
        (function(){
            function setCollapsed(name, collapsed){
                localStorage.setItem('admin-section-' + name, collapsed ? '1' : '0');
            }
            function isCollapsed(name){
                return localStorage.getItem('admin-section-' + name) === '1';
            }

            document.querySelectorAll('.section-toggle').forEach(function(el){
                var section = el.getAttribute('data-section');
                var submenu = document.querySelector('.submenu[data-section="' + section + '"]');
                var caret = el.querySelector('.caret');
                if(!submenu) return;
                // initial state
                if(isCollapsed(section)){
                    submenu.classList.add('collapsed');
                    caret.classList.add('rotated');
                }
                function toggle(){
                    var now = submenu.classList.toggle('collapsed');
                    caret.classList.toggle('rotated');
                    setCollapsed(section, now);
                }
                el.addEventListener('click', toggle);
                el.addEventListener('keypress', function(e){ if(e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(); } });
            });
        })();
    </script>

    <!-- Chart.js from CDN for lightweight interactive charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        function initChart(canvasId, color){
            var c = document.getElementById(canvasId);
            if(!c) return;
            try{
                var labels = JSON.parse(c.getAttribute('data-labels') || '[]');
                var counts = JSON.parse(c.getAttribute('data-counts') || '[]');
            }catch(e){ return; }
            var ctx = c.getContext('2d');
            // destroy existing chart if present
            if (c._chartInstance) {
                c._chartInstance.destroy();
            }
            c._chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '',
                        data: counts,
                        borderColor: color || '#0b84ff',
                        backgroundColor: (color || '#0b84ff'),
                        fill: false,
                        tension: 0.3,
                        pointRadius: 2,
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { x: { display: false }, y: { display: true, ticks: { beginAtZero: true } } }
                }
            });
        }
        window.addEventListener('load', function(){ initChart('logs-chart','#0b84ff'); initChart('spiders-chart','#ff7a59'); });
        window.addEventListener('resize', function(){ setTimeout(function(){ initChart('logs-chart','#0b84ff'); initChart('spiders-chart','#ff7a59'); }, 120); });
    </script>
</body>
</html>
