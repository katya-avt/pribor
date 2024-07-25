<?php

namespace App\Http\Requests\Orders\Order;

use App\Models\Orders\Customer;
use App\Models\Orders\Order;
use App\Models\Range\Item;
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
        $customer = $this->input('customer_inn');
        $drawing = $this->input('drawing');
        $sortBy = $this->input('sort_by');
        $sortDirection = $this->input('sort_direction');

        $columns = implode(',', array_keys(Order::getColumnsAndSortNameMatching()));
        $sortDirections = implode(',', array_keys(Order::getSortDirectionAndSortNameMatching()));

        return [
            'search' => [],

            'customer_inn' => $customer ? [
                new OptionMustBeSelectedFromList(Customer::class, 'name')
            ] : [],

            'drawing' => $drawing ? [
                new OptionMustBeSelectedFromList(Item::class)
            ] : [],

            'sort_by' => $sortBy || $sortDirection ? [
                'required_with:sort_direction', "in:$columns"
            ] : [],

            'sort_direction' => $sortBy || $sortDirection ? [
                'required_with:sort_by', "in:$sortDirections"
            ] : []
        ];
    }
}
