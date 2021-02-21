<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MembershipNumberValidation extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'membership_number' => 'required|numeric|exists:individual_ssaas,ssaa_number',
            'google_recaptcha_token' => 'required|string',
        ];
    }
}
