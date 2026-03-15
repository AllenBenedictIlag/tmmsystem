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
    if (!Auth::attempt([
    'username' => $request->username,
    'password' => $request->password
], $request->boolean('remember'))) {

    return back()->withErrors([
        'username' => 'Invalid username or password.'
    ]);
}
    $request->session()->regenerate();

    $user = auth()->user();
    $role = $user->getRoleNames()->first();

    return match($role) {
        'ADMIN' => redirect()->route('admin.dashboard'),
        'CORE' => redirect()->route('core.dashboard'),
        'CEO' => redirect()->route('ceo.dashboard'),
        'CREATIVES' => redirect()->route('creative.dashboard'),
        'SOCIAL_MEDIA_MANAGER' => redirect()->route('smm.dashboard'),
        default => redirect('/dashboard'),
    };
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
