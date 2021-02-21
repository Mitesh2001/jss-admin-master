<?php

namespace App\Http\Controllers\Admin;

use App\Family;
use App\Http\Controllers\Controller;
use App\Individual;
use Freshbitsweb\Laratables\Laratables;

class FamilyController extends Controller
{
    /**
     * Display a list of families
     *
     * @return \Illuminate\Http\Response
     **/
    public function index()
    {
        return view('admin.families.index');
    }

    /**
     * Returns the data for datatables.
     *
     * @return json
     */
    public function datatables()
    {
        return Laratables::recordsOf(Family::class);
    }

    /**
     * Display a add page of Family.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $individuals = Individual::select('id', 'surname', 'first_name')->get();

        return view('admin.families.add', compact(['individuals']));
    }

    /**
     * Store newly created family.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $validatedData = request()->validate(Family::validationRules());

        $family = Family::create([]);

        $individuals = Individual::whereIn('id', $validatedData['individual_id'])
            ->update(['family_id' => $family->id])
        ;

        return redirect()->route('admin.families.index')->with([
            'type' => 'success',
            'message' => 'Family added successfully.'
        ]);
    }

    /**
     * Edit specified family.
     *
     * @param int $familyId
     * @return \Illuminate\Http\Response
     */
    public function edit($familyId)
    {
        $individuals = Individual::select('id', 'surname', 'first_name')->get();

        $family = Family::with('individuals:id,family_id,first_name,surname')
            ->find($familyId)
        ;

        $family->individual_ids = $family->individuals->pluck('id')->toArray();

        return view('admin.families.edit', compact(['individuals', 'family']));
    }

    /**
     * Update specified family.
     *
     * @param int $familyId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($familyId)
    {
        $validatedData = request()->validate(Family::validationRules());

        Individual::where('family_id', $familyId)
            ->update(['family_id' => null])
        ;

        Individual::whereIn('id', $validatedData['individual_id'])
            ->update(['family_id' => $familyId])
        ;

        return redirect()->route('admin.families.index')->with([
            'type' => 'success',
            'message' => 'Family updated successfully.'
        ]);
    }

    /**
     * Deletes the family
     *
     * @param int $familyId
     * @return array
     */
    public function destroy($familyId)
    {
        $family = Family::with(['individuals', 'individuals.renewals'])->find($familyId);

        $isPendingRenewal = $family->individuals->filter(function ($individual, $key) {
            return $individual->renewals->contains('approved', false);
        });

        if ($isPendingRenewal->isNotEmpty()) {
            return redirect()->route('admin.families.index')->with([
                'type' => 'error',
                'message' => 'This family cannot be deleted as there is a pending renewal already.'
            ]);
        }

        $family->individuals()->update(['family_id' => null]);
        $family->delete();

        return redirect()->route('admin.families.index')->with([
            'type' => 'success',
            'message' => 'Family deleted successfully. All the invidividuals will be treated as adults or pensioners now.'
        ]);
    }
}
