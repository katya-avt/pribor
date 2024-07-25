<?php

namespace App\Http\Requests\ItemAvailabilityAndConsumption\Availability;

use App\Rules\ItemAvailabilityAndConsumption\Availability\QuantityForUnitItemsMustBeInteger;
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
        $item = $this->route('item');

        return [
            'cnt' => [
                'required', 'numeric', 'gt:0', 'max:999999',
                new QuantityForUnitItemsMustBeInteger($item)
            ]
        ];
    }
}
