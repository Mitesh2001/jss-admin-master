<?php

namespace App\Http\Controllers;

use App\Suburb;

class StaticDataController extends Controller
{
    /**
     * Returns the suburbs for the specified state
     *
     * @return json
     */
    public function suburbs()
    {
        request()->validate([
            'state_id' => 'required|numeric|exists:states,id'
        ]);

        return Suburb::getSelect2OptionsFor(request('state_id'));
    }
}
