<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class OptionMustBeSelectedFromList implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $model;
    protected $attribute;

    public function __construct($model, $attribute = null)
    {
        $this->model = $model;
        $this->attribute = $attribute;
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
        $attribute = $this->attribute ?? $attribute;

        return $this->model::where($attribute, $value)->first() !== null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('rules.option_must_be_selected_from_list');
    }
}
