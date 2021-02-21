<?php

namespace App;

use App\Rules\StartWithRule;
use App\Traits\Addressable;
use App\Traits\IndividualLaratables;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\Traits\LogsActivity;

/** @mixin \Eloquent */
class Individual extends Authenticatable
{
    use Addressable, SoftDeletes, LogsActivity, IndividualLaratables;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Validation rules
     *
     * @return array
     **/
    public static function validationRules($individualId = null)
    {
        return [
            'surname' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date',
            'gender_id' => 'nullable|numeric|exists:genders,id',
            'email_address' => 'nullable|email',
            'phone_number' => [
                'nullable',
                'string',
                'alpha_dash',
                'max:10',
                'min:10',
                new StartWithRule('0'),
            ],
            'mobile_number' => [
                'nullable',
                'string',
                'alpha_dash',
                'max:10',
                'min:10',
                new StartWithRule('04'),
            ],
            'occupation' => 'nullable|string|max:255',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'suburb_id' => 'nullable|numeric|exists:suburbs,id',
            'state_id' => 'nullable|numeric|exists:states,id',
            'post_code' => 'nullable|string|max:4|min:4',
            'pension_card' => 'required|boolean',
            'is_committee_member' => 'required|boolean',
            'is_club_lifetime_member' => 'required|boolean',
            'branch_code_id' => 'required|numeric|exists:branch_codes,id',
            'wwc_card_number' => 'nullable|string',
            'wwc_expiry_date' => 'nullable|date',
        ];
    }

    /**
     * Validation custom messages
     *
     * @return array
     **/
    public static function validationRulesCustomMessage()
    {
        return [
            'phone_number.alpha_dash' => 'The phone number should be limited to numbers only.',
            'mobile_number.alpha_dash' => 'The mobile number should be limited to numbers only.',
            'phone_number.max' => 'The phone number should be exactly 10 characters, no more, no less.',
            'mobile_number.max' => 'The mobile number should be exactly 10 characters, no more, no less.',
            'phone_number.min' => 'The phone number should be exactly 10 characters, no more, no less.',
            'mobile_number.min' => 'The mobile number should be exactly 10 characters, no more, no less.',
        ];
    }

    /**
     * Returns the name of the individual
     *
     * @return string
     **/
    public function getName()
    {
        return $this->first_name . ' ' . $this->surname;
    }

    /**
     * Returns the full name of the individual
     *
     * @return string
     **/
    public function getFullName()
    {
        return $this->first_name . ' ' . $this->middle_name . ' ' . $this->surname;
    }

    /**
     * Get the gender of the individual.
     */
    public function gender()
    {
        return $this->belongsTo('App\Gender');
    }

    /**
     * Get the suburb of the individual.
     */
    public function suburb()
    {
        return $this->belongsTo('App\Suburb');
    }

    /**
     * Get the state of the individual.
     */
    public function state()
    {
        return $this->belongsTo('App\State');
    }

    /**
     * Get the branch code of the individual.
     */
    public function branchCode()
    {
        return $this->belongsTo('App\BranchCode');
    }

    /**
     * Get the ssaa details of the individual.
     */
    public function ssaa()
    {
        return $this->hasOne('App\IndividualSsaa');
    }

    /**
     * Get the membership of the individual.
     */
    public function membership()
    {
        return $this->hasOne('App\IndividualMembership');
    }

    /**
    * The individual that belong to the disicipline.
    */
    public function disciplines()
    {
        return $this->belongsToMany('App\Discipline')
            ->withTimestamps()
            ->withPivot('id', 'is_lifetime_member', 'registered_at', 'approved_at')
        ;
    }

    /**
     * Get the officers of the individual.
     */
    public function officers()
    {
        return $this->hasMany('App\RangeOfficer');
    }

    /**
     * Get the events of the individual.
     */
    public function events()
    {
        return $this->hasMany('App\Event')->orderBy('happened_at', 'desc');
    }

    /**
     * Get the renewal entry of the individual.
     */
    public function renewalEntries()
    {
        return $this->hasMany('App\IndividualRenewal');
    }

    /**
     * Get the renewals of the individual.
     */
    public function renewals()
    {
        return $this->hasMany('App\Renewal');
    }

    /**
     * Get the receipts of the individual.
     */
    public function receipts()
    {
        return $this->belongsToMany('App\Receipt', 'receipt_individuals')->withPivot('is_payer');
    }

    /**
     * Get the ID Cards details of the individual.
     */
    public function idCards()
    {
        return $this->hasMany('App\IdCard');
    }

    /**
     * Get the issued keys details of the individual.
     */
    public function issuedKeys()
    {
        return $this->hasMany('App\Key')
            ->whereNull('returned_at')
            ->whereNull('loosed_at')
        ;
    }

    /**
     * Get the ID Card of the individual.
     */
    public function idCard()
    {
        return $this->hasOne('App\IdCard')->whereNull('printed_at');
    }

    /**
    * Get the supported firearms for the individual.
    */
    public function supportedFirearms()
    {
        return $this->belongsToMany('App\Firearm')
            ->whereNull('support_removed_at')
            ->whereNull('mark_as_disposed_at')
        ;
    }

    /**
     * Returns the label of the suburb of the individual
     *
     * @return string
     **/
    public function getSuburb()
    {
        return optional($this->suburb)->label;
    }

    /**
     * Returns the label of the state of the individual
     *
     * @return string
     **/
    public function getState()
    {
        return optional($this->state)->label;
    }

    /**
     * Returns the membership number of the individual
     *
     * @return string
     **/
    public function getMembershipNumber()
    {
        return optional($this->membership)->membership_number;
    }

    /**
     * Get the membership type label of the individual.
     */
    public function getType()
    {
        return optional(optional($this->membership)->type)->label;
    }

    /**
     * Get the membership type label of the individual.
     */
    public function getMembershipPrice()
    {
        return optional($this->membership)->getPrice();
    }

    /**
     * Returns the static details
     *
     * @return array
     **/
    public static function getDetails($individualId = null)
    {
        $staticDetails = [
            'genders' => Gender::getList(),
            'states' => State::getList(),
            'suburbs' => Suburb::getSelect2OptionsFor($stateId = 4),
            'branchCodes' => BranchCode::getList(),
        ];

        if ($individualId) {
            $individual = static::with('ssaa', 'membership', 'branchCode', 'events', 'events.type', 'disciplines', 'officers', 'receipts', 'receipts.items', 'receipts.payments', 'idCards', 'issuedKeys')
                ->withCount('supportedFirearms')
                ->find($individualId)
            ;

            $staticDetails = array_merge(
                $staticDetails,
                [
                    'membershipTypes' => MembershipType::getList(),
                    'eventTypes' => EventType::getList(),
                    'disciplineTypes' => Discipline::getList(),
                    'individual' => $individual,
                    'individualMembership' => optional($individual)->membership,
                    'individualDisciplines' => optional($individual)->disciplines,
                    'officers' => optional($individual)->officers,
                    'receipts' => optional($individual)->receipts,
                    'membershipNumber' => 'W02 ' . optional($individual->ssaa)->ssaa_number,
                    'individualEvents' => optional($individual)->events,
                ]
            );
        }

        return $staticDetails;
    }

    /**
     * Returns the parameter of the renewal
     *
     * @return array
     **/
    public static function getRenewalParameters($individualId)
    {
        $individual = Individual::with([
            'membership', 'membership.type', 'disciplines' => function ($query) {
                $query->whereNotNull('registered_at')
                    ->orWhere('approved_at', '>=', Carbon::now()->subMonth(6)->format('Y-m-d'))
                ;
            }
        ])->find($individualId);

        $membershipTypes = MembershipType::getList();

        $numberOfColumns = 1;
        $numberOfColumns += $individual->family_id ? 1 : 0;
        $numberOfColumns += $individual->pension_card ? 1 : 0;

        $family = $individual->getRenewalFamily();
        $familyIndividuals = optional($family)->individuals;

        $currentTypeId = optional($individual->membership)->type_id;
        if (($currentTypeId == 2 && ! $individual->family_id) ||
            ($currentTypeId == 3 && ! $individual->pension_card)
        ) {
            $currentTypeId = 1;
        }

        $isIndividualWithoutDisciplines = optional(optional($individual)->disciplines)->count() ? 0 : 1;
        $isFamilyWithoutDisciplines = $familyIndividuals ? $individual->isFamilyWithoutDisciplines($familyIndividuals) : 0;

        return [
            'individual' => $individual,
            'disciplines' => Discipline::all(),
            'genders' => Gender::getList(),
            'familyIndividualDisciplineIds' => $individual->getFamilyDisciplines($familyIndividuals),
            'familyIndividualIds' => $familyIndividuals ? $family->individuals->pluck('id')->toArray() : [],
            'states' => State::getList(),
            'suburbs' => Suburb::getSelect2OptionsFor($stateId = 4),
            'family' => $family,
            'currentTypeId' => $currentTypeId,
            'isIndividualWithoutDisciplines' => $isIndividualWithoutDisciplines,
            'isFamilyWithoutDisciplines' => $isFamilyWithoutDisciplines,
            'numberOfColumns' => 12 / $numberOfColumns,
            'familyPrice' => $membershipTypes->where('label', 'Family')->first()->price,
            'adultPrice' => $membershipTypes->where('label', 'Adult')->first()->price,
            'pensionerPrice' => $membershipTypes->where('label', 'Pensioner')->first()->price,
            'membershipTypes' => $membershipTypes,
        ];
    }

    /**
     * Returns the family details if the individual membership type is family.
     *
     * @return mixed \App\Family | null
     **/
    public function getRenewalFamily()
    {
        if (! $this->family_id) {
            return null;
        }

        $family = Family::with([
            'individuals', 'individuals.membership', 'individuals.disciplines' => function ($query) {
                $query->whereNotNull('registered_at')
                    ->orWhere('approved_at', '>=', Carbon::now()->subMonth(6)->format('Y-m-d'))
                ;
            }
        ])->find($this->family_id);

        $family->individuals->transform(function ($familyIndividual) {
            $familyIndividual->membershipNumber = $familyIndividual->getMembershipNumber();
            return $familyIndividual;
        });

        return $family;
    }

    /**
     * Returns the individual and discipline ids of the family
     *
     * @return \Illuminate\Support\Collaction
     * @return array
     **/
    public function getFamilyDisciplines($familyIndividuals)
    {
        if (! $familyIndividuals) {
            return [];
        }

        $familyIndividuals->transform(function ($individual) {
            $individual->discipline_ids = $individual->disciplines->pluck('id')->toArray();
            return $individual;
        })->get('id');

        return $familyIndividuals->pluck('discipline_ids', 'id')->toArray();
    }

    /**
     * Returns the id of the only paid member of the family, if any.
     *
     * @param \Illuminate\Support\Collection
     * @return mixed
     **/
    public function getFamilySpecialMember($familyIndividuals)
    {
        $specialMembers = $this->getSpecialMembers($familyIndividuals);

        if (optional($specialMembers)->count() == 1) {
            return $specialMembers->first();
        }

        return 0;
    }

    /**
     * Checks if any of the family members has no disciplines.
     *
     * @param \Illuminate\Support\Collection
     * @return boolean 0 or 1
     **/
    public function isFamilyWithoutDisciplines($familyIndividuals)
    {
        return (int) $familyIndividuals->contains(function ($familyIndividual) {
            return $familyIndividual->disciplines->isEmpty();
        });
    }

    /**
     * Returns the amount of the renewal
     *
     * @param \App\Family $family
     * @return array renewal amount && discount
     **/
    public function getRenewalAmount($family)
    {
        $membershipType = $this->getMembershipTypeLabel();

        $membershipPrice = MembershipType::where('label', $membershipType)->first()->price;

        $hasSpecialMember = optional($family)->individuals ?
            $this->getFamilySpecialMember(optional($family)->individuals) :
            0
        ;

        $discount = 0;
        if ($hasSpecialMember && request('type_id') == 2) {
            $mType = 'Adult';

            if ($hasSpecialMember->pension_card) {
                $mType = 'Pensioner';
            }

            $discount += MembershipType::where('label', $mType)->first()->price;
        }

        // Get all the discipline amount as per membership type
        $disciplineAmount = $this->getDisciplineAmount(strtolower($membershipType) . '_price');

        $discount += $this->getDiscount($membershipPrice, $family);

        return [
            number_format($membershipPrice + $disciplineAmount, 2),
            number_format($discount, 2),
        ];
    }

    /**
     * Get discount for committee member
     *
     * @param decimal $membershipPrice
     * @param \App\Family $family
     * @return int discount amount
     **/
    public function getDiscount($membershipPrice, $family)
    {
        $discount = 0;

        // Returns the discount if committee member is adult and pensioner
        if (in_array(request('type_id'), [1, 3])) {
            if ($this->is_committee_member || $this->is_club_lifetime_member) {
                $discount += $membershipPrice;
            }

            $disciplines = $this->disciplines;
            foreach (request('disciplines') as $disciplineId) {
                $discipline = $disciplines->firstWhere('id', $disciplineId);
                if ($discipline->pivot->is_lifetime_member) {
                    if ($this->pension_card) {
                        $discount += $discipline->pensioner_price;
                        continue;
                    }

                    $discount += $discipline->adult_price;
                }
            }

            return $discount;
        }

        if (! request('individuals')) {
            return 0;
        }

        $discount = 0;

        $requestIndividuals = Individual::query()
            ->whereIn('id', explode(',', implode(',', request('individuals'))))
            ->get()
        ;

        // If family members have more than or equal to 2 special member then Family Membership Free
        if ($this->getSpecialMembers($requestIndividuals)->count() >= 2) {
            $discount += $membershipPrice;
        }

        return $discount + $this->getFamilyDisciplinesDiscount($family);
    }

    /**
     * Returns the family disciplines discount
     *
     * @param \App\Family $family
     * @return int discount
     **/
    public function getFamilyDisciplinesDiscount($family)
    {
        $discount = 0;
        $notDiscountableDiscipline = collect([]);
        $disciplines = Discipline::whereIn('id', request('family_disciplines'))->get();

        foreach (request('family_disciplines') as $key => $disciplineId) {
            $familyMemberIds = explode(',', request('individuals')[$key]);

            // Count discount for the family member common disciplines (all family members)
            if (count($familyMemberIds) > 1) {
                $familyIndividuals = $family->individuals->whereIn('id', $familyMemberIds);
                $totalDiscountableFamilyMember = 0;

                foreach ($familyIndividuals as $familyIndividual) {
                    $familyDiscipline = $familyIndividual->disciplines->firstWhere('id', $disciplineId);

                    if ($familyDiscipline->pivot->is_lifetime_member) {
                        $totalDiscountableFamilyMember++;
                        continue;
                    }

                    $familyDiscipline->individual = $familyIndividual;
                    $notDiscountableDiscipline->push($familyDiscipline);
                }

                if ($totalDiscountableFamilyMember == $family->individuals->count()) {
                    $discount += $disciplines->firstWhere('id', $disciplineId)->family_price;
                    continue;
                }

                if ($totalDiscountableFamilyMember == $family->individuals->count() - 1) {
                    $discipline = $disciplines->firstWhere('id', $disciplineId);

                    if ($notDiscountableDiscipline[0]->individual->pension_card) {
                        $discount += $discipline->family_price - $discipline->pensioner_price;
                        continue;
                    }

                    $discount += $discipline->family_price - $discipline->adult_price;
                }

                continue;
            }

            // Count discount for the family member individual disciplines
            $familyIndividual = $family->individuals->firstWhere('id', $familyMemberIds[0]);
            $familyDiscipline = $familyIndividual->disciplines->firstWhere('id', $disciplineId);

            if ($familyDiscipline->pivot->is_lifetime_member) {
                if ($familyIndividual->pension_card) {
                    $discount += $familyDiscipline->pensioner_price;

                    continue;
                }

                $discount += $familyDiscipline->adult_price;
            }
        }

        return $discount;
    }

    /**
     * Returns the count of the free membership member
     *
     * @param \Illuminate\Support\Collection
     * @return \Illuminate\Support\Collection
     **/
    public function getSpecialMembers($individuals)
    {
        return $individuals->filter(function ($individual) {
            return $individual->is_committee_member || $individual->is_club_lifetime_member;
        });
    }

    /**
     * Returns the total disciplines price
     *
     * @param string price field name
     * @return int discipline price
     **/
    public function getDisciplineAmount($field)
    {
        // If membership type is adult or pensioner then return only discipline price
        if (request('type_id') != 2) {
            return Discipline::whereIn('id', request('disciplines'))->sum($field);
        }

        $totalAmount = 0;
        $disciplines = Discipline::whereIn('id', request('family_disciplines'))->get();
        $individuals = Individual::with('membership:id,individual_id,type_id')
            ->whereIn('id', explode(',', implode(',', request('individuals'))))
            ->get()
        ;

        foreach (request('family_disciplines') as $key => $disciplineId) {
            $individualIds = explode(',', request('individuals')[$key]);

            // If discipline have more then 1 family member then return family price
            if (count($individualIds) > 1) {
                $totalAmount += $disciplines->firstWhere('id', $disciplineId)->family_price;

                continue;
            }

            // If family member is pensioner then return pensioner price
            $isPensioner = optional(
                $individuals->firstWhere('id', $individualIds[0])
            )->pension_card;
            if ($isPensioner) {
                $totalAmount += $disciplines->firstWhere('id', $disciplineId)->pensioner_price;

                continue;
            }

            // Set adult price if not family member or pensioner
            $totalAmount += $disciplines->firstWhere('id', $disciplineId)->adult_price;
        }

        return $totalAmount;
    }

    /**
     * Return the membership type label
     *
     * @param int Type id
     * @return string
     **/
    public function getMembershipTypeLabel($typeId = null)
    {
        $typeId = $typeId ?: request('type_id');

        if ($typeId == 1) {
            return 'Adult';
        }

        if ($typeId == 3) {
            return 'Pensioner';
        }

        return 'Family';
    }

    /**
     * Update individual details
     *
     * @param \App\IndividualRenewal $individualRenewal
     * @return void
     **/
    public function updateDetails($individualRenewal)
    {
        $this->gender_id = $individualRenewal->gender_id;
        $this->mobile_number = $individualRenewal->mobile_number;
        $this->phone_number = $individualRenewal->phone_number;
        $this->address_line_1 = $individualRenewal->address_line_1;
        $this->address_line_2 = $individualRenewal->address_line_2;
        $this->suburb_id = $individualRenewal->suburb_id;
        $this->state_id = $individualRenewal->state_id;
        $this->post_code = $individualRenewal->post_code;

        $this->save();

        $this->ssaa->ssaa_expiry = $individualRenewal->ssaa_expiry;
        $this->ssaa->save();
    }

    /**
     * Returns the link to set the password.
     *
     * @return string
     **/
    public function getChoosePasswordLink()
    {
        $url = url()->temporarySignedRoute(
            'front.member_portal',
            now()->addHour(),
            [
                'choose-password' => true,
                'individual-id' => $this->id,
                'membership-number' => $this->ssaa->ssaa_number,
            ]
        );

        return str_replace(url('/').'/member-portal', config('app.member_portal_url'), $url);
    }

    /**
     * Update member details from the request
     *
     * @return void
     **/
    public function updateFromRequest()
    {
        $this->update([
            "occupation" => request('occupation'),
            "email_address" => request('email_address'),
            "phone_number" => request('phone_number'),
            "mobile_number" => request('mobile_number'),
            "address_line_1" => request('address_line_1'),
            "address_line_2" => request('address_line_2'),
            "suburb_id" => request('suburb_id'),
            "post_code" => request('post_code'),
            "state_id" => request('state_id'),
        ]);

        if (request('ssaa_expiry')) {
            $this->ssaa->ssaa_expiry = request('ssaa_expiry');
            $this->ssaa->save();
        }
    }
}
