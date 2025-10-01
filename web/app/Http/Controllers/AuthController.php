<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Show setup (first-run) page
    public function setup()
    {
        $pwFile = base_path('data') . DIRECTORY_SEPARATOR . 'password.txt';
        $password = file_exists($pwFile) ? null : (function() use ($pwFile) {
            $p = Str::random(32);
            file_put_contents($pwFile, $p);
            return $p;
        })();
        return view('setup', ['password' => $password]);
    }

    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $pwFile = base_path('data') . DIRECTORY_SEPARATOR . 'password.txt';
        if (!file_exists($pwFile)) {
            return redirect()->route('setup');
        }
        $expected = trim(file_get_contents($pwFile));
        $provided = $request->input('password', '');
        if (hash_equals($expected, $provided)) {
            $request->session()->put('is_admin', true);
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('login.form')->withErrors(['password' => '无效的密码']);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('is_admin');
        return redirect('/');
    }
}
