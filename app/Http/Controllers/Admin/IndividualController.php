<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Individual;
use App\IndividualMembership;
use App\IndividualSsaa;
use Freshbitsweb\Laratables\Laratables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class IndividualController extends Controller
{
    /**
     * Display a list of Individuals.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.individuals.index');
    }

    /**
     * Returns the data for datatables.
     *
     * @return json
     */
    public function datatables()
    {
        return Laratables::recordsOf(Individual::class);
    }

    /**
     * Display a add page of Individual.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parameters = Individual::getDetails();

        return view('admin.individuals.add', $parameters);
    }

    /**
     * Store new Individual.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $validatedData = Validator::make(
            request()->all(),
            Individual::validationRules(),
            Individual::validationRulesCustomMessage()
        )->validate();

        Validator::make(
            request()->all(),
            IndividualSsaa::validationRules(),
            IndividualSsaa::validationRulesCustomMessage()
        )->validate();

        $validatedData = $this->changeCaseOfSpecificField($validatedData);

        $individual = Individual::create($validatedData);

        $individual->ssaa()->create([
            'ssaa_number' => request('ssaa_number'),
            'ssaa_status' => request('ssaa_status'),
            'ssaa_expiry' => request('ssaa_expiry')
        ]);

        $individual->idCard()->create();

        return redirect()->route('admin.individuals.edit', ['individual' => $individual->id])->with([
            'type' => 'success',
            'message' => 'Individual added. And, an entry is made in the ID Cards to be printed.'
        ]);
    }

    /**
     * Edit individual page
     *
     * @param \App\Individual $individual
     * @return \Illuminate\Http\Response
     **/
    public function edit($individualId)
    {
        $parameters = Individual::getDetails($individualId);

        return view('admin.individuals.edit', $parameters);
    }

    /**
     * Update Individual.
     *
     * @param \App\Individual $individual
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Individual $individual)
    {
        $validatedData = Validator::make(
            request()->all(),
            Individual::validationRules($individual->id),
            Individual::validationRulesCustomMessage()
        )->validate();

        Validator::make(
            request()->all(),
            IndividualSsaa::validationRules($individual->id),
            IndividualSsaa::validationRulesCustomMessage()
        )->validate();
        $membershipValidatedData = request()->validate(IndividualMembership::validationRules());

        $validatedData = $this->changeCaseOfSpecificField($validatedData);

        $individual->update($validatedData);

        $individual->ssaa()->update([
            'ssaa_number' => request('ssaa_number'),
            'ssaa_status' => request('ssaa_status'),
            'ssaa_expiry' => request('ssaa_expiry')
        ]);

        $this->addMembershipStatusEvent($individual);

        $individual->membership()->updateOrCreate(
            ['individual_id' => $individual->id],
            [
                'membership_number' => $membershipValidatedData['membership_number'],
                'join_date' => $membershipValidatedData['join_date'],
                'status' => $membershipValidatedData['membership_status'],
                'type_id' => $membershipValidatedData['membership_type_id'],
                'expiry' => $membershipValidatedData['expiry'],
                'notes' => $membershipValidatedData['notes'],
            ]
        );

        return redirect()->route('admin.individuals.edit', ['individual' => $individual->id])->with([
            'type' => 'success',
            'message' => 'Individual updated successfully.'
        ]);
    }

    /**
     * Update individual ID Card
     *
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function updateIdCard()
    {
        request()->validate([
            'individual_id' => 'required|exists:individuals,id',
            'status' => 'required|in:1,0'
        ]);

        $individual = Individual::with('idCard')->find(request('individual_id'));

        if (request('status') == 1) {
            $individual->idCard->delete();

            return;
        }

        $individual->idCard()->create();
    }

    /**
     * Makes event when the status is changed
     *
     * @param \App\Individual
     * @return void
     **/
    public function addMembershipStatusEvent($individual)
    {
        if (
            ! $individual->membership ||
            $individual->membership->status == request('membership_status')
        ) {
            return;
        }

        $individual->events()->create([
            'type_id' => $individual->membership->getStatusEventTypeIdFor(request('membership_status')),
            'happened_at' => now(),
        ]);
    }

    /**
     * Sets the filter for records and displays the individuals.
     *
     * @return Illuminate\Http\Response
     */
    public function filter($filter = 'all')
    {
        session(['individuals_filter' => $filter]);

        return $this->index();
    }

    /**
     * Change case of specific field to the title case
     *
     * @param array $validatedData
     * @return array $validatedData
     **/
    public function changeCaseOfSpecificField($validatedData)
    {
        $validatedData['surname'] = Str::title($validatedData['surname']);
        $validatedData['first_name'] = Str::title($validatedData['first_name']);
        $validatedData['middle_name'] = Str::title($validatedData['middle_name']);
        $validatedData['occupation'] = Str::title($validatedData['occupation']);
        $validatedData['address_line_1'] = Str::title($validatedData['address_line_1']);
        $validatedData['address_line_2'] = Str::title($validatedData['address_line_2']);

        return $validatedData;
    }
}
