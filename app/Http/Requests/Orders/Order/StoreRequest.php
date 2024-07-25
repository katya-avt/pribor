<?php

namespace App\Http\Requests\Orders\Order;

use App\Models\Orders\Customer;
use App\Rules\OptionMustBeSelectedFromList;
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
        return [
            'code' => ['required', 'unique:orders', 'min:2', 'max:255'],
            'name' => ['required', 'unique:orders', 'min:2', 'max:255'],
            'closing_date' => ['required', 'date'],
            'customer_inn' => [
                'required',
                new OptionMustBeSelectedFromList(Customer::class, 'name'),
            ],
            'note' => []
        ];
    }
}
