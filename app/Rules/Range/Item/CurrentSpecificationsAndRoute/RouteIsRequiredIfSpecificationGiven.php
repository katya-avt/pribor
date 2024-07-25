<?php

namespace App\Rules\Range\Item\CurrentSpecificationsAndRoute;

use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;

class RouteIsRequiredIfSpecificationGiven implements Rule, DataAwareRule
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
        if($this->data['specification_number'] || $this->data['cover_number']) {
            if(!$this->data['route_number']) {
                return  false;
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
        return __('rules.route_is_required_if_specification_given');
    }

    public function setData($data)
    {
        $this->data = $data;
    }
}
