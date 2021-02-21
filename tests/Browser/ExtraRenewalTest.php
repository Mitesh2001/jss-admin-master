<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\RenewalPage;

class ExtraRenewalTest extends DuskTestCase
{
    /**
     * Test normal renewal submission.
     *
     * @group NormalRenewalWithoutDisciplines
     * @return void
     */
    public function testNormalRenewalWithoutDisciplines()
    {
        $individual = $this->createIndividualOf();
        $individual->disciplines()->detach();

        $this->browse(function (Browser $browser) use ($individual) {
            $browser->visit(new RenewalPage($url = getRenewalLink($individual->id, $renewalRunId = 1)))
                ->specifyMembershipsDetails('#type-adult h6')
                ->press('Next')
                ->assertSeeIn('#discipline-table', 'You do not seem to be associated with at least one discipline. Please email info@jarrahdaleshooters.org.au for assistance. Alternatively you can also phone Callen on 0422 522 540 or Daniel on 0402 503 075.')
                ->press('Next')
                ->assertDontSee('Submit')
            ;
        });
    }

    /**
     * Test normal renewal submission.
     *
     * @group FamilyRenewalWithoutDisciplines
     * @return void
     */
    public function testFamilyRenewalWithoutDisciplines()
    {
        $family = $this->createFamily();

        $this->browse(function (Browser $browser) use ($family) {
            $renewalIndividual = $family->individuals->random();
            $renewalIndividual->disciplines()->detach();

            $browser->visit(new RenewalPage($url = getRenewalLink($renewalIndividual->id, $renewalRunId = 1)))
                ->specifyMembershipsDetails('#type-family h6')
                ->press('Next')
                ->assertSeeIn('#family-member-table-body', 'Please add disciplines or remove this member.')
                ->assertSeeIn('#family-discipline-table', 'One or more of your family members are not associated with a discipline. Please contact your range officer/captain to have this rectified. Alternatively you can remove that family member from your family membership and proceed.')
                ->press('Next')
                ->assertDontSee('Submit')
            ;
        });
    }

    /**
     * Test normal renewal remove disciplines submission.
     *
     * @group NormalRenewalRemoveDisciplines
     * @return void
     */
    public function testNormalRenewalRemoveDisciplines()
    {
        $individual = $this->createIndividualOf();

        $this->browse(function (Browser $browser) use ($individual) {
            $discipline = $individual->disciplines->first();
            $totalAmount = formattedRound(($individual->disciplines->sum('adult_price') + $individual->membership->type->price) - $discipline->adult_price);

            $browser->visit(new RenewalPage($url = getRenewalLink($individual->id, $renewalRunId = 1)))
                ->specifyMembershipsDetails('#type-adult h6')
                ->press('Next')
                ->assertPresent('#discipline-table tr[data-id="' . $discipline->id . '"] .remove-discipline')
                ->click('#discipline-table tr[data-id="' . $discipline->id . '"] .remove-discipline')
                ->pause(1000)
                ->press('OK')
                ->assertMissing('#discipline-table tr[data-id="' . $discipline->id . '"] .remove-discipline')
                ->pause(1000)
                ->press('Next')
                ->assertSeeIn('#renewal-price', $totalAmount)
                ->click('#price-container .offline-payment')
                ->type('renewal_applier_full_name', 'Test')
                ->submitRenewal()
            ;
        });
    }

    /**
     * Test remove family member.
     *
     * @group RemoveFamilyMember
     * @return void
     */
    public function testRemoveFamilyMember()
    {
        $family = $this->createFamily();

        $this->browse(function (Browser $browser) use ($family) {
            $renewalIndividual = $family->individuals->random();
            $secondIndividual = $family->individuals->whereNotIn('id', [$renewalIndividual->id])->random();
            $thirdIndividual = $family->individuals->whereNotIn('id', [$renewalIndividual->id, $secondIndividual->id])->first();

            $browser->visit(new RenewalPage($url = getRenewalLink($renewalIndividual->id, $renewalRunId = 1)))
                ->specifyMembershipsDetails('#type-family h6')
                ->press('Next')
                ->assertPresent('#family-member-table .family-member-' . $renewalIndividual->id . ' .remove-family-member')
                ->click('#family-member-table .family-member-' . $renewalIndividual->id . ' .remove-family-member')
                ->pause(1000)
                ->assertSee('You cannot remove yourself from your family.')
                ->press('OK')
                ->pause(1000)
                ->assertPresent('#family-member-table .family-member-' . $secondIndividual->id . ' .remove-family-member')
                ->click('#family-member-table .family-member-' . $secondIndividual->id . ' .remove-family-member')
                ->pause(1000)
                ->assertSee('Are you sure you want to remove this family member?')
                ->press('OK')
                ->pause(1000)
                ->assertMissing('#family-member-table .family-member-' . $secondIndividual->id . ' .remove-family-member')
                ->assertPresent('#family-member-table .family-member-' . $thirdIndividual->id . ' .remove-family-member')
                ->click('#family-member-table .family-member-' . $thirdIndividual->id . ' .remove-family-member')
                ->pause(1000)
                ->assertSee('Removing this user will leave only you renewing. If you wish to renew just on your own, then return to the previous step and choose Adult.')
                ->press('OK')
                ->assertPresent('#family-member-table .family-member-' . $thirdIndividual->id . ' .remove-family-member')
            ;
        });
    }

    /**
     * Test renewal amount has 0 submission.
     *
     * @group NormalRenewalAmountZero
     * @return void
     */
    public function testNormalRenewalAmountZero()
    {
        $individual = $this->createIndividualOf($typeId = 1, [
            'is_committee_member' => $isCommitteeMember = rand(0, 1),
            'is_club_lifetime_member' => $isCommitteeMember ? false : true,
        ]);

        $individual = $this->setDisciplinesLifitimeMember($individual);

        $this->browse(function (Browser $browser) use ($individual) {
            $browser->visit(new RenewalPage($url = getRenewalLink($individual->id, $renewalRunId = 1)))
                ->specifyMembershipsDetails('#type-adult h6')
                ->press('Next')
                ->press('Next')
                ->assertSeeIn('#total-amount', 0.00)
                ->click('#price-container .offline-payment')
                ->type('renewal_applier_full_name', 'Test')
                ->submitRenewal()
            ;
        });
    }

    /**
     * Set all the disciplines lifetime column
     *
     * @param \App\Individual $individual
     * @return \App\Individual $individual
     **/
    public function setDisciplinesLifitimeMember($individual)
    {
        foreach ($individual->disciplines as $discipline) {
            $individual->disciplines()
                    ->updateExistingPivot($discipline->id, ['is_lifetime_member' => true])
                ;
        }

        return $individual->load(['membership', 'ssaa', 'disciplines', 'membership.type']);
    }
}
