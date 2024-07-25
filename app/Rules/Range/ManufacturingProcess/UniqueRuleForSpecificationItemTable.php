<?php

namespace App\Rules\Range\ManufacturingProcess;

use App\Models\Range\Item;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueRuleForSpecificationItemTable implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected string $table;
    protected string $columnName;
    protected string $columnValue;
    protected ?string $ignoreValue;

    public function __construct($table, $columnName, $columnValue, $ignoreValue = null)
    {
        $this->table = $table;
        $this->columnName = $columnName;
        $this->columnValue = $columnValue;
        $this->ignoreValue = $ignoreValue;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $item = Item::getItemByDrawing($value);

        if ($item) {
            $query = DB::table($this->table)
                ->where($this->columnName, $this->columnValue)
                ->where('item_id', $item->id);

            return $this->ignoreValue ?
                $query->where('item_id', '<>', $this->ignoreValue)->doesntExist() :
                $query->doesntExist();
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('rules.unique_rule_for_specification_item_table');
    }
}
