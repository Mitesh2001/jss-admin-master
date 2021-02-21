<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/** @mixin \Eloquent */
class MembershipType extends Model
{
    /**
     * Returns the cached list of resource
     *
     * @return \Illuminate\Support\Collection
     **/
    public static function getList()
    {
        return Cache::rememberForever('App\MembershipType', function () {
            return static::select('id', 'label', 'price')->get();
        });
    }
}
