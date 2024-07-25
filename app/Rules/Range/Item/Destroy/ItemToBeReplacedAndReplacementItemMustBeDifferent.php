<?php

namespace App\Rules\Range\Item\Destroy;

use App\Models\Range\Item;
use Illuminate\Contracts\Validation\Rule;

class ItemToBeReplacedAndReplacementItemMustBeDifferent implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $itemToBeReplaced;

    public function __construct($itemToBeReplaced)
    {
        $this->itemToBeReplaced = $itemToBeReplaced;
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
        $replacementItem = Item::getItemByDrawing($value);

        return !$replacementItem || $replacementItem->id != $this->itemToBeReplaced->id;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('rules.item_to_be_replaced_and_replacement_item_must_be_different');
    }
}
