<?php

namespace App\View\Components\Forms\Selects;

use Illuminate\View\Component;
use function view;

class Select extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public string $name,
        public string $label,
        public $options,
        public string $key,
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
        return view('components.forms.selects.select');
    }
}
