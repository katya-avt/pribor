<?php

namespace App\Rules\Range\Item\CurrentSpecificationsAndRoute;

use Illuminate\Contracts\Validation\Rule;

class OnlyProprietaryItemsMayHaveSpecification implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $item;

    public function __construct($item)
    {
        $this->item = $item;
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
        if (!$this->item->itemType->isProprietary()) {
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
        return __('rules.only_proprietary_items_may_have_specification');
    }
}
