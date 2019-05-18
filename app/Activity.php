<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ User;



class Activity extends Model
{
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @param Class $parameter
     * @return void
     */
    protected $casts = [
        'changes' => 'array'
    ];



    /**
     * Get the subject of the activity.
     *
     * @return \Illuminate\Database\Eloquent\MorphTo
     */
    public function subject()
    {
        return $this->morphTo();
    }



    /**
     * Return the user associated with the activity.
     *
     * @return \Illuminate\Database\Eloquent\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
