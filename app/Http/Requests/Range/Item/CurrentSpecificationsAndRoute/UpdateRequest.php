<?php

namespace App\Http\Requests\Range\Item\CurrentSpecificationsAndRoute;

use App\Rules\Range\Item\CurrentSpecificationsAndRoute\ManufacturingProcessIsMandatoryToBeFilledIn;
use App\Rules\Range\Item\CurrentSpecificationsAndRoute\OnlyProprietaryItemsMayHaveSpecification;
use App\Rules\Range\Item\CurrentSpecificationsAndRoute\OptionMustBeSelectedFromListDependingOnValue;
use App\Rules\Range\Item\CurrentSpecificationsAndRoute\RouteIsRequiredIfSpecificationGiven;
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

        $specificationNumberRequiredRule = $item->itemType->isProprietary() ? ['required'] :
            [new OnlyProprietaryItemsMayHaveSpecification($item)];
        $routeNumberRequiredRule = $item->itemType->isTolling() ? ['required'] : [];

        return [
            'specification_number' => $specificationNumberRequiredRule + [
                    new ManufacturingProcessIsMandatoryToBeFilledIn($item, 'specifications'),
                    new OptionMustBeSelectedFromListDependingOnValue($item, 'specifications'),
                ],
            'cover_number' => [
                new ManufacturingProcessIsMandatoryToBeFilledIn($item, 'covers'),
                new OptionMustBeSelectedFromListDependingOnValue($item, 'covers'),
            ],
            'route_number' => $routeNumberRequiredRule + [
                    new ManufacturingProcessIsMandatoryToBeFilledIn($item, 'routes'),
                    new RouteIsRequiredIfSpecificationGiven(),
                    new OptionMustBeSelectedFromListDependingOnValue($item, 'routes'),
                ],
        ];
    }
}
