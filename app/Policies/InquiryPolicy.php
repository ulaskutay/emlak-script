<?php

namespace App\Policies;

use App\Models\Inquiry;
use App\Models\User;

class InquiryPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Inquiry $inquiry): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isAgent() && $inquiry->assigned_agent_id) {
            return $inquiry->assigned_agent->user_id === $user->id;
        }

        if ($user->isAgent() && $inquiry->listing) {
            return $inquiry->listing->agent->user_id === $user->id;
        }

        return false;
    }

    public function update(User $user, Inquiry $inquiry): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isAgent() && $inquiry->assigned_agent_id && $inquiry->assigned_agent->user_id === $user->id;
    }
}

