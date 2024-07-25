<?php

namespace App\Rules\Range\Item\UnitFactor\RuleComponents;

use App\Models\Range\Unit;
use Illuminate\Contracts\Validation\Rule;

class FactorEqualThousandWhenUnitsAreKgAndTon implements Rule
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
        if (
            $this->data['item']['unit_code'] == Unit::KG &&
            $this->data['purchased']['unit_code'] == Unit::T
        ) {
            if ($value != 1000) {
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
        return __('rules.factor_equal_thousand_when_units_are_kg_and_ton');
    }
}
