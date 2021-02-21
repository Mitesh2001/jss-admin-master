<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

/** @mixin \Eloquent */
class FirearmType extends StaticType
{
    use LogsActivity, SoftDeletes;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['label'];
}
