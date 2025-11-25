<?php

namespace App\Policies;

use App\Models\Listing;
use App\Models\User;

class ListingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Listing $listing): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isAgent() && $listing->agent->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Listing $listing): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isAgent() && $listing->agent->user_id === $user->id;
    }

    public function delete(User $user, Listing $listing): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isAgent() && $listing->agent->user_id === $user->id;
    }
}

