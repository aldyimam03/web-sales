<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the form for login.
     */
    public function loginPage()
    {
        return view('auth.login');
    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            $credentials = $request->only('email', 'password');
            $remember = $request->has('remember');

            $user = User::where('email', $credentials['email'])->first();
            if (!$user) {
                return redirect()
                    ->back()
                    ->withInput($request->only('email'))
                    ->with('error', 'Email tidak ditemukan!');
            }

            if (!Hash::check($credentials['password'], $user->password)) {
                return redirect()
                    ->back()
                    ->withInput($request->only('email'))
                    ->with('error', 'Password salah!');
            }

            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();
                return redirect()
                    ->intended(route('dashboard'))
                    ->with('success', 'Berhasil login!');
            }

            return redirect()
                ->back()
                ->withInput($request->only('email'))
                ->with('error', 'Gagal login. Silakan coba lagi!');
        } catch (\Throwable $th) {
            // Log error untuk debugging
            Log::error('Login error: ' . $th->getMessage());

            return redirect()
                ->back()
                ->withInput($request->only('email'))
                ->with('error', 'Terjadi kesalahan saat login!');
        }
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('success', 'Berhasil logout');
        } catch (\Throwable $th) {
            Log::error('Logout error: ' . $th->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat logout!');
        }
    }
}
