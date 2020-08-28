<?php

namespace App;

trait Followable
{

    public function follow(User $user)
    {
        return $this->follows()->save($user);
    }

    public function unfollow(User $user)
    {
        return $this->follows()->detach($user);
    }

    public function toggleFollow(User $user)
    {
//        if ($this->following($user)) {
//            return $this->unfollow($user);
//        }
//
//        return $this->follow($user);
        $this->follows()->toggle($user);
    }

    // checks who you follow
    public function follows()
    {
        // click on belongsToMany to see the options we use to be more specific
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'following_user_id');
    }

    public function following(User $user)
    {
        // return $this->follows->contains($user);
        // check if record exists
        return $this->follows()->where('following_user_id', $user->id)->exists();
    }
}
