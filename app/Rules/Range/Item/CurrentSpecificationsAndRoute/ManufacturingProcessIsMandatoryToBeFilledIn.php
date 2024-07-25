<?php

namespace App\Rules\Range\Item\CurrentSpecificationsAndRoute;

use Illuminate\Contracts\Validation\Rule;

class ManufacturingProcessIsMandatoryToBeFilledIn implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $item;
    protected $method;

    public function __construct($item, $method)
    {
        $this->item = $item;
        $this->method = $method;
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
        if ($this->item->{$this->method}()->exists()) {
            if (!$value) {
                return false;
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
        return __('rules.manufacturing_process_is_mandatory_to_be_filled_in');
    }
}
