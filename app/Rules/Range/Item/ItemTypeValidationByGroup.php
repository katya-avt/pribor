<?php

namespace App\Rules\Range\Item;

use App\Models\Range\Group;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;

class ItemTypeValidationByGroup implements Rule, DataAwareRule
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
        return in_array($group, array_keys(Group::getGroupsAndItemTypesMatching())) &&
            in_array($value, Group::getGroupsAndItemTypesMatching()[$group]);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('rules.item_type_validation_by_group');
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}
