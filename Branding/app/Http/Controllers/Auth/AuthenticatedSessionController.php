<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // 1. Ambil Role/Regional User
        $role = $request->user()->regional;

        // 2. Daftar yang BOLEH masuk Dashboard Admin (Hanya Management)
        // JT, DK, LP, JB, JR TIDAK dimasukkan di sini karena mereka input data
        $aksesAdmin = ['Admin', 'Design'];

        // 3. Cek Logika
        if (in_array($role, $aksesAdmin)) {
            // Jika Admin atau Design -> Ke Dashboard Admin
            return redirect()->intended(route('admin.dashboard'));
        }

        // 4. Selain Admin & Design (JT, DK, LP, JB, JR) -> Ke Home User
        return redirect()->intended(route('user.home'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    
}
