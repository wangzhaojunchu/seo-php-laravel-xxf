<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Dashboard</title>
    <style>
    .icon{width:14px;height:14px;display:inline-block;flex:0 0 14px}
    .section-toggle{display:flex;align-items:center;gap:6px;padding:4px 6px;border-radius:6px;cursor:pointer;color:var(--muted);margin-top:8px;font-size:13px}
        .section-toggle:hover{background:rgba(0,0,0,0.02);color:var(--text)}
        .submenu.collapsed{display:none}
        .caret{margin-left:auto;transition:transform .15s}
        .caret.rotated{transform:rotate(90deg)}
        :root{--bg:#f6f8fa;--card:#ffffff;--accent:#2b6cb0;--muted:#6b7280;--text:#111;--sidebar:#fff}
        .theme-dark{--bg:#0b1220;--card:#0f1724;--accent:#60a5fa;--muted:#9aa6b2;--text:#e6eef8;--sidebar:#071026}
        .theme-light{--bg:#f6f8fa;--card:#ffffff;--accent:#2b6cb0;--muted:#6b7280;--text:#111;--sidebar:#ffffff}

        html,body{height:100%;margin:0;font-family:Inter,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:var(--bg);color:var(--text);transition:background .25s,color .25s}
        .wrap{display:flex;min-height:100vh}
    .sidebar{width:200px;background:var(--sidebar);border-right:1px solid rgba(0,0,0,0.06);padding:14px;transition:background .25s;flex:0 0 200px;min-width:200px}
        .brand{font-weight:700;margin-bottom:18px;font-size:18px}
        .nav{margin:12px 0}
    .nav a{display:block;padding:8px 10px;border-radius:6px;color:var(--text);text-decoration:none;margin-bottom:4px;transition:background .12s,color .12s;font-size:13px}
        .nav a.active{background:var(--accent);color:#fff;font-weight:600}
        .submenu{margin-left:8px;padding-left:8px;border-left:1px dashed rgba(0,0,0,0.04)}
    .content{flex:1;padding:20px}
        .topbar{background:transparent}
    .panel{background:var(--card);border-radius:10px;padding:14px;box-shadow:0 6px 20px rgba(2,6,23,0.06);transition:background .2s,box-shadow .2s}
    .content-inner{overflow:auto}
    table{width:100%;border-collapse:collapse}
    table, table th, table td{border:1px solid rgba(0,0,0,0.06)}
    table th, table td{padding:6px 8px}
    pre.log{background:#071226;color:#cfe8ff;padding:8px;border-radius:6px;overflow:auto;max-height:400px;font-family:Menlo,Consolas,monospace;font-size:12px}
        .muted{color:var(--muted)}
    .btn{background:var(--accent);color:#fff;padding:6px 10px;border-radius:8px;border:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;height:34px;box-sizing:border-box;text-decoration:none;font-size:13px}
        form.inline{display:flex;gap:12px;align-items:center}
        form.inline > div{display:flex;flex-direction:column}
    label.small{display:block;font-size:12px;color:var(--muted);margin-bottom:6px}
    input[type="password"],input[type="text"]{padding:6px 10px;border:1px solid rgba(0,0,0,0.06);border-radius:6px;height:34px;box-sizing:border-box;background:transparent;color:var(--text);font-size:13px}
        @media (max-width:860px){.sidebar{display:none}.content{padding:16px}}
    .pager-btn{display:inline-flex;align-items:center;justify-content:center;padding:5px 8px;border-radius:6px;background:transparent;border:1px solid rgba(0,0,0,0.04);min-width:36px;height:30px;color:var(--text);text-decoration:none;font-size:12px}
        .pager-btn.disabled{opacity:0.45;pointer-events:none}
    table th, table td{font-size:13px}
    .muted{font-size:13px}
    .per-select{height:34px;border-radius:6px;padding:6px 8px;border:1px solid rgba(0,0,0,0.06);background:transparent;color:var(--text);font-size:13px}
    .bot-item{display:inline-flex;align-items:center;gap:6px;padding:4px 8px;background:rgba(0,0,0,0.03);border-radius:6px;font-size:12px;transition:background .12s,transform .08s;user-select:none}
    .bot-item:hover{background:rgba(0,0,0,0.07)}
    .bot-item .bot-action{text-decoration:none;font-weight:700;transition:transform .08s;display:inline-block}
    .bot-item .bot-action:hover{transform:scale(1.08)}
    .bot-item .bot-action:active{transform:scale(1.02)}
    .bot-icon{stroke:var(--muted);width:14px;height:14px}
    .range-btn.range-active{background:linear-gradient(180deg, rgba(11,132,255,0.12), rgba(11,132,255,0.06));border:1px solid rgba(11,132,255,0.22);color:var(--accent)}
    
        /* Modal styles for add/edit dialogs */
        .modal-backdrop{position:fixed;inset:0;display:none;align-items:center;justify-content:center;background:rgba(2,6,23,0.45);z-index:9999}
        .modal-backdrop.show{display:flex}
        .modal{background:var(--card);border-radius:10px;padding:18px;width:720px;max-width:96%;box-shadow:0 12px 40px rgba(2,6,23,0.2);color:var(--text)}
        .modal-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
        .modal-close{background:transparent;border:none;font-size:18px;cursor:pointer;color:var(--muted)}
        .modal-body{max-height:60vh;overflow:auto}
        .modal-footer{display:flex;justify-content:flex-end;gap:8px;margin-top:12px}
    /* nicer modal scrollbar */
    .modal-body::-webkit-scrollbar{height:8px;width:8px}
    .modal-body::-webkit-scrollbar-thumb{background:rgba(0,0,0,0.12);border-radius:6px}
    </style>
</head>
<body>
    <!-- SVG sprite: centralized icon definitions for smaller markup and consistent styling -->
    <svg style="display:none;">
        <symbol id="icon-home" viewBox="0 0 24 24">
            <path d="M3 11.5L12 4l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V11.5z" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
        </symbol>
        <symbol id="icon-settings" viewBox="0 0 24 24">
            <path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a1 1 0 0 1-1.4 1.4l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.3V20a1 1 0 0 1-2 0v-.1a1.7 1.7 0 0 0-1-1.3 1.7 1.7 0 0 0-1.8.3l-.1.1A1 1 0 0 1 7.3 18l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.3-1H6a1 1 0 0 1 0-2h.1a1.7 1.7 0 0 0 1.3-1 1.7 1.7 0 0 0-.3-1.8L7 7.3A1 1 0 0 1 8.4 5.9l.1.1a1.7 1.7 0 0 0 1.8.3h.1A1.7 1.7 0 0 0 12 6.2V6a1 1 0 0 1 2 0v.2a1.7 1.7 0 0 0 1 1.3h.1a1.7 1.7 0 0 0 1.8-.3l.1-.1A1 1 0 0 1 18.6 7l-.1.1a1.7 1.7 0 0 0-.3 1.8c.2.5.6.9 1.1 1.1H19a1 1 0 0 1 0 2h-.1c-.5.2-.9.6-1.1 1.1z" stroke="currentColor" stroke-width="1" fill="none"/>
        </symbol>
        <symbol id="icon-password" viewBox="0 0 24 24">
            <path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l4-4 1-1 3 3-1 1-4 4-3 3H2z" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <path d="M15 4l-3 3 5 5 3-3-5-5z" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <path d="M9 15l-4.5 4.5" stroke="currentColor" stroke-width="1.2" fill="none"/>
        </symbol>
        <symbol id="icon-operation" viewBox="0 0 24 24">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <polyline points="14 2 14 8 20 8" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <line x1="16" y1="13" x2="8" y2="13" stroke="currentColor" stroke-width="1.2"/>
            <line x1="16" y1="17" x2="8" y2="17" stroke="currentColor" stroke-width="1.2"/>
        </symbol>
        <symbol id="icon-repair" viewBox="0 0 24 24">
            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" stroke="currentColor" stroke-width="1.2" fill="none"/>
        </symbol>
        <symbol id="icon-menu" viewBox="0 0 24 24">
            <path d="M3 13h18M3 6h18M8 20h8" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
        </symbol>
        <symbol id="icon-sites" viewBox="0 0 24 24">
            <path d="M20 17.58A5 5 0 0 0 15 8a5 5 0 0 0-9.35 2.12 5 5 0 0 0-1.05 9.88" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <line x1="3" y1="21" x2="21" y2="21" stroke="currentColor" stroke-width="1.2"/>
        </symbol>
        <symbol id="icon-models" viewBox="0 0 24 24">
            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <polyline points="3.27 6.96 12 12.01 20.73 6.96" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <line x1="12" y1="22.08" x2="12" y2="12" stroke="currentColor" stroke-width="1.2"/>
        </symbol>
        <symbol id="icon-ai" viewBox="0 0 24 24">
            <path d="M17.5 19H9a7 7 0 1 1 0-14h8.5a5.5 5.5 0 1 1 0 11z" stroke="currentColor" stroke-width="1.2" fill="none"/>
        </symbol>
        <symbol id="icon-list" viewBox="0 0 24 24">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <line x1="3" y1="9" x2="21" y2="9" stroke="currentColor" stroke-width="1.2"/>
            <line x1="3" y1="15" x2="21" y2="15" stroke="currentColor" stroke-width="1.2"/>
        </symbol>
        <symbol id="icon-collection" viewBox="0 0 24 24">
            <path d="M10 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <path d="M12.5 19.5L10 22l-2.5-2.5" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <path d="M12.5 4.5L10 2 7.5 4.5" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <path d="M4.5 12.5L2 10l2.5-2.5" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <path d="M19.5 12.5L22 10l-2.5-2.5" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.2" fill="none"/>
        </symbol>
        <symbol id="icon-api" viewBox="0 0 24 24">
            <path d="M3 7h18M3 12h18M3 17h18" stroke="currentColor" stroke-width="1.2" fill="none"/>
        </symbol>
        <symbol id="icon-access" viewBox="0 0 24 24">
            <path d="M12 2v4M6 6v2a6 6 0 0 0 12 0V6" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <rect x="3" y="10" width="18" height="11" rx="2" stroke="currentColor" stroke-width="1.2" fill="none"/>
        </symbol>
        <symbol id="icon-logs" viewBox="0 0 24 24">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <line x1="3" y1="9" x2="21" y2="9" stroke="currentColor" stroke-width="1.2"/>
            <line x1="9" y1="21" x2="9" y2="9" stroke="currentColor" stroke-width="1.2"/>
        </symbol>
        <symbol id="icon-spiders" viewBox="0 0 24 24">
            <path d="M10 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <path d="M14 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <path d="M18 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <path d="M6 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" stroke="currentColor" stroke-width="1.2" fill="none"/>
        </symbol>
        <symbol id="icon-ip" viewBox="0 0 24 24">
            <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <path d="M2 12h20M12 2v20" stroke="currentColor" stroke-width="1.2" fill="none"/>
        </symbol>
        <symbol id="icon-ua" viewBox="0 0 24 24">
            <path d="M12 2a5 5 0 0 1 5 5v2a5 5 0 0 1-5 5 5 5 0 0 1-5-5V7a5 5 0 0 1 5-5z" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <path d="M7 21c1.5-1 3-1 5-1s3.5 0 5 1" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <circle cx="12" cy="10" r="1.6" fill="currentColor" />
        </symbol>
        <!-- Bot icons: generic robot head shapes, use color via container style -->
        <symbol id="icon-bot-google" viewBox="0 0 24 24">
            <rect x="4" y="6" width="16" height="12" rx="3" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <circle cx="9" cy="11" r="1.4" fill="currentColor"/>
            <circle cx="15" cy="11" r="1.4" fill="currentColor"/>
            <rect x="10" y="14" width="4" height="1.2" rx="0.6" fill="currentColor"/>
        </symbol>
        <symbol id="icon-bot-bing" viewBox="0 0 24 24">
            <rect x="4" y="5" width="16" height="14" rx="2" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <path d="M8 10h8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
            <path d="M8 14h8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
        </symbol>
        <symbol id="icon-bot-baidu" viewBox="0 0 24 24">
            <circle cx="12" cy="8" r="3" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <path d="M6 18c2-2 4-2 6-2s4 0 6 2" stroke="currentColor" stroke-width="1.2" fill="none"/>
        </symbol>
        <symbol id="icon-bot-default" viewBox="0 0 24 24">
            <rect x="6" y="6" width="12" height="10" rx="2" stroke="currentColor" stroke-width="1.2" fill="none"/>
            <circle cx="9.5" cy="10.5" r="0.9" fill="currentColor"/>
            <circle cx="14.5" cy="10.5" r="0.9" fill="currentColor"/>
        </symbol>
    </svg>

    <div class="wrap">
        <aside class="sidebar">
            <div class="brand">管理员面板</div>
            <nav class="nav">
                <a href="{{ route('admin.dashboard') }}" class="{{ ($page ?? '') === 'home' ? 'active' : '' }}">
                    <span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-home"></use></svg></span>
                    管理员首页
                </a>

                <div class="section-toggle" data-section="core-settings" role="button" tabindex="0">
                    <span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-settings"></use></svg></span>
                    核心设置
                    <span class="caret" data-caret-for="core-settings">›</span>
                </div>
                <div class="submenu" data-section="core-settings">
                    <a href="{{ route('admin.settings') }}" class="{{ ($page ?? '') === 'settings' ? 'active' : '' }}"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-settings"></use></svg></span>系统设置</a>
                    <a href="{{ route('admin.password') }}" class="{{ ($page ?? '') === 'password' ? 'active' : '' }}"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-password"></use></svg></span>修改密码</a>
                    <a href="{{ route('admin.operation_logs') }}" class="{{ ($page ?? '') === 'operation_logs' ? 'active' : '' }}"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-operation"></use></svg></span>操作日志</a>
                    <a href="{{ route('admin.repair') }}" class="{{ ($page ?? '') === 'repair' ? 'active' : '' }}"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-repair"></use></svg></span>系统修复</a>
                </div>

                <div class="section-toggle" data-section="sites" role="button" tabindex="0">
                    <span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-menu"></use></svg></span>
                    站群管理
                    <span class="caret" data-caret-for="sites">›</span>
                </div>
                <div class="submenu" data-section="sites">
                    <a href="{{ route('admin.sites') }}" class="{{ ($page ?? '') === 'sites' ? 'active' : '' }}"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-sites"></use></svg></span>网站管理</a>
                    <a href="{{ route('admin.models') }}" class="{{ ($page ?? '') === 'models' ? 'active' : '' }}"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-models"></use></svg></span>模型管理</a>
                </div>

                <div class="section-toggle" data-section="content" role="button" tabindex="0">
                    <span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-ai"></use></svg></span>
                    内容管理
                    <span class="caret" data-caret-for="content">›</span>
                </div>
                    <div class="submenu" data-section="content">
                    <a href="{{ route('admin.content.ai') }}" class="{{ ($page ?? '') === 'content_ai' ? 'active' : '' }}"><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M17.5 19H9a7 7 0 1 1 0-14h8.5a5.5 5.5 0 1 1 0 11z"/></svg></span>AI 生成</a>
                    <a href="{{ route('admin.content.manage') }}" class="{{ ($page ?? '') === 'content_manage' ? 'active' : '' }}"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-list"></use></svg></span>内容列表</a>
                    @php $ctab = request()->query('tab') ?? 'keywords'; @endphp
                    <a href="{{ route('admin.content.resources') }}?tab=keywords" class="{{ ($page ?? '') === 'content_resources' && $ctab === 'keywords' ? 'active' : '' }}"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-list"></use></svg></span>关键词管理</a>
                    <a href="{{ route('admin.content.resources') }}?tab=columns" class="{{ ($page ?? '') === 'content_resources' && $ctab === 'columns' ? 'active' : '' }}"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-list"></use></svg></span>栏目管理</a>
                    <a href="{{ route('admin.content.resources') }}?tab=tips" class="{{ ($page ?? '') === 'content_resources' && $ctab === 'tips' ? 'active' : '' }}"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-list"></use></svg></span>Tips 管理</a>
                    <a href="{{ route('admin.content.resources') }}?tab=suffixes" class="{{ ($page ?? '') === 'content_resources' && $ctab === 'suffixes' ? 'active' : '' }}"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-list"></use></svg></span>标题后缀</a>
                    <a href="{{ route('admin.content.collection') }}" class="{{ ($page ?? '') === 'content_collection' ? 'active' : '' }}"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-collection"></use></svg></span>采集管理</a>
                    <a href="{{ route('admin.api') }}" class="{{ ($page ?? '') === 'api_management' ? 'active' : '' }}"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-api"></use></svg></span>API 管理</a>
                </div>

                <div class="section-toggle" data-section="access" role="button" tabindex="0">
                    <span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-access"></use></svg></span>
                    访问控制
                    <span class="caret" data-caret-for="access">›</span>
                </div>
                <div class="submenu" data-section="access">
                    <a href="{{ route('admin.logs') }}" class="{{ ($page ?? '') === 'logs' ? 'active' : '' }}"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-logs"></use></svg></span>访问记录</a>
                    <a href="{{ route('admin.spiders') }}" class="{{ ($page ?? '') === 'spiders' ? 'active' : '' }}"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-spiders"></use></svg></span>蜘蛛记录</a>
                    <a href="{{ route('admin.access.ip') }}" class="{{ ($page ?? '') === 'access_ip' ? 'active' : '' }}"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-ip"></use></svg></span>访问IP控制</a>
                    <a href="{{ route('admin.access.ua') }}" class="{{ ($page ?? '') === 'access_ua' ? 'active' : '' }}"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-ua"></use></svg></span>访问UA控制</a>
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
                            'operation_logs' => '操作日志',
                            'repair' => '系统修复',
                            'sites' => '网站管理',
                            'models' => '模型管理',
                            'content_ai' => 'AI 生成设置',
                            'api_management' => 'API 管理',
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
                    <h2>站点分组管理</h2>
                    @if(session('status'))<div style="background:#ecffed;padding:8px;border-radius:6px;margin-bottom:12px">{{ session('status') }}</div>@endif

                    <div style="display:flex;gap:12px;align-items:flex-start;flex-wrap:wrap">
                        <div style="flex:1;min-width:320px">
                            <h3>分组列表</h3>
                            @if(empty($groups))
                                <p class="muted">尚未配置任何分组。</p>
                            @else
                                <table>
                                    <thead><tr><th>分组名</th><th>域名数量</th><th>默认模型</th><th>操作</th></tr></thead>
                                    <tbody>
                                    @foreach($groups as $g)
                                        <tr>
                                            <td>{{ $g['name'] ?? '(unnamed)' }}</td>
                                            <td>{{ isset($g['domains']) ? count($g['domains']) : 0 }}</td>
                                            <td>{{ $g['model'] ?? '' }}</td>
                                            <td>
                                                <?php $g_b64 = base64_encode(json_encode($g, JSON_UNESCAPED_UNICODE)); ?>
                                                <a href="#" class="group-edit-link" data-group-b64='{{ $g_b64 }}'>编辑</a>
                                                <a href="#" class="group-remove-link" data-group-name="{{ $g['name'] ?? '' }}" style="color:#dc3545;margin-left:8px">删除</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        <div style="flex:1;min-width:360px">
                                <div style="display:flex;justify-content:space-between;align-items:center">
                                <h3 id="groupFormTitle">分组</h3>
                                <button type="button" class="btn" id="openAddGroupBtn">添加分组</button>
                            </div>
                            <!-- keep inline form as fallback but hide it in favor of modal -->
                            <div id="groupFormContainer" style="display:none">
                                <h4>添加分组 (备用表单)</h4>
                                <form method="POST" action="{{ route('admin.sites.save') }}" id="groupForm">
                                    @csrf
                                    <input type="hidden" name="groups_json" id="groups_json_input">
                                    <div style="margin-top:8px">
                                        <label class="small">分组名称</label>
                                        <input type="text" name="group_name" id="group_name" style="width:100%;padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px">
                                    </div>
                                    <div style="margin-top:8px">
                                        <label class="small">域名 (每行一条)</label>
                                        <textarea name="group_domains" id="group_domains" style="width:100%;min-height:120px;border:1px solid rgba(0,0,0,0.06);padding:8px;border-radius:8px"></textarea>
                                    </div>
                                    <div style="display:flex;gap:8px;align-items:center;margin-top:8px">
                                        <label><input type="checkbox" name="force_www" id="force_www"> 强制跳转到 www</label>
                                        <label><input type="checkbox" name="force_mobile" id="force_mobile"> 移动端跳转到 m.wap</label>
                                    </div>
                                    <div style="margin-top:8px">
                                        <label class="small">默认模型</label>
                                        <input type="text" name="group_model" id="group_model" style="width:100%;padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px" placeholder="模型 key，例如: default_model">
                                    </div>
                                    <div style="margin-top:8px">
                                        <label class="small">模板 (可选)</label>
                                        <input type="text" name="group_template" id="group_template" style="width:100%;padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px" placeholder="模板名或路径">
                                    </div>
                                    <div style="margin-top:12px;display:flex;gap:8px"><button class="btn" type="submit">保存分组</button><button type="button" id="exportGroupsBtn" class="btn" style="background:#6b7280">导出 JSON</button></div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Backups panel -->
                    <div style="margin-top:18px;display:flex;gap:12px;align-items:flex-start;flex-wrap:wrap">
                        <div style="flex:1">
                            <h3>备份</h3>
                            <div id="backupsList" style="background:rgba(0,0,0,0.02);padding:8px;border-radius:8px;min-height:60px">加载中...</div>
                        </div>
                    </div>
                    <script>
                        // Toast helper
                        function showToast(msg, type){
                            var c = document.getElementById('toastContainer');
                            if(!c) return; var el = document.createElement('div');
                            el.style.background = (type==='error') ? '#ffefef' : '#f0fff4';
                            el.style.border = (type==='error') ? '1px solid #f5c2c7' : '1px solid #c6f6d5';
                            el.style.color = (type==='error') ? '#9b1c1c' : '#064e3b';
                            el.style.padding = '8px 12px'; el.style.borderRadius='8px'; el.style.boxShadow='0 6px 18px rgba(2,6,23,0.06)'; el.textContent = msg;
                            c.appendChild(el);
                            setTimeout(function(){ el.style.opacity = '0'; setTimeout(()=>el.remove(),300); }, 4200);
                        }

                        // Load backups
                        function loadBackups(){
                            var el = document.getElementById('backupsList'); if(!el) return; el.innerHTML='加载中...';
                            fetch('{{ route('admin.sites.backups') }}').then(r=>r.json()).then(list=>{
                                if(!list || !list.length){ el.innerHTML = '<div class="muted">暂无备份</div>'; return; }
                                el.innerHTML = '';
                                list.forEach(function(b){
                                    var row = document.createElement('div'); row.style.display='flex'; row.style.justifyContent='space-between'; row.style.alignItems='center'; row.style.padding='6px 8px';
                                    var left = document.createElement('div'); left.innerHTML = '<strong>'+b.file+'</strong><div class="muted">'+b.mtime+'</div>';
                                    var right = document.createElement('div');
                                    var btn = document.createElement('button'); btn.className='btn'; btn.style.marginLeft='8px'; btn.textContent='恢复';
                                    btn.addEventListener('click', function(){ if(!confirm('确认从备份恢复：'+b.file+' ?')) return; fetch('{{ route('admin.sites.restore') }}', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}, body: JSON.stringify({backup: b.file}) }).then(r=>r.json()).then(j=>{ if(j.ok){ showToast('恢复成功','success'); setTimeout(()=>location.reload(),400); } else{ showToast('恢复失败: '+(j.message||''),'error'); } }).catch(e=>{ showToast('网络错误: '+e.message,'error'); }); });
                                    right.appendChild(btn);
                                    row.appendChild(left); row.appendChild(right); el.appendChild(row);
                                });
                            }).catch(e=>{ el.innerHTML = '<div class="muted">加载失败</div>'; console.error(e); });
                        }

                        // Replace alert in removeGroup earlier: redefine removeGroup to use toast if present
                        (function(){
                            var orig = window.removeGroup;
                            window.removeGroup = function(name){
                                if(!confirm('确认删除分组：' + name + ' ?')) return;
                                fetch('{{ route('admin.sites.delete') }}', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}, body: JSON.stringify({ name: name }) }).then(r=>r.json()).then(j=>{ if(j.ok){ showToast('分组已删除','success'); loadBackups(); setTimeout(()=>location.reload(),500); } else { showToast('删除失败: '+(j.message||''),'error'); } }).catch(e=>{ showToast('网络错误: '+e.message,'error'); });
                            };
                        })();

                        // Initialize backups panel on load
                        document.addEventListener('DOMContentLoaded', function(){ loadBackups(); });
                    </script>
                    </div>

                    <script>
                        function loadGroup(g){
                            try{
                                document.getElementById('groupFormTitle').innerText = '编辑分组';
                                document.getElementById('group_name').value = g.name || '';
                                document.getElementById('group_domains').value = (g.domains || []).join('\n');
                                document.getElementById('force_www').checked = !!g.force_www;
                                document.getElementById('force_mobile').checked = !!g.force_mobile;
                                document.getElementById('group_model').value = g.model || '';
                                document.getElementById('group_template').value = g.template || '';
                            }catch(e){ console.error(e); }
                        }
                        function removeGroup(name){
                            if(!confirm('确认删除分组：' + name + ' ?')) return;
                            var el = document.getElementById('groupFormTitle');
                            el && (el.innerText = 'Deleting...');
                            fetch('{{ route('admin.sites.delete') }}', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                body: JSON.stringify({ name: name })
                            }).then(r=>r.json().then(j=>({status:r.status, json:j}))).then(res=>{
                                if(res.status >=200 && res.status < 300 && res.json.ok){
                                    alert('分组已删除');
                                    location.reload();
                                } else {
                                    alert('删除失败: ' + (res.json.message || ('HTTP ' + res.status)));
                                }
                            }).catch(e=>{ alert('网络错误: ' + e.message); }).finally(()=>{ el && (el.innerText = '添加分组'); });
                        }
                        document.getElementById('exportGroupsBtn').addEventListener('click', function(){
                            var g = { name: document.getElementById('group_name').value || 'group', domains: (document.getElementById('group_domains').value||'').split('\n').map(s=>s.trim()).filter(Boolean), force_www: !!document.getElementById('force_www').checked, force_mobile: !!document.getElementById('force_mobile').checked, model: document.getElementById('group_model').value||'', template: document.getElementById('group_template').value||'' };
                            var blob = new Blob([JSON.stringify(g, null, 2)], {type:'application/json'});
                            var url = URL.createObjectURL(blob); var a = document.createElement('a'); a.href = url; a.download = (g.name||'group') + '.json'; document.body.appendChild(a); a.click(); a.remove();
                        });
                    </script>

                @elseif(($page ?? '') === 'models')
                    <h2>模型管理</h2>
                    @if(session('status'))<div style="background:#ecffed;padding:8px;border-radius:6px;margin-bottom:12px">{{ session('status') }}</div>@endif

                    <div style="display:flex;gap:12px;flex-wrap:wrap">
                        <div style="flex:1;min-width:320px">
                            <h3>已配置模型</h3>
                            @if(empty($modelsList))
                                <p class="muted">暂无模型。</p>
                            @else
                                <table>
                                    <thead><tr><th>名称</th><th>Key</th><th>模板</th><th>操作</th></tr></thead>
                                    <tbody>
                                    @foreach($modelsList as $m)
                                        <tr>
                                            <td>{{ $m['name'] ?? '' }}</td>
                                            <td>{{ $m['key'] ?? '' }}</td>
                                            <td>{{ is_array($m['template'] ?? null) ? json_encode($m['template'], JSON_UNESCAPED_UNICODE) : ($m['template'] ?? '') }}</td>
                                            <?php $m_b64 = base64_encode(json_encode($m, JSON_UNESCAPED_UNICODE)); ?>
                                            <td><a href="#" class="model-edit-link" data-model-b64='{{ $m_b64 }}'>编辑</a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        <div style="flex:1;min-width:360px">
                            <h3 id="modelFormTitle">添加模型</h3>
                            <form method="POST" action="{{ route('admin.models.save') }}" id="modelForm">
                                @csrf
                                <input type="hidden" name="models_json" id="models_json_input">
                                <div style="margin-top:8px">
                                    <label class="small">显示名称</label>
                                    <input type="text" id="model_name" style="width:100%;padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px">
                                </div>
                                <div style="margin-top:8px">
                                    <label class="small">唯一 key (英数字和下划线)</label>
                                    <input type="text" id="model_key" style="width:100%;padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px" placeholder="例如: default_model">
                                </div>
                                <div style="margin-top:8px">
                                    <label class="small">模板映射 (每行一条，格式: <code>KEY =&gt; template.html</code>，例如: <code>/news =&gt; news_template.html</code>; 默认会包含 <code>page</code> 和 <code>list</code>)</label>
                                    <textarea id="model_template" style="width:100%;min-height:120px;border:1px solid rgba(0,0,0,0.06);padding:8px;border-radius:8px" placeholder="page => template.html\nlist => list.html"></textarea>
                                </div>
                                <div style="margin-top:12px;display:flex;gap:8px"><button class="btn" type="button" id="saveModelBtn">保存模型</button><button type="button" id="clearModelBtn" class="btn" style="background:#6b7280">清空</button></div>
                            </form>
                        </div>
                    </div>

                    <script>
                        function editModel(m){
                            try{
                                document.getElementById('modelFormTitle').innerText = '编辑模型';
                                document.getElementById('model_name').value = m.name || '';
                                document.getElementById('model_key').value = m.key || '';
                                document.getElementById('model_template').value = JSON.stringify(m.template || {}, null, 2);
                            }catch(e){ console.error(e); }
                        }
                        document.getElementById('saveModelBtn').addEventListener('click', function(){
                            var name = document.getElementById('model_name').value || '';
                            var key = document.getElementById('model_key').value || '';
                            var tmpl = document.getElementById('model_template').value || '{}';
                            try { var parsed = JSON.parse(tmpl); } catch(e){ alert('模板 JSON 格式错误: ' + e.message); return; }
                            var models = [];
                            // load existing from page via server-side injected JSON? We'll build minimal payload
                            models.push({ name: name, key: key, template: parsed });
                            document.getElementById('models_json_input').value = JSON.stringify(models);
                            document.getElementById('modelForm').submit();
                        });
                        document.getElementById('clearModelBtn').addEventListener('click', function(){ document.getElementById('model_name').value=''; document.getElementById('model_key').value=''; document.getElementById('model_template').value=''; document.getElementById('modelFormTitle').innerText='添加模型'; });
                    </script>

                    <script>
                        // Delegated handlers for dynamic edit/remove links to avoid inline onclick with embedded JSON
                        document.addEventListener('click', function(e){
                            // group edit
                            var a = e.target.closest && e.target.closest('.group-edit-link');
                            if(a){ e.preventDefault(); try{ var raw = a.getAttribute('data-group-b64') || a.getAttribute('data-group') || ''; var jsonStr = raw; if(raw && a.getAttribute('data-group-b64')){ try{ jsonStr = decodeURIComponent(escape(window.atob(raw))); }catch(e){ try{ jsonStr = window.atob(raw); }catch(e2){ jsonStr = raw; } } } var g = JSON.parse(jsonStr || '{}'); if(window.openGroupModal) openGroupModal(g); else if(window.loadGroup) loadGroup(g); }catch(err){ console.error('parse group json', err); } return; }
                            var ra = e.target.closest && e.target.closest('.group-remove-link');
                            if(ra){ e.preventDefault(); var name = ra.getAttribute('data-group-name') || ''; if(window.removeGroup) removeGroup(name); return; }
                            var ma = e.target.closest && e.target.closest('.model-edit-link');
                            if(ma){ e.preventDefault(); try{ var rawm = ma.getAttribute('data-model-b64') || ma.getAttribute('data-model') || ''; var jsonM = rawm; if(rawm && ma.getAttribute('data-model-b64')){ try{ jsonM = decodeURIComponent(escape(window.atob(rawm))); }catch(e){ try{ jsonM = window.atob(rawm); }catch(e2){ jsonM = rawm; } } } var m = JSON.parse(jsonM || '{}'); if(window.openModelModal) openModelModal(m); else if(window.editModel) editModel(m); }catch(err){ console.error('parse model json', err); } return; }
                        });
                    </script>

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
                        $id = 'icon-bot-default';
                        $color = '#6b7280';
                        if (str_contains($botName, 'google')) { $id = 'icon-bot-google'; $color = '#34A853'; }
                        elseif (str_contains($botName, 'bing')) { $id = 'icon-bot-bing'; $color = '#008373'; }
                        elseif (str_contains($botName, 'baidu')) { $id = 'icon-bot-baidu'; $color = '#2932E1'; }
                        $svg = "<svg class=\"bot-icon\" viewBox=\"0 0 24 24\" style=\"color:{$color}\"><use xlink:href=\"#{$id}\"/></svg>";
                        return $svg;
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
                    <form method="GET" action="{{ route('admin.logs') }}" id="logsRangeForm" style="display:flex;gap:8px;align-items:center;margin-bottom:12px;flex-wrap:wrap">
                        <label class="small">快速范围</label>
                        <div style="display:flex;gap:6px">
                            <button type="button" class="btn range-btn" data-range="7">1 周</button>
                            <button type="button" class="btn range-btn" data-range="30">1 个月</button>
                            <button type="button" class="btn range-btn" data-range="183">6 个月</button>
                            <button type="button" class="btn range-btn" data-range="365">1 年</button>
                        </div>
                        <label class="small">从 <input type="date" name="from" id="logs_from" value="{{ $from ?? ($date ?? date('Y-m-d', strtotime('-1 month'))) }}"></label>
                        <label class="small">到 <input type="date" name="to" id="logs_to" value="{{ $to ?? ($date ?? date('Y-m-d')) }}"></label>
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
                    <form method="GET" action="{{ route('admin.spiders') }}" id="spidersRangeForm" style="display:flex;gap:8px;align-items:center;margin-bottom:12px;flex-wrap:wrap">
                        <label class="small">快速范围</label>
                        <div style="display:flex;gap:6px">
                            <button type="button" class="btn range-btn" data-range="7">1 周</button>
                            <button type="button" class="btn range-btn" data-range="30">1 个月</button>
                            <button type="button" class="btn range-btn" data-range="183">6 个月</button>
                            <button type="button" class="btn range-btn" data-range="365">1 年</button>
                        </div>
                        <label class="small">从 <input type="date" name="from" id="spiders_from" value="{{ $from ?? ($date ?? date('Y-m-d', strtotime('-1 month'))) }}"></label>
                        <label class="small">到 <input type="date" name="to" id="spiders_to" value="{{ $to ?? ($date ?? date('Y-m-d')) }}"></label>
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

                @elseif(($page ?? '') === 'operation_logs')
                    <h2>操作日志</h2>
                    <form method="GET" action="{{ route('admin.operation_logs') }}" id="oplogsRangeForm" style="display:flex;gap:8px;align-items:center;margin-bottom:12px;flex-wrap:wrap">
                        <label class="small">快速范围</label>
                        <div style="display:flex;gap:6px">
                            <button type="button" class="btn range-btn" data-range="7">1 周</button>
                            <button type="button" class="btn range-btn" data-range="30">1 个月</button>
                            <button type="button" class="btn range-btn" data-range="183">6 个月</button>
                            <button type="button" class="btn range-btn" data-range="365">1 年</button>
                        </div>
                        <label class="small">从 <input type="date" name="from" id="op_from" value="{{ $from ?? ($date ?? date('Y-m-d', strtotime('-1 month'))) }}"></label>
                        <label class="small">到 <input type="date" name="to" id="op_to" value="{{ $to ?? ($date ?? date('Y-m-d')) }}"></label>
                        <label class="small">搜索 <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="用户/IP/URI"></label>
                        <div style="margin-left:auto;display:flex;gap:8px">
                            <button class="btn" type="submit">筛选</button>
                            <a class="btn" href="{{ route('admin.operation_logs') }}?from={{ $from ?? ($date ?? date('Y-m-d')) }}&to={{ $to ?? ($date ?? date('Y-m-d')) }}&download=action">导出 CSV</a>
                        </div>
                    </form>

                    @if(empty($logs))
                        <p class="muted">没有匹配的操作日志。</p>
                    @else
                        <table style="width:100%;border-collapse:collapse">
                            <thead>
                                <tr style="text-align:left;border-bottom:1px solid rgba(0,0,0,0.06)">
                                    <th style="padding:8px;width:60px">#</th>
                                    <th style="padding:8px">时间</th>
                                    <th style="padding:8px">用户</th>
                                    <th style="padding:8px">IP</th>
                                    <th style="padding:8px">方法</th>
                                    <th style="padding:8px">URI</th>
                                    <th style="padding:8px">状态</th>
                                    <th style="padding:8px">耗时</th>
                                    <th style="padding:8px">操作摘要</th>
                                    <th style="padding:8px">Payload</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $i => $row)
                                    <tr>
                                        <td style="padding:8px">{{ (($currentPage ?? 1)-1) * ($per ?? 20) + ($i+1) }}</td>
                                        <td style="padding:8px">{{ $row['datetime'] }}</td>
                                        <td style="padding:8px">{{ $row['user'] }}</td>
                                        <td style="padding:8px">{{ $row['ip'] }}</td>
                                        <td style="padding:8px">{{ $row['method'] }}</td>
                                        <td style="padding:8px">{{ $row['uri'] }}</td>
                                        <td style="padding:8px">{{ $row['status'] }}</td>
                                        <td style="padding:8px">{{ $row['duration'] }}</td>
                                        <td style="padding:8px">{{ $row['action'] ?? '' }}</td>
                                        <td style="padding:8px;max-width:360px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="{{ $row['payload'] }}">{{ $row['payload'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div style="margin-top:12px;display:flex;align-items:center;gap:8px">
                            <div class="muted">共 {{ $total }} 条结果</div>
                            <div style="margin-left:auto;display:flex;gap:6px">
                                @for($p=1;$p<=$pages;$p++)
                                    <a href="?from={{ $from }}&to={{ $to }}&q={{ $q }}&page={{ $p }}&per_page={{ $per }}" style="padding:6px 8px;border-radius:6px;background:{{ $p == ($currentPage ?? 1) ? 'rgba(0,0,0,0.06)' : 'transparent' }}">{{ $p }}</a>
                                @endfor
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

                @elseif(($page ?? '') === 'api_management')
                    <h2>API 管理</h2>
                    @if(session('status'))<div style="background:#ecffed;padding:8px;border-radius:6px;margin-bottom:12px">{{ session('status') }}</div>@endif
                    @if($errors->any())<div style="background:#fff6f6;padding:8px;border-radius:6px;margin-bottom:12px;color:#9b1c1c">{{ $errors->first() }}</div>@endif

                    <p class="muted">在此管理外部上传 API 的配置。配置以 JSON 存储于 <code>data/api/config.json</code>。</p>

                    <form method="POST" action="{{ route('admin.api.save') }}" id="apiConfigForm">
                        @csrf
                        <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin-bottom:12px">
                            <label class="small">启用 API</label>
                            <input type="checkbox" id="api_enabled_checkbox" name="api_enabled" {{ (isset($apiConfigJson) && strpos($apiConfigJson, '"enabled": true') !== false) ? 'checked' : '' }}>
                            <label class="small">Endpoint</label>
                            <input type="text" id="api_endpoint" name="api_endpoint" style="padding:6px;border:1px solid rgba(0,0,0,0.06);border-radius:6px" value="{{ isset($apiConfigJson) ? (json_decode($apiConfigJson, true)['endpoint'] ?? '/api/articles') : '/api/articles' }}">
                            <label class="small">Key</label>
                            <input type="text" id="api_key_field" name="api_key_field" readonly style="padding:6px;border:1px solid rgba(0,0,0,0.06);border-radius:6px;background:rgba(0,0,0,0.03)" value="{{ session('api_key') ?? (isset($apiConfigJson) ? (json_decode($apiConfigJson, true)['key'] ?? '') : '') }}">
                            <button type="button" class="btn" id="copyApiKeyBtn">复制密钥</button>
                            <button type="button" class="btn" id="regenKeyBtn">生成新密钥</button>
                        </div>

                        <input type="hidden" name="config_json" id="hidden_config_json">
                        <div style="margin-top:6px;margin-bottom:12px;color:var(--muted)">保存后系统将写入 <code>data/api/config.json</code>，密钥会自动生成（若为空）。</div>
                        <div style="margin-top:6px;padding:12px;background:rgba(0,0,0,0.02);border-radius:8px">
                            <div class="muted">示例上传 (curl):</div>
                            <pre style="white-space:pre-wrap">curl -X POST "http://your-site/api/articles" -H "X-API-Key: {{ session('api_key') ?? (isset($apiConfigJson) ? (json_decode($apiConfigJson, true)['key'] ?? 'your-api-key') : 'your-api-key') }}" -d "title=标题&summary=摘要&content=内容&publish=1"</pre>
                        </div>

                        <div style="margin-top:12px;display:flex;gap:8px">
                            <button class="btn" type="submit">保存配置</button>
                        </div>
                    </form>

                    <hr style="margin:16px 0;border:none;border-top:1px solid rgba(0,0,0,0.06)">
                    <h3>测试上传</h3>
                    <form id="testUploadForm" style="display:flex;flex-direction:column;gap:8px;max-width:720px">
                        <input type="text" id="t_title" placeholder="标题" style="padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px">
                        <input type="text" id="t_summary" placeholder="摘要" style="padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px">
                        <textarea id="t_content" placeholder="内容" style="min-height:120px;padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px"></textarea>
                        <label><input type="checkbox" id="t_publish"> 立即发布</label>
                        <div style="display:flex;gap:8px"><button type="button" class="btn" id="testSubmitBtn">提交到 API</button><span id="testResult" class="muted"></span></div>
                    </form>

                    <script>
                        function buildConfigJson(){
                            var enabled = !!document.getElementById('api_enabled_checkbox').checked;
                            var endpoint = document.getElementById('api_endpoint').value || '/api/articles';
                            var key = document.getElementById('api_key_field').value || '';
                            var obj = { enabled: enabled, endpoint: endpoint, key: key, allowed_sources: ['api'] };
                            return JSON.stringify(obj, null, 2);
                        }
                        document.getElementById('regenKeyBtn')?.addEventListener('click', function(){
                            // generate 32 chars
                            function gen(){ try { return btoa(String.fromCharCode.apply(null, crypto.getRandomValues(new Uint8Array(16)))).replace(/=+$/,'').substr(0,32); } catch(e) { return Math.random().toString(36).substr(2,32); } }
                            document.getElementById('api_key_field').value = gen();
                        });

                        document.getElementById('copyApiKeyBtn')?.addEventListener('click', function(){
                            var key = document.getElementById('api_key_field').value || '';
                            if (!key) return;
                            navigator.clipboard?.writeText(key).then(function(){
                                var el = document.getElementById('testResult'); el.style.color = 'green'; el.textContent = 'API key 已复制到剪贴板';
                                setTimeout(function(){ el.textContent = ''; }, 2500);
                            }).catch(function(){
                                var el = document.getElementById('testResult'); el.style.color = 'red'; el.textContent = '复制失败';
                                setTimeout(function(){ el.textContent = ''; }, 2500);
                            });
                        });

                        // On submit, populate hidden_config_json
                        document.getElementById('apiConfigForm')?.addEventListener('submit', function(e){
                            document.getElementById('hidden_config_json').value = buildConfigJson();
                        });

                        document.getElementById('testSubmitBtn')?.addEventListener('click', async function(){
                            var title = document.getElementById('t_title').value || '';
                            var summary = document.getElementById('t_summary').value || '';
                            var content = document.getElementById('t_content').value || '';
                            var publish = !!document.getElementById('t_publish').checked;
                            var key = document.getElementById('api_key_field').value || '';
                            var el = document.getElementById('testResult');
                            if (!title || !content) { alert('请填写标题和内容'); return; }
                            var form = new URLSearchParams();
                            form.append('title', title);
                            form.append('summary', summary);
                            form.append('content', content);
                            form.append('publish', publish ? '1' : '0');
                            el.style.color = 'black'; el.textContent = '发送中...';
                            try {
                                var resp = await fetch(document.getElementById('api_endpoint').value || '/api/articles', {
                                    method: 'POST',
                                    headers: { 'X-API-Key': key, 'Content-Type': 'application/x-www-form-urlencoded' },
                                    body: form.toString()
                                });
                                var text = await resp.text();
                                var parsed = null;
                                try { parsed = JSON.parse(text); } catch(e){ parsed = null; }
                                if (resp.ok) {
                                    el.style.color = 'green';
                                    if (parsed) el.textContent = '成功: ' + (parsed.id || JSON.stringify(parsed));
                                    else el.textContent = '成功: ' + text.slice(0,150);
                                } else {
                                    el.style.color = 'red';
                                    if (parsed && parsed.message) el.textContent = '错误: ' + parsed.message;
                                    else el.textContent = '错误: HTTP ' + resp.status + ' - ' + text.slice(0,300);
                                }
                            } catch (err) {
                                el.style.color = 'red'; el.textContent = '网络错误: ' + (err.message || err);
                            }
                            setTimeout(function(){ if (el) el.textContent = ''; }, 8000);
                        });
                    </script>

                    <h3 style="margin-top:18px">推荐的 JSON 格式</h3>
                    <pre class="muted" style="padding:12px;background:rgba(0,0,0,0.02);border-radius:8px;white-space:pre-wrap">{
  "enabled": true,                // 是否启用 API
  "endpoint": "/api/articles",  // 接收文章的端点
  "key": "your-api-key",        // 用于简单鉴权的共享密钥
  "allowed_sources": ["api"],   // 标注来源
  "rate_limit_per_min": 60,       // 可选，限流设置
  "allowed_ips": ["127.0.0.1"]  // 可选，允许的调用方 IP 白名单
}</pre>

                    <script>
                        document.getElementById('validateBtn')?.addEventListener('click', function(){
                            try {
                                var txt = document.querySelector('textarea[name="config_json"]').value;
                                JSON.parse(txt);
                                alert('JSON 格式有效');
                            } catch (e) {
                                alert('JSON 格式错误: ' + e.message);
                            }
                        });
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

                    <!-- API settings moved to API 管理 页面 -->

                @elseif(($page ?? '') === 'content_manage')
                    @php
                        // Defensive defaults in case controller did not provide them
                        $articles = $articles ?? [];
                        $total = $total ?? 0;
                        $currentPage = $currentPage ?? 1;
                        $pages = $pages ?? 1;
                        $perPage = $perPage ?? 10;
                        $f_source = $f_source ?? '';
                        $f_status = $f_status ?? '';
                        $f_from = $f_from ?? '';
                        $f_to = $f_to ?? '';
                    @endphp
                    <h2>内容列表</h2>
                    <form method="GET" action="#" style="display:flex;gap:12px;align-items:center;margin-bottom:16px;flex-wrap:wrap">
                        <label class="small">从 <input type="date" name="from" value="{{ $f_from ?? '' }}"></label>
                        <label class="small">到 <input type="date" name="to" value="{{ $f_to ?? '' }}"></label>
                        <select name="source" class="per-select">
                            <option value="">所有来源</option>
                            <option value="ai" {{ (isset($f_source) && $f_source=='ai') ? 'selected' : '' }}>AI 生成</option>
                            <option value="crawled" {{ (isset($f_source) && $f_source=='crawled') ? 'selected' : '' }}>采集</option>
                            <option value="api" {{ (isset($f_source) && $f_source=='api') ? 'selected' : '' }}>API上传</option>
                        </select>
                        <select name="status" class="per-select">
                            <option value="">所有状态</option>
                            <option value="published" {{ (isset($f_status) && $f_status=='published') ? 'selected' : '' }}>已发布</option>
                            <option value="unpublished" {{ (isset($f_status) && $f_status=='unpublished') ? 'selected' : '' }}>未发布</option>
                        </select>
                        <button class="btn" type="submit">筛选</button>
                    </form>

                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px">
                        <div>内容资源管理已迁移到单独页面。</div>
                        <a href="{{ route('admin.content.resources') }}" class="btn">前往内容资源管理</a>
                    </div>

                    @elseif(($page ?? '') === 'content_resources')
                    <h2>内容资源管理</h2>
                    <div style="display:flex;gap:12px;align-items:center;margin-bottom:12px;flex-wrap:wrap">
                        <div style="display:flex;gap:8px;align-items:center">
                            <button class="btn" type="button" data-type="keywords" onclick="switchContentTab('keywords')">关键词</button>
                            <button class="btn" type="button" data-type="columns" onclick="switchContentTab('columns')">栏目</button>
                            <button class="btn" type="button" data-type="tips" onclick="switchContentTab('tips')">Tips</button>
                            <button class="btn" type="button" data-type="suffixes" onclick="switchContentTab('suffixes')">标题后缀</button>
                        </div>
                        <div style="margin-left:auto;display:flex;gap:8px;align-items:center">
                            <label class="small" style="margin:0">选择分组</label>
                            <select id="content_group_select" style="padding:6px;border-radius:6px;border:1px solid rgba(0,0,0,0.06)"></select>
                        </div>
                    </div>

                    <div style="display:flex;gap:16px;align-items:flex-start;flex-wrap:wrap;margin-bottom:18px">
                        <div style="min-width:320px;flex:1;background:#fff;padding:14px;border-radius:10px;box-shadow:0 1px 3px rgba(0,0,0,0.04)">
                            <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
                                <h4 id="content_tab_title">关键词管理</h4>
                                <div style="margin-left:auto;display:flex;gap:8px">
                                    <button class="btn" id="openAddResourceBtn" style="background:#06c">添加</button>
                                    <button class="btn" id="refreshFilesBtn" style="background:#6b7280">刷新</button>
                                </div>
                            </div>
                            <div style="margin-bottom:8px;color:var(--muted)">选择分组和类型后，可在此添加或上传文本内容，系统会保存为文本文件。</div>
                            <form id="contentUploadForm" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:8px">
                                <div style="display:flex;gap:8px;align-items:center">
                                    <label class="small" style="margin:0">目标分组</label>
                                    <select id="content_group_select2" style="padding:6px;border-radius:6px;border:1px solid rgba(0,0,0,0.06)"></select>
                                    <label class="small" style="margin:0">类型</label>
                                    <select id="content_type_select" style="padding:6px;border-radius:6px;border:1px solid rgba(0,0,0,0.06)">
                                        <option value="keywords">关键词</option>
                                        <option value="columns">栏目</option>
                                        <option value="tips">Tips</option>
                                        <option value="suffixes">标题后缀</option>
                                    </select>
                                </div>
                                <div style="display:flex;gap:8px"><input type="file" name="file" id="content_file_input" accept=".txt,.csv,text/*"><button type="button" class="btn" id="uploadContentBtn">上传文件</button></div>
                            </form>
                        </div>

                        <div style="min-width:360px;flex:1;background:#fff;padding:14px;border-radius:10px;box-shadow:0 1px 3px rgba(0,0,0,0.04)">
                            <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
                                <h4 style="margin:0">文件列表</h4>
                                <div style="margin-left:auto;color:var(--muted)">Group: <strong id="currentGroupLabel">{{ $resource_group ?? 'default' }}</strong> • Type: <strong id="currentTypeLabel">{{ $resource_tab ?? 'keywords' }}</strong></div>
                            </div>
                            <div id="contentFilesList" style="min-height:160px">
                                @if(!empty($resource_files) && is_array($resource_files))
                                    @foreach($resource_files as $f)
                                        <div style="display:flex;justify-content:space-between;align-items:center;padding:8px;border-bottom:1px solid rgba(0,0,0,0.04)">
                                            <div>
                                                <div style="font-weight:600">{{ $f['file'] }}</div>
                                                <div class="muted">{{ $f['mtime'] }} • {{ $f['size'] }} bytes</div>
                                            </div>
                                            <div style="display:flex;gap:8px">
                                                <a class="btn" href="#" style="background:#10b981" onclick="event.preventDefault(); window.location='{{ url('/data/group') }}/'+encodeURIComponent('{{ $resource_group ?? 'default' }}')+'/'+encodeURIComponent('{{ $resource_tab ?? 'keywords' }}')+'/'+encodeURIComponent('{{ $f['file'] }}')">下载</a>
                                                <form method="POST" action="{{ route('admin.content.delete_file') }}" style="display:inline">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="hidden" name="group" value="{{ $resource_group ?? 'default' }}">
                                                    <input type="hidden" name="type" value="{{ $resource_tab ?? 'keywords' }}">
                                                    <input type="hidden" name="file" value="{{ $f['file'] }}">
                                                    <button class="btn" style="background:#dc3545">删除</button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="muted">暂无文件</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <script>
                        // Load groups into selects
                        function loadGroupsToSelects(){
                            fetch('{{ route('admin.sites.json') }}').then(r=>r.json()).then(list=>{
                                var sel = document.getElementById('content_group_select');
                                var sel2 = document.getElementById('content_group_select2');
                                if(!sel || !sel2) return;
                                sel.innerHTML = '';
                                sel2.innerHTML = '';
                                list.forEach(function(g){ var opt = document.createElement('option'); opt.value = g.name; opt.textContent = g.name; sel.appendChild(opt); sel2.appendChild(opt.cloneNode(true)); });
                                // set selects to server-provided values if present
                                try{
                                    var serverGroup = '{{ $resource_group ?? '' }}';
                                    var serverTab = '{{ $resource_tab ?? '' }}';
                                    if(serverGroup && sel.querySelector('option[value="'+serverGroup+'"]')){ sel.value = serverGroup; sel2.value = serverGroup; document.getElementById('currentGroupLabel').textContent = serverGroup; }
                                    var t = new URLSearchParams(window.location.search || '').get('tab') || serverTab || 'keywords';
                                    switchContentTab(t);
                                    if(!sel.value && sel.options.length) sel.value = sel.options[0].value;
                                }catch(e){ console.warn(e); }
                            }).catch(e=>{ console.warn('无法加载分组列表', e); });
                        }

                        var currentContentType = 'keywords';
                        function switchContentTab(t){ currentContentType = t; document.getElementById('content_tab_title').innerText = (t==='keywords'?'关键词管理':(t==='columns'?'栏目管理':(t==='tips'?'Tips 管理':'标题后缀'))); document.getElementById('content_type_select').value = t; }

                        document.getElementById('uploadContentBtn').addEventListener('click', function(){
                            var f = document.getElementById('content_file_input').files[0];
                            var grp = document.getElementById('content_group_select2').value;
                            var type = document.getElementById('content_type_select').value;
                            if(!f){ alert('请选择文件'); return; }
                            if(!grp){ alert('请选择分组'); return; }
                            var fd = new FormData(); fd.append('file', f); fd.append('group', grp); fd.append('type', type);
                            var btn = this; btn.disabled = true; btn.textContent = '上传中...';
                            fetch('{{ route('admin.content.upload') }}', { method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: fd }).then(r=>r.json()).then(j=>{ if(j.ok){ showToast('上传成功','success'); window.location = '{{ route('admin.content.resources') }}?tab='+encodeURIComponent(type)+'&group='+encodeURIComponent(grp); } else { showToast('上传失败: '+(j.message||''),'error'); } }).catch(e=>{ showToast('网络错误: '+e.message,'error'); }).finally(()=>{ btn.disabled=false; btn.textContent='上传文件'; });
                        });

                        function loadFilesForCurrent(){
                            var grp = document.getElementById('content_group_select')?.value || document.getElementById('content_group_select2')?.value || '';
                            var type = document.getElementById('content_type_select')?.value || currentContentType;
                            if(!grp){ document.getElementById('contentFilesList').innerHTML = '<div class="muted">请选择分组</div>'; return; }
                            document.getElementById('contentFilesList').innerHTML = '加载中...';
                            fetch('{{ route('admin.content.files') }}?group=' + encodeURIComponent(grp) + '&type=' + encodeURIComponent(type)).then(r=>r.json()).then(j=>{
                                if(!j.ok){ document.getElementById('contentFilesList').innerHTML = '<div class="muted">加载失败</div>'; return; }
                                var el = document.getElementById('contentFilesList'); el.innerHTML = '';
                                if(!j.files || !j.files.length){ el.innerHTML = '<div class="muted">暂无文件</div>'; return; }
                                j.files.forEach(function(f){ var row = document.createElement('div'); row.style.display='flex'; row.style.justifyContent='space-between'; row.style.alignItems='center'; row.style.padding='6px 8px'; var left = document.createElement('div'); left.innerHTML = '<strong>'+f.file+'</strong><div class="muted">'+f.mtime+' • '+f.size+' bytes</div>'; var right = document.createElement('div'); var del = document.createElement('button'); del.className='btn'; del.style.background='#dc3545'; del.textContent='删除'; del.addEventListener('click', function(){ if(!confirm('确认删除 '+f.file+' ?')) return; fetch('{{ route('admin.content.delete_file') }}', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}, body: JSON.stringify({ group: grp, type: type, file: f.file }) }).then(r=>r.json()).then(j=>{ if(j.ok){ showToast('已删除','success'); loadFilesForCurrent(); } else { showToast('删除失败: '+(j.message||''),'error'); } }).catch(e=>{ showToast('网络错误: '+e.message,'error'); }); }); right.appendChild(del); row.appendChild(left); row.appendChild(right); el.appendChild(row); });
                            }).catch(e=>{ document.getElementById('contentFilesList').innerHTML = '<div class="muted">加载失败</div>'; });
                        }

                        document.getElementById('refreshFilesBtn').addEventListener('click', function(){
                            var grp = document.getElementById('content_group_select')?.value || document.getElementById('content_group_select2')?.value || '';
                            var type = document.getElementById('content_type_select')?.value || currentContentType;
                            if(!grp) return alert('请选择分组');
                            window.location = '{{ route('admin.content.resources') }}?tab='+encodeURIComponent(type)+'&group='+encodeURIComponent(grp);
                        });
                        document.getElementById('content_group_select')?.addEventListener('change', function(e){ document.getElementById('content_group_select2').value = e.target.value; });
                        document.getElementById('content_group_select2')?.addEventListener('change', function(e){ document.getElementById('content_group_select').value = e.target.value; });
                        document.getElementById('content_type_select')?.addEventListener('change', function(e){ document.getElementById('currentTypeLabel').textContent = e.target.value; });

                        // open Add modal
                        document.getElementById('openAddResourceBtn').addEventListener('click', function(){
                            var grp = document.getElementById('content_group_select2').value || '{{ $resource_group ?? 'default' }}';
                            var type = document.getElementById('content_type_select').value || '{{ $resource_tab ?? 'keywords' }}';
                            openModal({ title: '添加资源文件', html: `
                                <div style="display:flex;flex-direction:column;gap:8px">
                                    <label class="small">文件名</label>
                                    <input id="add_res_name" value="new.txt" style="padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px">
                                    <label class="small">内容</label>
                                    <textarea id="add_res_content" style="min-height:160px;padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px"></textarea>
                                    <input type="hidden" id="add_res_group" value="`+grp+`">
                                    <input type="hidden" id="add_res_type" value="`+type+`">
                                </div>
                            `, onSave: function(){
                                var name = document.getElementById('add_res_name').value || 'new.txt';
                                var content = document.getElementById('add_res_content').value || '';
                                var grp2 = document.getElementById('add_res_group').value;
                                var type2 = document.getElementById('add_res_type').value;
                                var fd = new FormData(); fd.append('_token','{{ csrf_token() }}'); fd.append('name', name); fd.append('content', content); fd.append('group', grp2); fd.append('type', type2);
                                fetch('{{ route('admin.content.resources.add') }}', { method:'POST', body: fd }).then(function(){ window.location='{{ route('admin.content.resources') }}?tab='+encodeURIComponent(type2)+'&group='+encodeURIComponent(grp2); });
                            }});
                        });
                        // initialize on load
                        document.addEventListener('DOMContentLoaded', function(){
                            // Immediately set tab from URL so clicking left-nav links with ?tab=columns (etc.) works
                            try{
                                var params = new URLSearchParams(window.location.search || '');
                                var t = params.get('tab') || 'keywords';
                                if(t) switchContentTab(t);
                            }catch(e){ console.warn(e); }

                            loadGroupsToSelects();
                            setTimeout(loadFilesForCurrent, 500);
                        });
                    </script>

                    

              

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
    
    <!-- Modals for add/edit group and model -->
    <div id="modalBackdrop" class="modal-backdrop" role="dialog" aria-hidden="true">
        <div class="modal" role="document" aria-modal="true">
            <div class="modal-header">
                <div id="modalTitle">...</div>
                <button id="modalCloseBtn" class="modal-close" aria-label="Close">✕</button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- dynamic content inserted here -->
            </div>
            <div class="modal-footer" id="modalFooter">
                <button class="btn" id="modalSaveBtn">保存</button>
                <button class="btn" id="modalCancelBtn" style="background:#6b7280">取消</button>
            </div>
        </div>
    </div>

    <!-- Toast container -->
    <div id="toastContainer" style="position:fixed;right:18px;bottom:18px;z-index:10000;display:flex;flex-direction:column;gap:8px"></div>

    <script>
        (function(){
            const backdrop = document.getElementById('modalBackdrop');
            const titleEl = document.getElementById('modalTitle');
            const bodyEl = document.getElementById('modalBody');
            const saveBtn = document.getElementById('modalSaveBtn');
            const closeBtn = document.getElementById('modalCloseBtn');
            const cancelBtn = document.getElementById('modalCancelBtn');

            function openModal(opts){
                // opts: { title, html, onSave }
                titleEl.innerText = opts.title || '';
                bodyEl.innerHTML = opts.html || '';
                backdrop.classList.add('show');
                backdrop.setAttribute('aria-hidden','false');
                // attach save handler
                saveBtn._onClick && saveBtn.removeEventListener('click', saveBtn._onClick);
                saveBtn._onClick = function(e){ e.preventDefault(); if(typeof opts.onSave === 'function') opts.onSave(); };
                saveBtn.addEventListener('click', saveBtn._onClick);
            }

            function closeModal(){
                backdrop.classList.remove('show');
                backdrop.setAttribute('aria-hidden','true');
            }

            closeBtn.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', closeModal);
            backdrop.addEventListener('click', function(e){ if(e.target === backdrop) closeModal(); });

            // Expose helpers for group and model modals
            window.openGroupModal = function(existing){
                const html = `
                    <div>
                        <label class="small">分组名称</label>
                        <input type="text" id="modal_group_name" style="width:100%;padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px" value="${(existing && existing.name) ? existing.name.replace(/"/g,'&quot;') : ''}">
                    </div>
                    <div style="margin-top:8px">
                        <label class="small">域名 (每行一条)</label>
                        <textarea id="modal_group_domains" style="width:100%;min-height:120px;border:1px solid rgba(0,0,0,0.06);padding:8px;border-radius:8px">${(existing && Array.isArray(existing.domains)) ? existing.domains.join('\n') : ''}</textarea>
                    </div>
                    <div style="display:flex;gap:8px;align-items:center;margin-top:8px">
                        <label><input type="checkbox" id="modal_force_www" ${existing && existing.force_www ? 'checked' : ''}> 强制跳转到 www</label>
                        <label><input type="checkbox" id="modal_force_mobile" ${existing && existing.force_mobile ? 'checked' : ''}> 移动端跳转到 m.wap</label>
                    </div>
                    <div style="margin-top:8px">
                                <label class="small">默认模型</label>
                                <select id="modal_group_model_select" style="width:100%;padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px">
                                    <option value="">(无)</option>
                                </select>
                                <input type="hidden" id="modal_group_model_hidden" value="${(existing && existing.model) ? existing.model : ''}">
                            </div>
                    <div style="margin-top:8px">
                        <label class="small">模板 (可选)</label>
                        <input type="text" id="modal_group_template" style="width:100%;padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px" value="${(existing && existing.template) ? existing.template : ''}" placeholder="模板名或路径">
                    </div>
                `;
                openModal({ title: existing ? '编辑分组' : '添加分组', html: html, onSave: function(){
                    // Collect values from modal
                    var obj = {};
                    obj.name = document.getElementById('modal_group_name').value || '';
                    obj.domains = (document.getElementById('modal_group_domains').value || '').split('\n').map(s=>s.trim()).filter(Boolean);
                    obj.force_www = !!document.getElementById('modal_force_www').checked;
                    obj.force_mobile = !!document.getElementById('modal_force_mobile').checked;
                    var sel = document.getElementById('modal_group_model_select');
                    obj.model = (sel && sel.value) ? sel.value : (document.getElementById('modal_group_model_hidden').value || '');
                    obj.template = document.getElementById('modal_group_template').value || '';
                    if (!obj.name) { showToast('分组名不能为空', 'error'); return; }
                    // POST JSON to server
                    fetch('{{ route('admin.sites.json') }}', { method: 'POST', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}' }, body: JSON.stringify(obj) })
                        .then(r=>r.json().then(j=>({s:r.status,j:j})))
                        .then(res=>{
                            if(res.s>=200 && res.s<300 && res.j.ok){ showToast(res.j.message || '已保存', 'success'); closeModal(); setTimeout(()=>location.reload(),400); }
                            else { showToast(res.j.message || '保存失败', 'error'); }
                        }).catch(e=>{ showToast('网络错误: '+e.message,'error'); });
                } });
                // After modal content inserted, populate models select
                setTimeout(function(){
                    var sel = document.getElementById('modal_group_model_select');
                    var hidden = document.getElementById('modal_group_model_hidden');
                    if(!sel) return;
                    fetch('{{ route('admin.models.list') }}').then(r=>r.json()).then(list=>{
                        // clear
                        sel.innerHTML = '<option value="">(无)</option>';
                        list.forEach(function(m){
                            var opt = document.createElement('option');
                            opt.value = m.key || m['key'] || '';
                            opt.textContent = (m.name || m['name'] || opt.value) + (opt.value ? ' ('+opt.value+')' : '');
                            sel.appendChild(opt);
                        });
                        if(hidden && hidden.value){ sel.value = hidden.value; }
                    }).catch(e=>{ console.error('无法加载模型列表', e); });
                });
                    // after models select populated, also wire autosize for domains textarea to avoid inner scrollbar
                
                setTimeout(function(){
                    var sel = document.getElementById('modal_group_model_select');
                    var hidden = document.getElementById('modal_group_model_hidden');
                    if(sel){
                        fetch('{{ route('admin.models.list') }}').then(r=>r.json()).then(list=>{
                            // clear
                            sel.innerHTML = '<option value="">(无)</option>';
                            list.forEach(function(m){
                                var opt = document.createElement('option');
                                opt.value = m.key || m['key'] || '';
                                opt.textContent = (m.name || m['name'] || opt.value) + (opt.value ? ' ('+opt.value+')' : '');
                                sel.appendChild(opt);
                            });
                            if(hidden && hidden.value){ sel.value = hidden.value; }
                        }).catch(e=>{ console.error('无法加载模型列表', e); });
                    }

                    // auto-resize helper for domains textarea to eliminate ugly scrollbars
                    function autosizeTextarea(el){
                        if(!el) return;
                        el.style.overflow = 'hidden';
                        el.style.resize = 'none';
                        el.style.height = 'auto';
                        var h = el.scrollHeight;
                        // add a tiny buffer
                        el.style.height = (h + 2) + 'px';
                    }
                    var ta = document.getElementById('modal_group_domains');
                    if(ta){
                        // initialize size and bind input
                        autosizeTextarea(ta);
                        ta.addEventListener('input', function(){ autosizeTextarea(ta); });
                    }
                }, 80);
            };

            window.openModelModal = function(existing){
                const html = `
                    <div>
                        <label class="small">显示名称</label>
                        <input type="text" id="modal_model_name" style="width:100%;padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px" value="${(existing && existing.name) ? existing.name.replace(/"/g,'&quot;') : ''}">
                    </div>
                    <div style="margin-top:8px">
                        <label class="small">唯一 key (英数字和下划线)</label>
                        <input type="text" id="modal_model_key" style="width:100%;padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px" value="${(existing && existing.key) ? existing.key : ''}">
                    </div>
                    <div style="margin-top:8px">
                        <label class="small">模板映射 (多行，格式: <code>key => template.html</code>，例如: <code>page => template.html</code>)</label>
                        <textarea id="modal_model_template" style="width:100%;min-height:160px;border:1px solid rgba(0,0,0,0.06);padding:8px;border-radius:8px" placeholder="page => template.html\nlist => list.html"></textarea>
                    </div>
                `;
                openModal({ title: existing ? '编辑模型' : '添加模型', html: html, onSave: function(){
                    // parse multiline mapping into object
                    var tmplTxt = document.getElementById('modal_model_template').value || '';
                    var lines = tmplTxt.split(/\r?\n/).map(l=>l.trim()).filter(Boolean);
                    var mapping = {};
                    lines.forEach(function(ln){
                        var parts = ln.split(/=>/);
                        if(parts.length >= 2){
                            var k = parts[0].trim();
                            var v = parts.slice(1).join('=>').trim();
                            if(k) mapping[k] = v;
                        }
                    });
                    // ensure defaults
                    if(Object.keys(mapping).length === 0){ mapping = { page: 'template.html', list: 'list.html' }; }
                    if(!mapping.page) mapping.page = 'template.html';
                    if(!mapping.list) mapping.list = mapping.list || 'list.html';

                    var name = document.getElementById('modal_model_name').value || '';
                    var key = document.getElementById('modal_model_key').value || '';
                    var models = [];
                    models.push({ name: name, key: key, template: mapping });
                    document.getElementById('models_json_input').value = JSON.stringify(models);
                    document.getElementById('modelForm').submit();
                } });

                // after modal inserted, prefill template textarea from existing.template if present
                setTimeout(function(){
                    try{
                        var ta = document.getElementById('modal_model_template');
                        if(!ta) return;
                        if(existing && existing.template){
                            if(typeof existing.template === 'string'){
                                ta.value = 'page => ' + existing.template;
                            } else if(typeof existing.template === 'object'){
                                var lines = [];
                                Object.keys(existing.template).forEach(function(k){ lines.push(k + ' => ' + existing.template[k]); });
                                ta.value = lines.join('\n');
                            }
                        } else {
                            ta.value = 'page => template.html\nlist => list.html';
                        }
                    }catch(e){console.error(e);}
                }, 60);
            };

            // Replace the existing '添加' buttons with modal triggers where appropriate
            // Add click handlers to existing edit links
            try{
                // For groups: change loadGroup to open modal
                window.loadGroup = function(g){ openGroupModal(g); };
                // For models: change editModel to open modal
                window.editModel = function(m){ openModelModal(m); };
                // Open add group modal
                document.getElementById('openAddGroupBtn')?.addEventListener('click', function(){ openGroupModal(null); });
            }catch(e){ console.error(e); }
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
        // Quick-range buttons helper (1 week, 1 month, 6 months, 1 year)
        function setQuickRange(days, fromId, toId){
            var to = new Date();
            var from = new Date();
            from.setDate(to.getDate() - parseInt(days,10) + 1);
            function toYMD(d){ return d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0'); }
            var elFrom = document.getElementById(fromId); var elTo = document.getElementById(toId);
            if(elFrom) elFrom.value = toYMD(from);
            if(elTo) elTo.value = toYMD(to);
        }
        document.addEventListener('click', function(e){
            var btn = e.target.closest && e.target.closest('.range-btn');
            if(!btn) return;
            e.preventDefault();
            var days = btn.getAttribute('data-range');
            // find the nearest form container to decide which inputs to set
            var form = btn.closest && btn.closest('form');
            if(!form) return;
            var fromId = null, toId = null;
            if(form.id === 'logsRangeForm'){ fromId='logs_from'; toId='logs_to'; }
            else if(form.id === 'spidersRangeForm'){ fromId='spiders_from'; toId='spiders_to'; }
            else if(form.id === 'oplogsRangeForm'){ fromId='op_from'; toId='op_to'; }
            if(fromId && toId){
                setQuickRange(days, fromId, toId);
                // toggle active class on sibling buttons
                var grp = form.querySelectorAll('.range-btn');
                grp.forEach(function(b){ b.classList.remove('range-active'); });
                btn.classList.add('range-active');
                // auto-submit form
                setTimeout(function(){ form.submit(); }, 120);
            }
        });

        window.addEventListener('load', function(){ initChart('logs-chart','#0b84ff'); initChart('spiders-chart','#ff7a59'); });
        window.addEventListener('resize', function(){ setTimeout(function(){ initChart('logs-chart','#0b84ff'); initChart('spiders-chart','#ff7a59'); }, 120); });
    </script>
</body>
</html>
