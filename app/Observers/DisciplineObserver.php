<?php

namespace App\Observers;

use App\Discipline;
use Illuminate\Support\Facades\Cache;

class DisciplineObserver
{
    /**
     * Handle the discipline "created" event.
     *
     * @param  \App\Discipline  $discipline
     * @return void
     */
    public function created(Discipline $discipline)
    {
        Cache::forget('App\Discipline');
    }

    /**
     * Handle the discipline "updated" event.
     *
     * @param  \App\Discipline  $discipline
     * @return void
     */
    public function updated(Discipline $discipline)
    {
        Cache::forget('App\Discipline');
    }

    /**
     * Handle the discipline "deleted" event.
     *
     * @param  \App\Discipline  $discipline
     * @return void
     */
    public function deleted(Discipline $discipline)
    {
        Cache::forget('App\Discipline');
    }
}
