<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ifClosedContract implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $id; 

    public function __construct( $id )
    {
        $this->id = (int) $id;
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
        if( $this->id > 0 ){
            return \App\models\Contracts::find($this->id)->is_active == 1 ? true : false; 
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
        return 'Contract Already Closed';
    }
}
