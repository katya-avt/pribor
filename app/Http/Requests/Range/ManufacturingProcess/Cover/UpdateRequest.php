<?php

namespace App\Http\Requests\Range\ManufacturingProcess\Cover;

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
        $cover = $this->route('cover');

        return [
            'number' => [
                'required', 'regex:/^\d+(-\d+)?$/', 'min:6', 'max:64',
                'unique:specifications',
                Rule::unique('covers')->ignore($cover->number, 'number'),
                'unique:routes'
            ]
        ];
    }
}
