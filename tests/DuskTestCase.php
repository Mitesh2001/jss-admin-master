<?php

namespace Tests;

use Tests\Traits\SeedStaticData;
use Tests\Traits\CreatesIndividuals;
use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication, SeedStaticData, CreatesIndividuals;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--headless'
        ]);

        return RemoteWebDriver::create(
            'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY,
                $options
            )
        );
    }

    /**
     * Checks the Database entries.
     *
     * @param \App\Individual
     * @param string $priceField
     * @return void
     */
    public function checkDatabaseEntries($individual, $priceField = 'adult_price')
    {
        $this->assertDatabaseHas('individual_renewals', [
            'type_id' => $individual->membership->type_id,
            'ssaa_expiry' => now()->addYear()->format('Y-m-d'),
            'amount' => $individual->disciplines->sum($priceField) + $individual->membership->type->price,
            'payment_type' => 1,
            'renewal_applier_full_name' => 'Test',
        ]);

        foreach ($individual->disciplines as $discipline) {
            $this->assertDatabaseHas('discipline_individual_renewal', [
                'discipline_id' => $discipline->id,
                'individual_id' => $individual->id,
                'is_lifetime_member' => $discipline->pivot->is_lifetime_member,
                'price' => $discipline->$priceField,
            ]);
        }
        $this->assertDatabaseHas('renewals', [
            'individual_id' => $individual->id,
            'approved' => 0,
            'renewal_run_id' => 1,
        ]);

        $this->assertDatabaseHas('receipt_individuals', [
            'individual_id' => $individual->id,
            'is_payer' => 1,
        ]);
    }
}
