<?php

namespace App\Http\Requests\Range\Item;

use App\Models\Range\Group;
use App\Models\Range\ItemType;
use App\Models\Range\MainWarehouse;
use App\Models\Range\ManufactureType;
use App\Models\Range\Unit;
use App\Rules\OptionMustBeSelectedFromList;
use App\Rules\Range\Item\BaseUnitValidationByGroup;
use App\Rules\Range\Item\DetailsOnlyField;
use App\Rules\Range\Item\ItemTypeValidationByGroup;
use App\Rules\Range\Item\PurchasedItemsOnlyField;
use App\Rules\Range\Item\PurchasedItemUnitValidationByGroup;
use App\Rules\Range\Item\UnitFactor\UnitFactorValidate;
use App\Rules\Range\Item\ValueForUnitItemsMustBeInteger;
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
        $itemType = $this->input('item.item_type_id');

        $rulesForPurchasePriceField = $itemType == ItemType::PURCHASED ?
            [
                'required',
                'numeric',
                'gt:0',
                'max:99999999'
            ] : [
                new PurchasedItemsOnlyField()
            ];

        $rulesForPurchaseField = $itemType == ItemType::PURCHASED ?
            [
                'required',
                'numeric',
                'gt:0',
                'max:99999999',
                new ValueForUnitItemsMustBeInteger()
            ] : [
                new PurchasedItemsOnlyField()
            ];

        $rulesForPurchasedUnitCodeField = $itemType == ItemType::PURCHASED ?
            [
                'required',
                new OptionMustBeSelectedFromList(Unit::class, 'short_name'),
                new PurchasedItemUnitValidationByGroup()
            ] : [
                new PurchasedItemsOnlyField()
            ];

        $rulesForUnitFactorField = $itemType == ItemType::PURCHASED ?
            [
                'required',
                'numeric',
                'gt:0',
                'max:9999',
                new UnitFactorValidate()
            ] : [
                new PurchasedItemsOnlyField()
            ];

        $group = $this->input('item.group_id');

        $rulesForDetailField = $group == Group::DETAIL ?
            [
                'required',
                'regex:/^[1-9]+\d*\.?\d*x[1-9]+\d*\.?\d*x[1-9]+\d*\.?\d*$/',
                'max:32'
            ]
            : [
                new DetailsOnlyField()
            ];

        return [
            'item.drawing' => [
                'required', 'min:2', 'max:255', 'unique:items,drawing'
            ],
            'item.name' => [
                'required', 'min:2', 'max:255', 'unique:items,name'
            ],
            'item.item_type_id' => [
                'required', new OptionMustBeSelectedFromList(ItemType::class, 'name'),
                new ItemTypeValidationByGroup()
            ],
            'item.main_warehouse_code' => [
                'required', new OptionMustBeSelectedFromList(MainWarehouse::class, 'name')
            ],
            'item.group_id' => [
                'required', new OptionMustBeSelectedFromList(Group::class, 'name')
            ],
            'item.unit_code' => [
                'required', new OptionMustBeSelectedFromList(Unit::class, 'short_name'),
                new BaseUnitValidationByGroup()
            ],
            'item.manufacture_type_id' => [
                'required', new OptionMustBeSelectedFromList(ManufactureType::class, 'name')
            ],
            'detail.detail_size' => $rulesForDetailField,
            'detail.billet_size' => $rulesForDetailField,

            'purchased.purchase_price' => $rulesForPurchasePriceField,
            'purchased.purchase_lot' => $rulesForPurchaseField,
            'purchased.order_point' => $rulesForPurchaseField,
            'purchased.unit_factor' => $rulesForUnitFactorField,
            'purchased.unit_code' => $rulesForPurchasedUnitCodeField,
        ];
    }
}
