<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAvatarAttribute()
    {
        return "https://i.pravatar.cc/40?u=" . $this->email;
    }

    public function timeline()
    {
        // return Tweet::latest()->get();
        // return Tweet::where('user_id', $this->id)->latest()->get();

        // include all of the user's tweets
        // as well as the tweets of everyone
        // they folllow ... in decending order by date.
        // $ids = $this->follows->pluck('id');

        // user id to show his tweets as well.
        // $ids->push($this->id);

        // Give me all tweets of user ids that are inside this array.
        $friends = $this->follows->pluck('id');

        return Tweet::whereIn('user_id', $friends)
            ->orWhere('user_id', $this->id)
            ->latest()->get();
    }

    public function tweets()
    {
        return $this->hasMany(Tweet::class);
    }

    public function follow(User $user)
    {
        return $this->follows()->save($user);
    }

    // checks who you follow
    public function follows()
    {
        // click on belongsToMany to see the options we use to be more specific
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'following_user_id');
    }
}
