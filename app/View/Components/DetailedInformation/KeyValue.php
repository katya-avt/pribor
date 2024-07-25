<?php

namespace App\View\Components\DetailedInformation;

use Illuminate\View\Component;
use function view;

class KeyValue extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public string $key,
        public string $value
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
        return view('components.detailed-information.key-value');
    }
}
