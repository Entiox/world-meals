<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CategoryRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $failMessage = "Invalid value for parameter \"category\"";
        if(ctype_digit($value) || is_int($value))
        {
            if((int) $value < 1){
                $fail($failMessage);
            }
        }
        else if($value != "NULL" && $value != "!NULL")
        {
            $fail($failMessage);
        }
    }
}
