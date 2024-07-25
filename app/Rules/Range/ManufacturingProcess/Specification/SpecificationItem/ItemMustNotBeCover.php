<?php

namespace App\Rules\Range\ManufacturingProcess\Specification\SpecificationItem;

use App\Models\Range\Item;
use Illuminate\Contracts\Validation\Rule;

class ItemMustNotBeCover implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

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
        $item = Item::getItemByDrawing($value);
        return $item ? !$item->group->isCover() : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('rules.item_must_not_be_cover');
    }
}
