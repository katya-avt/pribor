<?php

namespace App\Rules\Range\ManufacturingProcess\Route\RoutePoint;

use Illuminate\Contracts\Validation\Rule;

class RoutePointNumberMustNotExceedCurrentQuantity implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $route;

    public function __construct($route)
    {
        $this->route = $route;
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
        $pointCount = $this->route->points()->count();
        return $value <= $pointCount;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('rules.route_point_number_must_not_exceed_current_quantity');
    }
}
