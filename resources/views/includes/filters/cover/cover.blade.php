<form
    action="{{ route('covers.index') }}"
    method="get">

    <x-forms.inputs.input
        name="search"
        label="Поиск:"
        type="text"
        message={{ $message }}
            value="{{ $data['search'] ?? '' }}">
    </x-forms.inputs.input>

    <div class="input-group">
        <x-forms.inputs.input
            name="drawing"
            label="Чертеж:"
            type="text"
            message={{ $message }}
                value="{{ $data['drawing'] ?? '' }}">
        </x-forms.inputs.input>

        <x-modals.modal name="item" size="lg" title="Выберете изделие"></x-modals.modal>
    </div>

    <div class="d-flex mb-3">
        <div class="mr-5">
            <x-forms.buttons.button label="Применить"></x-forms.buttons.button>
        </div>
        <x-forms.buttons.filter-clean route="covers.index"></x-forms.buttons.filter-clean>
    </div>
</form>
