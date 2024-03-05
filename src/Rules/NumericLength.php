<?php

namespace GiorgioSpa\Rules;

use Illuminate\Contracts\Validation\Rule;

class NumericLength implements Rule
{
    protected string $attrName;

    protected int $length;

    public function __construct($length, $attrName = null)
    {
        $this->attrName = $attrName;
        $this->length = $length;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     */
    public function passes($attribute, $value): bool
    {
        return strlen($value) == $this->length;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        $attrName = $this->attrName ?? ':attribute';

        return $attrName.'必须为'.$this->length.'位';
    }
}
