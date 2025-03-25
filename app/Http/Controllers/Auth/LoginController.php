<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Debug untuk cek apakah request masuk dengan benar
        // dd($request->all());

        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $loginField = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (Auth::attempt([$loginField => $request->email, 'password' => $request->password])) {
            return response()->json([
                'success' => true,
                'redirect' => url('/dashboardnew')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email/Username atau password salah'
        ], 200); // <- Status tetap 200
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['success' => true, 'redirect' => url('/')]);
    }
}


