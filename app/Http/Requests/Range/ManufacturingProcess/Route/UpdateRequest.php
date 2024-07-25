<?php

namespace App\Http\Requests\Range\ManufacturingProcess\Route;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $route = $this->route('route');

        return [
            'number' => [
                'required', 'regex:/^[0-9]+$/', 'min:6', 'max:64',
                'unique:specifications',
                'unique:covers',
                Rule::unique('routes')->ignore($route->number, 'number')
            ]
        ];
    }
}
