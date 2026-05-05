<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserRepositoryInterface $users) {}

    public function index(Request $request)
    {
        return view('admin.users.index', [
            'users'   => $this->users->paginateAdmin($request->only('q', 'role'), 20),
            'filters' => $request->only('q', 'role'),
        ]);
    }

    public function show(User $user)
    {
        return view('admin.users.show', [
            'user'   => $user->load(['orders']),
        ]);
    }

    public function toggleActive(User $user)
    {
        $user->update(['is_active' => ! $user->is_active]);
        return back()->with('success', 'Statut du compte modifié.');
    }
}
