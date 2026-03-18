<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    // Called by Gate::authorize('update', $post)
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    // Called by Gate::authorize('delete', $post)
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }
}