<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Login</title>
    <style>
        :root{--bg:#f6f8fa;--card:#ffffff;--accent:#2b6cb0;--muted:#6b7280}
        html,body{height:100%;margin:0;font-family:Inter,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:var(--bg);color:#111}
        .container{min-height:100%;display:flex;align-items:center;justify-content:center;padding:24px}
        .card{width:100%;max-width:420px;background:var(--card);border-radius:12px;box-shadow:0 6px 18px rgba(12,18,26,0.08);padding:28px}
        h1{margin:0 0 8px;font-size:20px}
        p.lead{margin:0 0 18px;color:var(--muted)}
        .field{margin-bottom:14px}
        label{display:block;font-size:13px;color:var(--muted);margin-bottom:6px}
        input[type="password"],input[type="text"]{width:100%;padding:10px 12px;border:1px solid #e6e9ef;border-radius:8px;font-size:15px}
        .actions{display:flex;align-items:center;justify-content:space-between;margin-top:12px}
        button.primary{background:var(--accent);color:#fff;border:none;padding:10px 14px;border-radius:8px;cursor:pointer}
        .help{font-size:13px;color:var(--muted)}
        .error{background:#fff6f6;border:1px solid #ffd3d3;padding:10px;border-radius:8px;color:#9b1c1c;margin-bottom:12px}
        .toggle{background:transparent;border:none;color:var(--accent);cursor:pointer;font-size:13px}
        .footer-note{margin-top:16px;font-size:13px;color:var(--muted)}
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>管理员登陆</h1>
            <p class="lead">输入管理员密码</p>

            @if($errors->any())
                <div class="error">
                    <strong>登陆失败</strong>
                    <ul style="margin:8px 0 0;padding-left:18px">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
                <div class="field">
                    <label for="password">管理员密码</label>
                    <div style="display:flex;gap:8px;align-items:center">
                        <input id="password" type="password" name="password" autocomplete="one-time-code" placeholder="输入管理员密码" required autofocus>
                        <button type="button" class="toggle" onclick="togglePassword()">Show</button>
                    </div>
                </div>

                <div class="actions">
                    <div class="help">没有密码<a href="{{ route('setup') }}">打开设置</a></div>
                    <button class="primary" type="submit">登陆</button>
                </div>
            </form>

            <div class="footer-note">提示: 保存密码到安全的地方</div>
        </div>
    </div>

    <script>
        function togglePassword(){
            const p = document.getElementById('password');
            const btn = document.querySelector('.toggle');
            if(p.type === 'password'){ p.type = 'text'; btn.textContent = 'Hide'; }
            else { p.type = 'password'; btn.textContent = 'Show'; }
        }
    </script>
</body>
</html>
