<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Illuminate\Support\Str;

class RenewalPage extends Page
{
    public $url;

    /**
     * Initiate the page.
     *
     * @param string URL to the page
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return $this->url;
    }

    /**
     * Specifies the membership tab details.
     *
     * @param  \Laravel\Dusk\Browser $browser
     * @param  string $membershipSelector
     * @return void
     */
    public function specifyMembershipsDetails(Browser $browser, $membershipSelector = '#type-adult h6')
    {
        $browser->press('Next')
            ->script(["document.getElementById('ssaa-expiry')._flatpickr.setDate('" . now()->addYear() . "')"])
        ;

        $browser->assertSee('Current membership type')
            ->click($membershipSelector)
        ;
    }

    /**
     * Specifies the other details tab details.
     *
     * @param  \Laravel\Dusk\Browser  $browser
     * @return void
     */
    public function specifyOtherDetails(Browser $browser)
    {
        $browser->press('Next')
            ->assertSee('Disciplines')
            ->assertDontSee('Family Members')
        ;
    }

    /**
     * Specifies the other details tab details.
     *
     * @param  \Laravel\Dusk\Browser  $browser
     * @param \App\Individual $individual
     * @param string $priceField discipline price field name
     * @return void
     */
    public function specifyConfirmationDetails(Browser $browser, $individual, $priceField = 'adult_price', $familyPrice = 0)
    {
        $disciplinePrice = $familyPrice ?: $individual->disciplines->sum($priceField);
        $membershipLabel = Str::title(str_replace('_price', ' ', $priceField)) . 'Membership';

        $browser->press('Next')
            ->assertSee($disciplinePrice + $individual->membership->type->price)
            ->assertSee('Renewal Details')
            ->assertSee($membershipLabel)
            ->assertSee('$' . $individual->membership->type->price)
            ->assertDontSee('Discount -')
            ->assertDontSee('You have chosen to pay offline. Offline payments can be made by making a bank transfer to JSS Inc, BSB: 036032, ACC: 579111')
            ->click('#price-container .offline-payment')
            ->assertSee('You have chosen to pay offline. Offline payments can be made by making a bank transfer to JSS Inc, BSB: 036032, ACC: 579111')
            ->type('renewal_applier_full_name', 'Test')
        ;
    }

    /**
     * Specifies the other details tab details.
     *
     * @param  \Laravel\Dusk\Browser  $browser
     * @return void
     */
    public function submitRenewal(Browser $browser)
    {
        $browser->press('Submit')
            ->assertSee('Renewal Payment')
            ->assertSee('Not Complete')
            ->assertSee('You have chosen to pay offline. Offline payments can be made by making a bank transfer to JSS Inc, BSB: 036032, ACC: 579111')
        ;
    }
}
