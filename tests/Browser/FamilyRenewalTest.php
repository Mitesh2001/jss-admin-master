<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\RenewalPage;
use Tests\Traits\FamilyRenewalPrepareDetails;

class FamilyRenewalTest extends DuskTestCase
{
    use FamilyRenewalPrepareDetails;

    /**
     * Test normal renewal submission.
     *
     * @group familyNormalRenewal
     * @return void
     */
    public function testNormalRenewal()
    {
        $family = $this->createFamily();

        $this->browse(function (Browser $browser) use ($family) {
            $renewalIndividual = $family->individuals->random();

            $browser->visit(new RenewalPage($url = getRenewalLink($renewalIndividual->id, 1)))
                ->specifyMembershipsDetails('#type-family h6')
                ->press('Next')
                ->assertSeeIn('#family-container', 'Family Members')
                ->specifyConfirmationDetails($renewalIndividual, $priceField = 'family_price', $this->getFamilyDisciplinesAmount($family))
                ->submitRenewal()
                ->assertSee('Your family members must now also complete their renewals and accept the terms and conditions in order for their membership to be valid.')
            ;
        });
    }

    /**
     * Test second or third family member renewal submission.
     *
     * @group familySecondMemberRenewal
     * @return void
     */
    public function testSecondMemberRenewal()
    {
        $family = $this->createFamily();

        $this->browse(function (Browser $browser) use ($family) {
            $renewalIndividual = $family->individuals->random();

            $browser->visit(new RenewalPage($url = getRenewalLink($renewalIndividual->id, 1)))
                ->specifyMembershipsDetails('#type-family h6')
                ->press('Next')
                ->assertSeeIn('#family-container', 'Family Members')
                ->specifyConfirmationDetails($renewalIndividual, $priceField = 'family_price', $this->getFamilyDisciplinesAmount($family))
                ->submitRenewal()
                ->assertSee('Your family members must now also complete their renewals and accept the terms and conditions in order for their membership to be valid.')
            ;

            $renewalIndividual = $family->individuals
                ->whereNotIn('id', [$renewalIndividual->id])
                ->random()
            ;
            $browser->visit(new RenewalPage($url = getRenewalLink($renewalIndividual->id, 1)))
                ->press('Next')
                ->assertDontSee('Do you wish to renew your membership as an adult or a family')
                ->script(["document.getElementById('ssaa-expiry')._flatpickr.setDate('" . now()->addYear() . "')"]) ;
            $browser->press('Next')
                ->assertDontSee('Family Members')
                ->assertDontSee('Disciplines')
                ->assertDontSee('$')
                ->assertDontSee('Discount -')
                ->assertDontSee('You have chosen to pay offline. Offline payments can be made by making a bank transfer to JSS Inc, BSB: 036032, ACC: 579111')
                ->assertSee('Your membership has already been paid for by another family member. You must accept the terms and conditions below to complete your renewal. If you are under 18, the terms and conditions must be accepted by your parent or guardian.')
                ->type('renewal_applier_full_name', 'Test')
                ->press('Submit')
                ->assertDontSee('Renewal Payment')
                ->assertDontSee('Not Complete')
                ->assertDontSee('Your family members must now also complete their renewals and accept the terms and conditions in order for their membership to be valid.')
                ->assertSee('Thank you for submitting your membership renewal.')
            ;
        });
    }

    /**
     * Test normal renewal but family member common discipline.
     *
     * @group familyNormalRenewalCommonDiscipline
     * @return void
     */
    public function testNormalRenewalCommonDiscipline()
    {
        $family = $this->createFamily($individualDetails = [], $firstFamilyMemberDetails = [], $isCommonDiscipline = true);

        $this->browse(function (Browser $browser) use ($family) {
            $renewalIndividual = $family->individuals->random();

            $browser->visit(new RenewalPage($url = getRenewalLink($renewalIndividual->id, 1)))
                ->specifyMembershipsDetails('#type-family h6')
                ->press('Next')
                ->assertSeeIn('#family-container', 'Family Members')
                ->assertSeeIn('#family-discipline-table', 'All family members')
                ->specifyConfirmationDetails($renewalIndividual, $priceField = 'family_price', $this->getFamilyDisciplinesAmount($family))
                ->submitRenewal()
                ->assertSee('Your family members must now also complete their renewals and accept the terms and conditions in order for their membership to be valid.')
            ;
        });
    }

    /**
     * Test full membership price discount.
     *
     * @group familyFullMembershipDiscount
     * @return void
     */
    public function testFullMembershipDiscount()
    {
        $family = $this->createFamily($individualDetails = [
            'is_committee_member' => true,
            'is_club_lifetime_member' => true,
        ]);

        $this->browse(function (Browser $browser) use ($family) {
            $renewalIndividual = $family->individuals->random();

            $browser->visit(new RenewalPage($url = getRenewalLink($renewalIndividual->id, 1)))
                ->specifyMembershipsDetails('#type-family h6')
                ->press('Next')
                ->assertSeeIn('#family-container', 'Family Members')
                ->press('Next')
                ->assertSee($this->getFamilyDisciplinesAmount($family))
                ->assertSee('Renewal Details')
                ->assertSee('Family Membership')
                ->assertSee('Discount -')
                ->click('#discount-container span.link')
                ->waitForText('Discount Details')
                ->assertSee('Family Membership')
                ->press('CLOSE')
                ->pause(1000)
                ->click('#price-container .offline-payment')
                ->type('renewal_applier_full_name', 'Test')
                ->submitRenewal()
                ->assertSee('Your family members must now also complete their renewals and accept the terms and conditions in order for their membership to be valid.')
            ;
        });
    }

    /**
     * Test membership price discount.
     *
     * @group familyMembershipAdultPriceDiscount
     * @return void
     */
    public function testMembershipAdultPriceDiscount()
    {
        $family = $this->createFamily($individualDetails = [
            'is_committee_member' => true,
            'is_club_lifetime_member' => true,
        ], $firstFamilyMemberDetails = [
            'is_committee_member' => false,
            'is_club_lifetime_member' => false,
        ]);

        $this->browse(function (Browser $browser) use ($family) {
            $renewalIndividual = $family->individuals->random();
            $membershipDiscount = $this->getMembershipDiscount($family);
            $totalAmount = ($this->getFamilyDisciplinesAmount($family) + $renewalIndividual->membership->type->price) - $membershipDiscount;

            $browser->visit(new RenewalPage($url = getRenewalLink($renewalIndividual->id, 1)))
                ->specifyMembershipsDetails('#type-family h6')
                ->press('Next')
                ->assertSeeIn('#family-container', 'Family Members')
                ->press('Next')
                ->assertSee($totalAmount)
                ->assertSee('Renewal Details')
                ->assertSee('Family Membership')
                ->assertSee('Discount -')
                ->click('#discount-container span.link')
                ->waitForText('Discount Details')
                ->assertSee('Family Membership')
                ->press('CLOSE')
                ->pause(1000)
                ->click('#price-container .offline-payment')
                ->type('renewal_applier_full_name', 'Test')
                ->submitRenewal()
                ->assertSee('Your family members must now also complete their renewals and accept the terms and conditions in order for their membership to be valid.')
            ;
        });
    }

    /**
     * Test membership price discount.
     *
     * @group familyMembershipPensionerPriceDiscount
     * @return void
     */
    public function testMembershipPensionerPriceDiscount()
    {
        $family = $this->createFamily($individualDetails = [
            'is_committee_member' => true,
            'is_club_lifetime_member' => true,
        ], $firstFamilyMemberDetails = [
            'pension_card' => true,
            'is_committee_member' => false,
            'is_club_lifetime_member' => false,
        ]);

        $this->browse(function (Browser $browser) use ($family) {
            $renewalIndividual = $family->individuals->random();
            $membershipDiscount = $this->getMembershipDiscount($family);
            $totalAmount = ($this->getFamilyDisciplinesAmount($family) + $renewalIndividual->membership->type->price) - $membershipDiscount;

            $browser->visit(new RenewalPage($url = getRenewalLink($renewalIndividual->id, 1)))
                ->specifyMembershipsDetails('#type-family h6')
                ->press('Next')
                ->assertSeeIn('#family-container', 'Family Members')
                ->press('Next')
                ->assertSee($totalAmount)
                ->assertSee('Renewal Details')
                ->assertSee('Family Membership')
                ->assertSee('Discount -')
                ->click('#discount-container span.link')
                ->waitForText('Discount Details')
                ->assertSee('Family Membership')
                ->press('CLOSE')
                ->pause(1000)
                ->click('#price-container .offline-payment')
                ->type('renewal_applier_full_name', 'Test')
                ->submitRenewal()
                ->assertSee('Your family members must now also complete their renewals and accept the terms and conditions in order for their membership to be valid.')
            ;
        });
    }

    /**
     * Test adult family member discipline discount.
     *
     * @group familyAdultDisciplineDiscount
     * @return void
     */
    public function testAdultDisciplineDiscount()
    {
        $family = $this->createFamily(
            $individualDetails = [],
            $firstFamilyMemberDetails = [],
            $isCommonDiscipline = false,
            $isDisciplineDiscount = true
        );

        $this->browse(function (Browser $browser) use ($family) {
            $renewalIndividual = $family->individuals->random();

            $membershipDiscount = $this->getMembershipDiscount($family);
            $disciplineDiscount = $this->getDisciplineDiscount($family);

            $totalAmount = ($this->getFamilyDisciplinesAmount($family) + $renewalIndividual->membership->type->price) - $membershipDiscount - $disciplineDiscount;

            $browser->visit(new RenewalPage($url = getRenewalLink($renewalIndividual->id, 1)))
                ->specifyMembershipsDetails('#type-family h6')
                ->press('Next')
                ->assertSeeIn('#family-container', 'Family Members')
                ->press('Next')
                ->assertSee($totalAmount)
                ->assertSee('Renewal Details')
                ->assertSee('Family Membership')
                ->assertSee('Discount -')
                ->click('#price-container .offline-payment')
                ->type('renewal_applier_full_name', 'Test')
                ->submitRenewal()
                ->assertSee('Your family members must now also complete their renewals and accept the terms and conditions in order for their membership to be valid.')
            ;
        });
    }

    /**
     * Test pensioner family member discipline discount.
     *
     * @group familyPensionerDisciplineDiscount
     * @return void
     */
    public function testPensionerDisciplineDiscount()
    {
        $family = $this->createFamily(
            $individualDetails = [
                'pension_card' => true,
            ],
            $firstFamilyMemberDetails = [],
            $isCommonDiscipline = false,
            $isDisciplineDiscount = true
        );

        $this->browse(function (Browser $browser) use ($family) {
            $renewalIndividual = $family->individuals->random();

            $membershipDiscount = $this->getMembershipDiscount($family);
            $disciplineDiscount = $this->getDisciplineDiscount($family);

            $totalAmount = ($this->getFamilyDisciplinesAmount($family) + $renewalIndividual->membership->type->price) - $membershipDiscount - $disciplineDiscount;

            $browser->visit(new RenewalPage($url = getRenewalLink($renewalIndividual->id, 1)))
                ->specifyMembershipsDetails('#type-family h6')
                ->press('Next')
                ->assertSeeIn('#family-container', 'Family Members')
                ->press('Next')
                ->assertSee($totalAmount)
                ->assertSee('Renewal Details')
                ->assertSee('Family Membership')
                ->assertSee('Discount -')
                ->click('#price-container .offline-payment')
                ->type('renewal_applier_full_name', 'Test')
                ->submitRenewal()
                ->assertSee('Your family members must now also complete their renewals and accept the terms and conditions in order for their membership to be valid.')
            ;
        });
    }

    /**
     * Test family member common discipline discount.
     *
     * @group familyCommonDisciplineDiscount
     * @return void
     */
    public function testCommonDisciplineDiscount()
    {
        $family = $this->createFamily(
            $individualDetails = [
                'is_committee_member' => true,
                'is_club_lifetime_member' => true,
            ],
            $firstFamilyMemberDetails = [],
            $isCommonDiscipline = true,
            $isDisciplineDiscount = true
        );

        $family = $this->setCommonDisciplineLifetimeMemberField($family);

        $this->browse(function (Browser $browser) use ($family) {
            $renewalIndividual = $family->individuals->random();

            $membershipDiscount = (int)$this->getMembershipDiscount($family);
            $disciplineDiscount = $this->getDisciplineDiscount($family);

            $totalAmount = ($this->getFamilyDisciplinesAmount($family) + $renewalIndividual->membership->type->price) - ($membershipDiscount + $disciplineDiscount);

            $browser->visit(new RenewalPage($url = getRenewalLink($renewalIndividual->id, 1)))
                ->specifyMembershipsDetails('#type-family h6')
                ->press('Next')
                ->assertSeeIn('#family-container', 'Family Members')
                ->press('Next')
                ->assertSee($totalAmount)
                ->assertSee('Renewal Details')
                ->assertSee('Family Membership')
                ->assertSee('Discount -')
                ->assertSeeIn('#discount-container', $membershipDiscount + $disciplineDiscount)
                ->click('#price-container .offline-payment')
                ->type('renewal_applier_full_name', 'Test')
                ->submitRenewal()
                ->assertSee('Your family members must now also complete their renewals and accept the terms and conditions in order for their membership to be valid.')
            ;
        });
    }

    /**
     * Test family member discipline adult price discount.
     *
     * @group familyCommonDisciplineAdultPriceDiscount
     * @return void
     */
    public function testCommonDisciplineAdultPriceDiscount()
    {
        $family = $this->createFamily(
            $individualDetails = [
                'is_committee_member' => true,
                'is_club_lifetime_member' => true,
            ],
            $firstFamilyMemberDetails = [],
            $isCommonDiscipline = true,
            $isDisciplineDiscount = true
        );

        $family = $this->setCommonDisciplineLifetimeMemberField($family, $isNotDiscountForFirstDiscipline = true);

        $this->browse(function (Browser $browser) use ($family) {
            $renewalIndividual = $family->individuals->random();

            $membershipDiscount = (int)$this->getMembershipDiscount($family);
            $disciplineDiscount = $this->getDisciplineDiscount($family);

            $totalAmount = ($this->getFamilyDisciplinesAmount($family) + $renewalIndividual->membership->type->price) - ($membershipDiscount + $disciplineDiscount);

            $browser->visit(new RenewalPage($url = getRenewalLink($renewalIndividual->id, 1)))
                ->specifyMembershipsDetails('#type-family h6')
                ->press('Next')
                ->assertSeeIn('#family-container', 'Family Members')
                ->press('Next')
                ->assertSee($totalAmount)
                ->assertSee('Renewal Details')
                ->assertSee('Family Membership')
                ->assertSee('Discount -')
                ->assertSeeIn('#discount-container', $membershipDiscount + $disciplineDiscount)
                ->click('#price-container .offline-payment')
                ->type('renewal_applier_full_name', 'Test')
                ->submitRenewal()
                ->assertSee('Your family members must now also complete their renewals and accept the terms and conditions in order for their membership to be valid.')
            ;
        });
    }

    /**
     * Test family member discipline Pensioner price discount.
     *
     * @group familyCommonDisciplinePensionerPriceDiscount
     * @return void
     */
    public function testCommonDisciplinePensionerPriceDiscount()
    {
        $family = $this->createFamily(
            $individualDetails = [
                'is_committee_member' => true,
                'is_club_lifetime_member' => true,
            ],
            $firstFamilyMemberDetails = [],
            $isCommonDiscipline = true,
            $isDisciplineDiscount = true
        );

        $family = $this->setCommonDisciplineLifetimeMemberField($family, $isNotDiscountForFirstDiscipline = true, $isPensioner = true);

        $this->browse(function (Browser $browser) use ($family) {
            $renewalIndividual = $family->individuals->random();

            $membershipDiscount = (int)$this->getMembershipDiscount($family);
            $disciplineDiscount = $this->getDisciplineDiscount($family);

            $totalAmount = ($this->getFamilyDisciplinesAmount($family) + $renewalIndividual->membership->type->price) - ($membershipDiscount + $disciplineDiscount);

            $browser->visit(new RenewalPage($url = getRenewalLink($renewalIndividual->id, 1)))
                ->specifyMembershipsDetails('#type-family h6')
                ->press('Next')
                ->assertSeeIn('#family-container', 'Family Members')
                ->press('Next')
                ->assertSee($totalAmount)
                ->assertSee('Renewal Details')
                ->assertSee('Family Membership')
                ->assertSee('Discount -')
                ->assertSeeIn('#discount-container', $membershipDiscount + $disciplineDiscount)
                ->click('#price-container .offline-payment')
                ->type('renewal_applier_full_name', 'Test')
                ->submitRenewal()
                ->assertSee('Your family members must now also complete their renewals and accept the terms and conditions in order for their membership to be valid.')
            ;
        });
    }
}
