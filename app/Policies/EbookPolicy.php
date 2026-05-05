<?php

namespace App\Policies;

use App\Models\Ebook;
use App\Models\User;

class EbookPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(?User $user, Ebook $ebook): bool
    {
        return $ebook->is_published || ($user?->isAdmin() ?? false);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Ebook $ebook): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Ebook $ebook): bool
    {
        return $user->isAdmin();
    }
}
