<?php

namespace App\Rules\Range;

use App\Models\Range\Item;
use Illuminate\Contracts\Validation\Rule;

class DetailSpecificationMayContain implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $item;

    public function __construct($item)
    {
        $this->item = $item;
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
        if ($this->item->group->isDetail()) {
            $newItem = Item::query()->where($attribute, $value)->first();

            if ($newItem) {
                if (!
                (
                    $newItem->group->isMetal() ||
                    $newItem->group->isPlastic()
                )
                ) {
                    return false;
                }
            }
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
        return __('rules.detail_specification_may_contain');
    }
}
