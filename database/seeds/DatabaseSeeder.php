<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(StaticDataSeeder::class);

        $this->call(ExtraDataSeeder::class);

        factory(App\Individual::class, 200)->create();

        factory(App\Family::class, 10)->create();

        factory(App\IndividualRenewal::class, 10)->states('offline')->create();

        factory(App\IndividualRenewal::class, 10)->states('online')->create();

        factory(App\CalendarEvent::class, 10)->create();

        factory(App\User::class)->create([
            'individual_id' => 1,
            'username' => 'admin',
            'password' => bcrypt('123456'),
            'type' => 1,
        ]);

        factory(App\User::class, 5)->states('admin')->create();

        factory(App\User::class)->create([
            'individual_id' => 2,
            'username' => 'captain',
            'password' => bcrypt('123456'),
            'type' => 2,
        ]);

        factory(App\User::class, 5)->states('captain')->create();
    }
}
