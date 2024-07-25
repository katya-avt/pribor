<?php

namespace App\Http\Requests\Range\ManufacturingProcess\Specification;

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
            'number' => [
                'required', 'regex:/^\d+(-\d+)?$/', 'min:6', 'max:64',
                'unique:specifications', 'unique:covers', 'unique:routes'
            ]
        ];
    }
}
