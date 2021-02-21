<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StartWithRule implements Rule
{
    public $startWithValue;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($startWithValue)
    {
        $this->startWithValue = $startWithValue;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (substr($value, 0, strlen($this->startWithValue)) == $this->startWithValue) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if ($this->startWithValue == 0) {
            return 'The :attribute needs to include area code.';
        }

        return 'The :attribute must start with ' . $this->startWithValue . '.';
    }
}
