<div>
    @if($itemId)
        @component('components.modals.elements.launch-item-current-manufacturing-process-modal-button', ['name' => $name, 'itemId' => $itemId])@endcomponent
    @else
        @if($change)
            @component('components.modals.elements.launch-change-modal-button', ['name' => $name, 'change' => $change])@endcomponent
        @else
            @if($body)
                @component('components.modals.elements.launch-confirm-modal-button', ['name' => $name, 'confirmType' => $confirmType])@endcomponent
            @else
                @component('components.modals.elements.launch-choose-modal-button', ['name' => $name])@endcomponent
            @endif
        @endif
    @endif

    <div class="modal fade" id="{{ $name }}Modal" tabindex="-1" aria-labelledby="{{ $name }}ModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-{{ $size }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $name }}ModalLabel">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($change)
                        ...
                    @else
                        @if($body)
                            @component('components.modals.confirm.body', ['body' => $body])@endcomponent
                        @else
                            @component('components.modals.choose.body')@endcomponent
                        @endif
                    @endif
                </div>
                <div class="modal-footer">
                    @if($body)
                        @component('components.modals.confirm.footer', ['name' => $name])@endcomponent
                    @else
                        @component('components.modals.choose.footer')@endcomponent
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
