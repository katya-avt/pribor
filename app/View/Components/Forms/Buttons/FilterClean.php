<?php

namespace App\View\Components\Forms\Buttons;

use Illuminate\View\Component;
use function view;

class FilterClean extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public string $route,
        public array $params = []
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
        return view('components.forms.buttons.filter-clean');
    }
}
