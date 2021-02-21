<?php

namespace App\Http\Controllers\Admin;

use App\Discipline;
use App\Firearm;
use App\FirearmType;
use App\Http\Controllers\Controller;
use App\Individual;
use Freshbitsweb\Laratables\Laratables;
use Illuminate\Http\Request;

class FirearmController extends Controller
{
    /**
     * Display a list of firearms
     *
     * @return \Illuminate\Http\Response
     **/
    public function index()
    {
        $this->setFiltersInSessionIfRequired();

        $individuals = Individual::select('id', 'surname', 'first_name')->with('membership')->get()->sortBy('first_name');
        $disciplines = Discipline::getList()->sortBy('label');
        $types = FirearmType::getList()->sortBy('label');

        return view('admin.firearms.index', compact('individuals', 'disciplines', 'types'));
    }

    /**
     * Returns the data for datatables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatables()
    {
        return Laratables::recordsOf(Firearm::class);
    }

    /**
     * Saves the new firearm
     *
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function store(Request $request)
    {
        $validatedData = request()->validate(Firearm::validationRules());

        unset($validatedData['individual_ids']);

        $firearm = Firearm::create($validatedData);

        $firearm->individuals()->attach($request->input('individual_ids'));

        return back()->with([
            'type' => 'success',
            'message' => 'Firearm added successfully.',
        ]);
    }

    /**
     * UPdates the specified firearm
     *
     * @param \App\Firearm $firearm
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function update(Request $request, Firearm $firearm)
    {
        $validatedData = request()->validate(Firearm::validationRules());

        unset($validatedData['individual_ids']);

        $firearm->update($validatedData);

        $firearm->individuals()->detach();
        $firearm->individuals()->attach($request->input('individual_ids'));

        return back()->with([
            'type' => 'success',
            'message' => 'Firearm updated successfully.',
        ]);
    }

    /**
     * Remove support for specified firearm
     *
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function removeSupport()
    {
        $validatedData = request()->validate([
            'id' => 'required|exists:firearms,id',
            'support_removed_at' => 'required|date',
            'support_reason' => 'required|string',
        ]);

        $firearm = Firearm::findOrFail(request('id'));
        $firearm->support_removed_at = $validatedData['support_removed_at'];
        $firearm->support_reason = $validatedData['support_reason'];
        $firearm->save();

        return back()->with([
            'type' => 'success',
            'message' => 'Firearm has beed remove from support successfully.',
        ]);
    }

    /**
     * Mark as disposed specified firearm
     *
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function markAsDisposed()
    {
        $validatedData = request()->validate([
            'id' => 'required|exists:firearms,id',
            'mark_as_disposed_at' => 'required|date',
            'disposed_reason' => 'required|string',
        ]);

        $firearm = Firearm::findOrFail(request('id'));
        $firearm->mark_as_disposed_at = $validatedData['mark_as_disposed_at'];
        $firearm->disposed_reason = $validatedData['disposed_reason'];
        $firearm->save();

        return back()->with([
            'type' => 'success',
            'message' => 'Firearm has beed remove from support successfully.',
        ]);
    }

    /**
     * Set default filter session values
     *
     * @return void
     **/
    public function setFiltersInSessionIfRequired()
    {
        if (session()->exists('firearm_discipline_type')) {
            return;
        }

        session(['firearm_discipline_type' => 0]);
        session(['firearm_status' => 1]);
        session(['firearm_membership_status' => 0]);
    }

    /**
     * Set firearms display filters
     *
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function setFilters()
    {
        session(['firearm_discipline_type' => request('discipline')]);
        session(['firearm_status' => request('firearm_status')]);
        session(['firearm_membership_status' => request('firearm_membership_status')]);
        session(['firearm_membership_number' => request('firearm_membership_number')]);

        return back()->with([
            'type' => 'success',
            'message' => 'Status applied successfully.',
        ]);
    }
}
