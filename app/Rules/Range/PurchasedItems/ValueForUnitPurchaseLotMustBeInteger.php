<?php

namespace App\Rules\Range\PurchasedItems;

use Illuminate\Contracts\Validation\Rule;

class ValueForUnitPurchaseLotMustBeInteger implements Rule
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
        if ($this->item) {
            if ($this->item->purchasedItem->unit->isUnit()) {
                if (!filter_var($value, FILTER_VALIDATE_INT)) {
                    return false;
                }
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
        return __('rules.value_for_unit_purchase_lot_must_be_integer');
    }
}
