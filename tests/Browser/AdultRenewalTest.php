<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\RenewalPage;
use Tests\DuskTestCase;

class AdultRenewalTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @group adultNormalRenewal
     * @return void
     */
    public function testNormalRenewal()
    {
        $individual = $this->createIndividualOf();

        $this->browse(function (Browser $browser) use ($individual) {
            $browser->visit(new RenewalPage(getRenewalLink($individual->id, 1)))
                ->specifyMembershipsDetails()
                ->specifyOtherDetails()
                ->specifyConfirmationDetails($individual)
                ->submitRenewal()
            ;
        });

        $this->checkDatabaseEntries($individual);
    }

    /**
     * Test membership discount.
     *
     * @group adultMembershipDiscount
     * @return void
     */
    public function testMembershipDiscount()
    {
        $individual = $this->createIndividualOf($typeId = 1, [
            'pension_card' => 0,
            'is_committee_member' => $isCommitteeMember = rand(0, 1),
            'is_club_lifetime_member' => $isCommitteeMember ? false : true,
        ]);

        $this->browse(function (Browser $browser) use ($individual) {
            $browser->visit(new RenewalPage($url = getRenewalLink($individual->id, 1)))
                ->specifyMembershipsDetails()
                ->press('Next')
                ->press('Next')
                ->assertSee('Discount -')
                ->assertSeeIn('#total-discount', $individual->membership->type->price)
                ->assertSeeIn('#renewal-price', $individual->disciplines->sum('adult_price'))
                ->click('#discount-container span.link')
                ->waitForText('Discount Details')
                ->assertSee('Adult Membership')
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

        $this->checkDatabaseEntries($individual);
    }

    /**
     * Test renewal discipline discount.
     *
     * @group adultDisciplineDiscount
     * @return void
     */
    public function testDisciplineDiscount()
    {
        $individual = $this->createIndividualOf($typeId = 1, $individualDetails = [], $isDisciplineDiscount = true);

        $discountableDisciplinesAmount = $individual->disciplines->where('pivot.is_lifetime_member', 1)->sum('adult_price');

        $this->browse(function (Browser $browser) use ($individual, $discountableDisciplinesAmount) {
            $browser->visit(new RenewalPage($url = getRenewalLink($individual->id, 1)))
                ->specifyMembershipsDetails()
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

        $this->checkDatabaseEntries($individual);
    }
}
