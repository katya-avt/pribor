<?php

namespace App\Http\Requests\Range\Item\Destroy;

use App\Models\Range\Item;
use App\Rules\OptionMustBeSelectedFromList;
use App\Rules\Range\Item\Destroy\ItemToBeReplacedAndReplacementItemMustBeDifferent;
use App\Rules\Range\Item\Destroy\ItemToBeReplacedGroupAndReplacementItemGroupMustBeTheSame;
use Illuminate\Foundation\Http\FormRequest;

class CoverStoreRequest extends FormRequest
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
            'drawing' => [
                'required',
                new OptionMustBeSelectedFromList(Item::class),
                new ItemToBeReplacedGroupAndReplacementItemGroupMustBeTheSame($item),
                new ItemToBeReplacedAndReplacementItemMustBeDifferent($item)
            ],
            'factor' => [
                'required', 'numeric', 'gt:0', 'max:99',
            ]
        ];
    }
}
