<?php

namespace App\Rules\Orders\Order\OrderItem;

use App\Models\Range\Item;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueRuleForOrderItemTable implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $order;
    protected ?string $ignoreValue;

    public function __construct($order, $ignoreValue = null)
    {
        $this->order = $order;
        $this->ignoreValue = $ignoreValue;
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

        if ($item) {
            $query = DB::table('order_item')
                ->where('order_id', $this->order->id)
                ->where('item_id', $item->id);

            return $this->ignoreValue ?
                $query->where('item_id', '<>', $this->ignoreValue)->doesntExist() :
                $query->doesntExist();
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
        return __('rules.unique_rule_for_order_item_table');
    }
}
