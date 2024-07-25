<?php

namespace App\Rules\Range\Item;

use App\Models\Range\Group;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;

class DetailsOnlyField implements Rule, DataAwareRule
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
        $group = $this->data['item']['group_id'];
        if ($group != Group::DETAIL) {
            if ($value) {
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
        return __('rules.details_only_field');
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}
