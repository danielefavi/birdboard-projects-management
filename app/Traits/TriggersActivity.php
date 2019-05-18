<?php

namespace App\Traits;
use App\Activity;



trait TriggersActivity
{

    /**
     * Boot the trait.
     */
    protected static function bootRecordsActivity()
    {
        foreach(static::getModelEventsToRecord() as $event) {
            static::$event(function($model) use ($event)) {
                $model->recordActivity(
                    $model->formatActivityDescription($event);
                );
            }
        }
    }



    /**
     * Record the activity for the model.
     *
     * @param string $description
     * @return void
     */
    public function recordActivity($description)
    {
        $this->activitySubject()
            ->activity()
            ->create([
                'description' => $description
            ]);
    }



    /**
     * The activity feed for the project;
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activity()
    {
        return $this->hasMany(Activity::class);
    }



    /**
     * Get the subject for the activity recording;
     *
     * @return $this
     */
    public function activitySubject()
    {
        return $this;
    }



    /**
     * Get the model events that should trigger activity recording.
     *
     * @return array
     */
    protected static function getModelEventsToRecord()
    {
        if (isset(static::$modelEventsToRecord)) {
            return static::$modelEventsToRecord;
        }

        return ['created', 'updated', 'deleted'];
    }



    /**
     * Format the activity description.
     *
     * @param string $event
     * @return string
     */
    public function formatActivityDescription($event)
    {
        return "{$event}_" . strtolower(class_basename($this));
    }
}
