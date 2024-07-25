<?php

namespace App\Rules\Range\Item\UnitFactor;

use App\Rules\Range\Item\UnitFactor\RuleComponents\FactorEqualOneWhenUnitsMatch;
use App\Rules\Range\Item\UnitFactor\RuleComponents\FactorEqualThousandWhenUnitsAreKgAndTon;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;

class UnitFactorValidate implements Rule, DataAwareRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $data;
    protected string $message = '';

    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $unitsMatchValidateRule = new FactorEqualOneWhenUnitsMatch($this->data);
        $unitsAreKgAndTonValidateRule = new FactorEqualThousandWhenUnitsAreKgAndTon($this->data);

        $rules = [
            $unitsMatchValidateRule,
            $unitsAreKgAndTonValidateRule
        ];

        foreach ($rules as $rule) {
            if (!$rule->passes($attribute, $value)) {
                $this->message = $rule->message();
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
        return $this->message;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}
