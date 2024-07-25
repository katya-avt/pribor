<?php

namespace App\Rules\Range\Item\ManufacturingProcess;

use Illuminate\Contracts\Validation\Rule;

class SelectedManufacturingProcessMustNotBeEmpty implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $model;
    protected string $method;

    public function __construct($model, $method)
    {
        $this->model = $model;
        $this->method = $method;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $manufacturingProcess = $this->model::find($value);
        if ($manufacturingProcess) {
            $manufacturingProcessItems = $manufacturingProcess->{$this->method};

            if ($manufacturingProcessItems->isEmpty()) {
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
        return __('rules.selected_manufacturing_process_must_not_be_empty');
    }
}
