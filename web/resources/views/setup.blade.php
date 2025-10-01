<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>初始化设置</title>
</head>
<body>
    <h1>初始化设置</h1>
    
    @if(empty($password))
        <p>密码已设置。若需要查看或重置密码，请通过服务器上的配置或删除 <code>data/password.txt</code> 来重新生成。</p>
        <p>现在你可以<a href="{{ route('login.form') }}">去登陆页面</a>尝试登录。</p>
    @else
        <p>一个密码已经生成，请拷贝它到安全的地方，这个密码不会再次显示。</p>
        <pre style="background:#f2f2f2;padding:1rem;border:1px solid #ddd;">{{ $password }}</pre>
        <p>在保存密码后，你可以<a href="{{ route('login.form') }}">到登陆界面</a>.</p>
    @endif
</body>
</html>
