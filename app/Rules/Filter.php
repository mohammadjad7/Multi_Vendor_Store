<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Filter implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    protected $forbidden;

    public function __construct($forbidden)
    {
        $this->forbidden = $forbidden;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
        // if ($value == 'laravel') {
        //     $fail("The $attribute field => use your word man");
        // }
        
        if (in_array(strtolower($value) , $this->forbidden)) {
            $fail("The $attribute field => use your word man");
        }
    }
}
