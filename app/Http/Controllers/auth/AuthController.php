<?php

namespace App\Http\Controllers\auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function index():View
    {
        return view('pages.auth.index');
    }
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials)) {
            // Hapus semua sesi pengguna sebelumnya dari tabel 'sessions'
            $userId = Auth::id();
            DB::table('sessions')
                ->where('user_id', $userId)
                ->delete();
            $request->session()->regenerate();

            return redirect()->intended(route('editor.dashboard'));
        } else {
            return back()->with('LoginError', 'Login Gagal');
        }

    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
