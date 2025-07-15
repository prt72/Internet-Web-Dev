<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // ✅ Custom redirect path after login
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        // This middleware allows only guests to access login
        $this->middleware('guest')->except('logout');
    }

    // ✅ Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ✅ Handle login logic
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect to intended or default dashboard
            return redirect()->intended($this->redirectTo);
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->withInput($request->only('email'));
    }

    // ✅ Optional: handle logout manually if not using AuthenticatesUsers trait
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
