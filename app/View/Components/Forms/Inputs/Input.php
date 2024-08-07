<?php

namespace App\View\Components\Forms\Inputs;

use Illuminate\View\Component;
use function view;

class Input extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public function __construct(
        public string $name,
        public string $label,
        public string $type,
        public string $message,
        public ?string $value = null,
        public ?string $nestedArrayValue = null,
        public bool $ajax = false
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.inputs.input');
    }
}
