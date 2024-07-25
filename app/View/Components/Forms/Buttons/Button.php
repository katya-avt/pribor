<?php

namespace App\View\Components\Forms\Buttons;

use Illuminate\View\Component;
use function view;

class Button extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public string $label
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
        return view('components.forms.buttons.button');
    }
}
