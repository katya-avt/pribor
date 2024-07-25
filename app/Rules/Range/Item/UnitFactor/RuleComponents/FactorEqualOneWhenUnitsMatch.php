<?php

namespace App\Rules\Range\Item\UnitFactor\RuleComponents;

use Illuminate\Contracts\Validation\Rule;

class FactorEqualOneWhenUnitsMatch implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
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
        if ($this->data['item']['unit_code'] == $this->data['purchased']['unit_code']) {
            if ($value != 1) {
                return false;
            }
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
        return __('rules.factor_equal_one_when_units_match');
    }
}
