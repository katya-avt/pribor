<?php

namespace App\Http\Requests\Range\ManufacturingProcess\Route;

use App\Models\Range\Operation;
use App\Models\Range\Point;
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
        $pointCode = $this->input('point_code');
        $operationCode = $this->input('operation_code');

        return [
            'search' => [],

            'point_code' => $pointCode ? [
                new OptionMustBeSelectedFromList(Point::class, 'code')
            ] : [],

            'operation_code' => $operationCode ? [
                new OptionMustBeSelectedFromList(Operation::class, 'code')
            ] : []
        ];
    }
}
