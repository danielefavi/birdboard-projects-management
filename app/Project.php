<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Task;
use App\User;
use App\Activity;
use App\RecordsActivity;



class Project extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected static $recordableEvents = [
        'created', 'updated',
    ];



    /**
     * Return the member relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')
            ->withTimestamps();
    }



    /**
     * Attach the given user to the project.
     *
     * @param User $user
     * @return string
     */
    public function invite(User $user)
    {
        return $this->members()->attach($user);
    }



    /**
     * Return the project path.
     *
     * @param string $suffix|null
     * @return string
     */
    public function path($suffix=null)
    {
        $path = "/projects/{$this->id}";

        if ($suffix) {
            $path .= '/' . ltrim($suffix, '/');
        }

        return $path;
    }


    /**
     * Relationship: a project belongs to an user.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo;
     */
    public function owner()
    {
        return $this->belongsTo(\App\User::class);
    }



    /**
     * Relationship: a project has many related tasks.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany;
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }



    /**
     * Add a task to the project.
     *
     * @return App\Task
     */
    public function addTask($body)
    {
        return $this->tasks()->create(compact('body'));
    }



    /**
     * The activity feed for the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }

}
