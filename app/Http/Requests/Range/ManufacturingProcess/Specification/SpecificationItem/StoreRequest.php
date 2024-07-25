<?php

namespace App\Http\Requests\Range\ManufacturingProcess\Specification\SpecificationItem;

use App\Models\Range\Item;
use App\Rules\OptionMustBeSelectedFromList;
use App\Rules\Range\ManufacturingProcess\Specification\SpecificationItem\ItemMustNotBeCover;
use App\Rules\Range\ManufacturingProcess\Specification\SpecificationItem\NewSpecificationItemValidate;
use App\Rules\Range\ManufacturingProcess\Specification\SpecificationItem\QuantityForUnitItemsMustBeInteger;
use App\Rules\Range\ManufacturingProcess\UniqueRuleForSpecificationItemTable;
use App\Rules\Range\SpecificationMustNotContainTheItemForWhichItIsFilledIn;
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
        $specification = $this->route('specification');
        $extraRules = [];

        $relatedItems = $specification->relatedItems;
        if ($relatedItems->isNotEmpty()) {
            $relatedItem = $relatedItems->first();
            $extraRules = [
                new SpecificationMustNotContainTheItemForWhichItIsFilledIn($relatedItem),
                new NewSpecificationItemValidate($relatedItem)
            ];
        }

        $drawingRules = array_merge([
            'required',
            new UniqueRuleForSpecificationItemTable('specification_item', 'specification_number', $specification->number),
            new OptionMustBeSelectedFromList(Item::class),
            new ItemMustNotBeCover()
        ],
            $extraRules
        );

        return [
            'drawing' => $drawingRules,

            'cnt' => [
                'required', 'numeric', 'gt:0', 'max:99',
                new QuantityForUnitItemsMustBeInteger()
            ]
        ];
    }
}
