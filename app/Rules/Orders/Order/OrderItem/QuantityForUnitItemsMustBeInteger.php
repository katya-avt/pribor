<?php

namespace App\Rules\Orders\Order\OrderItem;

use App\Models\Range\Item;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\InvokableRule;

class QuantityForUnitItemsMustBeInteger implements DataAwareRule, InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */

    /**
     * All of the data under validation.
     *
     * @var array
     */
    protected $data = [];

    public function __invoke($attribute, $value, $fail)
    {
        if ($this->data['item_id']) {
            $item = Item::getItemByDrawing($this->data['item_id']);
            if ($item) {
                if ($item->unit->isUnit()) {
                    if (!filter_var($value, FILTER_VALIDATE_INT)) {
                        $fail(__('rules.quantity_for_unit_items_must_be_integer'));
                    }
                }
            }
        }
    }

    /**
     * Set the data under validation.
     *
     * @param  array  $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}
