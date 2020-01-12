<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class haveContracts implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $mobileNo;

    public function __construct($mobileNo)
    {
        $this->mobileNo = $mobileNo;
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
        $count = \App\models\Tenants::where('is_available', 2)
                                    ->where('emirates_id', $value)
                                    ->where('mobile', $this->mobileNo)
                                    ->count();
        if( $count > 0  ){
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
        return 'You dont have any active contracts.';
    }
}
