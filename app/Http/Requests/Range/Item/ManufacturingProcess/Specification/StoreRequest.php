<?php

namespace App\Http\Requests\Range\Item\ManufacturingProcess\Specification;

use App\Models\Range\Specification;
use App\Rules\OptionMustBeSelectedFromList;
use App\Rules\Range\Item\ManufacturingProcess\SelectedManufacturingProcessMustNotBeEmpty;
use App\Rules\Range\Item\ManufacturingProcess\Specification\DetailSpecificationCannotContainMoreThanOneElement;
use App\Rules\Range\Item\ManufacturingProcess\Specification\SelectedSpecificationValidate;
use App\Rules\Range\Item\ManufacturingProcess\UniqueRuleForItemManufacturingProcessTable;
use App\Rules\Range\SelectedSpecificationMustNotContainTheItemForWhichItIsSelected;
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
        $item = $this->route('item');

        return [
            'number' => [
                'required',
                new OptionMustBeSelectedFromList(Specification::class),
                new UniqueRuleForItemManufacturingProcessTable('item_specification', $item->id, 'specification_number'),
                new SelectedSpecificationMustNotContainTheItemForWhichItIsSelected($item),
                new SelectedManufacturingProcessMustNotBeEmpty(Specification::class, 'items'),
                new SelectedSpecificationValidate($item),
                new DetailSpecificationCannotContainMoreThanOneElement($item)
            ]
        ];
    }
}
