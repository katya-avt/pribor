<?php

namespace App\Http\Requests\Range\ManufacturingProcess\Route\RoutePoint;

use App\Models\Range\Operation;
use App\Models\Range\Point;
use App\Models\Range\Rate;
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
            'point_code' => [
                'required', new OptionMustBeSelectedFromList(Point::class, 'code')
            ],
            'operation_code' => [
                'required', new OptionMustBeSelectedFromList(Operation::class, 'code')
            ],
            'unit_time' => [
                'required', 'numeric', 'gt:0', 'max:9999'
            ],
            'working_time' => [
                'required', 'numeric', 'gt:0', 'max:9999'
            ],
            'lead_time' => [
                'required', 'numeric', 'gt:0', 'max:9999'
            ],
            'rate_code' => [
                'required', new OptionMustBeSelectedFromList(Rate::class, 'code')
            ]
        ];
    }
}
