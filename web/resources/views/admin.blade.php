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
    /* Stronger visible borders for all tables site-wide */
    table{width:100%;border-collapse:collapse;border:1px solid rgba(0,0,0,0.08)}
    table, table th, table td{border:1px solid rgba(0,0,0,0.08)}
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
                    <a href="{{ route('admin.settings') }}" class="{{ ($page ?? '') === 'settings' ? 'active' : '' }}" data-config="data/settings.json"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-settings"></use></svg></span>系统设置</a>
                    <a href="{{ route('admin.password') }}" class="{{ ($page ?? '') === 'password' ? 'active' : '' }}"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-password"></use></svg></span>修改密码</a>
                    <a href="{{ route('admin.operation_logs') }}" class="{{ ($page ?? '') === 'operation_logs' ? 'active' : '' }}"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-operation"></use></svg></span>操作日志</a>
                    <a href="{{ route('admin.repair') }}" class="{{ ($page ?? '') === 'repair' ? 'active' : '' }}"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-repair"></use></svg></span>系统修复</a>
                </div>

                <div class="section-toggle" data-section="sites" role="button" tabindex="0">
                    <span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-menu"></use></svg></span>
                    站群管理
                    <span class="caret" data-caret-for="sites">›</span>
                </div>
                @php $ctab = request()->query('tab') ?? 'keywords'; @endphp
                <div class="submenu" data-section="sites">
                    <a href="{{ route('admin.sites') }}" class="{{ ($page ?? '') === 'sites' ? 'active' : '' }}" data-config="data/sites.json"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-sites"></use></svg></span>网站管理</a>
                    <a href="{{ route('admin.models') }}" class="{{ ($page ?? '') === 'models' ? 'active' : '' }}" data-config="data/models.json"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-models"></use></svg></span>模型管理</a>
                    <a href="{{ route('admin.content.resources') }}?tab=keywords" class="{{ ($page ?? '') === 'content_resources' && $ctab === 'keywords' ? 'active' : '' }}" data-config="data/group/{group}/{type}/"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-list"></use></svg></span>关键词管理</a>
                    <a href="{{ route('admin.content.resources') }}?tab=columns" class="{{ ($page ?? '') === 'content_resources' && $ctab === 'columns' ? 'active' : '' }}" data-config="data/group/{group}/{type}/"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-list"></use></svg></span>栏目管理</a>
                    <a href="{{ route('admin.content.resources') }}?tab=tips" class="{{ ($page ?? '') === 'content_resources' && $ctab === 'tips' ? 'active' : '' }}" data-config="data/group/{group}/{type}/"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-list"></use></svg></span>Tips 管理</a>
                    <a href="{{ route('admin.content.resources') }}?tab=suffixes" class="{{ ($page ?? '') === 'content_resources' && $ctab === 'suffixes' ? 'active' : '' }}" data-config="data/group/{group}/{type}/"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-list"></use></svg></span>标题后缀</a>
                </div>

                <div class="section-toggle" data-section="content" role="button" tabindex="0">
                    <span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-ai"></use></svg></span>
                    内容管理
                    <span class="caret" data-caret-for="content">›</span>
                </div>
                    <div class="submenu" data-section="content">
                    <a href="{{ route('admin.content.ai') }}" class="{{ ($page ?? '') === 'content_ai' ? 'active' : '' }}"><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M17.5 19H9a7 7 0 1 1 0-14h8.5a5.5 5.5 0 1 1 0 11z"/></svg></span>AI 生成</a>
                    <a href="{{ route('admin.content.manage') }}" class="{{ ($page ?? '') === 'content_manage' ? 'active' : '' }}" data-config="data/article/published/ , data/article/unpublished/"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-list"></use></svg></span>内容列表</a>
                    <a href="{{ route('admin.content.collection') }}" class="{{ ($page ?? '') === 'content_collection' ? 'active' : '' }}"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-collection"></use></svg></span>采集管理</a>
                    <a href="{{ route('admin.api') }}" class="{{ ($page ?? '') === 'api_management' ? 'active' : '' }}" data-config="data/api/config.json"><span class="icon"><svg class="icon" viewBox="0 0 24 24"><use xlink:href="#icon-api"></use></svg></span>API 管理</a>
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
            <style>
                /* Improve table readability: subtle striping and padded content blocks */
                .content-inner { background: #f8fafc; padding: 8px; border-radius: 8px; }
                /* Generic card/panel class to replace inline white panels */
                .card { background: var(__card); padding: 14px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
                .card .card-header { display:flex;align-items:center;gap:12px;margin-bottom:10px }
                .card .card-body { margin-top:8px }
                /* Alert boxes (errors/warnings) */
                .alert { padding:8px;border-radius:6px;margin-bottom:12px }
                .alert-error { background:#fff6f6;color:#9b1c1c;border:1px solid #ffd3d3 }
                .theme-dark .alert { background: #0e1b26 !important; color: #ffd6d6 !important; border-color: rgba(255,100,100,0.12) !important; }
                .content-inner table { width:100%; border-collapse:collapse; }
                .content-inner table tbody tr:nth-child(odd) { background: #ffffff; }
                .content-inner table tbody tr:nth-child(even) { background: #fbfdff; }
                .content-inner table th, .content-inner table td { padding: 8px 10px; }
                /* Apply similar readable styles to other admin tables */
                table { border-collapse: collapse; }
                table th { background: rgba(0,0,0,0.04); color: #111827; padding: 8px 10px; text-align: left; }
                table td { padding: 8px 10px; }
                table tbody tr:nth-child(odd) td { background: #ffffff; }
                table tbody tr:nth-child(even) td { background: #fbfdff; }
                /* Ensure table text color contrasts with background in both light/dark themes */
                .theme-light table td, .theme-light table th { color: #111827; }
                .theme-dark table td, .theme-dark table th { color: #e6eef8; }
                /* Dark theme: use dark row backgrounds and darker container to avoid light-on-light issues */
                .theme-dark .content-inner { background: #071026; }
                .theme-dark table tbody tr:nth-child(odd) td { background: #081524; }
                .theme-dark table tbody tr:nth-child(even) td { background: #06101c; }
                .theme-dark table th { background: rgba(255,255,255,0.04); }
                /* Specific fix for Sites/Models list links to ensure visibility */
                /* Success alerts */
                .alert-success { background: #ecffed; color: #064e2a; border: 1px solid rgba(6,78,42,0.06); }
                .theme-dark .alert-success { background: #06231a !important; color: #9be6c9 !important; border-color: rgba(155, 222, 199, 0.06) !important; }
                /* Pager current state (replaces inline background usage) */
                .pager-current { background: rgba(0,0,0,0.06); }
                .theme-dark .pager-current { background: rgba(255,255,255,0.06); }
                /* Small pager summary box (replaces inline white box) */
                .pager-summary { padding:6px 8px; border-radius:6px; display:inline-block; background:#fff; }
                .theme-dark .pager-summary { background: #071026 !important; color: #e6eef8 !important; box-shadow: 0 1px 3px rgba(0,0,0,0.6) !important; }
                .theme-light a { color: #0b60d0; }
                .theme-dark a { color: #9cc7ff; }
                /* Override common inline white card backgrounds in dark mode to avoid visible white borders */
                .theme-dark [style*="background:#fff"] { background-color: #071026 !important; color: #e6eef8 !important; box-shadow: 0 1px 3px rgba(0,0,0,0.6) !important; }
                /* Catch common inline white backgrounds / small light panels and neutralize them in dark mode */
                .theme-dark [style*="background:#fff"],
                .theme-dark [style*="background: #fff"],
                .theme-dark [style*="background:#ffffff"],
                .theme-dark [style*="background:#fff;"],
                .theme-dark [style*="background: #fff;"],
                .theme-dark [style*="background:#fff "] {
                    background-color: #071026 !important;
                    color: #e6eef8 !important;
                    box-shadow: none !important;
                    border-color: rgba(255,255,255,0.04) !important;
                }
                .theme-dark [style*="background: rgba(0,0,0,0.02)"],
                .theme-dark [style*="background:rgba(0,0,0,0.02)"] {
                    background: rgba(255,255,255,0.02) !important;
                }
                /* Inline borders often use rgba(0,0,0,0.06) — make them subtle in dark theme */
                .theme-dark [style*="border:1px solid rgba(0,0,0,0.06)"],
                .theme-dark [style*="border: 1px solid rgba(0,0,0,0.06)"] {
                    border-color: rgba(255,255,255,0.04) !important;
                }
                /* Prevent bright outlines/box-shadows from inline-styled elements */
                .theme-dark [style*="box-shadow"], .theme-dark [style*="box-shadow:"] { box-shadow: none !important; }
                .theme-dark .btn { box-shadow: none; }
                /* Remove bright focus outlines/ rings in dark mode for elements that may get them via browser defaults or inline styles */
                .theme-dark :where(button, a, input, textarea, select):focus { outline: none !important; box-shadow: 0 0 0 2px rgba(255,255,255,0.02) !important; }
                .theme-dark :where(button[style*="background:#"], [style*="background:#fff"]) { box-shadow: none !important; }
            </style>
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
                    {{ $titles[$page ?? 'home'] ?? ($page ? ucfirst($page) : '未知') }}
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
                    <hr style="margin:12px 0;border:none;border-top:1px solid rgba(0,0,0,0.06)">
                    <h3>后台路径配置</h3>
                    <p class="muted">为了提高安全性，你可以自定义后台访问路径（例如将 <code>/admin</code> 改为 <code>/管理面板</code> 或其他）。请仅使用字母、数字、下划线或短横线。</p>
                    @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
                    <form method="POST" action="{{ route('admin.settings.save') }}" style="display:flex;gap:8px;align-items:center">
                        @csrf
                        <label class="small" style="margin:0">后台路径前缀</label>
                        <input type="text" name="admin_prefix" value="{{ $admin_config['prefix'] ?? 'admin' }}" style="padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px">
                        <button class="btn" type="submit">保存</button>
                    </form>

                @elseif(($page ?? '') === 'sites')
                    <h2>站点分组管理</h2>
                    @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

                    <div style="display:flex;gap:16px;align-items:flex-start;flex-wrap:wrap;margin-bottom:18px">
                        <div class="card" style="min-width:360px;flex:2;">
                            <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
                                <h4 style="margin:0">分组管理</h4>
                                <div style="margin-left:12px;display:flex;gap:6px">
                                    <button id="sitesTabListBtn" class="btn" style="background:transparent;border:1px solid rgba(0,0,0,0.06);padding:6px 10px">列表</button>
                                    <button id="sitesTabAddBtn" class="btn" style="background:#06c;color:#fff;padding:6px 10px">添加分组</button>
                                </div>
                                <div style="margin-left:auto;color:var(--muted)">共 <strong>{{ count($groups ?? []) }}</strong> 个分组</div>
                            </div>

                            <div id="sitesListPanel">
                                @if(empty($groups))
                                    <div class="muted">尚未配置任何分组。</div>
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

                            <div id="sitesAddPanel" style="display:none">
                                <div style="display:flex;justify-content:space-between;align-items:center">
                                    <h3 id="groupFormTitle">分组</h3>
                                    <button type="button" class="btn" id="openAddGroupBtn">添加分组</button>
                                </div>
                                <!-- keep inline form as fallback but hide it in favor of modal -->
                                <div id="groupFormContainer" style="margin-top:12px">
                                    <p class="muted">使用弹窗添加或编辑分组。若弹窗不可用会回退到下面的备用表单。</p>
                                    <div style="display:none">
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
                        </div>
                    </div>

                    <!-- Descriptions Manager (optional) -->
                    @if(!empty($show_descriptions))
                        <hr style="margin:18px 0;border:none;border-top:1px solid rgba(0,0,0,0.06)" />
                        <h3>描述管理（Desriptions）</h3>
                        <p class="muted">可为每个分组定义描述模板。模板中可使用变量：{keyword}, {tips}, {ip}, {time}</p>
                        <div style="display:flex;gap:12px;align-items:center;margin-bottom:12px;flex-wrap:wrap">
                            <label class="small">选择分组</label>
                            <select id="desc_group_select" style="padding:6px;border-radius:6px;border:1px solid rgba(0,0,0,0.06)">
                                @foreach($groups as $g)
                                    @php $gkey = $g['key'] ?? ($g['name'] ?? ''); @endphp
                                    <option value="{{ $gkey }}" {{ (isset($descriptions_group) && $descriptions_group === $gkey) ? 'selected' : '' }}>{{ $g['name'] ?? $gkey }}</option>
                                @endforeach
                            </select>
                            <div style="margin-left:auto"><button id="addDescriptionBtn" class="btn">添加模板</button></div>
                        </div>

                        <div id="descriptionsList" style="background:rgba(0,0,0,0.02);padding:8px;border-radius:8px">
                            @if(!empty($descriptions_list))
                                <table>
                                    <thead><tr><th>名称</th><th>模板预览</th><th>操作</th></tr></thead>
                                    <tbody>
                                    @foreach($descriptions_list as $d)
                                        <tr data-id="{{ $d['id'] ?? '' }}">
                                            <td>{{ $d['name'] ?? '' }}</td>
                                            <td style="max-width:560px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="{{ $d['template'] ?? '' }}">{{ $d['template'] ?? '' }}</td>
                                            <td>
                                                <button class="btn desc-edit-btn" data-id="{{ $d['id'] ?? '' }}">编辑</button>
                                                <button class="btn" style="background:#dc3545" data-id="{{ $d['id'] ?? '' }}" class="desc-delete-btn">删除</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="muted">暂无描述模板。</div>
                            @endif
                        </div>

                        <script>
                            (function(){
                                var token = '{{ csrf_token() }}';
                                function loadDescriptions(){
                                    var grp = document.getElementById('desc_group_select').value || 'default';
                                    fetch('{{ route('admin.sites.descriptions.json') }}?group=' + encodeURIComponent(grp)).then(r=>r.json()).then(list=>{
                                        var el = document.getElementById('descriptionsList'); if(!el) return;
                                        if(!list || !list.length){ el.innerHTML = '<div class="muted">暂无描述模板。</div>'; return; }
                                        var html = '<table><thead><tr><th>名称</th><th>模板预览</th><th>操作</th></tr></thead><tbody>';
                                        list.forEach(function(it){ html += '<tr data-id="'+(it.id||'')+'"><td>'+escapeHtml(it.name||'')+'</td><td style="max-width:560px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="'+escapeHtml(it.template||'')+'">'+escapeHtml(it.template||'')+'</td><td><button class="btn desc-edit-btn" data-id="'+(it.id||'')+'">编辑</button> <button class="btn desc-delete" style="background:#dc3545" data-id="'+(it.id||'')+'">删除</button></td></tr>'; });
                                        html += '</tbody></table>';
                                        el.innerHTML = html;
                                    }).catch(e=>{ console.error(e); });
                                }
                                function escapeHtml(s){ return (s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;'); }

                                document.getElementById('desc_group_select')?.addEventListener('change', function(){ loadDescriptions(); });

                                document.getElementById('addDescriptionBtn')?.addEventListener('click', function(){
                                    openModal({ title: '添加描述模板', html: '<div><label class="small">名称</label><input id="desc_modal_name" style="width:100%;padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px">\n<label class="small" style="margin-top:8px">模板 (可用变量 {keyword} {tips} {ip} {time})</label><textarea id="desc_modal_template" style="width:100%;min-height:160px;padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px"></textarea></div>', onSave: function(){
                                        var grp = document.getElementById('desc_group_select').value || 'default';
                                        var name = document.getElementById('desc_modal_name').value || '';
                                        var tpl = document.getElementById('desc_modal_template').value || '';
                                        if(!name || !tpl){ showToast('名称和模板不能为空','error'); return; }
                                        fetch('{{ route('admin.sites.descriptions.save') }}', { method:'POST', headers: {'Content-Type':'application/json','X-CSRF-TOKEN': token}, body: JSON.stringify({ group: grp, name: name, template: tpl }) }).then(r=>r.json()).then(j=>{ if(j.ok){ showToast('已保存','success'); closeModal(); loadDescriptions(); } else { showToast('保存失败: '+(j.message||''),'error'); } }).catch(e=>{ showToast('网络错误','error'); console.error(e); });
                                    } });
                                });

                                // delegated edit/delete handlers
                                document.addEventListener('click', function(e){
                                    var ed = e.target.closest && e.target.closest('.desc-edit-btn');
                                    if(ed){ var id = ed.getAttribute('data-id'); var grp = document.getElementById('desc_group_select').value || 'default'; fetch('{{ route('admin.sites.descriptions.json') }}?group='+encodeURIComponent(grp)).then(r=>r.json()).then(list=>{ var it = (list||[]).find(x=> (x.id||'')===id); if(!it){ showToast('未找到模板','error'); return; } openModal({ title: '编辑模板', html: '<div><label class="small">名称</label><input id="desc_modal_name" style="width:100%;padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px" value="'+escapeHtml(it.name||'')+'">\n<label class="small" style="margin-top:8px">模板</label><textarea id="desc_modal_template" style="width:100%;min-height:160px;padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px">'+escapeHtml(it.template||'')+'</textarea></div>', onSave:function(){ var name = document.getElementById('desc_modal_name').value || ''; var tpl = document.getElementById('desc_modal_template').value || ''; if(!name||!tpl){ showToast('名称和模板不能为空','error'); return; } fetch('{{ route('admin.sites.descriptions.save') }}', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN': token}, body: JSON.stringify({ group: grp, id: id, name: name, template: tpl }) }).then(r=>r.json()).then(j=>{ if(j.ok){ showToast('已保存','success'); closeModal(); loadDescriptions(); } else { showToast('保存失败: '+(j.message||''),'error'); } }).catch(e=>{ showToast('网络错误','error'); }); } }); }).catch(e=>{ console.error(e); }); return; }

                                    var del = e.target.closest && e.target.closest('.desc-delete');
                                    if(del){ if(!confirm('确认删除该模板？')) return; var id = del.getAttribute('data-id'); var grp = document.getElementById('desc_group_select').value || 'default'; fetch('{{ route('admin.sites.descriptions.delete') }}', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN': token}, body: JSON.stringify({ group: grp, id: id }) }).then(r=>r.json()).then(j=>{ if(j.ok){ showToast('已删除','success'); loadDescriptions(); } else { showToast('删除失败: '+(j.message||''),'error'); } }).catch(e=>{ showToast('网络错误','error'); console.error(e); }); return; }
                                });

                                // initial load
                                document.addEventListener('DOMContentLoaded', function(){ loadDescriptions(); });
                            })();
                        </script>
                    @endif

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
                        // Ensure group remove links on the Sites page trigger deletion
                        document.addEventListener('click', function(e){
                            var ra = e.target.closest && e.target.closest('.group-remove-link');
                            if(ra){ e.preventDefault(); var name = ra.getAttribute('data-group-name') || ''; if(!name) { showToast('分组名缺失','error'); return; } removeGroup(name); return; }
                        });
                        document.getElementById('exportGroupsBtn').addEventListener('click', function(){
                            var g = { name: document.getElementById('group_name').value || 'group', domains: (document.getElementById('group_domains').value||'').split('\n').map(s=>s.trim()).filter(Boolean), force_www: !!document.getElementById('force_www').checked, force_mobile: !!document.getElementById('force_mobile').checked, model: document.getElementById('group_model').value||'', template: document.getElementById('group_template').value||'' };
                            var blob = new Blob([JSON.stringify(g, null, 2)], {type:'application/json'});
                            var url = URL.createObjectURL(blob); var a = document.createElement('a'); a.href = url; a.download = (g.name||'group') + '.json'; document.body.appendChild(a); a.click(); a.remove();
                        });
                    </script>

                @elseif(($page ?? '') === 'models')
                    <h2>模型管理</h2>
                    @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

                    <div style="display:flex;gap:16px;align-items:flex-start;flex-wrap:wrap;margin-bottom:18px">
                        <div class="card" style="min-width:360px;flex:2;">
                            <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
                                <h4 style="margin:0">模型管理</h4>
                                <div style="margin-left:12px;display:flex;gap:6px">
                                    <button id="modelsTabListBtn" class="btn" style="background:transparent;border:1px solid rgba(0,0,0,0.06);padding:6px 10px">列表</button>
                                    <button id="modelsTabAddBtn" class="btn" style="background:#06c;color:#fff;padding:6px 10px">添加模型</button>
                                </div>
                                <div style="margin-left:auto;color:var(--muted)">共 <strong>{{ count($modelsList ?? []) }}</strong> 个模型</div>
                            </div>

                            <div id="modelsListPanel">
                                @if(empty($modelsList))
                                    <div class="muted">暂无模型。</div>
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
                                                <td>
                                                    <a href="#" class="model-edit-link" data-model-b64='{{ $m_b64 }}'>编辑</a>
                                                    &nbsp;|&nbsp;
                                                    <a href="#" class="model-delete-link" data-model-key="{{ $m['key'] ?? '' }}" style="color:#dc3545">删除</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>

                            <div id="modelsAddPanel" style="display:none">
                                <div style="margin-top:8px">
                                    <p class="muted">模型通过弹出框管理，点击“添加模型”或编辑一条模型以打开管理对话框。</p>
                                    <button id="openAddModelBtn" class="btn">添加模型</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        // Legacy inline model form removed — use modal-based flow.
                        // Provide a minimal safe editModel fallback (no-op if modal unavailable)
                        function editModel(m){
                            try{ if(window.openModelModal) return openModelModal(m); }catch(e){ console.error(e); }
                        }
                        // Ensure the "添加模型" button opens the modal
                        document.getElementById('openAddModelBtn')?.addEventListener('click', function(){ if(window.openModelModal) openModelModal(null); });
                    </script>

                    <script>
                        // Delegated handlers for dynamic edit/remove links to avoid inline onclick with embedded JSON
                        document.addEventListener('click', function(e){
                            // group edit
                            var a = e.target.closest && e.target.closest('.group-edit-link');
                            if(a){ e.preventDefault(); try{ var raw = a.getAttribute('data-group-b64') || a.getAttribute('data-group') || ''; var jsonStr = raw; if(raw && a.getAttribute('data-group-b64')){ try{ jsonStr = decodeURIComponent(escape(window.atob(raw))); }catch(e){ try{ jsonStr = window.atob(raw); }catch(e2){ jsonStr = raw; } } } var g = JSON.parse(jsonStr || '{}'); if(window.openGroupModal) openGroupModal(g); else if(window.loadGroup) loadGroup(g); }catch(err){ console.error('parse group json', err); } return; }
                            var ra = e.target.closest && e.target.closest('.group-remove-link');
                            if(ra){
                                e.preventDefault();
                                var name = ra.getAttribute('data-group-name') || '';
                                if(!name){ showToast('分组名缺失','error'); return; }
                                if(!confirm('确认删除分组：' + name + ' ?')) return;
                                fetch('{{ route('admin.sites.delete') }}', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}, body: JSON.stringify({ name: name }) })
                                    .then(function(r){ return r.json().then(function(j){ return { status: r.status, json: j }; }); })
                                    .then(function(res){ if(res.status >=200 && res.status < 300 && res.json && res.json.ok){ showToast('分组已删除','success'); setTimeout(function(){ location.reload(); }, 350); } else { showToast('删除失败: '+(res.json && res.json.message ? res.json.message : ('HTTP ' + res.status)),'error'); } })
                                    .catch(function(e){ showToast('网络错误: ' + (e && e.message ? e.message : e),'error'); console.error(e); });
                                return;
                            }
                            var ma = e.target.closest && e.target.closest('.model-edit-link');
                            if(ma){ e.preventDefault(); try{ var rawm = ma.getAttribute('data-model-b64') || ma.getAttribute('data-model') || ''; var jsonM = rawm; if(rawm && ma.getAttribute('data-model-b64')){ try{ jsonM = decodeURIComponent(escape(window.atob(rawm))); }catch(e){ try{ jsonM = window.atob(rawm); }catch(e2){ jsonM = rawm; } } } var m = JSON.parse(jsonM || '{}'); if(window.openModelModal) openModelModal(m); else if(window.editModel) editModel(m); }catch(err){ console.error('parse model json', err); } return; }
                            var md = e.target.closest && e.target.closest('.model-delete-link');
                            if(md){ e.preventDefault(); var key = md.getAttribute('data-model-key') || ''; if(!key) return; if(!confirm('确认删除模型：' + key + ' ?')) return; fetch('{{ route('admin.models.delete') }}', { method: 'POST', headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}, body: JSON.stringify({ key: key }) }).then(r=>r.json()).then(j=>{ if(j && j.ok){ showToast('模型已删除','success'); setTimeout(()=>location.reload(),350); } else { showToast('删除失败: '+(j.message||''),'error'); } }).catch(e=>{ showToast('网络错误: '+e.message,'error'); }); return; }
                        });
                    </script>

                @elseif(($page ?? '') === 'access_ip')
                    <h2>访问 IP 控制</h2>
                    @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
                    
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
                    @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

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
                    @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
                    @if($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif
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
                            <!-- 筛选现在为自动触发（更改即筛选） -->
                            <a class="btn" href="{{ route('admin.logs') }}?from={{ $from ?? ($date ?? date('Y-m-d')) }}&to={{ $to ?? ($date ?? date('Y-m-d')) }}&download=access">下载 CSV</a>
                        </div>
                    </form>

                    @if($errors->any())
                        <div class="alert alert-error">{{ $errors->first() }}</div>
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
                                            <a class="pager-btn {{ $p == $cp ? 'pager-current' : '' }}" href="?from={{ $from }}&to={{ $to }}&ip={{ $ip }}&q={{ $q }}&page={{ $p }}&per_page={{ $per }}">{{ $p }}</a>
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
                            <!-- 筛选现在为自动触发（更改即筛选） -->
                            <a class="btn" href="{{ route('admin.spiders') }}?from={{ $from ?? ($date ?? date('Y-m-d')) }}&to={{ $to ?? ($date ?? date('Y-m-d')) }}&download=spider">下载 CSV</a>
                        </div>
                    </form>

                    @if($errors->any())
                        <div class="alert alert-error">{{ $errors->first() }}</div>
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
                                            <a class="pager-btn {{ $p == $cp ? 'pager-current' : '' }}" href="?from={{ $from }}&to={{ $to }}&bot={{ $bot }}&ip={{ $ip }}&page={{ $p }}&per_page={{ $per }}">{{ $p }}</a>
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
                            <!-- 筛选现在为自动触发（更改即筛选） -->
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
                                    <th style="padding:8px">负载</th>
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
                                    <a href="?from={{ $from }}&to={{ $to }}&q={{ $q }}&page={{ $p }}&per_page={{ $per }}" class="pager-btn {{ $p == ($currentPage ?? 1) ? 'pager-current' : '' }}" style="padding:6px 8px;border-radius:6px">{{ $p }}</a>
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
                    @if($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif

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
                            <pre style="white-space:pre-wrap">curl -X POST "{{ rtrim(url('/'), '/') }}{{ isset($apiConfigJson) ? (json_decode($apiConfigJson, true)['endpoint'] ?? '/api/articles') : '/api/articles' }}" -H "X-API-Key: {{ session('api_key') ?? (isset($apiConfigJson) ? (json_decode($apiConfigJson, true)['key'] ?? 'your-api-key') : 'your-api-key') }}" -d "title=标题&summary=摘要&content=内容&publish=1"</pre>
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
                    <form id="contentFiltersForm" method="GET" action="#" style="display:flex;gap:12px;align-items:center;margin-bottom:16px;flex-wrap:wrap">
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
                    </form>

                    <div id="contentListContainer">
                    <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:12px">
                        @if(!empty($articles) && is_array($articles))
                            <div class="content-inner">
                                <table>
                                    <thead>
                                        <tr style="text-align:left">
                                            <th style="width:64px">序号</th>
                                            <th>标题</th>
                                            <th style="width:420px">摘要</th>
                                            <th style="width:120px">来源</th>
                                            <th style="width:110px">状态</th>
                                            <th style="width:160px">创建时间</th>
                                            <th style="width:120px">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $startIndex = (($currentPage ?? 1)-1) * ($perPage ?? 10); @endphp
                                        @foreach($articles as $art)
                                            @php
                                                $title = $art['title'] ?? ($art['id'] ?? 'Untitled');
                                                $created = $art['created_at'] ?? '';
                                                $source = $art['source'] ?? ($art['origin'] ?? 'unknown');
                                                $status = $art['status'] ?? (isset($art['published']) && $art['published'] ? 'published' : 'unpublished');
                                                $id = $art['id'] ?? ($art['uuid'] ?? '');
                                                $rawContent = trim(preg_replace('/\s+/',' ', strip_tags($art['content'] ?? '')));
                                                $excerpt = \Illuminate\Support\Str::limit($rawContent, 220, '...');
                                                $rawUrl = url('/data/article') . '/' . ($status === 'published' ? 'published' : 'unpublished') . '/' . ($id ? urlencode($id) . '.json' : '');
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->index + 1 + $startIndex }}</td>
                                                <td style="max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="{{ $title }}">{{ $title }}</td>
                                                <td style="max-width:420px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="{{ $rawContent }}">{{ $excerpt }}</td>
                                                <td class="muted">{{ $source }}</td>
                                                <td class="muted">{{ $status }}</td>
                                                <td class="muted">{{ $created }}</td>
                                                <td><a class="btn" href="{{ $rawUrl }}" style="background:#10b981">查看JSON</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="muted">暂无文章</div>
                        @endif

                        {{-- Pagination --}}
                        @php $cp = $currentPage ?? 1; $pgs = $pages ?? 1; @endphp
                        @if($pgs > 1)
                            <div style="display:flex;gap:8px;align-items:center;margin-top:8px">
                                @if($cp>1)
                                    <a class="btn" href="?page={{ $cp-1 }}&from={{ urlencode($f_from ?? '') }}&to={{ urlencode($f_to ?? '') }}&source={{ urlencode($f_source ?? '') }}&status={{ urlencode($f_status ?? '') }}">上一页</a>
                                @endif
                                <div class="pager-summary">第 {{ $cp }} / {{ $pgs }} 页</div>
                                @if($cp<$pgs)
                                    <a class="btn" href="?page={{ $cp+1 }}&from={{ urlencode($f_from ?? '') }}&to={{ urlencode($f_to ?? '') }}&source={{ urlencode($f_source ?? '') }}&status={{ urlencode($f_status ?? '') }}">下一页</a>
                                @endif
                            </div>
                        @endif
                    </div>
                    </div>

                    @elseif(($page ?? '') === 'content_resources')
                    @php
                        $tabLabelMap = [ 'keywords' => '关键词管理', 'columns' => '栏目管理', 'tips' => 'Tips 管理', 'suffixes' => '标题后缀' ];
                        $curTab = $resource_tab ?? (request()->query('tab') ?? 'keywords');
                        $pageTitle = $tabLabelMap[$curTab] ?? '内容资源管理';
                    @endphp
                    <h2>{{ $pageTitle }}</h2>
                    <div style="display:flex;gap:12px;align-items:center;margin-bottom:12px;flex-wrap:wrap">
                        <div style="margin-left:auto;display:flex;gap:8px;align-items:center">
                            <label class="small" style="margin:0">选择分组</label>
                            <select id="content_group_select" style="padding:6px;border-radius:6px;border:1px solid rgba(0,0,0,0.06)"></select>
                        </div>
                    </div>

                    <div style="display:flex;gap:16px;align-items:flex-start;flex-wrap:wrap;margin-bottom:18px">
                        <div class="card" style="min-width:360px;flex:2;">
                            <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
                                <h4 style="margin:0">文件列表</h4>
                                <div style="margin-left:12px;display:flex;gap:6px">
                                    <button id="tabListBtn" class="btn" style="background:transparent;border:1px solid rgba(0,0,0,0.06);padding:6px 10px">列表</button>
                                    <button id="tabAddBtn" class="btn" style="background:#06c;color:#fff;padding:6px 10px">添加文档</button>
                                </div>
                                <div style="margin-left:auto;color:var(--muted)">Group: <strong id="currentGroupLabel">{{ $resource_group ?? 'default' }}</strong> • Type: <strong id="currentTypeLabel">{{ $resource_tab ?? 'keywords' }}</strong></div>
                            </div>

                            <!-- Panels: list and inline add -->
                            <div id="contentListPanel">
                                <div id="contentFilesList" style="min-height:160px">
                                    @if(!empty($resource_files) && is_array($resource_files))
                                        @foreach($resource_files as $f)
                                            <div style="display:flex;justify-content:space-between;align-items:center;padding:8px;border-bottom:1px solid rgba(0,0,0,0.04)">
                                                <!-- File entry -->
                                                    <div class="card" style="flex:1 1 100%;min-width:0;">
                                                    <div style="display:flex;gap:8px">
                                                        @php $fgroup = $f['group'] ?? ($resource_group ?? 'default'); @endphp
                                                        <a class="btn" href="#" style="background:#10b981" onclick="event.preventDefault(); window.location='{{ url('/data/group') }}/'+encodeURIComponent('{{ $fgroup }}')+'/'+encodeURIComponent('{{ $resource_tab ?? 'keywords' }}')+'/'+encodeURIComponent('{{ $f['file'] }}')">下载</a>
                                                        <button class="btn delete-resource-btn" data-file="{{ $f['file'] }}" data-group="{{ $fgroup }}" data-type="{{ $resource_tab ?? 'keywords' }}" style="background:#dc3545">删除</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="muted">暂无文件</div>
                                    @endif
                                </div>
                            </div>

                            <div id="contentAddPanel" style="display:none">
                                <div style="display:flex;flex-direction:column;gap:8px">
                                    <label class="small">目标分组</label>
                                    <select id="add_inline_group" style="padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px"></select>
                                    <select id="add_inline_type" style="display:none">
                                        <option value="keywords">keywords</option>
                                        <option value="columns">columns</option>
                                        <option value="tips">tips</option>
                                        <option value="suffixes">suffixes</option>
                                    </select>
                                    <label class="small">上传文件（优先）</label>
                                    <input type="file" id="add_inline_file" accept=".txt,.csv,text/*">
                                    <label class="small">或直接输入文本内容（若上传了文件则以文件为准）</label>
                                    <input id="add_inline_name" placeholder="文件名（如不上传则需要填写）" style="padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px">
                                    <textarea id="add_inline_content" style="min-height:160px;padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px"></textarea>
                                    <div id="add_inline_error" style="color:#dc3545;display:none;margin-top:6px"></div>
                                    <div style="display:flex;gap:8px">
                                        <button id="add_inline_submit" class="btn" style="background:#06c;color:#fff">提交</button>
                                        <button id="add_inline_cancel" class="btn" style="background:#6b7280">取消</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                        <!-- Hidden form used by model modal to submit models JSON safely -->
                        <form id="modelForm" method="POST" action="{{ route('admin.models.save') }}" style="display:none">
                            @csrf
                            <input type="hidden" name="models_json" id="models_json_input" value="">
                            <input type="hidden" name="_orig_key" id="_orig_key_input" value="">
                        </form>

                    <script>
                        // Load groups into selects. Add an "全部" option for filters (not for upload target).
                        function loadGroupsToSelects(){
                                fetch('{{ route('admin.sites.json') }}').then(r=>r.json()).then(list=>{
                                    // populate any known group selects on the page
                                    var selectors = ['content_group_select','content_group_select2','add_inline_group','content_group_select_mobile'];
                                    selectors.forEach(function(id){
                                        var sel = document.getElementById(id);
                                        if(!sel) return;
                                        sel.innerHTML = '';
                                        // for filter selects (content_group_*), add an "全部" option
                                        if(id.indexOf('content_group') === 0 && id !== 'add_inline_group'){
                                            var allOpt = document.createElement('option'); allOpt.value = 'all'; allOpt.textContent = '全部'; sel.appendChild(allOpt);
                                        }
                                        list.forEach(function(g){ var opt = document.createElement('option'); opt.value = (g.key || g.name); opt.textContent = (g.name || g.key || ''); sel.appendChild(opt); });
                                    });
                                    // set selects to server-provided values if present
                                    try{
                                        var serverGroup = '{{ $resource_group ?? 'all' }}';
                                        var serverTab = '{{ $resource_tab ?? '' }}';
                                        if(serverGroup){
                                            var s = document.getElementById('content_group_select'); if(s && s.querySelector('option[value="'+serverGroup+'"]')){ s.value = serverGroup; var opt = s.querySelector('option[value="'+serverGroup+'"]'); document.getElementById('currentGroupLabel').textContent = opt ? opt.textContent : serverGroup; }
                                            var s2 = document.getElementById('content_group_select2'); if(s2 && s2.querySelector('option[value="'+serverGroup+'"]')) { s2.value = serverGroup; }
                                            var a = document.getElementById('add_inline_group'); if(a && a.querySelector('option[value="'+serverGroup+'"]')) a.value = serverGroup;
                                        }
                                        var t = new URLSearchParams(window.location.search || '').get('tab') || serverTab || 'keywords';
                                        switchContentTab(t);
                                    }catch(e){ console.warn(e); }
                                }).catch(e=>{ console.warn('无法加载分组列表', e); });
                        }

                        var currentContentType = 'keywords';
                        function switchContentTab(t){ currentContentType = t; document.getElementById('content_tab_title').innerText = (t==='keywords'?'关键词管理':(t==='columns'?'栏目管理':(t==='tips'?'Tips 管理':'标题后缀'))); document.getElementById('content_type_select').value = t; }

                        // Primary Add button now switches to inline 添加文档 面板
                        document.getElementById('openAddResourceBtnPrimary')?.addEventListener('click', function(){
                            var tabAdd = document.getElementById('tabAddBtn'); if(tabAdd) tabAdd.click();
                            else { try{ document.getElementById('openAddResourceBtn')?.click(); }catch(e){} }
                        });

                        function loadFilesForCurrent(){
                            var grp = document.getElementById('content_group_select')?.value || document.getElementById('content_group_select2')?.value || '';
                            var type = document.getElementById('content_type_select')?.value || currentContentType;
                            // treat empty selection as 'all' (show all groups)
                            if(!grp) grp = 'all';
                            document.getElementById('contentFilesList').innerHTML = '加载中...';
                            fetch('{{ route('admin.content.files') }}?group=' + encodeURIComponent(grp) + '&type=' + encodeURIComponent(type)).then(r=>r.json()).then(j=>{
                                if(!j.ok){ document.getElementById('contentFilesList').innerHTML = '<div class="muted">加载失败</div>'; return; }
                                var el = document.getElementById('contentFilesList'); el.innerHTML = '';
                                if(!j.files || !j.files.length){ el.innerHTML = '<div class="muted">暂无文件</div>'; return; }
                                j.files.forEach(function(f){
                                    var row = document.createElement('div'); row.style.display='flex'; row.style.justifyContent='space-between'; row.style.alignItems='center'; row.style.padding='6px 8px';
                                    var left = document.createElement('div');
                                    var groupLabel = f.group ? ('<span class="muted">['+f.group+'] </span>') : '';
                                    left.innerHTML = groupLabel + '<strong>'+f.file+'</strong><div class="muted">'+f.mtime+' • '+f.size+' bytes</div>';
                                    var right = document.createElement('div');
                                    var dl = document.createElement('a'); dl.className='btn'; dl.href = '#'; dl.style.background = '#10b981'; dl.textContent = '下载';
                                    dl.addEventListener('click', function(ev){ ev.preventDefault(); var gFor = f.group || grp; window.location = '{{ url('/data/group') }}/' + encodeURIComponent(gFor) + '/' + encodeURIComponent(type) + '/' + encodeURIComponent(f.file); });
                                    var del = document.createElement('button'); del.className='btn'; del.style.background='#dc3545'; del.textContent='删除';
                                    del.addEventListener('click', function(){ if(!confirm('确认删除 '+f.file+' ?')) return; var gFor = f.group || grp; fetch('{{ route('admin.content.delete_file') }}', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}, body: JSON.stringify({ group: gFor, type: type, file: f.file }) }).then(r=>r.json()).then(j=>{ if(j.ok){ showToast('已删除','success'); loadFilesForCurrent(); } else { showToast('删除失败: '+(j.message||''),'error'); } }).catch(e=>{ showToast('网络错误: '+e.message,'error'); }); });
                                    right.appendChild(dl); right.appendChild(del); row.appendChild(left); row.appendChild(right); el.appendChild(row);
                                });
                            }).catch(e=>{ document.getElementById('contentFilesList').innerHTML = '<div class="muted">加载失败</div>'; });
                        }

                        document.getElementById('content_group_select')?.addEventListener('change', function(e){
                            var v = e.target.value;
                            var sel2 = document.getElementById('content_group_select2'); if(sel2) sel2.value = v;
                            var curLbl = document.getElementById('currentGroupLabel'); if(curLbl){ var opt = e.target.options[e.target.selectedIndex]; curLbl.textContent = opt ? opt.textContent : v; }
                            // reload files for the newly-selected group
                            try{ loadFilesForCurrent(); } catch(err){ console.warn('loadFilesForCurrent missing', err); }
                        });

                        document.getElementById('content_group_select2')?.addEventListener('change', function(e){
                            var v = e.target.value;
                            var sel1 = document.getElementById('content_group_select'); if(sel1) sel1.value = v;
                            var curLbl = document.getElementById('currentGroupLabel'); if(curLbl){ var opt = e.target.options[e.target.selectedIndex]; curLbl.textContent = opt ? opt.textContent : v; }
                            try{ loadFilesForCurrent(); } catch(err){ console.warn('loadFilesForCurrent missing', err); }
                        });

                        document.getElementById('content_type_select')?.addEventListener('change', function(e){
                            var t = e.target.value;
                            var lbl = document.getElementById('currentTypeLabel'); if(lbl) lbl.textContent = t;
                            try{ switchContentTab(t); loadFilesForCurrent(); } catch(err){ console.warn('switchContentTab/loadFilesForCurrent missing', err); }
                        });

                        // Old modal-based add flow removed in favor of inline 添加文档 面板

                        // bind AJAX delete handlers for server-rendered buttons
                        function bindDeleteButtons(){
                            document.querySelectorAll('.delete-resource-btn').forEach(function(btn){
                                if (btn._bound) return; btn._bound = true;
                                btn.addEventListener('click', function(e){
                                    var file = this.getAttribute('data-file');
                                    var grp = this.getAttribute('data-group');
                                    var type = this.getAttribute('data-type');
                                    if(!confirm('确认删除 '+file+' ?')) return;
                                    fetch('{{ route('admin.content.delete_file') }}', { method:'POST', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}' }, body: JSON.stringify({ group: grp, type: type, file: file }) }).then(r=>r.json()).then(j=>{ if(j.ok){ showToast('已删除','success'); loadFilesForCurrent(); } else { showToast('删除失败: '+(j.message||''),'error'); } }).catch(e=>{ showToast('网络错误: '+e.message,'error'); });
                                });
                            });
                        }
                        // Inline Add/List tab wiring
                        function initInlineTabs(){
                            var tabList = document.getElementById('tabListBtn');
                            var tabAdd = document.getElementById('tabAddBtn');
                            var panelList = document.getElementById('contentListPanel');
                            var panelAdd = document.getElementById('contentAddPanel');
                            function showList(){ panelList.style.display='block'; panelAdd.style.display='none'; tabList.style.opacity=1; tabAdd.style.opacity=0.6; }
                            function showAdd(){ panelList.style.display='none'; panelAdd.style.display='block'; tabList.style.opacity=0.6; tabAdd.style.opacity=1; }
                            tabList?.addEventListener('click', showList);
                            tabAdd?.addEventListener('click', showAdd);

                            // Populate inline group/type selects
                            fetch('{{ route('admin.sites.json') }}').then(r=>r.json()).then(list=>{
                                var g = document.getElementById('add_inline_group'); if(!g) return;
                                g.innerHTML = '';
                                list.forEach(function(item){ var o = document.createElement('option'); o.value = (item.key || item.name); o.textContent = (item.name || item.key || ''); g.appendChild(o); });
                                var cur = '{{ $resource_group ?? 'default' }}'; if(g.querySelector('option[value="'+cur+'"]')) g.value = cur;
                                document.getElementById('add_inline_type').value = '{{ $resource_tab ?? 'keywords' }}';
                            }).catch(e=>{ console.warn('无法加载分组', e); });

                            // cancel button returns to list
                            document.getElementById('add_inline_cancel')?.addEventListener('click', function(){ showList(); });

                            // submit inline add
                            function submitInlineAdd(e){
                                try{
                                    // prevent re-entrancy
                                    if(submitInlineAdd._running) return;
                                    var btn = null;
                                    if (e && e.currentTarget && e.currentTarget.id === 'add_inline_submit') btn = e.currentTarget;
                                    else if (e && e.target && e.target.closest) btn = e.target.closest('#add_inline_submit');
                                    if(!btn) return;
                                    var errEl = document.getElementById('add_inline_error'); if(errEl){ errEl.style.display='none'; errEl.textContent=''; }
                                    btn.disabled = true; btn.style.opacity = '0.6';
                                    submitInlineAdd._running = true;
                                    var grpEl = document.getElementById('add_inline_group');
                                    var grp = (grpEl && grpEl.value) ? grpEl.value : '{{ $resource_group ?? 'default' }}';
                                    var typeEl = document.getElementById('add_inline_type');
                                    var type = (typeEl && typeEl.value) ? typeEl.value : '{{ $resource_tab ?? 'keywords' }}';
                                    var name = (document.getElementById('add_inline_name') && document.getElementById('add_inline_name').value) ? document.getElementById('add_inline_name').value : 'new.txt';
                                    var content = (document.getElementById('add_inline_content') && document.getElementById('add_inline_content').value) ? document.getElementById('add_inline_content').value : '';
                                    var fileInput = document.getElementById('add_inline_file');
                                    var fd = new FormData(); fd.append('_token','{{ csrf_token() }}'); fd.append('group', grp); fd.append('type', type);
                                    if (fileInput && fileInput.files && fileInput.files[0]) {
                                        // Client-side size check to avoid server 413 responses.
                                        var MAX_BYTES = 2 * 1024 * 1024; // must match server-side limit
                                        if (fileInput.files[0].size > MAX_BYTES) {
                                            var msg = '文件太大，最大允许 2 MB';
                                            if (errEl) { errEl.style.display = 'block'; errEl.textContent = msg; }
                                            showToast(msg, 'error');
                                            btn.disabled = false; btn.style.opacity = '1'; submitInlineAdd._running = false;
                                            return;
                                        }
                                        fd.append('file', fileInput.files[0]);
                                    }
                                    else { fd.append('name', name); fd.append('content', content); }

                                    fetch('{{ route('admin.content.resources.add') }}', { method:'POST', body: fd }).then(function(r){
                                        return r.json().then(function(j){ return { status: r.status, body: j }; });
                                    }).then(function(resp){
                                        var j = resp.body;
                                        if (j && j.ok){ showToast('已添加','success'); try{ showList(); }catch(e){}; loadFilesForCurrent(); }
                                        else { var msg = (j && j.message) ? j.message : ('HTTP ' + resp.status); if(errEl){ errEl.style.display='block'; errEl.textContent = msg; } showToast('添加失败: '+msg,'error'); }
                                    }).catch(function(e){ if(errEl){ errEl.style.display='block'; errEl.textContent = e.message; } showToast('网络错误: '+e.message,'error'); })
                                    .finally(function(){ btn.disabled = false; btn.style.opacity = '1'; submitInlineAdd._running = false; });
                                }catch(ex){ console.error('submitInlineAdd failed', ex); }
                            }

                            // Primary binding
                            var inlineBtn = document.getElementById('add_inline_submit'); if(inlineBtn){ if(!inlineBtn._bound){ inlineBtn.addEventListener('click', submitInlineAdd); inlineBtn._bound = true; } }
                            // Fallback delegated binding in case of dynamic replacement (add this delegated handler only once)
                            if(!window._submitInlineDelegatedAdded){
                                window._submitInlineDelegatedAdded = true;
                                document.addEventListener('click', function(ev){
                                    var b = ev.target.closest && ev.target.closest('#add_inline_submit');
                                    if(!b) return;
                                    ev.preventDefault();
                                    // If primary button has explicit binding, skip delegated call (primary handler will run)
                                    if(b._bound) return;
                                    // Also skip if a submission is currently running
                                    if(submitInlineAdd._running) return;
                                    submitInlineAdd(ev);
                                });
                            }
                        }
                        // initialize on load
                        document.addEventListener('DOMContentLoaded', function(){
                            // Immediately set tab from URL so clicking left-nav links with ?tab=columns (etc.) works
                            try{
                                var params = new URLSearchParams(window.location.search || '');
                                var t = params.get('tab') || 'keywords';
                                if(t) switchContentTab(t);
                            }catch(e){ console.warn(e); }

                            loadGroupsToSelects();
                            setTimeout(function(){ loadFilesForCurrent(); bindDeleteButtons(); initInlineTabs(); }, 500);
                            // Initialize Sites and Models tab panels
                            try{ initSitesTabs(); }catch(e){}
                            try{ initModelsTabs(); }catch(e){}
                        });
                    </script>

                    <script>
                        // Sites tab wiring
                        function initSitesTabs(){
                            var listBtn = document.getElementById('sitesTabListBtn');
                            var addBtn = document.getElementById('sitesTabAddBtn');
                            var listPanel = document.getElementById('sitesListPanel');
                            var addPanel = document.getElementById('sitesAddPanel');
                            if(!listBtn || !addBtn || !listPanel || !addPanel) return;
                            function showList(){ listPanel.style.display='block'; addPanel.style.display='none'; listBtn.style.opacity=1; addBtn.style.opacity=0.6; }
                            function showAdd(){ listPanel.style.display='none'; addPanel.style.display='block'; listBtn.style.opacity=0.6; addBtn.style.opacity=1; }
                            listBtn.addEventListener('click', showList);
                            addBtn.addEventListener('click', showAdd);
                            // default to list
                            showList();
                        }

                        // Models tab wiring
                        function initModelsTabs(){
                            var listBtn = document.getElementById('modelsTabListBtn');
                            var addBtn = document.getElementById('modelsTabAddBtn');
                            var listPanel = document.getElementById('modelsListPanel');
                            var addPanel = document.getElementById('modelsAddPanel');
                            if(!listBtn || !addBtn || !listPanel || !addPanel) return;
                            function showList(){ listPanel.style.display='block'; addPanel.style.display='none'; listBtn.style.opacity=1; addBtn.style.opacity=0.6; }
                            function showAdd(){ listPanel.style.display='none'; addPanel.style.display='block'; listBtn.style.opacity=0.6; addBtn.style.opacity=1; }
                            listBtn.addEventListener('click', showList);
                            addBtn.addEventListener('click', showAdd);
                            // default to list
                            showList();
                        }
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
    <script>
        // Auto-submit filter forms on change (debounced)
        (function(){
            var formIds = ['contentFiltersForm','logsRangeForm','spidersRangeForm','oplogsRangeForm'];
            formIds.forEach(function(fid){
                var f = document.getElementById(fid);
                if(!f) return;
                (function(form){
                    var timeout = null;
                    function submitDebounced(){
                        if(timeout) clearTimeout(timeout);
                        timeout = setTimeout(function(){ try{ form.submit(); }catch(e){ console.warn('submit failed', e); } }, 250);
                    }
                    Array.from(form.querySelectorAll('input, select')).forEach(function(el){
                        el.addEventListener('change', submitDebounced);
                        el.addEventListener('input', submitDebounced);
                    });
                })(f);
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

    <!-- Config tooltip (shows file path on hover) -->
    <div id="configTooltip" style="position:fixed;pointer-events:none;display:none;z-index:11000;padding:8px 10px;background:rgba(0,0,0,0.8);color:#fff;border-radius:6px;font-size:12px;max-width:320px;word-break:break-all"></div>

    <script>
        // Global toast helper (single canonical definition)
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
                        <label class="small">唯一 key (英数字和下划线)</label>
                        <input type="text" id="modal_group_key" style="width:100%;padding:8px;border:1px solid rgba(0,0,0,0.06);border-radius:6px" value="${(existing && existing.key) ? existing.key : ''}" title="用于内部标识，建议小写字母、数字和下划线">
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
                    obj.key = document.getElementById('modal_group_key')?.value || '';
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
                // After modal content inserted, populate models select and wire key auto-generation
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

                    // Auto-generate unique key for new groups and lock key on edit
                    try{
                        var keyInp = document.getElementById('modal_group_key');
                        var nameInp = document.getElementById('modal_group_name');
                        var cachedGroups = [];
                        // load existing groups to check uniqueness
                        fetch('{{ route('admin.sites.json') }}').then(r=>r.json()).then(list=>{ cachedGroups = list || []; }).catch(()=>{ cachedGroups = []; });

                        function slugify(s){ return (s||'').toLowerCase().replace(/[^a-z0-9_\-]+/g,'_').replace(/(^_+|_+$)/g,''); }

                        if (existing && existing.key) {
                            // editing: lock key
                            if(keyInp){ keyInp.readOnly = true; keyInp.style.background = 'rgba(0,0,0,0.03)'; keyInp.title = '编辑时不允许修改 key'; }
                        } else {
                            var userEditedKey = false;
                            keyInp?.addEventListener('input', function(){ userEditedKey = true; });
                            nameInp?.addEventListener('input', function(){ if(userEditedKey) return; var base = slugify(this.value) || 'group'; var candidate = base; var i = 1; var keys = (cachedGroups||[]).map(function(g){ return (g.key||g.name||'').toLowerCase(); }); while(keys.includes(candidate.toLowerCase())){ candidate = base + '_' + i; i++; } if(keyInp) keyInp.value = candidate; });
                            // initial trigger
                            if(nameInp && !keyInp.value){ nameInp.dispatchEvent(new Event('input')); }
                        }
                    }catch(e){ console.warn('group key generation failed', e); }
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

                // Helper: ensure hidden form exists and set values safely
                function submitModelPayload(modelsArr, origKeyVal){
                    var form = document.getElementById('modelForm');
                    if(!form){
                        // create and append to body as fallback
                        form = document.createElement('form'); form.id = 'modelForm'; form.method = 'POST'; form.action = '{{ route('admin.models.save') }}'; form.style.display = 'none';
                        var token = document.createElement('input'); token.type='hidden'; token.name='_token'; token.value='{{ csrf_token() }}'; form.appendChild(token);
                        var m = document.createElement('input'); m.type='hidden'; m.name='models_json'; m.id='models_json_input'; form.appendChild(m);
                        var o = document.createElement('input'); o.type='hidden'; o.name='_orig_key'; o.id='_orig_key_input'; form.appendChild(o);
                        document.body.appendChild(form);
                    }
                    var mEl = document.getElementById('models_json_input'); if(mEl) mEl.value = JSON.stringify(modelsArr);
                    var oEl = document.getElementById('_orig_key_input'); if(oEl) oEl.value = origKeyVal || '';
                    try{ form.submit(); }catch(e){
                        // fallback: send via fetch if submit fails
                        fetch(form.action, { method: 'POST', headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}, body: JSON.stringify({ models_json: JSON.stringify(modelsArr), _orig_key: origKeyVal || '' }) }).then(r=>{ location.reload(); }).catch(err=>{ alert('保存失败: '+(err.message||err)); });
                    }
                }

                openModal({ title: existing ? '编辑模型' : '添加模型', html: html, onSave: function(){
                    var tmplTxt = document.getElementById('modal_model_template')?.value || '';
                    var mapping = {};
                    // Try parse as JSON first (user pasted JSON)
                    try {
                        var j = JSON.parse(tmplTxt);
                        if (j && typeof j === 'object') {
                            mapping = j;
                        }
                    } catch(e) {
                        // not JSON - fall back to line parsing
                        var lines = tmplTxt.split(/\r?\n/).map(l=>l.trim()).filter(Boolean);
                        lines.forEach(function(ln){
                            var parts = ln.split(/=>/);
                            if(parts.length >= 2){
                                var k = parts[0].trim();
                                var v = parts.slice(1).join('=>').trim();
                                if(k) mapping[k] = v;
                            }
                        });
                    }
                    // ensure defaults if still empty
                    if(Object.keys(mapping).length === 0){ mapping = { page: 'template.html', list: 'list.html' }; }

                    var name = document.getElementById('modal_model_name')?.value || '';
                    var key = document.getElementById('modal_model_key')?.value || '';
                    var models = [];
                    models.push({ name: name, key: key, template: mapping });
                    // orig key for edit
                    var orig = existing && existing.key ? existing.key : '';
                    submitModelPayload(models, orig);
                } });

                // after modal inserted, prefill template textarea from existing.template if present
                setTimeout(function(){
                    try{
                        var ta = document.getElementById('modal_model_template');
                        var nameInp = document.getElementById('modal_model_name');
                        var keyInp = document.getElementById('modal_model_key');
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
                        // Also set the hidden original key field for edit operations if present
                        var origEl = document.getElementById('_orig_key_input'); if(origEl) origEl.value = (existing && existing.key) ? existing.key : '';

                        // If editing, lock the key field and visually indicate it
                        if (existing && existing.key) {
                            if(keyInp){ keyInp.readOnly = true; keyInp.style.background = 'rgba(0,0,0,0.03)'; keyInp.title = '编辑时不允许修改 key'; }
                        } else {
                            // For new models: auto-generate key from name and ensure uniqueness
                            var cachedModels = [];
                            fetch('{{ route('admin.models.list') }}').then(r=>r.json()).then(list=>{ cachedModels = list || []; }).catch(()=>{ cachedModels = []; });
                            function slugify(s){ return (s||'').toLowerCase().replace(/[^a-z0-9_\-]+/g,'_').replace(/^_+|_+$/g,''); }
                            var userEditedKey = false;
                            keyInp?.addEventListener('input', function(){ userEditedKey = true; });
                            nameInp?.addEventListener('input', function(){ if(userEditedKey) return; var base = slugify(this.value) || 'model'; var candidate = base; var i=1; var keys = (cachedModels||[]).map(function(m){ return (m.key||'').toLowerCase(); }); while(keys.includes(candidate.toLowerCase())){ candidate = base + '_' + i; i++; } if(keyInp) keyInp.value = candidate; });
                            // trigger generation on initial load if name present
                            if(nameInp && !keyInp.value){ nameInp.dispatchEvent(new Event('input')); }
                        }

                        // autosize template textarea
                        function autosize(el){ if(!el) return; el.style.overflow='hidden'; el.style.height='auto'; el.style.height = (el.scrollHeight + 4) + 'px'; }
                        autosize(ta); ta.addEventListener('input', function(){ autosize(ta); });
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
    <script>
        // Show config file path tooltip when hovering sidebar links that have data-config
        (function(){
            var tooltip = document.getElementById('configTooltip');
            if(!tooltip) return;
            var hoverDelay = 300; var hideDelay = 120; var enterTimer = null; var leaveTimer = null;

            function show(el){
                var text = el.getAttribute('data-config');
                if(!text) return;
                tooltip.textContent = text;
                tooltip.style.display = 'block';
                var rect = el.getBoundingClientRect();
                var top = rect.top + window.scrollY + rect.height/2 - tooltip.offsetHeight/2;
                var left = rect.right + 12;
                // if overflow right, place to left
                if(left + tooltip.offsetWidth > window.innerWidth - 8){ left = rect.left - tooltip.offsetWidth - 12; }
                // ensure visible Y
                if(top < 8) top = 8; if(top + tooltip.offsetHeight > window.innerHeight - 8) top = window.innerHeight - tooltip.offsetHeight - 8;
                tooltip.style.top = top + 'px'; tooltip.style.left = left + 'px';
            }
            function hide(){ tooltip.style.display = 'none'; }

            document.querySelectorAll('aside.sidebar a[data-config]').forEach(function(a){
                a.addEventListener('mouseenter', function(e){ clearTimeout(leaveTimer); enterTimer = setTimeout(function(){ show(a); }, hoverDelay); });
                a.addEventListener('mouseleave', function(e){ clearTimeout(enterTimer); leaveTimer = setTimeout(function(){ hide(); }, hideDelay); });
            });
        })();
    </script>
    <script>
        // AJAXify content filters: fetch filtered content and replace only the list container
        (function(){
            var form = document.getElementById('contentFiltersForm');
            var container = document.getElementById('contentListContainer');
            if(!form || !container) return;

            // helper: serialize form to query string
            function qsFromForm(f){ var p = new URLSearchParams(); Array.from(f.elements).forEach(function(el){ if(!el.name) return; if(el.type === 'checkbox') p.append(el.name, el.checked ? el.value || '1' : '0'); else p.append(el.name, el.value || ''); }); return p.toString(); }

            // Replace container HTML and rebind internal handlers
            function replaceContainer(html){
                // create a temporary element and extract the relevant fragment (matching id)
                var tmp = document.createElement('div'); tmp.innerHTML = html;
                var frag = tmp.querySelector('#contentListContainer');
                if(!frag) return false;
                container.innerHTML = frag.innerHTML;
                // rebind pagination links inside new container so clicks fetch via AJAX
                bindContainerPagination();
                return true;
            }

            function fetchAndReplace(url, push){
                var el = container; el.innerHTML = '<div class="muted">加载中...</div>';
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } }).then(function(resp){ return resp.text(); }).then(function(text){ var ok = replaceContainer(text); if(push && ok){ try{ history.pushState({}, '', url); }catch(e){} } }).catch(function(e){ el.innerHTML = '<div class="muted">加载失败</div>'; console.error(e); });
            }

            // intercept form submit (also used by debounce script) to do AJAX instead of full reload
            form.addEventListener('submit', function(e){ e.preventDefault(); var q = qsFromForm(form); var url = (location.pathname || '') + '?' + q; fetchAndReplace(url, true); });

            // intercept automatic form submits triggered by change/input handlers
            // replace form.submit to route to AJAX routine
            (function(){
                var originalSubmit = form.submit.bind(form);
                form._originalSubmit = originalSubmit;
                form.submit = function(){ var q = qsFromForm(form); var url = (location.pathname || '') + '?' + q; fetchAndReplace(url, true); };
            })();

            // Bind pagination links inside the container to AJAX
            function bindContainerPagination(){
                var links = container.querySelectorAll('a.pager-btn');
                links.forEach(function(a){ if(a._bound) return; a._bound = true; a.addEventListener('click', function(ev){ ev.preventDefault(); var href = a.getAttribute('href'); if(!href) return; fetchAndReplace(href, true); }); });
            }

            // initial bind
            bindContainerPagination();

            // handle back/forward navigation
            window.addEventListener('popstate', function(){ var q = location.search ? location.pathname + location.search : location.pathname; fetchAndReplace(q, false); });
        })();
    </script>
</body>
</html>
