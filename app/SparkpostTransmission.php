<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** @mixin \Eloquent */
class SparkpostTransmission extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['created_at', 'updated_at'];
}
