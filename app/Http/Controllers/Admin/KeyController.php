<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Individual;
use App\Key;
use Freshbitsweb\Laratables\Laratables;

class KeyController extends Controller
{
    /**
     * Display a list of keys
     *
     * @return \Illuminate\Http\Response
     **/
    public function index()
    {
        $this->setFiltersInSessionIfRequired();
        $individuals = Individual::select('id', 'surname', 'first_name')->with('membership')->get()->sortBy('first_name');

        return view('admin.keys.index', compact('individuals'));
    }

    /**
     * Returns the data for datatables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatables()
    {
        return Laratables::recordsOf(Key::class);
    }

    /**
     * Saves the new key
     *
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function store()
    {
        $validatedData = request()->validate(Key::validationRules());

        $keyCount = Key::query()
            ->where('key_type', $validatedData['key_type'])
            ->where('key_number', $validatedData['key_number'])
            ->whereNull('returned_at')
            ->whereNull('loosed_at')
            ->count()
        ;

        if ($keyCount) {
            return back()->with([
                'type' => 'error',
                'message' => 'Key has already been taken.',
            ]);
        }

        Key::create($validatedData);

        return back()->with([
            'type' => 'success',
            'message' => 'Key added successfully.',
        ]);
    }

    /**
     * Modifies the specified key
     *
     * @param \App\Key $key
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function update(Key $key)
    {
        $validatedData = request()->validate(Key::validationRules());

        $keyCount = Key::query()
            ->where('key_type', $validatedData['key_type'])
            ->where('key_number', $validatedData['key_number'])
            ->whereNull('returned_at')
            ->whereNull('loosed_at')
            ->where('id', '!=', $key->id)
            ->count()
        ;

        if ($keyCount) {
            return back()->with([
                'type' => 'error',
                'message' => 'Key has already been taken.',
            ]);
        }

        $key->update($validatedData);

        return back()->with([
            'type' => 'success',
            'message' => 'Key updated successfully.',
        ]);
    }

    /**
     * Removes the specified key
     *
     * @param \App\Key $key
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function destroy(Key $key)
    {
        $key->delete();

        return back()->with([
            'type' => 'success',
            'message' => 'Key deleted successfully.',
        ]);
    }

    /**
     * Mark specified key as loosed
     *
     * @param \App\Key $key
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function markAsLost(Key $key)
    {
        $validatedData = request()->validate([
            'loosed_at' => 'required|date'
        ]);

        $key->loosed_at = $validatedData['loosed_at'];
        $key->save();

        return back()->with([
            'type' => 'success',
            'message' => 'Key mark as lost successfully.',
        ]);
    }

    /**
     * Mark specified key as returned
     *
     * @param \App\Key $key
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function markAsReturned(Key $key)
    {
        $validatedData = request()->validate([
            'returned_at' => 'required|date'
        ]);

        $key->returned_at = $validatedData['returned_at'];
        $key->save();

        return back()->with([
            'type' => 'success',
            'message' => 'Key mark as returned successfully.',
        ]);
    }

    /**
     * Set default filter session values
     *
     * @return void
     **/
    private function setFiltersInSessionIfRequired()
    {
        if (session()->exists('key_status')) {
            return;
        }

        session(['key_status' => 1]);
        session(['key_type' => 0]);
    }

    /**
     * Set key display filters
     *
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function setFilters()
    {
        session(['key_status' => request('key_status')]);
        session(['key_type' => request('key_type')]);

        return back()->with([
            'type' => 'success',
            'message' => 'Filters applied successfully.',
        ]);
    }
}
