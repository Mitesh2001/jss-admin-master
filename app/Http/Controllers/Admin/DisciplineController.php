<?php

namespace App\Http\Controllers\Admin;

use App\Discipline;
use App\Http\Controllers\Controller;
use Freshbitsweb\Laratables\Laratables;

class DisciplineController extends Controller
{
    /**
     * Display a list of disciplines
     *
     * @return \Illuminate\Http\Response
     **/
    public function index()
    {
        return view('admin.disciplines.index');
    }

    /**
     * Returns the data for datatables.
     *
     * @return json
     */
    public function datatables()
    {
        return Laratables::recordsOf(Discipline::class);
    }

    /**
     * Display a add page of discipline.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.disciplines.add');
    }

    /**
     * Store newly created discipline.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $validatedData = request()->validate(Discipline::validationRules());

        Discipline::create($validatedData);

        return redirect()->route('admin.disciplines.index')->with([
            'type' => 'success',
            'message' => 'Discipline added successfully.'
        ]);
    }

    /**
     * Edit specified discipline.
     *
     * @param \App\Discipline $discipline
     * @return \Illuminate\Http\Response
     */
    public function edit(Discipline $discipline)
    {
        return view('admin.disciplines.edit', compact(['discipline']));
    }

    /**
     * Update specified discipline.
     *
     * @param \App\Discipline $discipline
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Discipline $discipline)
    {
        $validatedData = request()->validate(Discipline::validationRules());

        $discipline->update($validatedData);

        return redirect()->route('admin.disciplines.index')->with([
            'type' => 'success',
            'message' => 'Discipline updated successfully.'
        ]);
    }

    /**
     * Deletes the discipline
     *
     * @param \App\Discipline $discipline
     * @return array
     */
    public function destroy(Discipline $discipline)
    {
        $discipline->delete();

        return redirect()->route('admin.disciplines.index')->with([
            'type' => 'success',
            'message' => 'Discipline deleted successfully.'
        ]);
    }
}
