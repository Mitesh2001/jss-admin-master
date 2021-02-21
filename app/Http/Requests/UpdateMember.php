<?php

namespace App\Http\Requests;

use App\Rules\StartWithRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMember extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|numeric|exists:individuals,id',
            'membership_number' => 'required|string|exists:individual_memberships,membership_number',
            'occupation' => 'nullable|string|max:255',
            'email_address' => 'required|email|max:255',
            'phone_number' => [
                'required',
                'string',
                'alpha_dash',
                'size:10',
                new StartWithRule('0'),
            ],
            'mobile_number' => [
                'nullable',
                'string',
                'alpha_dash',
                'size:10',
                new StartWithRule('04'),
            ],
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'suburb_id' => 'required|numeric|exists:suburbs,id',
            'post_code' => 'required|string|size:4',
            'state_id' => 'required|numeric|exists:states,id',
            'ssaa_expiry' => 'nullable|date|after_or_equal:today',
            'google_recaptcha_token' => 'required|string',
        ];
    }
}
