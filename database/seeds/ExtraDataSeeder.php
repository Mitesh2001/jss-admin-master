<?php

use Illuminate\Database\Seeder;

use App\RenewalRunEmail;

class ExtraDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RenewalRunEmail::create([
            'renewal_run_id' => 1,
            'sparkpost_template_id' => 1,
            'sent_at' => now(),
        ]);
    }
}
