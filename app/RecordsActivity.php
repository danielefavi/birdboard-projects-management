<?php

namespace App;

trait RecordsActivity
{
    /**
     * The project's old attributes.
     *
     * @var array
     */
    public $oldAttirbutes = [];



    /**
     * Boot the trait.
     *
     * @return void
     */
    public static function bootRecordsActivity()
    {
        foreach (self::recordableEvents() as $event) {
            static::$event(function($model) use ($event) {
                $model->recordActivity( $model->activityDescription($event) );
            });

            if ($event == 'updated') {
                static::updating(function($model) {
                    $model->oldAttirbutes = $model->getOriginal();
                });
            }
        }
    }



    /**
     * Get the description of the activity.
     *
     * @param string $description
     * @return string
     */
    protected function activityDescription($description)
    {
        return "{$description}_" . strtolower(class_basename($this));
    }



    /**
     * Fetch the model events that should trigger activity.
     *
     * @return array
     */
    public static function recordableEvents()
    {
        if (isset(static::$recordableEvents)) {
            return static::$recordableEvents;
        }

        return ['created', 'updated', 'deleted'];
    }



    /**
     * The activity feed for the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }



    /**
     * Record activity for a project.
     *
     * @param string $description
     * @return void
     */
    public function recordActivity($description)
    {
        $this->activity()->create([
            'user_id' => $this->activityOwner()->id,
            'description' => $description,
            'changes' => $this->activityChanges(),
            'project_id' => class_basename($this) == 'Project' ? $this->id : $this->project_id,
        ]);
    }



    /**
     *
     *
     * @param Parameter $parameter
     * @return string
     */
    public function activityOwner()
    {
        $project = $this->project ?? $this;

        return $project->owner;
    }




    /**
     * Fetch the changes to the model.
     *
     * @return array
     */
    protected function activityChanges()
    {
        if ($this->wasChanged()) {
            return [
                'before' => array_except( array_diff($this->oldAttirbutes, $this->getAttributes()), 'updated_at'),
                'after' => array_except( $this->getChanges(), 'updated_at'),
            ];
        }

        return null;
    }

}
