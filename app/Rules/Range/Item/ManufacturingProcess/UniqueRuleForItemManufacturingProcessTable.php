<?php

namespace App\Rules\Range\Item\ManufacturingProcess;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueRuleForItemManufacturingProcessTable implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected string $table;
    protected string $itemId;
    protected string $columnName;

    public function __construct($table, $itemId, $columnName)
    {
        $this->table = $table;
        $this->itemId = $itemId;
        $this->columnName = $columnName;
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
        return DB::table($this->table)
            ->where('item_id', $this->itemId)
            ->where($this->columnName, $value)
            ->doesntExist();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('rules.unique_rule_for_item_manufacturing_process_table');
    }
}
