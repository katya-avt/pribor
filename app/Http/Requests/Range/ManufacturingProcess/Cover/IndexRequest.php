<?php

namespace App\Http\Requests\Range\ManufacturingProcess\Cover;

use App\Models\Range\Item;
use App\Rules\OptionMustBeSelectedFromList;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
        $drawing = $this->input('drawing');

        return [
            'search' => [],

            'drawing' => $drawing ? [
                new OptionMustBeSelectedFromList(Item::class)
            ] : []
        ];
    }
}
