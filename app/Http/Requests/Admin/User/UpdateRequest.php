<?php

namespace App\Http\Requests\Admin\User;

use App\Models\Users\Role;
use App\Rules\OptionMustBeSelectedFromList;
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
        $user = $this->route('user');
        $password = $this->input('password');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($user->email, 'email'),
                'regex:/^[a-z]+\.[a-z]+@priborvbg.ru$/'],
            'password' => $password ? ['required', 'string', 'min:8', 'confirmed'] : [],
            'role_id' => ['required', new OptionMustBeSelectedFromList(Role::class, 'name')]
        ];
    }
}

