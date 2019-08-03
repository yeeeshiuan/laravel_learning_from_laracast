<?php

namespace App;

trait RecordsActivity
{

	public $oldAttributes = [];


	/** 
		boot the trait 
	**/
	public static function bootRecordsActivity()
	{

		foreach (self::recordableEvents() as $event) {

			static::$event(function ($model) use($event) {

				$model->recordActivity($model->activityDscription($event));

			});

			if ($event === "updated") {
				static::updating(function ($model){
		            $model->oldAttributes = $model->getOriginal();
		        });
			}
		}
	}

	protected function activityDscription($description)
	{
		return "{$description}_" . strtolower(class_basename($this));
	}

	public function recordActivity($description)
    {

        $this->activity()->create([

            'user_id' => ($this->project ?? $this)->owner->id,

            'description' => $description,

            'changes' => $this->activityChanged(),

            'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project_id

        ]);

    }

    protected function activityChanged()
    {
        if ($this->wasChanged())
        {
            return [

                'before' => array_except(
                    array_diff($this->oldAttributes, $this->getAttributes()), 'updated_at'
                ),

                'after' => array_except($this->getChanges(), 'updated_at')

            ];
        }
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    protected static function recordableEvents()
    {
    	if (isset(static::$recordableEvents)) {
    		return static::$recordableEvents;
    	}

    	return ['created', 'updated', 'deleted'];
    }
}