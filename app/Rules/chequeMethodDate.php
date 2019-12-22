<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class chequeMethodDate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $method;

    public function __construct($method)
    {
        $this->method = (int) $method;
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
        if( $this->method == 2 && ( trim($value) == '' || $value == '0000-00-00' ) ){ //Cash
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Cheque cannot blank.';
    }
}
