<?php

namespace App\Rules\Range\Item\ManufacturingProcess\Specification;

use App\Models\Range\Specification;
use Illuminate\Contracts\Validation\Rule;

class DetailSpecificationCannotContainMoreThanOneElement implements Rule
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
        $specification = Specification::find($value);

        if ($specification) {
            if ($this->item->group->isDetail()) {
                if ($specification->items()->count() > 1) {
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
        return __('rules.detail_specification_cannot_contain_more_than_one_element');
    }
}
