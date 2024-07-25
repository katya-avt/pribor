<?php

namespace App\View\Components\Forms\Selects;

use Illuminate\View\Component;
use function view;

class SelectArray extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public string $name,
        public string $label,
        public array $options,
        public string $message,
        public ?string $oldValue = null
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
        return view('components.forms.selects.select-array');
    }
}
