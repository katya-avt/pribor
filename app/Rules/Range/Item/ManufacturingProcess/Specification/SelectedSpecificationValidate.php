<?php

namespace App\Rules\Range\Item\ManufacturingProcess\Specification;

use App\Models\Range\Specification;
use App\Rules\Range\AssemblyItemSpecificationMayContain;
use App\Rules\Range\DetailSpecificationMayContain;
use App\Rules\Range\GalvanicCoverSpecificationMayContain;
use Illuminate\Contracts\Validation\Rule;

class SelectedSpecificationValidate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected string $message = '';
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
            $specificationItems = $specification->items;

            $detailSpecificationValidateRule = new DetailSpecificationMayContain($this->item);
            $assemblyItemSpecificationValidateRule = new AssemblyItemSpecificationMayContain($this->item);
            $galvanicCoverSpecificationValidateRule = new GalvanicCoverSpecificationMayContain($this->item);

            foreach ($specificationItems as $specificationItem) {
                $rules = [
                    $detailSpecificationValidateRule,
                    $assemblyItemSpecificationValidateRule,
                    $galvanicCoverSpecificationValidateRule
                ];

                foreach ($rules as $rule) {
                    if (!$rule->passes('id', $specificationItem->id)) {
                        $this->message = $rule->message();
                        return false;
                    }
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
        return $this->message;
    }
}
