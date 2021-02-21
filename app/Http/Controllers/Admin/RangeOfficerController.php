<?php

namespace App\Http\Controllers\Admin;

use App\Discipline;
use App\Http\Controllers\Controller;
use App\Individual;
use App\RangeOfficer;
use Freshbitsweb\Laratables\Laratables;

class RangeOfficerController extends Controller
{
    /**
     * Display a list of range officers
     *
     * @return \Illuminate\Http\Response
     **/
    public function index()
    {
        $this->setFiltersInSessionIfRequired();
        $individuals = Individual::select('id', 'surname', 'first_name')->with('membership')->get()->sortBy('first_name');
        $disciplines = Discipline::getList()->sortBy('label');

        return view('admin.range_officers.index', compact('individuals', 'disciplines'));
    }

    /**
     * Returns the data for datatables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatables()
    {
        return Laratables::recordsOf(RangeOfficer::class);
    }

    /**
     * Saves the new range officer
     *
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function store()
    {
        $validatedData = request()->validate(RangeOfficer::validationRules());

        $officerCount = RangeOfficer::where('discipline_id', $validatedData['discipline_id'])
            ->where('individual_id', $validatedData['individual_id'])
            ->count()
        ;

        if ($officerCount) {
            return back()->with([
                'type' => 'error',
                'message' => 'The discipline has already been taken.',
            ]);
        }

        RangeOfficer::create($validatedData);

        return back()->with([
            'type' => 'success',
            'message' => 'Range officer added successfully.',
        ]);
    }

    /**
     * Modifies the specified range officer
     *
     * @param \App\RangeOfficer $rangeOfficer
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function update(RangeOfficer $rangeOfficer)
    {
        $validatedData = request()->validate(RangeOfficer::validationRules());

        $officerCount = RangeOfficer::where('discipline_id', $validatedData['discipline_id'])
            ->where('individual_id', $validatedData['individual_id'])
            ->where('id', '!=', $rangeOfficer->id)
            ->count()
        ;

        if ($officerCount) {
            return back()->with([
                'type' => 'error',
                'message' => 'The discipline has already been taken.',
            ]);
        }

        $rangeOfficer->update($validatedData);

        return back()->with([
            'type' => 'success',
            'message' => 'Range officer updated successfully.',
        ]);
    }

    /**
     * Removes the specified range officer
     *
     * @param \App\RangeOfficer $rangeOfficer
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function destroy(RangeOfficer $rangeOfficer)
    {
        $rangeOfficer->delete();

        return back()->with([
            'type' => 'success',
            'message' => 'Range officer deleted successfully.',
        ]);
    }

    /**
     * Set default filter session values
     *
     * @return void
     **/
    private function setFiltersInSessionIfRequired()
    {
        if (session()->exists('range_officer_discipline')) {
            return;
        }

        session(['range_officer_discipline' => 'all']);
    }

    /**
     * Sets the filters to the session
     *
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function setFilters()
    {
        session(['range_officer_discipline' => request('discipline')]);

        return back()->with([
            'type' => 'success',
            'message' => 'Filters applied successfully.',
        ]);
    }
}
