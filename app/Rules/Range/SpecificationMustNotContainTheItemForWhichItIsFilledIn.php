<?php

namespace App\Rules\Range;

use App\Models\Range\Item;
use Illuminate\Contracts\Validation\Rule;

class SpecificationMustNotContainTheItemForWhichItIsFilledIn implements Rule
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
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $inputItem = Item::getItemByDrawing($value);
        if ($inputItem) {
            if ($this->item->id == $inputItem->id) {
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
        return __('rules.specification_must_not_contain_the_item_for_which_it_is_filled_in');
    }
}
