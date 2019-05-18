<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

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



    /**
     * Relationship: an user has many projects.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany;
     */
    public function projects()
    {
        return $this->hasMany(\App\Project::class, 'owner_id')
            ->orderByDesc('updated_at');
    }


    /**
     *
     *
     * @return Collection
     */
    public function accessibleProjects()
    {
        return Project::where('owner_id', $this->id)
            ->orWhereHas('members', function($query) {
                $query->where('user_id', $this->id);
            })
            ->get();
    }
}
