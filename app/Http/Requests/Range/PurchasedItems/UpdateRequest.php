<?php

namespace App\Http\Requests\Range\PurchasedItems;

use App\Rules\Range\PurchasedItems\ValueForUnitPurchaseLotMustBeInteger;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
        $purchasedItem = $this->route('item');

        return [
            'purchase_lot' => [
                'required',
                'numeric',
                'gt:0', 'max:99999999',
                new ValueForUnitPurchaseLotMustBeInteger($purchasedItem)
            ],
            'order_point' => [
                'required',
                'numeric',
                'gt:0', 'max:99999999',
                new ValueForUnitPurchaseLotMustBeInteger($purchasedItem)
            ]
        ];
    }
}
