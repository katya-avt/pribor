<?php

namespace App\Http\Requests\Range\Item\ManufacturingProcess\Cover;

use App\Models\Range\Cover;
use App\Rules\OptionMustBeSelectedFromList;
use App\Rules\Range\Item\ManufacturingProcess\SelectedManufacturingProcessMustNotBeEmpty;
use App\Rules\Range\Item\ManufacturingProcess\UniqueRuleForItemManufacturingProcessTable;
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
                new OptionMustBeSelectedFromList(Cover::class),
                new UniqueRuleForItemManufacturingProcessTable('item_cover', $item->id, 'cover_number'),
                new SelectedManufacturingProcessMustNotBeEmpty(Cover::class, 'items')
            ]
        ];
    }
}
