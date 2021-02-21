<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\RenewalPage;

class PensionerRenewalTest extends DuskTestCase
{
    /**
     * Test normal renewal submission.
     *
     * @group pensionerNormalRenewal
     * @return void
     */
    public function testNormalRenewal()
    {
        $individual = $this->createIndividualOf($typeId = 3, [
            'pension_card' => true,
            'is_committee_member' => false,
            'is_club_lifetime_member' => false,
        ]);

        $this->browse(function (Browser $browser) use ($individual) {
            $browser->visit(new RenewalPage($url = getRenewalLink($individual->id, 1)))
                ->specifyMembershipsDetails('#type-pensioner h6')
                ->specifyOtherDetails()
                ->specifyConfirmationDetails($individual, 'pensioner_price')
                ->submitRenewal()
            ;
        });

        $this->checkDatabaseEntries($individual, $priceField = 'pensioner_price');
    }

    /**
     * Test membership discount.
     *
     * @group pensionerMembershipDiscount
     * @return void
     */
    public function testMembershipDiscount()
    {
        $individual = $this->createIndividualOf($typeId = 3, [
            'pension_card' => 1,
            'is_committee_member' => rand(0, 1),
            'is_club_lifetime_member' => rand(0, 1),
        ]);

        $this->browse(function (Browser $browser) use ($individual) {
            $browser->visit(new RenewalPage($url = getRenewalLink($individual->id, 1)))
                ->specifyMembershipsDetails('#type-pensioner h6')
                ->press('Next')
                ->press('Next')
                ->assertSee('Discount -')
                ->assertSeeIn('#total-discount', $individual->membership->type->price)
                ->assertSeeIn('#renewal-price', $individual->disciplines->sum('pensioner_price'))
                ->click('#discount-container span.link')
                ->waitForText('Discount Details')
                ->assertSee('Pensioner Membership')
                ->press('CLOSE')
                ->pause(1000)
                ->click('#price-container .offline-payment')
                ->type('renewal_applier_full_name', 'Test')
                ->submitRenewal()
            ;
        });

        $this->assertDatabaseHas('individual_renewals', [
            'discount' => $individual->membership->type->price,
        ]);

        $this->checkDatabaseEntries($individual, $priceField = 'pensioner_price');
    }

    /**
     * Test renewal discipline discount.
     *
     * @group pensionerDisciplineDiscount
     * @return void
     */
    public function testDisciplineDiscount()
    {
        $individual = $this->createIndividualOf($typeId = 3, $individualDetails = ['pension_card' => 1], $isDisciplineDiscount = true);

        $discountableDisciplinesAmount = $individual->disciplines->where('pivot.is_lifetime_member', 1)->sum('pensioner_price');

        $this->browse(function (Browser $browser) use ($individual, $discountableDisciplinesAmount) {
            $browser->visit(new RenewalPage($url = getRenewalLink($individual->id, 1)))
            ->specifyMembershipsDetails('#type-pensioner h6')
                ->press('Next')
                ->press('Next')
            ;
            if ($discountableDisciplinesAmount == 0) {
                $browser->assertDontSee('Discount -')
                    ->assertDontSeeIn('#total-discount', $discountableDisciplinesAmount)
                ;
            } else {
                $browser->assertSee('Discount -')
                    ->assertSeeIn('#total-discount', $discountableDisciplinesAmount)
                ;
            }

            $browser->click('#price-container .offline-payment')
                ->type('renewal_applier_full_name', 'Test')
                ->submitRenewal()
            ;
        });

        $this->assertDatabaseHas('individual_renewals', [
            'discount' => $discountableDisciplinesAmount,
        ]);

        $this->checkDatabaseEntries($individual, $priceField = 'pensioner_price');
    }
}
