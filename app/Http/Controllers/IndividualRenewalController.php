<?php

namespace App\Http\Controllers;

use App\Exceptions\StripeCardFailed;
use App\Exceptions\StripeChargeFailed;
use App\Family;
use App\Individual;
use App\IndividualRenewal;
use App\Jobs\ProcessRenewal;
use App\MembershipType;
use App\Renewal;
use App\RenewalRun;
use App\RenewalRunEntity;
use App\Utilities\RenewalRequestSecurityCheck;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Stripe\Charge;
use Stripe\Stripe;

class IndividualRenewalController extends Controller
{
    /**
     * Displays the renewal form.
     *
     * @param int Id of the individual
     * @param int Id of the renewal run
     * @return \Illuminate\Http\Response
     */
    public function index($individualId, $renewalRunId)
    {
        $this->renewalRequestCheck($individualId, $renewalRunId);

        $parameters = Individual::getRenewalParameters($individualId);
        $parameters['renewalRun'] = RenewalRun::findOrFail($renewalRunId);
        $parameters['isFamilyRenewalAlreadyPaid'] = $parameters['family'] ? $parameters['family']->isFamilyRenewalAlreadyPaid($renewalRunId) : null;

        return view('front.renewals.individual.index', $parameters);
    }

    /**
     * Submits the renewal form.
     *
     * @param int Id of the individual
     * @param int Id of the renewal run
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit($individualId, $renewalRunId)
    {
        $this->logUserAgent($individualId, $renewalRunId);

        $this->renewalRequestCheck($individualId, $renewalRunId);

        $disciplineIds = request('disciplines') ?: [];

        // Prepare details to store renewal
        $individual = Individual::with([
            'membership',
            'disciplines' => function ($query) use ($disciplineIds) {
                $query->whereIn('discipline_id', $disciplineIds);
            },
        ])->findOrFail($individualId);

        $family = $this->getFamilyDetailsAsPerRequest($individual->family_id);
        $isFamilyRenewalAlreadyPaid = $family ? $family->isFamilyRenewalAlreadyPaid($renewalRunId) : 0;

        // Security check for data mismatch
        $securityCheck = new RenewalRequestSecurityCheck();
        $securityCheck->renewalDataMismatch($individual, $family, $renewalRunId);

        // Validate request details
        $renewalRun = RenewalRun::select('id', 'start_date')
            ->findOrFail($renewalRunId)
        ;

        $validatedData = request()->validate(IndividualRenewal::validationRules($renewalRun->start_date));

        // Add individuals details to the renewal
        $transactionId = $transactionFee = null;
        $data = $this->addIndividualData($validatedData, $individual, $family);

        // Collect payment if required
        if ($this->isPaymentRequired($data['amount'], $data['discount'])) {
            $data = $this->collectPayment($data);
            $transactionId = substr($data['transaction_no'], -8);
            $transactionFee = array_key_exists('transaction_fee', $data) ? $data['transaction_fee'] : 0;
        }

        // Saves renewal details
        $this->saveRenewalDetails($data, $individual, optional($family)->individuals, $renewalRun->id);

        // Process renewal
        ProcessRenewal::dispatch($individual, $renewalRun, $transactionFee);

        return redirect()->route('front.individual_renewal_thank_you', [
            'individual' => $individualId,
            'renewalRun' => $renewalRunId,
            'isFamily' => ! $isFamilyRenewalAlreadyPaid && request('type_id') == 2 ? '1' : '0',
            'isFamilyRenewalAlreadyPaid' => $isFamilyRenewalAlreadyPaid ? '1' : '0',
            'transactionId' => $transactionId,
        ]);
    }

    /**
     * Adds more data from the individual.
     *
     * @param array
     * @param \App\Individual $individual
     * @return array
     */
    private function addIndividualData($data, $individual, $family)
    {
        unset($data['disciplines'], $data['family_member'], $data['stripe_token'], $data['is_family_renewal_already_paid'], $data['family_disciplines'], $data['individuals']);

        $membershipPrice = MembershipType::where('label', $individual->getMembershipTypeLabel())->first()->price;

        return array_merge($data, [
            'first_name' => $individual->first_name,
            'middle_name' => $individual->middle_name,
            'surname' => $individual->surname,
            'email_address' => $individual->email_address,
            'date_of_birth' => $individual->date_of_birth,
            'amount' => request('amount') + request('discount'),
            'membership_no' => $individual->getMembershipNumber(),
            'membership_price' => $membershipPrice,
        ]);
    }

    /**
     * Checks whether payment is required to be captured.
     *
     * @param int total
     * @param int discount
     * @return bool
     **/
    private function isPaymentRequired($total, $discount)
    {
        // If family renewal is already paid
        if (request('is_family_renewal_already_paid')) {
            return false;
        }

        // If payment is offline
        if (request('payment_type') == 1) {
            return false;
        }

        // If payment amount is zero
        if ($total - $discount == 0) {
            return false;
        }

        return true;
    }

    /**
     * Adds more data from the individual.
     *
     * @param array
     * @return array
     *
     * @throws StripeChargeFailed
     */
    private function collectPayment($data)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $charge = Charge::create([
                'amount' => intval($data['amount'] - $data['discount']) * 100,
                'currency' => 'AUD',
                'source' => request('stripe_token'),
                'description' => 'Membership Renewal of ' . $data['first_name'] . ' ' . $data['surname'] . ' (' . $data['membership_no'] . ')',
                "expand" => ["balance_transaction"],
            ]);
        } catch (\Throwable $th) {
            throw new StripeCardFailed($th->getMessage());
        }

        Log::channel('stripe')->info('Stripe charge created', ['charge' => $charge]);

        if ($charge->captured &&
            ! $charge->amount_refunded &&
            $charge->currency == 'aud'
        ) {
            $data['received_amount'] = $charge->amount / 100;
            $data['transaction_no'] = $charge->id;
            $data['transaction_fee'] = formattedRound($charge->balance_transaction->fee / 100);
            return $data;
        }

        throw new StripeChargeFailed;
    }

    /**
     * Save renewal details
     *
     * @param array $data
     * @param \App\Individual $individual
     * @param \Illuminate\Support\Collection $familyIndividuals
     * @param int $renewalRunId
     * @return void
     **/
    private function saveRenewalDetails($data, $individual, $familyIndividuals, $renewalRunId)
    {
        unset($data['transaction_fee']);
        $individualRenewal = $individual->renewalEntries()->create($data);

        $individual->renewals()->create([
            'individual_renewal_id' => $individualRenewal->id,
            'individual_id' => $individual->id,
            'renewal_run_id' => $renewalRunId
        ]);

        if (request('is_family_renewal_already_paid')) {
            $individualRenewal->parent_renewal_id = $individualRenewal->getRenewalOfFamilyMemberHead($renewalRunId)->id;
            $individualRenewal->save();

            return;
        }

        if ($data['type_id'] == 2) {
            $individualRenewal->familyMembers()->attach($this->getFamilyMemberDetails($familyIndividuals));
            $this->saveFamilyDisciplines($individualRenewal, $familyIndividuals);

            return;
        }


        foreach ($individual->disciplines as $discipline) {
            $disciplinePrice = $data['type_id'] == 3 ? $discipline->pensioner_price : $discipline->adult_price;

            $individualRenewal->disciplines()->attach([
                $discipline->id => [
                    'individual_id' => $individual->id,
                    'is_lifetime_member' => $discipline->pivot->is_lifetime_member,
                    'price' => $disciplinePrice,
                ]
            ]);
        }
    }

    /**
     * Redirects if individual has requested a renewal already.
     *
     * @param int Id of the individual
     * @param int Id of the renewal run
     * @return void
     */
    private function renewalRequestCheck($individualId, $renewalRunId)
    {
        if (Renewal::isRequestedAlready($individualId, $renewalRunId)) {
            abort(redirect('/renewal-requested-already'));
        }

        $renewalRun = RenewalRun::findOrFail($renewalRunId);

        if (
            $renewalRun->payment_due_date &&
            (new Carbon($renewalRun->payment_due_date))->isPast() ||
            ! $renewalRun->status
        ) {
            abort(404);
        }

        $renewalRunEntity = RenewalRunEntity::where('individual_id', $individualId)
            ->where('renewal_run_id', $renewalRunId)
            ->exists()
        ;

        if (! $renewalRunEntity) {
            abort(403);
        }
    }

    /**
     * Saves the family member disciplines
     *
     * @param \App\IndividualRenewal $individualRenewal
     * @param \Illuminate\Support\Collection $familyIndividuals
     * @return void
     **/
    public function saveFamilyDisciplines($individualRenewal, $familyIndividuals)
    {
        foreach (request('family_disciplines') as $key => $disciplineId) {
            $individualIds = explode(',', request('individuals')[$key]);

            foreach ($individualIds as $individualId) {
                $individualRenewal->disciplines()->attach(
                    $this->getFamilyDisciplineDetails($disciplineId, $individualId, $familyIndividuals, $individualIds)
                );
            }
        }
    }

    /**
     * Returns the details of the family member.
     *
     * @param \Illuminate\Support\Collection $familyIndividuals
     * @return array
     **/
    public function getFamilyMemberDetails($familyIndividuals)
    {
        $familyMemberDetails = [];

        foreach (request('family_member') as $familyMemberId) {
            $member = $familyIndividuals->firstWhere('id', $familyMemberId);

            $familyMemberDetails[$familyMemberId] = [
                'is_pensioner' => $member->pension_card,
                'is_committee_member' => $member->is_committee_member,
                'is_club_lifetime_member' => $member->is_club_lifetime_member,
            ];
        }

        return $familyMemberDetails;
    }

    /**
     * Returns the details of the family member discipline.
     *
     * @param int $disciplineId
     * @param int $individualId
     * @param \Illuminate\Support\Collection $familyIndividuals
     * @param array $individualIds
     * @return array
     **/
    public function getFamilyDisciplineDetails($disciplineId, $individualId, $familyIndividuals, $individualIds)
    {
        $familyMember = $familyIndividuals->firstWhere('id', $individualId);
        $familyMemberDiscipline = $familyMember->disciplines->firstWhere('id', $disciplineId);
        $disciplinePrice = $familyMemberDiscipline->adult_price;

        if ($familyMember->pension_card) {
            $disciplinePrice = $familyMemberDiscipline->pensioner_price;
        }

        if (count($individualIds) > 1) {
            $disciplinePrice = $familyMemberDiscipline->family_price;
        }

        return [
            $disciplineId => [
                'individual_id' => $individualId,
                'is_lifetime_member' => $familyMemberDiscipline->pivot->is_lifetime_member,
                'price' => $disciplinePrice,
            ]
        ];
    }

    /**
     * Return the family details as per the request details
     *
     * @param int $familyId family id
     * @return \App\Family
     **/
    public function getFamilyDetailsAsPerRequest($familyId)
    {
        // Individuals maybe something like this - [2, 4, '5,6,7', 9];
        $renewalIndividualIds = request('individuals') ? explode(',', implode(',', request('individuals'))) : [];
        $familyDisciplineIds = request('family_disciplines') ? request('family_disciplines') : [];

        return Family::with([
            'individuals' => function ($query) use ($renewalIndividualIds) {
                $query->whereIn('id', $renewalIndividualIds);
            },
            'individuals.disciplines' => function ($query) use ($renewalIndividualIds, $familyDisciplineIds) {
                $query->whereIn('individual_id', $renewalIndividualIds)
                    ->whereIn('discipline_id', $familyDisciplineIds)
                    ->where(function ($query) {
                        $query->whereNotNull('registered_at')
                            ->orWhere('approved_at', '>=', Carbon::now()->subMonth(6)->format('Y-m-d'))
                        ;
                    })
                ;
            }
        ])->find($familyId);
    }

    /**
     * Logged the client user agent details
     *
     * @param int $individualId
     * @param int $renewalRunId
     * @return void
     **/
    private function logUserAgent($individualId, $renewalRunId)
    {
        $browser = 'Other';
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        if (strpos($userAgent, 'Opera') || strpos($userAgent, 'OPR/')) {
            $browser = 'Opera';
        } elseif (strpos($userAgent, 'Edge')) {
            $browser = 'Edge';
        } elseif (strpos($userAgent, 'Chrome')) {
            $browser = 'Chrome';
        } elseif (strpos($userAgent, 'Safari')) {
            $browser = 'Safari';
        } elseif (strpos($userAgent, 'Firefox')) {
            $browser = 'Firefox';
        } elseif (strpos($userAgent, 'MSIE') || strpos($userAgent, 'Trident/7')) {
            $browser = 'Internet Explorer';
        }
        Log::channel('renewal_user_agent')->info('User Agent Details', [
            'browser_name' => $browser,
            'user_agent_details' => $userAgent,
            'individual_id' => $individualId,
            'renewal_run_id' => $renewalRunId,
            'request_details' => request()->all(),
        ]);
    }
}
