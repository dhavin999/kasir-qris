<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role setelah login berhasil
            $role = Auth::user()->role->name;
            if ($role === 'Admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'Kasir') {
                return redirect()->route('kasir.dashboard');
            } elseif ($role === 'Owner') {
                return redirect()->route('owner.dashboard');
            }

            return redirect('/');
        }

        
            return back()->withErrors([
            'email' => 'Email atau password yang kamu masukkan salah.',
            ])->onlyInput('email');
    }

    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

}

