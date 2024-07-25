<?php

namespace App\View\Components\Modals;

use Illuminate\View\Component;
use function view;

class Modal extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public function __construct(
        public string $name,
        public string $size,
        public string $title,
        public ?string $body = null,
        public ?string $change = null,
        public ?string $itemId = null,
        public string $confirmType = 'delete'
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
        return view('components.modals.modal');
    }
}
