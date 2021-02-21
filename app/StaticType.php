<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/** @mixin \Eloquent */
class StaticType extends Model
{
    /**
     * Returns the cached list of resource
     *
     * @return \Illuminate\Support\Collection
     **/
    public static function getList()
    {
        $cacheKey = get_called_class();

        return Cache::rememberForever($cacheKey, function () {
            return static::select('id', 'label')->get();
        });
    }
}
