<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateSocialLink implements ValidationRule
{
    protected $socialLinks;
    public function __construct(...$socialLinks)
    {
        $this->socialLinks = $socialLinks;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $isValid = false;

        foreach ($this->socialLinks as $link) {
            if (str_starts_with($value, $link)) {
                $isValid = true;
                break;
            }
        }

        if (!$isValid) {
            $fail("The {$attribute} must start with a valid link.");
        }
    }
}
