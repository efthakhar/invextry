<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function registrationForm()
    {
        if (Auth::check()) {
            return redirect('admin');
        }

        return view('auth.registration-form');
    }

    public function register(Request $request)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'user_name' => ['required', 'string', 'max:25'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required',  Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // assing created user to subscriber role
        $user->assignRole('subscriber');

        if ($user) {
            //return redirect('/login');
            return back()->with('registration-success', 'Account Created Successfully');
        } else {
            return back();
        }
    }
}
