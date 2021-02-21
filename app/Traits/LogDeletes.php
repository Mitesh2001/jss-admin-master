<?php

namespace App\Traits;

trait LogDeletes
{
    // Only the created and updated events will get logged automatically.
    protected static $recordEvents = ['created', 'updated'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            activity()->performedOn($model)
                ->withProperties($model->toArray())
                ->log('Deleted')
            ;
        });
    }
}
