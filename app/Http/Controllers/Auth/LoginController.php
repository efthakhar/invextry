<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Database\Seeders\DevDemo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginForm()
    {
        if (Auth::check()) {
            return redirect('admin');
        }

        return view('auth.login-form');
    }

    public function login(Request $request)
    {
        // make the whole demo during new login in demosite. comment this portion on production
        $seeder = new DevDemo();
        $seeder->run();

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, 100)) {
            $request->session()->regenerate();
            if (User::find(Auth::id())->can('manage_dashboard')) {

                redirect()->route('admin');
            }

            redirect()->route('home');
        }

        return back()->with('login-error', 'Incorrect Credentials Provided')->onlyInput('email', 'password');
    }

    public function logout(Request $request)
    {

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('loginForm');

    }
}
