<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/** @mixin \Eloquent */
class Suburb extends Model
{
    /**
     * Returns the cached list of suburbs
     *
     * @param int
     * @return \Illuminate\Support\Collection
     **/
    public static function getSelect2OptionsFor($stateId)
    {
        $cacheKey = 'suburbs_of_state_' . $stateId;

        return Cache::rememberForever($cacheKey, function () use ($stateId) {
            return static::select('id', 'label as text')
                ->where('state_id', $stateId)
                ->orderBy('label', 'asc')
                ->get()
            ;
        });
    }
}
