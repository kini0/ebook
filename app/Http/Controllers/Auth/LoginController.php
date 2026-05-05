<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    public function __construct(protected AuthService $auth) {}

    public function show()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $request->ensureIsNotRateLimited();

        try {
            $user = $this->auth->attemptLogin($request->only('email', 'password'), (bool) $request->boolean('remember'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            RateLimiter::hit($request->throttleKey());
            throw $e;
        }
        RateLimiter::clear($request->throttleKey());

        $request->session()->regenerate();

        return redirect()->intended($user->isAdmin() ? route('admin.dashboard') : route('customer.dashboard'));
    }

    public function destroy(Request $request)
    {
        $this->auth->logout();
        return redirect()->route('home')->with('success', 'Vous êtes déconnecté.');
    }
}
