<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('layouts.auth.login');
    }

    public function authenticate(Request $request)
    {

        $credentials = $request->validate([
            'npk' => 'required|min:6|max:6',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role == 1) {
                return redirect()->route('traceability.index');
            }
    
            if (Auth::user()->role == 0) {
                return redirect()->route('antenna.index');
            }   
        }

        return redirect()->back()->with('error', 'Email or password do not match our records!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.index');
    }
}
