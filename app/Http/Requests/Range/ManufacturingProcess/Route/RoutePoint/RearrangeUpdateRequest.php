<?php

namespace App\Http\Requests\Range\ManufacturingProcess\Route\RoutePoint;

use App\Rules\Range\ManufacturingProcess\Route\RoutePoint\RoutePointNumberMustNotExceedCurrentQuantity;
use App\Rules\Range\ManufacturingProcess\Route\RoutePoint\RoutePointNumberMustNotRepeat;
use Illuminate\Foundation\Http\FormRequest;

class RearrangeUpdateRequest extends FormRequest
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
            'order.*' => [
                'required', 'integer', 'min:1',
                new RoutePointNumberMustNotExceedCurrentQuantity($route),
                new RoutePointNumberMustNotRepeat()
            ]
        ];
    }
}
