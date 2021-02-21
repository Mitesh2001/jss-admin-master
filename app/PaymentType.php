<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** @mixin \Eloquent */
class PaymentType extends Model
{
    /**
     * Returns the payment types.
     *
     * @return \Illuminate\Support\Collection
     **/
    public static function getList()
    {
        return static::all();
    }
}
