<?php

namespace App\Rules\Range\ManufacturingProcess\Route\RoutePoint;

use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;

class RoutePointNumberMustNotRepeat implements Rule, DataAwareRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $data;

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
        $uniqueValues = array_unique($this->data['order']);
        return count($this->data['order']) === count($uniqueValues);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('rules.route_point_number_must_not_repeat');
    }

    public function setData($data)
    {
        $this->data = $data;
    }
}
