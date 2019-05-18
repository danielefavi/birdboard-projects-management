<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Project;
use App\Activity;
use App\RecordsActivity;



class Task extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $touches = ['project'];

    protected $casts = [
        'completed' => 'boolean'
    ];

    protected static $recordableEvents = [
        'created', 'deleted',
    ];

    /**
     * Relationship: a task belongs to a project.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo;
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }



    /**
     * Return the path of the taks.
     *
     * @return string
     */
    public function path()
    {
        return "/projects/{$this->project_id}/tasks/{$this->id}";
    }



    /**
     * Complete a task.
     *
     * @return void
     */
    public function complete()
    {
        $this->update(['completed' => true]);

        $this->recordActivity('completed_task');
    }



    /**
     * Mark a task as incompleted.
     *
     * @return void
     */
    public function incomplete()
    {
        $this->update(['completed' => false]);

        $this->recordActivity('incompleted_task');
    }
}
