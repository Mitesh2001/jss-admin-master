<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

/** @mixin \Eloquent */
class IndividualSsaa extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Validation rules
     *
     * @return array
     **/
    public static function validationRules($individualId = null)
    {
        $uniqueRule = Rule::unique('individual_ssaas');

        if ($individualId) {
            $uniqueRule = $uniqueRule->ignore($individualId, 'individual_id');
        }

        return [
            'ssaa_number' => [
                'required',
                'numeric',
                'max:999999999',
                $uniqueRule
            ],
            'ssaa_status' => 'required|boolean',
            'ssaa_expiry' => 'required|date',
        ];
    }

    /**
     * Validation custom messages
     *
     * @return array
     **/
    public static function validationRulesCustomMessage()
    {
        $individualSsaa = static::with('individual:id,first_name,surname')
            ->where('ssaa_number', request('ssaa_number'))
            ->first()
        ;

        if ($individualSsaa) {
            return [
                'ssaa_number.unique' => 'There is already an existing member: ' . $individualSsaa->individual->getName() . ' with the same SSAA number.',
            ];
        }

        return [];
    }

    /**
     * Get the individual details of the individual ssaa.
     */
    public function individual()
    {
        return $this->belongsTo('App\Individual');
    }
}
