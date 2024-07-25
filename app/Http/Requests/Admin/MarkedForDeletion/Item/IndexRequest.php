<?php

namespace App\Http\Requests\Admin\MarkedForDeletion\Item;

use App\Models\Range\Group;
use App\Models\Range\ItemType;
use App\Models\Range\MainWarehouse;
use App\Models\Range\ManufactureType;
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
        $itemTypeId = $this->input('item_type_id');
        $mainWarehouseCode = $this->input('main_warehouse_code');
        $groupId = $this->input('group_id');
        $manufactureTypeId = $this->input('manufacture_type_id');

        return [
            'search' => [],

            'item_type_id' => $itemTypeId ? [
                new OptionMustBeSelectedFromList(ItemType::class, 'name')
            ] : [],
            'main_warehouse_code' => $mainWarehouseCode ? [
                new OptionMustBeSelectedFromList(MainWarehouse::class, 'name')
            ] : [],
            'group_id' => $groupId ? [
                new OptionMustBeSelectedFromList(Group::class, 'name')
            ] : [],
            'manufacture_type_id' => $manufactureTypeId ? [
                new OptionMustBeSelectedFromList(ManufactureType::class, 'name')
            ] : []
        ];
    }
}
