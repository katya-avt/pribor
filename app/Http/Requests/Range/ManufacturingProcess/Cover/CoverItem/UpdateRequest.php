<?php

namespace App\Http\Requests\Range\ManufacturingProcess\Cover\CoverItem;

use App\Models\Range\Item;
use App\Rules\OptionMustBeSelectedFromList;
use App\Rules\Range\ManufacturingProcess\Cover\CoverItem\ItemMustBeCover;
use App\Rules\Range\ManufacturingProcess\UniqueRuleForSpecificationItemTable;
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
        $cover = $this->route('cover');
        $coverItem = $this->route('coverItem');

        return [
            'drawing' => [
                'required',
                new UniqueRuleForSpecificationItemTable('cover_item', 'cover_number', $cover->number, $coverItem->id),
                new OptionMustBeSelectedFromList(Item::class),
                new ItemMustBeCover()
            ],
            'area' => ['required', 'numeric', 'gt:0', 'max:99999'],
            'consumption' => ['required', 'numeric', 'gt:0', 'max:9']
        ];
    }
}
