<?php

namespace App\Http\Requests\Admin\User;

use App\Models\Users\Role;
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users',
                'regex:/^[a-z]+\.[a-z]+@priborvbg.ru$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', new OptionMustBeSelectedFromList(Role::class, 'name')]
        ];
    }
}
