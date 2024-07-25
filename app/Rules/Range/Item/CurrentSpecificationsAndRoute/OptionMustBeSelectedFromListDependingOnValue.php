<?php

namespace App\Rules\Range\Item\CurrentSpecificationsAndRoute;

use Illuminate\Contracts\Validation\Rule;

class OptionMustBeSelectedFromListDependingOnValue implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $item;
    protected $method;
    protected $message;

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
        if ($this->item->{$this->method}->isNotEmpty()) {
            if ($this->item->{$this->method}()->where($attribute, $value)->first() == null) {
                $this->message = __('rules.option_must_be_selected_from_list');
                return false;
            }
        } else {
            if ($value) {
                $this->message = __('rules.list_is_empty');
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
        return $this->message;
    }
}
