<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function __construct(protected AuthService $auth) {}

    public function show()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request)
    {
        $user = $this->auth->register($request->validated());
        Auth::login($user);
        return redirect()->route('verification.notice');
    }
}
