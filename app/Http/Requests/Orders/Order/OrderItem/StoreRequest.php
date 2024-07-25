<?php

namespace App\Http\Requests\Orders\Order\OrderItem;

use App\Models\Range\Item;
use App\Rules\OptionMustBeSelectedFromList;
use App\Rules\Orders\Order\OrderItem\OrderItemSpecificationValidate;
use App\Rules\Orders\Order\OrderItem\QuantityForUnitItemsMustBeInteger;
use App\Rules\Orders\Order\OrderItem\UniqueRuleForOrderItemTable;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $order = $this->route('order');

        return [
            'item_id' => [
                'required',
                new OptionMustBeSelectedFromList(Item::class, 'drawing'),
                new UniqueRuleForOrderItemTable($order),
                new OrderItemSpecificationValidate()
            ],
            'per_unit_price' => [
                'required',
                'numeric',
                'gt:0',
                'max:99999999',
            ],
            'cnt' => [
                'required',
                'numeric',
                'gt:0',
                'max:9999',
                new QuantityForUnitItemsMustBeInteger()
            ]
        ];
    }
}
