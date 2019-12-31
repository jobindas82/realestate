<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class flatAvailability implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $id; //////Existing id

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
      
        if( \App\models\Flats::find((int) $value)->is_available != 1 ){ //not available
            if( $this->id > 0 && \App\models\Contracts::find($this->id)->flat_id == $value ){
                return true;
            }
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
        return  'The Flat is not Available';
    }
}
