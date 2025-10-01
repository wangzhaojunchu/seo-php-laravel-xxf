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
    .sidebar{width:260px;background:var(--sidebar);border-right:1px solid rgba(0,0,0,0.06);padding:20px;transition:background .25s;flex:0 0 260px;min-width:260px}
        .brand{font-weight:700;margin-bottom:18px;font-size:18px}
        .nav{margin:12px 0}
        .nav a{display:block;padding:10px 12px;border-radius:8px;color:var(--text);text-decoration:none;margin-bottom:6px;transition:background .15s,color .15s}
        .nav a.active{background:rgba(43,108,176,0.12);color:var(--accent);font-weight:600}
        .submenu{margin-left:8px;padding-left:8px;border-left:1px dashed rgba(0,0,0,0.04)}
        .content{flex:1;padding:24px}
        .topbar{background:transparent}
    .panel{background:var(--card);border-radius:12px;padding:20px;box-shadow:0 8px 28px rgba(2,6,23,0.08);transition:background .25s,box-shadow .25s}
    .content-inner{overflow:auto}
    table{width:100%;border-collapse:collapse}
    table, table th, table td{border:1px solid rgba(0,0,0,0.06)}
    table th, table td{padding:8px}
        pre.log{background:rgba(2,6,23,0.6);color:#d6e6ff;padding:12px;border-radius:8px;overflow:auto;max-height:400px}
        .muted{color:var(--muted)}
        .btn{background:var(--accent);color:#fff;padding:8px 14px;border-radius:10px;border:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;height:38px}
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
    </style>
</head>
<body>
    <div class="wrap">
        <aside class="sidebar">
            <div class="brand">管理员面板</div>
            <nav class="nav">
                <a href="{{ route('admin.dashboard') }}" class="{{ ($page ?? '') === 'home' ? 'active' : '' }}">
                    <span class="icon"><!-- home icon -->
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 11.5L12 4l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V11.5z" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                    管理员首页
                </a>

                <div class="section-toggle" data-section="core-settings" role="button" tabindex="0">
                    <span class="icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a1 1 0 0 1-1.4 1.4l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.3V20a1 1 0 0 1-2 0v-.1a1.7 1.7 0 0 0-1-1.3 1.7 1.7 0 0 0-1.8.3l-.1.1A1 1 0 0 1 7.3 18l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.3-1H6a1 1 0 0 1 0-2h.1a1.7 1.7 0 0 0 1.3-1 1.7 1.7 0 0 0-.3-1.8L7 7.3A1 1 0 0 1 8.4 5.9l.1.1a1.7 1.7 0 0 0 1.8.3h.1A1.7 1.7 0 0 0 12 6.2V6a1 1 0 0 1 2 0v.2a1.7 1.7 0 0 0 1 1.3h.1a1.7 1.7 0 0 0 1.8-.3l.1-.1A1 1 0 0 1 18.6 7l-.1.1a1.7 1.7 0 0 0-.3 1.8c.2.5.6.9 1.1 1.1H19a1 1 0 0 1 0 2h-.1c-.5.2-.9.6-1.1 1.1z" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    核心设置
                    <span class="caret" data-caret-for="core-settings">›</span>
                </div>
                <div class="submenu" data-section="core-settings">
                    <a href="{{ route('admin.settings') }}" class="{{ ($page ?? '') === 'settings' ? 'active' : '' }}">系统设置</a>
                    <a href="{{ route('admin.password') }}" class="{{ ($page ?? '') === 'password' ? 'active' : '' }}">修改密码</a>
                    <a href="{{ route('admin.logs') }}" class="{{ ($page ?? '') === 'logs' ? 'active' : '' }}">操作日志</a>
                    <a href="{{ route('admin.repair') }}" class="{{ ($page ?? '') === 'repair' ? 'active' : '' }}">系统修复</a>
                </div>

                <div class="section-toggle" data-section="sites" role="button" tabindex="0">
                    <span class="icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 13h18M3 6h18M8 20h8" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    站群管理
                    <span class="caret" data-caret-for="sites">›</span>
                </div>
                <div class="submenu" data-section="sites">
                    <a href="{{ route('admin.sites') }}" class="{{ ($page ?? '') === 'sites' ? 'active' : '' }}">网站管理</a>
                    <a href="{{ route('admin.models') }}" class="{{ ($page ?? '') === 'models' ? 'active' : '' }}">模型管理</a>
                </div>

                <div class="section-toggle" data-section="access" role="button" tabindex="0">
                    <span class="icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2v4M6 6v2a6 6 0 0 0 12 0V6" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/><rect x="3" y="10" width="18" height="11" rx="2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    访问控制
                    <span class="caret" data-caret-for="access">›</span>
                </div>
                <div class="submenu" data-section="access">
                    <a href="{{ route('admin.logs') }}" class="{{ ($page ?? '') === 'logs' ? 'active' : '' }}">访问记录</a>
                    <a href="{{ route('admin.spiders') }}" class="{{ ($page ?? '') === 'spiders' ? 'active' : '' }}">蜘蛛记录</a>
                    <a href="{{ route('admin.access.ip') }}" class="{{ ($page ?? '') === 'access_ip' ? 'active' : '' }}">访问IP控制</a>
                    <a href="{{ route('admin.access.ua') }}" class="{{ ($page ?? '') === 'access_ua' ? 'active' : '' }}">访问UA控制</a>
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
                    <form method="POST" action="{{ route('admin.access.ip.save') }}">
                        @csrf
                        <label class="small">默认策略</label>
                        <div style="display:flex;gap:12px;align-items:center;margin-bottom:8px">
                            <label><input type="radio" name="default_mode" value="allow" {{ (isset($ipDefault) && $ipDefault === 'allow') ? 'checked' : (!isset($ipDefault) ? 'checked' : '') }}> 默认允许（白名单）</label>
                            <label><input type="radio" name="default_mode" value="deny" {{ (isset($ipDefault) && $ipDefault === 'deny') ? 'checked' : '' }}> 默认禁止（黑名单）</label>
                        </div>

                        <div style="margin-top:8px">
                            <label class="small">允许访问的 IP / CIDR / 范围（每行一个，例如 192.168.1.10 或 10.0.0.0/8 或 192.168.1.10-192.168.1.13）</label>
                            <textarea name="allow_ips" style="width:100%;min-height:120px;border:1px solid rgba(0,0,0,0.06);padding:8px;border-radius:8px">{{ isset($ipAllow) ? implode("\n", $ipAllow) : '' }}</textarea>
                        </div>

                        <div style="margin-top:8px">
                            <label class="small">禁止访问的 IP / CIDR / 范围（每行一个）</label>
                            <textarea name="deny_ips" style="width:100%;min-height:120px;border:1px solid rgba(0,0,0,0.06);padding:8px;border-radius:8px">{{ isset($ipDeny) ? implode("\n", $ipDeny) : '' }}</textarea>
                        </div>

                        <div style="margin-top:8px"><button class="btn" type="submit">保存</button></div>
                    </form>

                @elseif(($page ?? '') === 'access_ua')
                    <h2>访问 UA 控制</h2>
                    @if(session('status'))<div style="background:#ecffed;padding:8px;border-radius:6px;margin-bottom:12px">{{ session('status') }}</div>@endif
                    <form method="POST" action="{{ route('admin.access.ua.save') }}">
                        @csrf
                        <label class="small">选择常见爬虫 UA（选中将保存为规则）</label>
                        <div style="display:flex;flex-wrap:wrap;gap:8px;margin:8px 0">
                            @foreach($availableBots ?? [] as $bot)
                                <label style="display:inline-flex;align-items:center;gap:6px;padding:6px 8px;border:1px solid rgba(0,0,0,0.06);border-radius:8px;background:transparent">
                                    <input type="checkbox" name="bots[]" value="{{ $bot }}" {{ (isset($uaList) && in_array($bot, $uaList)) ? 'checked' : '' }}>
                                    <span>{{ $bot }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div style="margin-top:8px"><button class="btn" type="submit">保存</button></div>
                    </form>

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
