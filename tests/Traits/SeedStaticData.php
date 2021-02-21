<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\Artisan;

trait SeedStaticData
{
    /**
     * Register the base URL with Dusk.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh');

        Artisan::call('db:seed', [
            '--class' => 'StaticDataSeeder'
        ]);

        Artisan::call('db:seed', [
            '--class' => 'ExtraDataSeeder'
        ]);
    }
}
